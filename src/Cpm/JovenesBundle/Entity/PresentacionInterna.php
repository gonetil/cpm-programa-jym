<?php
/*
 * Created on 04/09/2013
 * @author gonetil
 * project jym
 * Copyleft 2013
 * 
 */

namespace Cpm\JovenesBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Cpm\JovenesBundle\Entity\Presentacion
 *
 * @ORM\Table
 * @ORM\Entity(repositoryClass="Cpm\JovenesBundle\Entity\PresentacionRepository")
 */
class PresentacionInterna extends Presentacion
{
	  
    /**
    * @ORM\ManyToOne(targetEntity="Proyecto")
    */
    private $proyecto;
    
    /**
    * @ORM\ManyToOne(targetEntity="Distrito")
 
    private $distrito;
   */
    /**
    * @ORM\ManyToOne(targetEntity="Localidad")
    
    private $localidad;
	*/
    
    /**
    * @ORM\ManyToOne(targetEntity="Invitacion")
    */
	private $invitacion;

	static function createFromInvitacion($invitacion) {
		$proyecto = $invitacion->getProyecto();
		$presentacion = new PresentacionInterna();
		$presentacion->setInvitacion($invitacion);
		$presentacion->setProyecto($proyecto);
	/*	$presentacion->setLocalidad( $proyecto->getEscuela()->getLocalidad() );
		$presentacion->setDistrito( $proyecto->getEscuela()->getLocalidad()->getDistrito() );
		$presentacion->setTitulo($proyecto->getTitulo());
		$presentacion->setEjeTematico($proyecto->getTemaPrincipal());
		$presentacion->setPersonasConfirmadas($invitacion->countInvitados());
		$presentacion->setAreaReferencia($proyecto->getEje());
		$presentacion->setTipoPresentacion($proyecto->getProduccionFinal());
		*/
		return $presentacion;
		
	}
    /**
     * Set proyecto
     *
     * @param Cpm\JovenesBundle\Entity\Proyecto $proyecto
     */
    public function setProyecto(\Cpm\JovenesBundle\Entity\Proyecto $proyecto)
    {
        $this->proyecto = $proyecto;
    }

    /**
     * Get proyecto
     *
     * @return Cpm\JovenesBundle\Entity\Proyecto 
     */
    public function getProyecto()
    {
        return $this->proyecto;
    }

    /**
     * Set distrito
     *
     * @param Cpm\JovenesBundle\Entity\Distrito $distrito
     */
    public function setDistrito(\Cpm\JovenesBundle\Entity\Distrito $distrito)
    {
        $this->distrito = $distrito;
    }

    /**
     * Get distrito
     *
     * @return Cpm\JovenesBundle\Entity\Distrito 
     */
    public function getDistrito()
    {
        return $this->getProyecto()->getEscuela()->getDistrito();
    }

    /**
     * Set localidad
     *
     * @param Cpm\JovenesBundle\Entity\Localidad $localidad
     */
    public function setLocalidad(\Cpm\JovenesBundle\Entity\Localidad $localidad)
    {
        $this->localidad = $localidad;
    }

    /**
     * Get localidad
     *
     * @return Cpm\JovenesBundle\Entity\Localidad 
     */
    public function getLocalidad()
    {
        return $this->getProyecto()->getEscuela()->getLocalidad();
    }
    
    
    public function getEscuela() {
    	
    	$ignore_list = array('de','sin','no','del','el','la','los','las');
    	
    	$escuela = $this->proyecto->getEscuela();
    	$nombre = "";
    	$tipo_inst =  ( ( $escuela->getTipoInstitucion() != null ) ? $escuela->getTipoInstitucion()->getId() : 0 );
    	switch ($tipo_inst) {
    		case 1 : //escuela publica
					 $palabras = preg_split("/ /",$escuela->getTipoEscuela()->getNombre() );
					 if (count($palabras) > 1) 
						 foreach ( $palabras as $pal) {
	       						if (!in_array($pal,$ignore_list))
	       							$nombre .= strtoupper(substr($pal,0,1));
							}
					else $nombre = $palabras[0];		
						
					$nombre .= " No. ".$escuela->getNumero();	
					break;	
					    			
    		case 2 : //escuela privada
    				$nombre = $escuela->getNombre();
    				break;
    		default : //otra, en realidad NULL
    				$nombre = $escuela->getOtroTipoInstitucion() . " " . $escuela->getNombre();

    	}
    	return $nombre;
    }
    

    /**
     * Get apellido_coordinador
     *
     * @return string 
     */
    public function getApellidoCoordinador()
    {
        return $this->getProyecto()->getCoordinador()->getApellido();
    }


    /**
     * Get nombre_coordinador
     *
     * @return string 
     */
    public function getNombreCoordinador()
    {
        return $this->getProyecto()->getCoordinador()->getNombre();
    }
    
    public function getInvitacion() {
    	return $this->invitacion;
    }
    public function setInvitacion($inv) {
    	$this->invitacion = $inv;
    }
    
    public function getTitulo() { return $this->proyecto->getTitulo(); }
    public function getEjeTematico() { return $this->proyecto->getTemaPrincipal(); }
    public function getAreaReferencia() { return $this->proyecto->getEje(); }
    public function getTipoPresentacion() { return $this->proyecto->getProduccionFinal(); }
    public function getPersonasConfirmadas() { return $this->invitacion->countInvitados(); }
    	
}