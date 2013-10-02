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
    * @ORM\ManyToOne(targetEntity="Invitacion")
    */
	private $invitacion;

	public function getTipo() { return 'interna'; }

	static function createFromInvitacion($invitacion) {
		$proyecto = $invitacion->getProyecto();
		$presentacion = new PresentacionInterna();
		$presentacion->setInvitacion($invitacion);
		$presentacion->setProyecto($proyecto);
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

    public function getDistrito()
    {
        return $this->getProyecto()->getEscuela()->getDistrito()->getNombre();
    }

    public function getLocalidad()
    {
        return $this->getProyecto()->getEscuela()->getLocalidad()->getNombre();
    }
    
    
	
	public function getProvincia()
    {
        return 'Buenos Aires';
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
    public function getPersonas_confirmadas() { return $this->getPersonasConfirmadas(); }	

	public function getValoracion() {
		$estado = $this->getProyecto()->getEstadoActual();
		if (!is_null($estado))
			return $estado->getValoracion();
		else
			return null;	
	}
	
}