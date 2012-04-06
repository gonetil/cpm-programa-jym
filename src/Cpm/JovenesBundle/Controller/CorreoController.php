<?php
namespace Cpm\JovenesBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Response;

use Cpm\JovenesBundle\Entity\Correo;
use Cpm\JovenesBundle\Form\CorreoType;

use Cpm\JovenesBundle\EntityDummy\ProyectoSearch;
use Cpm\JovenesBundle\Form\ProyectoSearchType;

use Cpm\JovenesBundle\EntityDummy\CorreoBatch;
use Cpm\JovenesBundle\Form\CorreoBatchType;

use Cpm\JovenesBundle\Entity\Plantilla;
use Cpm\JovenesBundle\Exception\Mailer\InvalidTemplateException;
use Cpm\JovenesBundle\Exception\Mailer\MailCannotBeSentException;

use Cpm\JovenesBundle\Filter\CorreoFilterForm;
use Cpm\JovenesBundle\Filter\CorreoFilter;

/**
 * Correo controller.
 *
 * @Route("/correo")
 */
class CorreoController extends BaseController
{
    /**
     * Lists all Correo entities.
     *
     * @Route("/", name="correo")
     * @Template()
     */
    public function indexAction()
    {
    	$filter = new CorreoFilter();
		$form = $this->createForm(new CorreoFilterForm(), $filter);
		$request = $this->getRequest();
		if ($request->query->get($form->getName())){
			$form->bindRequest($request);
		}
		//if ($form->isValid()) {
		
		$args= array (
			'entity' => $filter,
			'form' => $form->createView()
			
		);
		
        $q = $this->getRepository('CpmJovenesBundle:Correo')->filter($filter);
        return $this->paginate($q,$args);
        
    }

    /**
     * Finds and displays a Correo entity.
     *
     * @Route("/show", name="correo_show")
     * @Template()
     */
    public function showAction()
    {
		$id_correo = $this->getRequest()->get('id');
		$entity = $this->getRepository("CpmJovenesBundle:Correo")->findOneById($id_correo);

		if (!$entity) {
			throw $this->createNotFoundException('Unable to find Correo entity.');
		}

		return array (
			'entity' => $entity
		);
	}

	public function reenviarunavezAction(){
		$correos_ids = array(2, 3, 6, 11, 30, 34, 98, 105, 109, 111, 125, 126, 130, 135, 144, 147, 149, 170, 176, 177, 186, 197, 202, 203, 204, 214, 223, 233, 234, 240, 241, 246, 248, 259, 262, 267, 271, 280, 283, 292, 306, 312, 325, 346, 359, 362, 391, 405, 411, 412, 457, 475, 476, 496, 498, 499, 500, 501, 502, 503, 504, 505, 507, 508, 509, 510, 511, 512, 513, 514, 515, 516, 517, 518, 519, 520, 521, 522, 523, 524, 526, 527, 529, 530, 531, 532, 533, 534, 535, 537, 538, 539, 540, 541, 542, 544, 545, 547, 548, 549, 550, 551, 553, 556, 557, 558, 559, 560, 561, 563, 564, 565, 567, 570, 573, 574, 575, 576, 578, 580, 581, 587, 588, 589, 590, 592, 593, 594, 596, 597, 602, 623, 625);
		$cq= $this->getRepository("CpmJovenesBundle:Correo")->createQueryBuilder('c');
		$cq->andWhere("c.id in (:ids)")->setParameter('ids',$correos_ids);
		$correos = $cq->getQuery()->getResult();
		
		$mailer = $this->getMailer();
		$emisor = $this->getLoggedInUser();
		$enviados = 0;
		try{
			foreach ( $correos as $correoViejo) {
				$correo = $correoViejo->clonar();
				$correo->setEmisor($emisor);
	
				$correo = $mailer->enviarCorreo($correo);
				$enviados++;
			}
			die('Se enviaron '.$enviados.' correos con exito');
		}catch(InvalidTemplateException $e){
			die('La plantilla no es valida: ' .$e->getPrevious()->getMessage());
		}catch(MailCannotBeSentException $e){
			die('No se pudo enviar el correo. Verifique que los datos ingresados sean válidos');
		}
	}
	
