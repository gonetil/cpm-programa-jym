<?php

namespace Cpm\JovenesBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Cpm\JovenesBundle\Entity\Tanda
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Cpm\JovenesBundle\Entity\TandaRepository")
 */
class Tanda
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
     * @var integer $numero
     *
     * @ORM\Column(name="numero", type="integer")
     */
    private $numero;

    /**
     * @var datetime $fechaInicio
     *
     * @ORM\Column(name="fechaInicio", type="datetime")
     */
    private $fechaInicio;

    /**
     * @var datetime $fechaFin
     *
     * @ORM\Column(name="fechaFin", type="datetime")
     */
    private $fechaFin;

	/**
     * @ORM\OneToOne(targetEntity="InstanciaEvento")
     * @ORM\JoinColumn(name="instanciaEvento_id", referencedColumnName="id", nullable=false)
     */
    private $instanciaEvento;

	/**
     * @ORM\OneToMany(targetEntity="Dia", mappedBy="tanda", cascade={"all"})
     * @ORM\OrderBy({"numero" = "ASC"})
   
     **/
    private $dias;

       
    /**
    * @var boolean $completada
    *
    * @ORM\Column(name="completada", type="boolean")
    * Indica si la tanda ya esta completada, o sea si su informacion se incluye al exportarse al JSON
    */
    private $completada=false;
           
    
    /**
     * @ORM\OneToMany(targetEntity="Presentacion", mappedBy="tanda", cascade={"all"})
     */
	private $presentaciones;
    
    function __construct($instancia = null){
    	if($instancia != null){
			$this->fechaInicio=$instancia->getFechaInicio();
		    $this->fechaFin=$instancia->getFechaFin();	
		    $this->instanciaEvento=$instancia;
    	}
    	$this->setPresentaciones(array());
    	$this->setDias(array());
    }
    
    /**
     * Get ciclo
     *
     * @return Cpm\JovenesBundle\Entity\Ciclo
     */
    public function getCiclo()
    {
       if ($this->instanciaEvento)
	        return $this->instanciaEvento->getCiclo();
	   else return null;     
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
     * Set numero
     *
     * @param integer $numero
     */
    public function setNumero($numero)
    {
        $this->numero = $numero;
    }

    /**
     * Get numero
     *
     * @return integer 
     */
    public function getNumero()
    {
        return $this->numero;
    }

    /**
     * Set fechaInicio
     *
     * @param datetime $fechaInicio
     */
    public function setFechaInicio($fechaInicio)
    {
        $this->fechaInicio = $fechaInicio;
    }

    /**
     * Get fechaInicio
     *
     * @return datetime 
     */
    public function getFechaInicio()
    {
        return $this->fechaInicio;
    }

    /**
     * Set fechaFin
     *
     * @param datetime $fechaFin
     */
    public function setFechaFin($fechaFin)
    {
        $this->fechaFin = $fechaFin;
    }

    /**
     * Get fechaFin
     *
     * @return datetime 
     */
    public function getFechaFin()
    {
        return $this->fechaFin;
    }
    
    public function getInstanciaEvento()
    {
    	return $this->instanciaEvento;
    }

    public function setInstanciaEvento($ie)
    {
    	$this->instanciaEvento = $ie;
    }
 
 	public function getPresentaciones() {
 		return $this->presentaciones;
 	}
 	
 	public function setPresentaciones($pp) {
 		if (!($pp instanceof \Doctrine\Common\Collections\Collection))
 			$pp = new \Doctrine\Common\Collections\ArrayCollection($pp);
 		$this->presentaciones = $pp;
 	}
 	
 	public function addPresentacion(\Cpm\JovenesBundle\Entity\Presentacion $p) {
 		$this->presentaciones->add($p);
 		$p->setTanda($this);
 	}
 	
 	public function removePresentacion(\Cpm\JovenesBundle\Entity\Presentacion $p) {
 		$this->presentaciones->removeElement($p);
 		//No se le puede sacar la tanda $p->setTanda(null);
 	}

    public function getDias()
    {
    	return $this->dias;
    }

    public function setDias($dias)
    {
    	if (!($dias instanceof \Doctrine\Common\Collections\Collection))
 			$dias = new \Doctrine\Common\Collections\ArrayCollection($dias);
 		$this->dias = $dias;
    }
	
	function removeDia($diaABorrar){
		$this->dias->removeElement($diaABorrar);
		$this->reordenarDias();
	}
	
	function addDia($nuevoDia){
		$numero = $nuevoDia->getNumero();
		
		if (count($this->dias) == 0)
			$numero = 1;
		elseif (($numero < 0) || $numero > count($this->dias))
			$numero=$this->dias->last()->getNumero() ;
		
		foreach($this->dias as $dia){
			if ($dia->getNumero() > $numero)
				$dia->setNumero($dia->getNumero());
		}
		
		$nuevoDia->setNumero($numero);
		$this->dias->add($nuevoDia);
		$nuevoDia->setTanda($this);
		$this->reordenarDias();

	}
    
	protected function reordenarDias(){
		$numero = 1;
		foreach($this->dias as $dia){
			if ($dia->getNumero() != $numero)
				$dia->setNumero($numero);
			$numero      ; 
		}
	}

       
   public function getCompletada() {
       return $this->completada;
   }

   public function setCompletada($aBool) {
       $this->completada = $aBool;
   }
   
    
    public function __toString() {
    	return $this->getNumero()." (".$this->getFechaInicio()->format('d/m/Y').")";
    }
		
	 public function toArray($recursive_depth) {
    	if ($recursive_depth == 0)
    		return $this->getId();
    	elseif ($recursive_depth == 1){
    		//solo se retorna el count de las colecciones, no el contenido
    		$dias=count($this->getDias());
    		$presentaciones=count($this->getPresentaciones());
    	}else{
	  		//saque los array map porque duplicaban la estructura del array 
	    	$dias = array();
	    	foreach ( $this->getDias() as $dia )
	       		$dias[] = $dia->toArray($recursive_depth-1);
	
	    	$presentaciones = array();
	    	foreach ( $this->getPresentaciones() as $p ) 
				$presentaciones[] = $p->toArray($recursive_depth-1);
    	}
  		
    	
    	return array(
					'id' => $this->id,
					'numero' => $this->numero,
					'titulo' => $this->instanciaEvento->getTitulo()." - Tanda ".$this->numero,					 
					'fechaInicio' => date_format($this->fechaInicio,"d-m-y") ,
					'fechaFin' => date_format($this->fechaFin,"d-m-y"),
					'instanciaEvento' => $this->instanciaEvento->toArray(1),					 
 					'dias' => $dias,
 					'presentaciones' => $presentaciones,
 		);
    }
    
    public function equals($other){
    	return ($other instanceof Tanda) && ($this->getId() == $other->getId());
    }
}