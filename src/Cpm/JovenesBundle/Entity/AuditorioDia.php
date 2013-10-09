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
     * @ORM\OrderBy({"horaInicio" = "ASC"})
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
   
	public function getBloques($sorted = false) {
		return $this->bloques;
	}
	public function setBloques($b) {
		$this->bloques = $b;
	}
	
	public function addBloque(\Cpm\JovenesBundle\Entity\Bloque $b) {
		$this->bloques->add($b);
		$b->setAuditorioDia($this);

	}
	
    public function removeBloque(\Cpm\JovenesBundle\Entity\Bloque $b) {
    	$this->bloques->removeElement($b);
    	//$b->setAuditorioDia(null);
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
    
    
   public function equals($other)
    {
    	if ($other instanceof AuditorioDia)
        	return $this->getId() == $other->getId();
        else
        	return false;
    }

}