	/**
     * Reenvia un correo
     *
     * @Route("/{id}/reenviar", name="correo_reenviar")
     * @Template()
     */
    public function reenviarAction($id)
    {
		$correoViejo = $this->getRepository("CpmJovenesBundle:Correo")->findOneById($id);

		if (!$correoViejo) {
			throw $this->createNotFoundException('Unable to find Correo entity.');
		}

		$mailer = $this->getMailer();
		$correo = $correoViejo->clonar();
		$correo->setEmisor($this->getLoggedInUser());

		try{
			$correo = $mailer->enviarCorreo($correo);
			$this->setSuccessMessage('El correo número '.$id.' ha sido reenviado con éxito a ' .$correo->getEmail());
			return $this->redirect($this->generateUrl('correo_show', array (
					'id' => $correo->getId()
				)));
		}catch(InvalidTemplateException $e){
			$this->setErrorMessage('La plantilla no es valida: ' .$e->getPrevious()->getMessage());
		}catch(MailCannotBeSentException $e){
			$this->setErrorMessage('No se pudo enviar el correo. Verifique que los datos ingresados sean válidos');
		}

		return $this->redirect($this->generateUrl('correo_show', array (
					'id' => $id
		)));
	}

	/**
	 * Displays a form to create a new Correo entity.
	 *
	 * @Route("/new", name="correo_new")
	 * @Template()
	 */
	public function newAction() {
		$entity = new Correo();
		$form = $this->createForm(new CorreoType(), $entity);

		return array (
			'entity' => $entity,
			'form' => $form->createView()
		);
	}

	/**
	 * Creates a new Correo entity.
	 *
	 * @Route("/create", name="correo_create")
	 * @Method("post")
	 * @Template("CpmJovenesBundle:Correo:new.html.twig")
	 */
	public function createAction() {
		$correo = new Correo();
		$request = $this->getRequest();
		$form = $this->createForm(new CorreoType(), $correo);
		$form->bindRequest($request);

		if ($form->isValid()) {
			$mailer = $this->getMailer();
			$correo->setEmisor($this->getLoggedInUser());
			try{
				$correo = $mailer->enviarCorreo($correo);
				$this->setSuccessMessage('El correo ha sido enviado con éxito a ' .$correo->getEmail());
				return $this->redirect($this->generateUrl('correo_show', array (
					'id' => $correo->getId()
				)));
			}catch(InvalidTemplateException $e){
				$this->setErrorMessage('La plantilla no es valida: ' .$e->getPrevious()->getMessage());
			}catch(MailCannotBeSentException $e){
				$this->setErrorMessage('No se pudo enviar el correo. Verifique que los datos ingresados sean válidos');
			}

		}

		return array (
			'entity' => $correo,
			'form' => $form->createView()
		);
	}

	/**
	 * 
	 * Permite enviar un correo a un conjunto de destinatarios seleccionados
	 * @Template("CpmJovenesBundle:Correo:write_to_many.html.twig")
	 */
	public function writeToManyAction($proyectos_query) {

		$correoBatch = new CorreoBatch();

		$proyectos = new \ Doctrine \ Common \ Collections \ ArrayCollection($proyectos_query->getResult());

		$correoBatch->setProyectos($proyectos);

		$correoBatchForm = $this->createForm(new CorreoBatchType(), $correoBatch);
		return array (
			'form' => $correoBatchForm->createView(),
			'proyectos' => $proyectos_query->getResult(),

			
		);

	}
	

