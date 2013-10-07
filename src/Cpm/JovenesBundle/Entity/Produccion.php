<?php

namespace Cpm\JovenesBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Cpm\JovenesBundle\Entity\Produccion
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Cpm\JovenesBundle\Entity\ProduccionRepository")
 */
class Produccion
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
     * @ORM\Column(name="nombre", type="string",nullable=false)
     * @Assert\NotBlank(message="Debe completar el nombre del tipo de producción")
     */
    private $nombre;


    /**
     * @var boolean $anulado
     *
     * @ORM\Column(name="anulado", type="boolean")
     */
    private $anulado;

    /**
     * @var string $tipoPresentacion
     *
     * @ORM\Column(name="tipoPresentacion", type="string",nullable=false)
     * @Assert\NotBlank(message="Debe completar tipo de presentacion asociado a esta producción")
     */
	private $tipoPresentacion; //slug o nombre corto

    /**
     * @var string $duracionEstimada
     *
     * @ORM\Column(name="duracionEstimada", type="integer",options={"default" = 15})
     * @Assert\Min(limit="1", message="La duración no es válida. Debe ser mayor a 0")
     */
	private $duracionEstimada=15;
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
    
 
	public function getTipoPresentacion() {
		return $this->tipoPresentacion;
	}
	
	public function setTipoPresentacion($tp) {
		$this->tipoPresentacion = strtolower($tp);
	}
    
    public function getDuracionEstimada() {
    	return $this->duracionEstimada;
    }
    
    public function setDuracionEstimada($d) {
    	$this->duracionEstimada = $d;
    }
    public function __toString()
    {
    	return $this->nombre . ( ( is_null($this->tipoPresentacion) ) ? "" : " (".$this->tipoPresentacion.")"  );
    }
    
    public function toArray($recursive_depth) {
    	if ($recursive_depth == 0)
    		return $this->getId();
  	
  		return array(
			'id'=>$this->id, 
			'nombre'=>$this->nombre,
			'slug'=>$this->tipoPresentacion,
			'duracion'=>$this->getDuracionEstimada()
		);	
    }

   public function equals($other)
    {
    	if ($other instanceof Produccion)
        	return $this->getId() == $other->getId();
        else
        	return false;
    }    
    
}