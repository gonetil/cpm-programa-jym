<?php

namespace Cpm\JovenesBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Cpm\JovenesBundle\Entity\Auditorio
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Cpm\JovenesBundle\Entity\AuditorioRepository")
 */
class Auditorio
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
     * @var string $nombre
     *
     * @ORM\Column(name="nombre", type="string")
     */
    private $nombre;

    /**
     * @var boolean $anulado
     *
     * @ORM\Column(name="anulado", type="boolean", nullable=true)
     */
    private $anulado;
    
    
    /**
     * @ORM\ManyToMany(targetEntity="Produccion", inversedBy="auditorios",cascade={"persist"})
     * @ORM\JoinTable(name="ProduccionesAuditorio")
    **/
    private $producciones; //producciones que soporta el audiorio

    function __construct(){
    	$this->producciones = new \Doctrine\Common\Collections\ArrayCollection();
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

    /**
     * Set anulado
     *
     * @param boolean $anulado
     */
    public function setAnulado($anulado)
    {
        $this->anulado = $anulado;
    }

    /**
     * Get anulado
     *
     * @return boolean 
     */
    public function getAnulado()
    {
        return $this->anulado;
    }
 
 	public function getProducciones() {
 		return $this->producciones;
 	}   
 	
 	public function setProducciones($p) {
 		$this->producciones = $p;
 	}
 	
    public function __toString() {
    	return $this->getNombre(). ( $this->anulado ? " (ANULADO)" : "");
    }
    
    public function toArray($recursive_depth) {
    	if ($recursive_depth == 0)
    		return $this->getId();
    	
    	$producciones = array_map(function($produccion) {return $produccion->toArray(1); }, $this->getProducciones()->toArray());
    	return array('id' => $this->id ,
    		'nombre' => "{$this->nombre}", 
    		'anulado' => "{$this->anulado}",
    		'producciones' => $producciones
    	);
    }
    
   public function equals($other)
    {
    	if ($other instanceof Auditorio)
        	return $this->getId() == $other->getId();
        else
        	return false;
    }
}