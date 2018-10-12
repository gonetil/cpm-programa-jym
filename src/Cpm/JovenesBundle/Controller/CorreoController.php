<?php
namespace Cpm\JovenesBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Response;

use Cpm\JovenesBundle\Entity\Correo;
use Cpm\JovenesBundle\Form\CorreoType;


use Cpm\JovenesBundle\EntityDummy\CorreoBatch;
use Cpm\JovenesBundle\Form\CorreoBatchType;

use Cpm\JovenesBundle\EntityDummy\CorreoUsuarioBatch;
use Cpm\JovenesBundle\Form\CorreoUsuarioBatchType;


use Cpm\JovenesBundle\Entity\Plantilla;
use Cpm\JovenesBundle\Exception\Mailer\InvalidTemplateException;
use Cpm\JovenesBundle\Exception\Mailer\MailCannotBeSentException;

use Cpm\JovenesBundle\Filter\CorreoFilterForm;
use Cpm\JovenesBundle\Filter\CorreoFilter;

use Cpm\JovenesBundle\Entity\Archivo;

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
	public function reenviarBatchAction($entitiesQuery){
		$entities=$entitiesQuery->getResult();
		
		$mailer = $this->getMailer();
		$enviados = 0;
		set_time_limit(900+15*count($entities));
		
		try{
			foreach ( $entities as $correoViejo) {
	       		$correo = $correoViejo->clonar(true);
				$correoEnviado = $mailer->enviarCorreo($correo);
				$enviados++;
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
		$entity = $this->getEntity("CpmJovenesBundle:Correo", $id_correo);

		return array (
			'entity' => $entity
		);
	}

	public function reenviarunavezAction(){
//TODO documentar, no se usa
		$cq= $this->getRepository("CpmJovenesBundle:Correo")->createQueryBuilder('c');
		$cq->andWhere("c.id in (:ids)")->setParameter('ids',$correos_ids);
		$correos = $cq->getQuery()->getResult();
		
		$mailer = $this->getMailer();
		$enviados = 0;
		try{
			foreach ( $correos as $correoViejo) {
				$correo = $correoViejo->clonar(true);
	
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
    	$correoViejo  = $this->getEntity("CpmJovenesBundle:Correo", $id);
    	
		$mailer = $this->getMailer();
		$correo = $correoViejo->clonar(true);
		
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
		$em = $this->getDoctrine()->getEntityManager();

		if ($form->isValid()) {
				$mailer = $this->getMailer();
			try{
				$adjuntos = $mailer->procesarAdjuntos($correo);	
				$correo->setArchivos($adjuntos);
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
	 * @Template("CpmJovenesBundle:Correo:show_correo_batch_form.html.twig")
	 */
	public function showCorreoBatchFormAction($entitiesQuery) {

		$correoBatch = new CorreoBatch();
		
		$proyectos = $entitiesQuery->getResult();
		$correoBatch->setProyectos(new \ Doctrine \ Common \ Collections \ ArrayCollection($proyectos));

		$correoBatchForm = $this->createForm(new CorreoBatchType(), $correoBatch);
		return array (
			'form' => $correoBatchForm->createView(),
			'proyectos' => $proyectos,
		);

	}
	

	/**
	*
	* Envia un correo masivo
	* @Route("/correo_batch_submit", name="correo_batch_submit")
	* @Template("CpmJovenesBundle:Correo:show_correo_batch_form.html.twig")
	*/
	public function correoBatchSubmitAction() {
		$request = $this->getRequest();
		$correoBatch = new CorreoBatch();

		$correoBatchForm = $this->createForm(new CorreoBatchType(), $correoBatch);
		$correoBatchForm->bindRequest($request);
		$proyectos = $correoBatch->getProyectos();
		$mailer = $this->getMailer();

		if ($correoBatchForm->isValid() && count($proyectos)) {
			
			//	$valid = $mailer->validateTemplate($correoBatch->getCuerpo());
	
			$correoMaestro = new Correo();
			$correoMaestro->setAsunto($correoBatch->getAsunto());
			$correoMaestro->setCuerpo($correoBatch->getCuerpo()); 
			$correoMaestro->setArchivos($correoBatch->getArchivos());
			$mailer->procesarAdjuntos($correoMaestro);
			
				
			if ($correoBatch->getPreview()) //aun no deben mandarse los emails, sino que hay que previsualizarlos 
			{
				$exampleCorreo = $correoMaestro->clonar(false);
				$exampleCorreo->setDestinatario($proyectos[0]->getCoordinador());
				$exampleCorreo->setProyecto($proyectos[0]);
				
				
				try {
					$exampleCorreo= $mailer->enviarCorreo($exampleCorreo, array(), true);
					$correoBatch->setPreviewText($exampleCorreo->getCuerpo()); 
					$correoBatch->setPreview(false);
					$correoBatch->setCuerpo($correoMaestro->getCuerpo());
					$this->setWarnMessage("Por favor, verifique el texto del correo antes de enviarlo");
				}catch(InvalidTemplateException $e){
						$this->setErrorMessage('La plantilla no es valida: ' .$e->getPrevious()->getMessage());
				}
				
			}else{
				//LO mando
				
//				$correoMaestro->setArchivos($adjuntos);	
				$cant = 0;
                $estimated_seconds_per_project = (  ($correoBatch->getCcCoordinadores() ? 3 : 0)
                                                + ($correoBatch->getCcEscuelas() ? 3 : 0)
                                                + ($correoBatch->getCcColaboradores() ? 6 : 0));

				set_time_limit(900+$estimated_seconds_per_project*count($proyectos));

				try{
					foreach ($proyectos as $proyecto) {
						$correo=$correoMaestro->clonar(false);
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
				
			}
				  	
		} // form->isValid

$correoBatchForm=$this->createForm(new CorreoBatchType(), $correoBatch);
		return array (
				'form' => $correoBatchForm->createView(),
				'proyectos' => $correoBatch->getProyectos()
		);
	}
		
		
		
		
	/**
	 * 
	 * Permite enviar un correo a un conjunto de Usuarios
	 * @Template("CpmJovenesBundle:Correo:show_correo_a_usuario_batch_form.html.twig")
	 */
	public function showCorreoUsuarioBatchFormAction($entitiesQuery) {

		$correoBatch = new CorreoUsuarioBatch();
		
		$usuarios = $entitiesQuery->getResult();
		$correoBatch->setUsuarios(new \ Doctrine \ Common \ Collections \ ArrayCollection($usuarios));

		$correoBatchForm = $this->createForm(new CorreoUsuarioBatchType(), $correoBatch);
		return array (
			'form' => $correoBatchForm->createView(),
			'usuarios' => $usuarios,
		);

	}
		
			/**
	*
	* Envia un correo masivo a muchos usuarios
	* @Route("/correo_usuario_batch_submit", name="correo_usuario_batch_submit")
	* @Template("CpmJovenesBundle:Correo:show_correo_a_usuario_batch_form.html.twig")
	*/
	public function correoUsuarioBatchSubmitAction() {
		$request = $this->getRequest();
		$correoBatch = new CorreoUsuarioBatch();

		$correoBatchForm = $this->createForm(new CorreoUsuarioBatchType(), $correoBatch);
		$correoBatchForm->bindRequest($request);
		$usuarios = $correoBatch->getUsuarios();
		$mailer = $this->getMailer();

		if ($correoBatchForm->isValid() && count($usuarios)) {
			
			$correoMaestro = new Correo();
			$correoMaestro->setAsunto($correoBatch->getAsunto());
			$correoMaestro->setCuerpo($correoBatch->getCuerpo());
			$correoMaestro->setArchivos($correoBatch->getArchivos());
			$correoBatch->setCuerpo($correoMaestro->getCuerpo());
			$mailer->procesarAdjuntos($correoMaestro);
					
			if ($correoBatch->getPreview()) //aun no deben mandarse los emails, sino que hay que previsualizarlos 
			{
				$exampleCorreo = $correoMaestro->clonar(false);
				$exampleCorreo->setDestinatario($usuarios[0]);
				
				try {
					$exampleCorreo= $mailer->enviarCorreo($exampleCorreo, array(), true);
					$correoBatch->setPreviewText($exampleCorreo->getCuerpo()); 
					$correoBatch->setPreview(false);
					$this->setWarnMessage("Por favor, verifique el texto del correo antes de enviarlo");
				}catch(InvalidTemplateException $e){
						$this->setErrorMessage('La plantilla no es valida: ' .$e->getPrevious()->getMessage());
				}
				
			}else{
				
				//LO mando
				$cant = 0;
				set_time_limit(900+15*count($usuarios));
				try{
					foreach ($usuarios as $usuario) {
						$correo=$correoMaestro->clonar(false);
						$correo->setDestinatario($usuario);
						$correo->setEmail($usuario->getEmail());
						$mailer->enviarCorreo($correo);
						$cant++;
				
					}
					$this->setSuccessMessage("Se enviaron $cant correos satisfactoriamente");
					return $this->redirect($this->generateUrl('usuario'));
				}catch(InvalidTemplateException $e){
						$this->setErrorMessage('La plantilla no es valida: ' .$e->getPrevious()->getMessage());
				}catch(MailCannotBeSentException $e){
						$this->setErrorMessage("Se produjo un error al tratar de enviar los correos. Espere unos minutos e intente nuevamente. Si el problema persiste, contáctese con los administradores.".($cant?"Sin embargo, se enviaron $cant correos satisfactoriamente":""));
				}	
				
			}
				  	
		} // form->isValid

		$correoBatchForm=$this->createForm(new CorreoUsuarioBatchType(), $correoBatch);
			return array (
					'form' => $correoBatchForm->createView(),
					'usuarios' => $correoBatch->getUsuarios()
			);
	}
		
	
	
	
	
		
		
	/**
	 *
	 * @Route("/enviar_a_usuarios", name="correo_persnalizado")
	 */
	public function enviar_a_usuariosAction() {
		//TODO docuementar no se usa no?
		$usuarios_ids = array( 1,  7,  8,  9);
		$cq= $this->getRepository("CpmJovenesBundle:Usuario")->createQueryBuilder('c');
		$cq->andWhere("c.id in (:ids)")->setParameter('ids',$usuarios_ids);
		$destinatarios = $cq->getQuery()->getResult();
		
		$mailer = $this->getMailer();
		
		$cant = 0;
		try{
			foreach($destinatarios as $destinatario ){
				$correo = $mailer->getCorreoFromPlantilla('inscripci-n-al-programa');
				$correo->setDestinatario($destinatario);
				$mailer->enviarCorreo($correo);
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
	
			
	/**
	 * FIXME que pasa con este metodo?
	 * Envia un recordatorio a todos los coordinadores invitados a Chapa que aún no confirmaron ni rechazaron la invitación
	 * @Route("/recordar_chapa", name="recordar_chapa")
	 */
	public function recordar_chapaAction() {
		$chapa_id = 5; //el id interno de chapa
		//TODO ver este 5 hardcodeado para chapa
	   
		$chapa= $this->getEntity("CpmJovenesBundle:Evento", $chapa_id);

		$plantilla = 
		$query1 = $this->getRepository("CpmJovenesBundle:Correo")->createQueryBuilder("correo")->andWhere("correo.asunto like 'Invitación aún no confirmada'");
		$correos = $query1->getQuery()->getResult();
		$emails = array();
		foreach ( $correos as $correo) {
			$emails[] = $correo->getDestinatario();
      	}
      	
	   $query= $this->getRepository("CpmJovenesBundle:Invitacion")->createQueryBuilder('inv')->andWhere("inv.aceptoInvitacion is NULL");
	   $query->innerJoin('inv.instanciaEvento', 'ie')->innerJoin("ie.evento", "e")
	   	//	->innerJoin('inv.proyecto','p')->innerJoin('p.coordinador','coord')->andWhere('coord.email not in (:emails)')->setParameter('emails',$emails)
	   		 ->andWhere("e = :eventoChapa")->setParameter('eventoChapa',$chapa);			
	   		 
	   		 
	   $invitados = $query->getQuery()->getResult();
	
		
	   $mailer = $this->getMailer();
       set_time_limit(900+15*count($invitados));

		$cant = 0;
//		$plantilla = 'invitaci-n-a-n-no-confirmada';  //recordatorio para que confirmen la invitacion!
		$plantilla =  'inscripcion-finalizada';    //aviso de sorry laser, timeout
		try{
			foreach($invitados as $invitacion){
				$correo = $mailer->getCorreoFromPlantilla($plantilla);
				$correo->setProyecto($invitacion->getProyecto());
				$correo->setDestinatario($invitacion->getProyecto()->getCoordinador());
				$correo = $mailer->enviarCorreo($correo);
				$cant++;
				unset($correo);
				unset($invitacion);
				
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