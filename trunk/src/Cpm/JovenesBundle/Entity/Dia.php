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
    private $auditoriosDias;

    
    /**
     *  @ORM\ManyToOne(targetEntity="Tanda")
     */
    private $tanda;
    
    public function __construct(){
		$this->auditoriosDias = new \Doctrine\Common\Collections\ArrayCollection();
	}
    
    static function createDia($tanda,$numero=0,$auditoriosDias=null) {
    	$tandaDia = new Dia();
	    $tandaDia->setTanda($tanda);
	    $tandaDia->setNumero($numero);
	    if ($auditoriosDias != null )
	    	$tandaDia->setAuditoriosDias($auditoriosDias);
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
    

    public function getAuditoriosDias() {
    	return $this->auditoriosDias;
    }
    public function setAuditoriosDias($ad) {
    	$this->auditoriosDias = $ad;
    }
    public function addAuditorioDia($ad) {
    	$this->auditoriosDias[] = $ad;
    }
    
    public function getTanda() {
    	return $this->tanda;
    }
    public function setTanda($t) {
    	$this->tanda = $t;
    }
    
    public function __toString() {
    	return "Día ".$this->getNumero().", tanda ".$this->getTanda();
    }
    
    public function toArray($recursive_depth) {
    	if ($recursive_depth == 0)
    		return $this->getId();
    	
    	$auditoriosDias = array();
    	foreach ($this->getAuditoriosDias() as $ad )
       		$auditoriosDias[] = $ad->toArray($recursive_depth-1);
		
    	return array(
					'id' => "{$this->id}" , 
 					'tanda' => "{$this->getTanda()->getId()}",
 					'numero' => "{$this->numero}" ,
 					'auditoriosDias' => $auditoriosDias
 					);
    }

}