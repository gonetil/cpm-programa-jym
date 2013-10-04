<?php

namespace Cpm\JovenesBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Cpm\JovenesBundle\Entity\AuditorioDia
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Cpm\JovenesBundle\Entity\AuditorioDiaRepository")
 */
class AuditorioDia
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
     * @ORM\OneToMany(targetEntity="Bloque", mappedBy="auditorioDia", cascade={"all"})
     * @ORM\OrderBy({"posicion" = "ASC"})
     **/
    private $bloques;
    
        
    /**
     * @ORM\ManyToOne(targetEntity="Dia", inversedBy="auditoriosDia")
     * @ORM\JoinColumn(name="dia_id", referencedColumnName="id", nullable=false, onDelete="CASCADE")
     */
    private $dia;
    
    
    /**
     * @ORM\ManyToOne(targetEntity="Auditorio")
     * @ORM\JoinColumn(name="auditorio_id", referencedColumnName="id", nullable=false, onDelete="RESTRICT")
     */
     private $auditorio;
     
     public function __construct(){
		$this->bloques = new \Doctrine\Common\Collections\ArrayCollection();
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
    /*
     * 
	
	function addDia($nuevoDia){
		$numero = $nuevoDia->getNumero();
		
		if (count($this->dias) == 0)
			$numero = 1;
		elseif (($numero < 0) || $numero > count($this->dias))
			$numero=$this->dias->last()->getNumero()+1;
		
		foreach($this->dias as $dia){
			if ($dia->getNumero() > $numero)
				$dia->setNumero($dia->getNumero()+1);
		}
		
		$nuevoDia->setNumero($numero);
		$this->dias->add($nuevoDia);
		$nuevoDia->setTanda($this);
		$this->reordenarDias();

	}
     */

	public function getBloques($sorted = false) {
		if ($sorted) {
			$iterator = $this->bloques->getIterator(); 
			$iterator->uasort(function($b1,$b2) { return ($b1->getPosicion() <= $b2->getPosicion() ) ? -1 : 1 ;});
			return $iterator;
		}
		else
			return $this->bloques;
	}
	public function setBloques($b) {
		$this->bloques = $b;
	}
	
	public function addBloque(\Cpm\JovenesBundle\Entity\Bloque $b) {
		$this->bloques->add($b);
		$b->setAuditorioDia($this);
		
		if ($b->getPosicion() < 0)
			$b->setPosicion(count($this->bloques)+1);
		$this->reordenarBloques();
	}
	
    public function removeBloque(\Cpm\JovenesBundle\Entity\Bloque $b) {
    	$this->bloques->removeElement($b);
    	//$b->setAuditorioDia(null);
    	$this->reordenarBloques();
    }
    
    public function moverBloque($bloqueAMover, $desplazamiento){
    	$this->reordenarBloques();
    	
    	$nuevaPosicion=$bloqueAMover->getPosicion()+$desplazamiento;
    	$victima = $this->getBloqueEnPosicion($nuevaPosicion);
    	
    	if(!empty($victima)){
    		//si hay alguien en mi lugar lo desplazo 
    		$victima->setPosicion($bloqueAMover->getPosicion());
    	}
    	$bloqueAMover->setPosicion($nuevaPosicion);
    	
    	$this->reordenarBloques();
    }
    
    public function getBloqueEnPosicion($posicion){
    	foreach ($this->bloques as $b){
    		if ($b->getPosicion() == $posicion)
    			return $b;
    	}
    	return null;
    }
    
	protected function reordenarBloques(){
		$numero = 1;
		foreach($this->bloques as $b){
			if ($b->getPosicion() != $numero)
				$b->setPosicion($numero);
			$numero++; 
		}
	}
    
    
	public function getDia() {
		return $this->dia;
	}
	public function setDia(\Cpm\JovenesBundle\Entity\Dia $d) {
		$this->dia = $d;
	}
	
	public function getAuditorio() {
		return $this->auditorio;
	}
	public function setAuditorio(\Cpm\JovenesBundle\Entity\Auditorio $aud) {
		$this->auditorio = $aud;
	}
	
	public function getCiclo(){
    	return $this->dia->getCiclo();
    }
	
	public function __toString() {
		return "Día ".$this->getDia()->getNumero() . " - ".$this->getAuditorio()->getNombre() . ". Tanda ".$this->getDia()->getTanda();
	}
	
	public function toArray($recursive_depth) 
	{
		if ($recursive_depth == 0)
    		return $this->getId();
    	
		$bloques = array();
		foreach ( $this->bloques as $bloque )
			$bloques[] = $bloque->toArray($recursive_depth-1);
		
		return array( 
				'id' => $this->id , 
 				'bloques' => $bloques,
 				'dia' => $this->getDia()->getId(),
 				'auditorio' => $this->getAuditorio()->toArray($recursive_depth-1)
 			) ;
    }

}