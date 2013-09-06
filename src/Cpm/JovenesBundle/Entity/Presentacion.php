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
     * @var string $titulo
     *
     * @ORM\Column(name="titulo", type="string", length=255)
     */
    private $titulo;


   /**
    *  @ORM\ManyToOne(targetEntity="Tema")
     *  @ORM\JoinColumns({
    *   @ORM\JoinColumn(name="tema_id", referencedColumnName="id")
    * })
    */
    private $ejeTematico;
    
    /**
    *  @ORM\ManyToOne(targetEntity="Eje")
    *  @ORM\JoinColumns({
    *   @ORM\JoinColumn(name="eje_id", referencedColumnName="id")
    * })
    */
    private $areaReferencia;

    /**
    *  @ORM\ManyToOne(targetEntity="Produccion")
    *  @ORM\JoinColumns({
    *   @ORM\JoinColumn(name="produccion_id", referencedColumnName="id")
    * })
    */
    private $tipoPresentacion;

	
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
}