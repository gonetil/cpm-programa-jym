<?php

namespace Cpm\JovenesBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Cpm\JovenesBundle\Entity\Archivo
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Cpm\JovenesBundle\Entity\ArchivoRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Archivo
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
     * @var string $hash
     *
     * @ORM\Column(name="hash", type="string")
     */
    private $hash;

    /**
     * @var string $path
     *
     * @ORM\Column(name="path", type="string")
     */
	private $path;
	
   /**
     * @var datetime $fecha_creado
     *
     * @ORM\Column(name="fecha_creado", type="datetime", nullable=true)
     */
    private $fecha_creado;


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
     * Set hash
     *
     * @param string $hash
     */
    public function setHash($hash)
    {
        $this->hash = $hash;
    }

    /**
     * Get hash
     *
     * @return string 
     */
    public function getHash()
    {
        return $this->hash;
    }
    
    public function getPath() {
    	return $this->path;
    }
    
    public function setPath($path) {
    	$this->path = $path;
    }
    
    public function getCorreo() {
    	return $this->correo;
    }
    public function setCorreo($correo) {
    	$this->correo = $correo;
    }
    
    
     /**
     * Set fecha_creado
     *
     * @param datetime $fecha_creado
     */
    public function setFechaCreado(\datetime $fecha)
    {
        $this->fecha_creado = $fecha;
    }

    /**
     * Get fecha_creado
     *
     * @return datetime 
     */
    public function getFechaCreado()
    {
        return $this->fecha_creado;
    }
    	 /**
     *
     * @ORM\PrePersist
     * @ORM\PreUpdate
     */
    public function updatedTimestamps()
    {
            $this->setFechaCreado(new \DateTime(date('Y-m-d H:i:s')));
    }
    
    public function __toString() {
    	return $this->getNombre();
    }
}