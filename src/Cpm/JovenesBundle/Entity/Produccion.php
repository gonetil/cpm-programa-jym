<?php

namespace Cpm\JovenesBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

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
     * @ORM\Column(name="nombre", type="string")
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
     * @ORM\Column(name="tipoPresentacion", type="string",nullable="true")
     */

	private $tipoPresentacion; //slug o nombre corto

    /**
     * @var string $duracionEstimada
     *
     * @ORM\Column(name="duracionEstimada", type="integer",options={"default" = 0})
     */
	private $duracionEstimada=0;
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
		$this->tipoPresentacion = $tp;
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
    
}