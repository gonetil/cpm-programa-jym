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
     * @ORM\JoinColumn(name="instanciaEvento_id", referencedColumnName="id")
     */
    private $instanciaEvento;

	/**
     * @ORM\OneToMany(targetEntity="Dia", mappedBy="tanda", cascade={"all"})
     * @ORM\OrderBy({"numero" = "ASC"})
   
     **/
    private $dias;
    
    
    /**
     * @ORM\OneToMany(targetEntity="Presentacion", mappedBy="tanda", cascade={"all"})
     */
	private $presentaciones;
    
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

    public function getDias()
    {
    	return $this->dias;
    }

    public function setDias($d)
    {
    	$this->dias = $d;
    }
    
    public function addDia($d) {
    	$this->dias[] = $d;
    }
    
    public function __toString() {
    	return $this->getNumero()." (".$this->getFechaInicio()->format('d/m/Y').")";
    }
 
 	public function getPresentaciones() {
 		return $this->presentaciones;
 	}
 	
 	public function setPresentaciones($pp) {
 		$this->presentaciones = $pp;
 	}
 	
 	public function addPresentacion($p) {
 		$this->presentaciones[] = $p;
 	}
    
	static function createFromInstancia($instancia, $numero = 0) {
		$tanda = new Tanda();
		$tanda->setFechaInicio($instancia->getFechaInicio());
	    $tanda->setFechaFin($instancia->getFechaFin());	
	    $tanda->setInstanciaEvento($instancia);	
	    $tanda->setNumero($numero);
	    return $tanda;
	}
	function reordenarDias(){
		$numero = 1;
		foreach($this->dias as $dia){
			if ($dia->getNumero() != $numero)
				$dia->setNumero($numero);
			$numero++; 
		}
	}
	
	function agregarDia($numero){
		if (count($this->dias) == 0)
			$numero = 1;
		elseif (($numero < 0) || $numero > count($this->dias))
			$numero=$this->dias->last()->getNumero()+1;
		
		foreach($this->dias as $dia){
			if ($dia->getNumero() > $numero)
				$dia->setNumero($dia->getNumero()+1);
		}
		$dia = new Dia();
		$dia->setNumero($numero);
		$dia->setTanda($this);
		$this->dias->add($dia);
		$this->reordenarDias();
		return $dia;
	}
	
	function eliminarDia($diaABorrar){
		$this->dias->removeElement($diaABorrar);
		$this->reordenarDias();
	}
	
	function getDiaEnPosicion($posicion){
		foreach($this->dias as $dia){
			if ($dia->getNumero($posicion)) 
				return $dia;
		}
		throw new \InvalidArgumentException("Se pidio un dia en una posicion inválida $posicion de la tanda {$this->id}");
	}
		
		
	 public function toArray($recursive_depth) {
    	if ($recursive_depth == 0)
    		return $this->getId();
  
    	$dias = array_map(function($dia) { global $recursive_depth; return $dia->toArray($recursive_depth -1); } , $this->getDias()->toArray());
    	
    	$presentaciones = array_map( function($presentacion) { global $recursive_depth; 
    															   return $presentacion->toArray($recursive_depth-1); }, 
	    									 $this->getPresentaciones()->toArray() );
    		
    	return array(
					'id' => "{$this->id}" ,
					'numero' => "{$this->numero}" ,
					'fechaInicio' => date_format($this->fechaInicio,"d-m-y") ,
					'fechaFin' => date_format($this->fechaFin,"d-m-y"),
					//'instanciaEvento' => $this->getInstanciaEvento->getId(),					 
 					'dias' => $dias,
 					'presentaciones' => $presentaciones,
 		);
    }
}