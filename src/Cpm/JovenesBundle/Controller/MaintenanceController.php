<?php

namespace Cpm\JovenesBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Response;

use Cpm\JovenesBundle\Entity\Proyecto;
use Cpm\JovenesBundle\Entity\EstadoProyecto;

/**
 * Maintenance controller.
 *
 * @Route("/maintenance")
 */
class MaintenanceController extends BaseController
{

   /**
     * Does nothing
     *
     * @Route("/", name="maintenance")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entities = $em->getRepository('CpmJovenesBundle:Proyecto')->findAllQuery();

        return $this->paginate($entities);
    }

		/**
		 * Para cada proyecto con archivo adjunto, crea un estado "Presentado", al cual le asigna ese archivo, y se lo asigna al proyecto
		 * La fecha del estado será la fecha de modificación del archivo en el servidor
		 * El usuario del estado será el coordinador del proyecto
		 * @Route("/asignar_estados_presentado", name="asignar_estados_presentado")
	     * @Method("get")
		 */
		public function crear_estados_presentado() {
	    	$em = $this->getDoctrine()->getEntityManager();
	        $proyectos = $em->createQuery(
								    'SELECT p FROM CpmJovenesBundle:Proyecto p WHERE p.archivo is not null'
								)->getResult();

			$estadosManager = $this->getEstadosManager();
			$ignorados = $creados= 0;
			foreach ( $proyectos as $proyecto ) {
				$id = $proyecto->getId();
				$filename = $this->getUploadDir()."$id/".$proyecto->getArchivo();
       			$nuevoEstado = new EstadoProyecto();
       			
       			$nuevoEstado->setEstado(ESTADO_PRESENTADO);
       			$nuevoEstado->setUsuario($proyecto->getCoordinador());
       			$nuevoEstado->setArchivo($proyecto->getArchivo());
       			$nuevoEstado->setProyecto($proyecto);
       			$proyecto->setEstadoActual($nuevoEstado);
       			
       			if (file_exists($filename)) { 
       				echo "Procesando $id . Archivo: ".$proyecto->getArchivo()."<br/>";
       				$last_modified =  new \DateTime(date ("F d Y H:i:s.", filemtime($filename)));
       				$nuevoEstado->setFecha($last_modified);
       				$em->persist($proyecto);
		    		$em->persist($nuevoEstado);
		    		$em->flush();
		    		$creados++;
       			} else { 
       				echo "<strong>Proyecto".$proyecto->getId()." : no se encontro el archivo $filename</strong><br/>";
       				$ignorados++;
       			}
			}
			return new Response("Todo ok. Se creadon $creados estados y se ignoraron $ignorados proyectos");

			
			
		}	
}