	/**
	*
	* Envia un correo masivo
	* @Route("/send_mass_email", name="correo_send_mass_email")
	* @Template("CpmJovenesBundle:Correo:write_to_many.html.twig")
	*/
	public function sendMassEmailAction() {
		$request = $this->getRequest();
		$correoBatch = new CorreoBatch();

		$correoBatchForm = $this->createForm(new CorreoBatchType(), $correoBatch);
		$correoBatchForm->bindRequest($request);
		$proyectos = $correoBatch->getProyectos();

		if ($correoBatchForm->isValid() && count($proyectos)) {
			
			$repository = $this->getEntityManager()->getRepository('CpmJovenesBundle:Proyecto');
			
			$mailer = $this->getMailer();
			$valid = $mailer->isValidTemplate($correoBatch->getCuerpo());

			if ($valid != "success") {
				
				$this->setErrorMessage('La plantilla no es valida: ' .$valid);					
				return new Response(
							$this->renderView("CpmJovenesBundle:Correo:write_to_many.html.twig",
							array('form' => $correoBatchForm->createView(),
								  "proyectos" => $correoBatch->getProyectos()
				)));
			}
				
				$example = $proyectos[0];
				$context = array (
					Plantilla :: _USUARIO => $example->getCoordinador(),
					Plantilla :: _PROYECTO => $example,
					Plantilla :: _URL => '?',
					Plantilla :: _URL_SITIO => $mailer->getParameter('url_sitio'),
					Plantilla :: _FECHA => new \ DateTime()
				);
				//FIXME, estas cosas con las variables del context lo deebria hacer el mailer
				try {
					$template = $mailer->renderTemplate($correoBatch->getCuerpo(), $context);
					$cuerpo = $template;
				}
				 catch(InvalidTemplateException $e){
						$this->setErrorMessage('La plantilla no es valida: ' .$e->getPrevious()->getMessage());
						return new Response(
											$this->renderView("CpmJovenesBundle:Correo:write_to_many.html.twig",
															array('form' => $correoBatchForm->createView(), 
																  "proyectos" => $correoBatch->getProyectos()
											)));
				}
				
				if ($correoBatch->getPreview()) //aun no deben mandarse los emails, sino que hay que previsualizarlos 
				{
					$correoBatch->setPreview(false); //para la prox
					$correoBatchForm = $this->createForm(new CorreoBatchType(), $correoBatch);
					$content = $this->renderView("CpmJovenesBundle:Correo:write_to_many.html.twig", array (
						'form' => $correoBatchForm->createView(),
						'proyectos' => $proyectos,
						'show_preview' => true,
						'asunto' => $correoBatch->getAsunto(),
						'cuerpo' => $cuerpo
					));
					$this->setWarnMessage("Por favor, verifique el texto del correo antes de enviarlo");
					return new Response($content);
				}

				$correo = new Correo();
				$correo->setAsunto($correoBatch->getAsunto());
				$correo->setCuerpo($correoBatch->getCuerpo());
				$correo->setEmisor($this->getLoggedInUser());
				$cant = 0;
				try{
					foreach ($proyectos as $proyecto) {
	
						$correo->setProyecto($proyecto);
						if ($correoBatch->getCcColaboradores()) {
							$enviados = $mailer->enviarCorreoAColaboradores($correo);
							$cant += count($enviados);
						}
	
						if ($correoBatch->getCcEscuelas()) {
							$mailer->enviarCorreoAEscuela($correo);
							$cant++;
						}
	
						if ($ccCoordinadores = $correoBatch->getCcCoordinadores()) {
							$mailer->enviarCorreoACoordinador($correo);
							$cant++;
						}
					}
					$this->setSuccessMessage("Se enviaron $cant correos satisfactoriamente");
					return $this->redirect($this->generateUrl('proyecto'));
				}catch(InvalidTemplateException $e){
					$this->setErrorMessage('La plantilla no es valida: ' .$e->getPrevious()->getMessage());
				}catch(MailCannotBeSentException $e){
					$this->setErrorMessage("Se produjo un error al tratar de enviar los correos. Espere unos minutos e intente nuevamente. Si el problema persiste, contáctese con los administradores.".($cant?"Sin embargo, se enviaron $cant correos satisfactoriamente":""));
				}	
			
				  	
			} // form->isValid

			return array (
				'form' => $correoBatchForm->createView(),
				'proyectos' => $correoBatch->getProyectos()
			);
		}
	}