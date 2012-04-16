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
     * @Route("/", name="correo")
     * @Template()
     */
    public function indexAction()
    {
    	return $this->filterAction(new CorreoFilter(), 'correo');
    }

	/**
    */ 
	public function reenviarBatchAction($entities){
		$emisor = $this->getLoggedInUser();
		$mailer = $this->getMailer();
		$enviados = 0;
		try{
			foreach ( $entities as $correoViejo) {
	       		$correo = $correoViejo->clonar();
				$correo->setEmisor($emisor);
				$correoEnviado = $mailer->enviarCorreo($correo);
				$enviados++;
				echo "reenvio a ".$correoViejo->getEmail();
			}
		}catch(InvalidTemplateException $e){
			$this->setErrorMessage('La plantilla no es valida: ' .$e->getPrevious()->getMessage());
		}catch(MailCannotBeSentException $e){
			$this->setErrorMessage('No se pudo enviar el correo. Verifique que los datos ingresados sean válidos');
		}
		
		$this->setSuccessMessage('Se reenviaron '.$enviados.' correos con éxito');
		
		return $this->redirect($this->generateUrl('correo'));
		
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
		
		
		function fetchCorreoAction() { 
			
		}
		
		
		
		
	/**
	 *
	 * @Route("/enviar_a_usuarios", name="correo_persnalizado")
	 */
	public function enviar_a_usuariosAction() {
		
		$usuarios_ids = array( 1,  7,  8,  9);
		$cq= $this->getRepository("CpmJovenesBundle:Usuario")->createQueryBuilder('c');
		$cq->andWhere("c.id in (:ids)")->setParameter('ids',$usuarios_ids);
		$destinatarios = $cq->getQuery()->getResult();
		
		$mailer = $this->getMailer();
		
		$cant = 0;
		try{
			foreach($destinatarios as $destinatario ){
				$correo = $mailer->getCorreoFromPlantilla('inscripci-n-al-programa');
				$correo->setEmisor($this->getLoggedInUser());
				$correo->setDestinatario($destinatario);
				$correo = $mailer->enviarCorreo($correo);
				$cant++;
			}
		}catch(InvalidTemplateException $e){
				$this->setErrorMessage('La plantilla no es valida: ' .$e->getPrevious()->getMessage());
			}catch(MailCannotBeSentException $e){
				$this->setErrorMessage('No se pudo enviar el correo. Verifique que los datos ingresados sean válidos');
		}
		echo "se enviaron $cant correos";
		exit;
	}
}