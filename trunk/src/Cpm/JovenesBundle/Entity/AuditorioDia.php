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
     **/
    private $bloques;
    
        
    /**
     *  @ORM\ManyToOne(targetEntity="Dia")
     */
    private $dia;
    
    
    /**
     * @ORM\ManyToOne(targetEntity="Auditorio")
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
	
	public function addBloque($b) {
		$this->bloques[] = $b;
	}
	
	public function getDia() {
		return $this->dia;
	}
	public function setDia($d) {
		$this->dia = $d;
	}
	
	public function getAuditorio() {
		return $this->auditorio;
	}
	public function setAuditorio($aud) {
		$this->auditorio = $aud;
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