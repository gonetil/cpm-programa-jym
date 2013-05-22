<?php

namespace Cpm\JovenesBundle\EntityDummy;

/**
 * formulario de confirmación de información del proyecto para Chapa
 */

class ConfirmacionCamposChapa {
	public $titulo;
	public $temaPrincipal;
	public $produccionFinal;
	private $proyecto;
	
	public $id;
	public function getId()
    {
        return $this->id;
    }
	
	public function __construct($invitacion=null)
	{ 
		if ($invitacion) 
		{ 
			$this->proyecto = $invitacion->getProyecto();
	
		} 
	}
	public function setTitulo($titulo)
    {
    	if ($this->proyecto) { 
        	$this->proyecto->setTitulo($titulo);
    	}
    	
    }

    public function getTitulo()
    {
    	if ($this->proyecto != null) 
	        return $this->proyecto->getTitulo();
	    else return null;    
    }
	
	
	public function setTemaPrincipal(\Cpm\JovenesBundle\Entity\Tema $temaPrincipal)
    {
        if ($this->proyecto != null)  
 	       $this->proyecto->setTemaPrincipal($temaPrincipal);
 	     
    }

    public function getTemaPrincipal()
    {
        if ($this->proyecto != null) 
        	return $this->proyecto->getTemaPrincipal();
        else return null;
    }

    public function setProduccionFinal(\Cpm\JovenesBundle\Entity\Produccion $produccionFinal)
    {
        if ($this->proyecto != null) { 
 	       $this->proyecto->setProduccionFinal($produccionFinal);
 	     } 
    }
    public function getProduccionFinal()
    {
    	if ($this->proyecto != null) 
	        return $this->proyecto->getProduccionFinal();
	    else
	    	return null;    
    }
    
    public function getEscuela() { 
    	return $this->proyecto->getEscuela();
    }

    public function setEscuela($esc) { 
    	$this->proyecto->setEscuela($esc);
    }
	
	public function getDeQueSeTrata() {
		return $this->proyecto->getDeQueSeTrata();
	}
	
	public function setDeQueSeTrata($dqst) {
		$this->proyecto->setDeQueSeTrata($dqst);
	}
	
	public function getEje() { 
		return $this->proyecto->getEje();
	}
	public function setEje($eje) {
		$this->proyecto->setEje($eje);
	}
	
}