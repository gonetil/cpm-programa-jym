<?php

namespace Cpm\JovenesBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Cpm\JovenesBundle\Entity\Dia
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Cpm\JovenesBundle\Entity\DiaRepository")
 */
class Dia
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
     * @ORM\OneToMany(targetEntity="AuditorioDia", mappedBy="dia", cascade={"all"})
     **/
    private $auditoriosDia;

    
    /**
     *  @ORM\ManyToOne(targetEntity="Tanda", inversedBy="dias")
     * @ORM\JoinColumn(name="tanda_id", referencedColumnName="id", nullable=false, onDelete="CASCADE")
     */
    private $tanda;
    
    public function __construct($numero=-1){
    	$this->numero=$numero;
		$this->auditoriosDia = new \Doctrine\Common\Collections\ArrayCollection();
	}
    
    static function createDia($tanda,$numero=0,$auditoriosDia=null) {
    	$tandaDia = new Dia();
	    
	    $tandaDia->setNumero($numero);
	    if ($auditoriosDia != null )
	    	$tandaDia->setAuditoriosDia($auditoriosDia);
	    return $tandaDia;
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
    

    public function getAuditoriosDia() {
    	return $this->auditoriosDia;
    }
    public function setAuditoriosDia($auditoriosDia) {
    	if (!($auditoriosDia instanceof \Doctrine\Common\Collections\Collection))
 			$auditoriosDia = new \Doctrine\Common\Collections\ArrayCollection($auditoriosDia);
    	$this->auditoriosDia = $auditoriosDia;
    }
    
    public function addAuditorioDia($ad) {
    	$this->auditoriosDia->add($ad);
    	$ad->setDia($this);
    }
    
    public function removeAuditorioDia($ad) {
    	$this->auditoriosDia->removeElement($ad);
    	//$ad->setDia(null);
    }
    
    
    
    public function getTanda() {
    	return $this->tanda;
    }
    public function setTanda($t) {
    	$this->tanda = $t;
    }
    
    public function getCiclo(){
    	return $this->tanda->getCiclo();
    }
    
    public function __toString() {
    	return "Día ".$this->getNumero().", tanda ".$this->getTanda();
    }
    
    public function toArray($recursive_depth) {
    	if ($recursive_depth == 0)
    		return $this->getId();
    	
    	$auditoriosDia = array();
    	foreach ($this->getAuditoriosDia() as $ad )
       		$auditoriosDia[] = $ad->toArray($recursive_depth-1);
		
    	return array(
					'id' => $this->id , 
 					'tanda' => $this->getTanda()->getId(),
 					'numero' => $this->numero ,
 					'auditoriosDia' => $auditoriosDia
 					);
    }

	
   public function equals($other)
    {
    	if ($other instanceof Dia)
        	return $this->getId() == $other->getId();
        else
        	return false;
    }

    /**
     * retorna un array con todos los bloques del dia ordenados a partir de la hora de inicio, sin importar en que auditorio toca cada uno
     * Esto es necesario para exportar a HTML y a doc las tandas
     */

    public function getSortedBloques() {
        $all_bloques = array();
        foreach($this->getAuditoriosDia() as $ad) 
            $all_bloques = array_merge($all_bloques,$ad->getBloques()->toArray());
        
        uasort($all_bloques,function($b1,$b2){
			$hora1 = new \DateTime($b1->getHoraInicio()->format('H:i'));
			$hora2 = new \DateTime($b2->getHoraInicio()->format('H:i'));
            return ($hora1 > $hora2);
        });
        
        return $all_bloques;

    }

}