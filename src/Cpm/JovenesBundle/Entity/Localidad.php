<?php

namespace Cpm\JovenesBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Cpm\JovenesBundle\Entity\Localidad
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Cpm\JovenesBundle\Entity\LocalidadRepository")
 */
class Localidad
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
    *  @ORM\ManyToOne(targetEntity="Distrito")
     * @ORM\JoinColumn(name="distrito_id", referencedColumnName="id", nullable="false", onDelete="RESTRICT")
    */
    private $distrito;
    
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
    
    public function __toString(){
    	return $this->nombre;
    }

    /**
     * Set distrito
     *
     * @param Cpm\JovenesBundle\Entity\Distrito $distrito
     */
    public function setDistrito(\Cpm\JovenesBundle\Entity\Distrito $distrito)
    {
        $this->distrito = $distrito;
    }

    /**
     * Get distrito
     *
     * @return Cpm\JovenesBundle\Entity\Distrito 
     */
    public function getDistrito()
    {
        return $this->distrito;
    }
}