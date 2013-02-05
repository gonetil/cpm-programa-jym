<?php

namespace Cpm\JovenesBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Cpm\JovenesBundle\Entity\TipoEscuela
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Cpm\JovenesBundle\Entity\TipoEscuelaRepository")
 */
class TipoEscuela
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
     * 
     * @Assert\NotBlank()
     */
    private $nombre;

    /**
     * @var boolean $anulado
     *
     * @ORM\Column(name="anulado", type="boolean")
     */
    private $anulado;


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
    
    
    
    public function __toString()
    {
    	return $this->nombre;
    }
    
}