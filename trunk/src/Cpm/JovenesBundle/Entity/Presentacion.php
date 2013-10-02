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
     * @ORM\JoinColumn(name="bloque_id", referencedColumnName="id", nullable=true, onDelete="SET NULL")
     */
     
	private $bloque;
	
    /**
     * @ORM\ManyToOne(targetEntity="Tanda", inversedBy="presentaciones")
     * @ORM\JoinColumn(name="tanda_id", referencedColumnName="id", nullable=false)
     */
    private $tanda;
    
    public abstract function getTitulo();
	public abstract function getEjeTematico();
    public abstract function getAreaReferencia();
	public abstract function getTipoPresentacion();
	public abstract function getEscuela();
	public abstract function getProvincia();
	public abstract function getLocalidad();
	public abstract function getDistrito();
    public abstract function getApellidoCoordinador();
    public abstract function getNombreCoordinador();
    public abstract function getPersonasConfirmadas();
    public abstract function getTipo();
    public abstract function getValoracion();
        
    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
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
    
    public function esExterna() { return ($this->getTipo() == 'externa'); }
    
    public function getTanda() {
    	return $this->tanda;
    }
    
    public function setTanda(\Cpm\JovenesBundle\Entity\Tanda $t) {
    	if (!empty($this->tanda) && $this->tanda->equals($t))
    		return;//no hago nada,la tanda no cambio
    	$this->tanda = $t;
    	$this->bloque=null;
    }
    
  
    public function getInvitacion() {
    	return null; //las presentaciones no tienen invitaciones, salvo las presentaciones internas
    }
    
    public function makeItSafe($str) {
    	
    	return (
    				stripslashes
    					(
    						str_ireplace(
    							array('\"',"\'",'"',"“","”"), 
    							"'", 
    							$str 
    						 ) 
    					)
    			);
    }
    public function toArray($recursive_depth) 
    {
		if ($recursive_depth == 0)
    		return $this->getId();
    	
    	return array(
			 		'id' => "{$this->id}",
			    	'bloque' => (!$this->bloque)?'' : "{$this->bloque->getId()}",
			    	'tanda' => "{$this->getTanda()->getId()}",
			    	'titulo' => $this->makeItSafe($this->getTitulo()),
			    	'ejeTematico' => (!$this->getEjeTematico())?'': $this->getEjeTematico()->toArray($recursive_depth-1),
			    	'areaReferencia' => (!$this->getAreaReferencia())?'' : $this->getAreaReferencia()->toArray($recursive_depth-1),
			    	'tipoPresentacion' => (!$this->getTipoPresentacion())?'': $this->getTipoPresentacion()->toArray($recursive_depth-1),
			    	'escuela' => $this->getEscuela(),
			    	'provincia' => $this->getProvincia(),
			    	'localidad' => $this->getLocalidad(),
			    	'distrito' => $this->getDistrito(),
			    	'apellidoCoordinador' => $this->getApellidoCoordinador(),
			    	'nombreCoordinador' => $this->getNombreCoordinador(),
			    	'personasConfirmadas' => $this->getPersonasConfirmadas(),
			    	'valoracion' => $this->getValoracion(),
			    	'tipo' => $this->getTipo()
			  );
    }
    
    
    
}
