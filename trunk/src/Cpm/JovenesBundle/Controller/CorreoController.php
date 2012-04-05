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