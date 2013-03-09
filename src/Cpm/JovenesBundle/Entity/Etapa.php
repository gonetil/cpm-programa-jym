<?php

namespace Cpm\JovenesBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Cpm\JovenesBundle\Entity\Etapa
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Cpm\JovenesBundle\Entity\EtapaRepository")
 */
class Etapa
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
     * @ORM\Column(name="nombre", type="string", length=255)
     */
    private $nombre;

    /**
     * @var string $descripcion
     *
     * @ORM\Column(name="descripcion", type="text")
     */
    private $descripcion;
    
        
    /**
     * @var integer $numero
     * 
     * @ORM\Column (name="numero", type="integer", nullable="false")
    */
    private $numero;

    /**
     * @var string $proyectosVigentesAction
     *
     * @ORM\Column(name="proyectos_vigentes_action", type="string", length=255)
     */
    private $proyectosVigentesAction;

    /**
     * @var array $accionesUsuario
     *
     * @ORM\Column(name="acciones_de_usuario", type="array", nullable="false")
     */
    private $accionesDeUsuario = array();

    /**
     * @var array $accionesProyecto
     *
     * @ORM\Column(name="acciones_de_proyecto", type="array", nullable="false")
     */
    private $accionesDeProyecto = array();
        

    /**
     * @var boolean $deprecated
     *
     * @ORM\Column(name="deprecated", type="boolean", nullable="false")
     */
    private $deprecated = false;


	public function __construct(){
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
     * Set descripcion
     *
     * @param string $descripcion
     */
    public function setDescripcion($descripcion)
    {
        $this->descripcion = $descripcion;
    }

    /**
     * Get descripcion
     *
     * @return string 
     */
    public function getDescripcion()
    {
        return $this->descripcion;
    }
    
    /**
     * Set numero
     *
     * @param string $numero
     */
    public function setNumero($numero)
    {
        $this->numero = $numero;
    }

    /**
     * Get numero
     *
     * @return string 
     */
    public function getNumero()
    {
        return $this->numero;
    }

    /**
     * Set deprecated
     *
     * @param boolean $deprecated
     */
    public function setDeprecated($deprecated)
    {
        $this->deprecated = $deprecated;
    }

    /**
     * Get deprecated
     *
     * @return boolean 
     */
    public function getDeprecated()
    {
        return $this->deprecated;
    }

    
    public function __toString()
    {
        return $this->nombre;
    }

    /**
     * Set proyectosVigentesAction
     *
     * @param string $proyectosVigentesAction
     */
    public function setProyectosVigentesAction($proyectosVigentesAction)
    {
        $this->proyectosVigentesAction = $proyectosVigentesAction;
    }

    /**
     * Get proyectosVigentesAction
     *
     * @return string 
     */
    public function getProyectosVigentesAction()
    {
        return $this->proyectosVigentesAction;
    }

    /**
     * Set accionesDeUsuario
     *
     * @param array $accionesDeUsuario
     */
    public function setAccionesDeUsuario($accionesDeUsuario)
    {
        $this->accionesDeUsuario = $accionesDeUsuario;
    }

    /**
     * Get accionesDeUsuario
     *
     * @return array 
     */
    public function getAccionesDeUsuario()
    {
        return $this->accionesDeUsuario;
    }

    /**
     * Set accionesDeProyecto
     *
     * @param array $accionesDeProyecto
     */
    public function setAccionesDeProyecto($accionesDeProyecto)
    {
        $this->accionesDeProyecto = $accionesDeProyecto;
    }

    /**
     * Get accionesDeProyecto
     *
     * @return array 
     */
    public function getAccionesDeProyecto()
    {
        return $this->accionesDeProyecto;
    }
    
    public function equals($other)
    {
    	if ($other instanceof Etapa)
        	return $this->getId() == $other->getId();
        else
        	return false;
    }
    
    
}