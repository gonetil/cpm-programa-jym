<?php

namespace Cpm\JovenesBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Cpm\JovenesBundle\Entity\Ciclo
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Cpm\JovenesBundle\Entity\CicloRepository")
 */
class Ciclo
{
    /**
     * @var integer $id
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string $titulo
     *
     * @ORM\Column(name="titulo", type="string", length=255)
     */
    private $titulo;

    /**
     * @var boolean $activo
     *
     * @ORM\Column(name="activo", type="boolean")
     */
    private $activo;

    /**
     * @var Etapa $etapaActual
     *
     * @ORM\ManyToOne(targetEntity="Etapa")
     */
    private $etapaActual;
    
    /**
     */
    private $ciclo;

    /**
     * @var array $historial
     *
     * @ORM\Column(name="historial", type="array")
     */
    private $historial;


	public function __construct(){
		$this->activo = false;
		$this->etapaActual = '';
	}

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set titulo
     *
     * @param string $titulo
     */
    public function setTitulo($titulo)
    {
        $this->titulo = $titulo;
    }

    /**
     * Get titulo
     *
     * @return string 
     */
    public function getTitulo()
    {
        return $this->titulo;
    }

    /**
     * Set activo
     *
     * @param boolean $activo
     */
    public function setActivo($activo)
    {
        if ($this->activo == $activo)
        	return;
        
        if ($activo)
   			$desc = "Se activa el ciclo en la etapa ".$this->etapaActual;
   		else 
	   		$desc = "Se desactiva el ciclo en la etapa ".$this->etapaActual;
        
        $this->activo = $activo;
    	
        $this->add2Historial($desc);
    }

    /**
     * Get activo
     *
     * @return boolean 
     */
    public function getActivo()
    {
        return $this->activo;
    }

    /**
     * Set etapaActual
     *
     * @param Cpm\JovenesBundle\Entity\Etapa $etapaActual
     */
    public function setEtapaActual(\Cpm\JovenesBundle\Entity\Etapa $etapaActual)
    {
    	if (!empty($this->etapaActual) && ($etapaActual->equals($this->etapaActual))) 
    		return;
    	$desc = "Se pasa a la siguiente etapa [".$etapaActual->getNombre()."]";
        $this->etapaActual = $etapaActual;
        $this->add2Historial($desc);
    }

    /**
     * Get etapaActual
     *
     * @return Cpm\JovenesBundle\Entity\Etapa 
     */
    public function getEtapaActual()
    {
        return $this->etapaActual;
    }

    /**
     * Set historial
     *
     * @param array $historial
     */
    public function setHistorial($historial)
    {
        $this->historial = $historial;
    }

    /**
     * Get historial
     *
     * @return array 
     */
    public function getHistorial()
    {
        return $this->historial;
    }
    

	public function add2Historial($evento)
    {
        $this->historial[] = date("Y-m-d H:i")." : ".$evento;
    }
    
    public function __toString()
    {
        return $this->titulo;
    }
}