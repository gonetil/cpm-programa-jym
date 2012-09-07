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
			//$this->titulo = ($this->proyecto->getTitulo());
			//$this->temaPrincipal = ($this->proyecto->getTemaPrincipal());
			//$this->produccionFinal = ($this->proyecto->getProduccionFinal());
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
        //$this->temaPrincipal = $temaPrincipal;
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
//        $this->produccionFinal = $produccionFinal;
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
	
	
}