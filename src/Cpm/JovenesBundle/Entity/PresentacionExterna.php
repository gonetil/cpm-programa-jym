<?php
/*
 * Created on 04/09/2013
 * @author gonetil
 * project jym
 * Copyleft 2013
 * 
 */
namespace Cpm\JovenesBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Cpm\JovenesBundle\Entity\PresentacionExterna
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Cpm\JovenesBundle\Entity\PresentacionRepository")
 */
class PresentacionExterna extends Presentacion
{

    /**
     * @var string $escuela
     *
     * @ORM\Column(name="escuela", type="string", length=255)
     */
    private $escuela;	
    
        /**
     * @var string $provincia
     *
     * @ORM\Column(name="provincia", type="string", length=255)
     */
    private $provincia;
    
    /**
     * @var string $localidad
     *
     * @ORM\Column(name="localidad", type="string", length=255)
     */
    private $localidad;


    /**
     * @var string $apellido_coordinador
     *
     * @ORM\Column(name="apellido_coordinador", type="string", length=255)
     */
    private $apellido_coordinador;	

    /**
     * @var string $nombre_coordinador
     *
     * @ORM\Column(name="nombre_coordinador", type="string", length=255)
     */
    private $nombre_coordinador;

    /**
     * Set escuela
     *
     * @param string $escuela
     */
    public function setEscuela($escuela)
    {
        $this->escuela = $escuela;
    }

    /**
     * Get escuela
     *
     * @return string 
     */
    public function getEscuela()
    {
        return $this->escuela;
    }

    /**
     * Set provincia
     *
     * @param string $provincia
     */
    public function setProvincia($provincia)
    {
        $this->provincia = $provincia;
    }

    /**
     * Get provincia
     *
     * @return string 
     */
    public function getProvincia()
    {
        return $this->provincia;
    }
	
	public function getDistrito() {
		return $this->getProvincia();
	}
    /**
     * Set localidad
     *
     * @param string $localidad
     */
    public function setLocalidad($localidad)
    {
        $this->localidad = $localidad;
    }

    /**
     * Get localidad
     *
     * @return string 
     */
    public function getLocalidad()
    {
        return $this->localidad;
    }

    /**
     * Set apellido_coordinador
     *
     * @param string $apellidoCoordinador
     */
    public function setApellidoCoordinador($apellidoCoordinador)
    {
        $this->apellido_coordinador = $apellidoCoordinador;
    }

    /**
     * Get apellido_coordinador
     *
     * @return string 
     */
    public function getApellidoCoordinador()
    {
        return $this->apellido_coordinador;
    }

    /**
     * Set nombre_coordinador
     *
     * @param string $nombreCoordinador
     */
    public function setNombreCoordinador($nombreCoordinador)
    {
        $this->nombre_coordinador = $nombreCoordinador;
    }

    /**
     * Get nombre_coordinador
     *
     * @return string 
     */
    public function getNombreCoordinador()
    {
        return $this->nombre_coordinador;
    }
    

}