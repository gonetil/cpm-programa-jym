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
     *  @ORM\ManyToOne(targetEntity="Dia", cascade={"all"})
     */
    private $dia;
    
    
    /**
     * @ORM\ManyToOne(targetEntity="Auditorio")
     */
     private $auditorio;
     
     
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
	
	public function toArray($recursive_depth,$parent_recursive) {
		$bloques = array();
		foreach ( $this->bloques as $bloque ) 
			$bloques[] = ( ($recursive_depth > 0) ? $bloque->toArray($recursive_depth-1,false) : $bloque->getId() );
       		 
		
 			$auditorioDia = array( 'id' => $this->id , 
 							 'bloques' => $bloques,
 							 'dia' => ( ($parent_recursive) ? $this->getDia()->toArray(0,$parent_recursive) : $this->getDia()->getId() ),
 							 'auditorio' => ($recursive_depth > 0) ? $this->getAuditorio()->toArray($recursive_depth-1,$parent_recursive) : $this->getAuditorio()->getId()
 							 ) ;
 							 
 			return $auditorioDia;				 
    }

}