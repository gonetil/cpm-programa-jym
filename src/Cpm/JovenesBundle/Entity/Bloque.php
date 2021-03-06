<?php

namespace Cpm\JovenesBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Cpm\JovenesBundle\Entity\Bloque
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Cpm\JovenesBundle\Entity\BloqueRepository")
 */
class Bloque
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
     * @var datetime $horaInicio
     *
     * @ORM\Column(name="horaInicio", type="datetime")
     */
    private $horaInicio;

    /**
     * @var integer $duracion
     *
     * @ORM\Column(name="duracion", type="integer")
     */
    private $duracion=15;

 	/**
     * @ORM\ManyToOne(targetEntity="AuditorioDia")
     * @ORM\JoinColumn(name="auditorioDia_id", referencedColumnName="id", nullable=false, onDelete="CASCADE")
     */
    private $auditorioDia;
    
   /**
     * @var string $nombre
     *
     * @ORM\Column(name="nombre", type="string")
     */
    private $nombre;

   /**
     * @var boolean tienePresentaciones
     *
     * @ORM\Column(name="tienePresentaciones", type="boolean", nullable=true)
     */
    private $tienePresentaciones=true;

    /**
     * @ORM\ManyToMany(targetEntity="Tema", inversedBy="bloques",cascade={"persist"})
     * @ORM\JoinTable(name="BloqueTema")
     **/
    public $ejesTematicos;
    
    
    /**
     * @ORM\ManyToMany(targetEntity="Eje", inversedBy="bloques",cascade={"persist"})
     * @ORM\JoinTable(name="BloqueEje")
     **/
    public  $areasReferencia;


    /**
     * @ORM\OneToMany(targetEntity="Presentacion", mappedBy="bloque")
     * @ORM\OrderBy({"posicion" = "ASC"})
     */
	private $presentaciones;
     
    function __construct(){
    	$this->ejesTematicos = new \Doctrine\Common\Collections\ArrayCollection();
    	$this->areasReferencia=new \Doctrine\Common\Collections\ArrayCollection();
    	$this->presentaciones = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set id
     *
     * @param integer $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * Set horaInicio
     *
     * @param datetime $horaInicio
     */
    public function setHoraInicio($horaInicio)
    {
        $this->horaInicio = $horaInicio;
    }

    /**
     * Get horaInicio
     *
     * @return datetime 
     */
    public function getHoraInicio()
    {
        return $this->horaInicio;
    }

    /**
     * Set duracion
     *
     * @param integer $duracion
     */
    public function setDuracion($duracion)
    {
        $this->duracion = $duracion;
    }

    /**
     * Get duracion
     *
     * @return integer 
     */
    public function getDuracion()
    {
        return $this->duracion;
    }
    
    public function getAuditorioDia() {
		return $this->auditorioDia;
	}
	public function setAuditorioDia($d) {
		$this->auditorioDia = $d;
	}
	
	  /**
     * Set nombre
     *
     * @param string $nombre
     */
    public function setNombre($nombre)
    {
        $this->nombre = $nombre;
    }

    /**
     * Get nombre
     *
     * @return string 
     */
    public function getNombre()
    {
        return $this->nombre;
    }
 
 
 	public function getTienePresentaciones() {
 		return $this->tienePresentaciones;
 	}
 	
 	public function setTienePresentaciones($lasTiene) {
 		$this->tienePresentaciones = $lasTiene;
 	}
 	   
 	public function getPresentaciones() {
 		return $this->presentaciones;
 	}
 	
 	public function setPresentaciones($presentaciones) {
    	if (!($presentaciones instanceof \Doctrine\Common\Collections\Collection))
    		$presentaciones = new \Doctrine\Common\Collections\ArrayCollection($presentaciones);
    	$this->presentaciones=$presentaciones;
 	}
    
	public function addPresentacion(\Cpm\JovenesBundle\Entity\Presentacion $p) {
		$this->presentaciones->add($p);
		$p->setBloque($this);
	}
	
    public function removePresentacion(\Cpm\JovenesBundle\Entity\Presentacion $p) {
    	$this->presentaciones->removeElement($p);
    	$p->setBloque(null);
    }
	
    public function setEjesTematicos($ejesTematicos)
    {
    	$this->ejesTematicos=$ejesTematicos;
    }

    public function getEjesTematicos()
    {
        return $this->ejesTematicos;
    }

    public function setAreasReferencia($areasReferencia)
    {
    	$this->areasReferencia=$areasReferencia;
    }

    public function getAreasReferencia()
    {
        return $this->areasReferencia;
    }
    
    public function getCiclo(){
    	return $this->auditorioDia->getCiclo();
    }
    
    public function reposicionarPresentacion($presentacion, $nueva_posicion){
		if ($nueva_posicion <=0){
			$lastP = null;
			$maxP = 0;
			for($i=0;$i<count($this->presentaciones);$i++) {
		   		$p=$this->presentaciones->get($i);
		   		if ($p->getPosicion() > $maxP){
		   			$maxP = $p->getPosicion();
		   		}
			}
			$nueva_posicion = $maxP+1;
		}else{
			for($i=0;$i<count($this->presentaciones);$i++) {
				$p = $this->presentaciones->get($i);
				if ($p->getPosicion() >= $nueva_posicion) {
					$p->setPosicion($p->getPosicion()+1);
					$em->persist($p);
				}
			}
		}
		$presentacion->setPosicion( $nueva_posicion );
    }
    
    public function __toString() {
    	return "Bloque: ".$this->nombre."; Tanda ".$this->getAuditorioDia()->getDia()->getTanda()."";
    }
    
    public function toArray($recursive_depth) {
 		if ($recursive_depth == 0)
    		return $this->getId();
    	
       	$presentaciones = array_map(function($presentacion) { global $recursive_depth; return $presentacion->toArray($recursive_depth-1); }, $this->getPresentaciones()->toArray());
       	$ejes = array_map(function($eje) {return $eje->toArray(1); }, $this->getEjesTematicos()->toArray());
       	
       	$areas = array_map(function($area) {return $area->toArray(1); }, $this->getAreasReferencia()->toArray());

		return array(
							'id' => $this->id , 
 							 'nombre' => $this->nombre,
 							 'tienePresentaciones' => ($this->tienePresentaciones?true:false),
 							 'duracion' => $this->duracion,
 							 'horaInicio' => date_format($this->horaInicio,"H:i"),
 							 'auditorioDia' => $this->auditorioDia->getId(),
 							 'presentaciones' => $presentaciones,
 							 'ejesTematicos' => $ejes,
 							 'areasReferencia' => $areas
 				);				 
    }
    
   public function equals($other)
    {
    	if ($other instanceof Bloque)
        	return $this->getId() == $other->getId();
        else
        	return false;
    }
}