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
 * @ORM\MappedSuperclass
 * @ORM\InheritanceType("JOINED")
 * @ORM\DiscriminatorColumn(name="discr", type="string")
 * @ORM\DiscriminatorMap({"interna" = "PresentacionInterna", "externa" = "PresentacionExterna"})
 * 
 * @ORM\Entity(repositoryClass="Cpm\JovenesBundle\Entity\PresentacionRepository")
 */
abstract class Presentacion
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
     * @ORM\ManyToOne(targetEntity="Bloque", inversedBy="presentaciones")
     * @ORM\JoinColumn(name="bloque_id", referencedColumnName="id", nullable=true)
     */
     
	private $bloque;
	
    /**
     * @ORM\ManyToOne(targetEntity="Tanda", inversedBy="presentaciones")
     * @ORM\JoinColumn(name="tanda_id", referencedColumnName="id", nullable=false)
     */
    private $tanda;
    
    public abstract function getTipo();
	
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
     * Set ejeTematico
     *
     * @param Cpm\JovenesBundle\Entity\Tema $ejeTematico
     */
    public function setEjeTematico(\Cpm\JovenesBundle\Entity\Tema $ejeTematico)
    {
        $this->ejeTematico = $ejeTematico;
    }

    /**
     * Get ejeTematico
     *
     * @return Cpm\JovenesBundle\Entity\Tema 
     */
    public function getEjeTematico()
    {
        return $this->ejeTematico;
    }

    /**
     * Set areaReferencia
     *
     * @param Cpm\JovenesBundle\Entity\Eje $areaReferencia
     */
    public function setAreaReferencia(\Cpm\JovenesBundle\Entity\Eje $areaReferencia)
    {
        $this->areaReferencia = $areaReferencia;
    }

    /**
     * Get areaReferencia
     *
     * @return Cpm\JovenesBundle\Entity\Eje 
     */
    public function getAreaReferencia()
    {
        return $this->areaReferencia;
    }

    /**
     * Set tipoPresentacion
     *
     * @param Cpm\JovenesBundle\Entity\Produccion $tipoPresentacion
     */
    public function setTipoPresentacion(\Cpm\JovenesBundle\Entity\Produccion $tipoPresentacion)
    {
        $this->tipoPresentacion = $tipoPresentacion;
    }

    /**
     * Get tipoPresentacion
     *
     * @return Cpm\JovenesBundle\Entity\Produccion 
     */
    public function getTipoPresentacion()
    {
        return $this->tipoPresentacion;
    }
    
    public function hasBloque() {
    	return !empty($this->bloque);
    }
    
    public function getBloque() {
    	return $this->bloque;
    }
    
    public function setBloque($b) {
    	$this->bloque = $b;
    }
    
        
    public function getTanda() {
    	return $this->tanda;
    }
    
    public function setTanda($t) {
    	$this->tanda = $t;
    }
    
    abstract function getApellidoCoordinador();
    abstract function getNombreCoordinador();
    
        
    public function getPersonasConfirmadas() {
    	return $this->personas_confirmadas;
    }
    
    public function setPersonasConfirmadas($pc) {
    	$this->personas_confirmadas = $pc;
    }
    
    public function getInvitacion() {
    	return null; //las presentaciones no tienen invitaciones, salvo las presentaciones internas
    }
    
    public function toArray($recursive_depth) 
    {
		if ($recursive_depth == 0)
    		return $this->getId();
    	
    	return array(
			 		'id' => "{$this->id}",
			    	'titulo' => $this->getTitulo(),
			    	'tanda_id' => "{$this->getTanda()->getId()}",
			    	'areaReferencia' => (!$this->getAreaReferencia())?'' : $this->getAreaReferencia()->toArray($recursive_depth-1),
			    	'ejeTematico' => (!$this->getEjeTematico())?'': $this->getEjeTematico()->toArray($recursive_depth-1),
			    	'tipo' => $this->getTipo(),
			    	'bloque' => (!$this->bloque)?'' : "{$this->bloque->getId()}",
			  );
    }
}
