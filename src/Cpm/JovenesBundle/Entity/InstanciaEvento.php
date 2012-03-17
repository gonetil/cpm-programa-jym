<?php

namespace Cpm\JovenesBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Cpm\JovenesBundle\Entity\Evento;

/**
 * Cpm\JovenesBundle\Entity\InstanciaEvento
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Cpm\JovenesBundle\Entity\InstanciaEventoRepository")
 */
class InstanciaEvento
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
     * @var string $subtitulo
     *
     * @ORM\Column(name="subtitulo", type="string", length=255)
     */
    private $subtitulo;

    /**
     * @var string $descripcion
     *
     * @ORM\Column(name="descripcion", type="text", nullable="true")
     */
    private $descripcion;

    /**
     * @var string $url
     *
     * @ORM\Column(name="url", type="string", length=255, nullable="true")
     */
    private $url;

    /**
     * @var string $lugar
     *
     * @ORM\Column(name="lugar", type="string", length=255, nullable="true")
     */
    private $lugar;

    /**
     * @var datetime $fechaInicio
     *
     * @ORM\Column(name="fecha_inicio", type="datetime")
     */
    private $fechaInicio;

    /**
     * @var datetime $fechaFin
     *
     * @ORM\Column(name="fecha_fin", type="datetime")
     */
    private $fechaFin;

    /**
     * @var Cpm\JovenesBundle\Entity\Evento $evento
     * 
     * @ORM\ManyToOne(targetEntity="Evento", inversedBy="instancias")
     */
    private $evento;

    /**
     * @var Cpm\JovenesBundle\Entity\Invitacion $invitaciones
     *
     * @ORM\OneToMany(targetEntity="Invitacion", mappedBy="instanciaEvento")
     */
    private $invitaciones;

	public function __construct(){
		
		$this->invitaciones = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set subtitulo
     *
     * @param string $subtitulo
     */
    public function setSubtitulo($subtitulo)
    {
        $this->subtitulo = $subtitulo;
    }

    /**
     * Get subtitulo
     *
     * @return string 
     */
    public function getSubtitulo()
    {
        return $this->subtitulo;
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
     * Set url
     *
     * @param string $url
     */
    public function setUrl($url)
    {
        $this->url = $url;
    }

    /**
     * Get url
     *
     * @return string 
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * Set lugar
     *
     * @param string $lugar
     */
    public function setLugar($lugar)
    {
        $this->lugar = $lugar;
    }

    /**
     * Get lugar
     *
     * @return string 
     */
    public function getLugar()
    {
        return $this->lugar;
    }

    /**
     * Set fechaInicio
     *
     * @param datetime $fechaInicio
     */
    public function setFechaInicio($fechaInicio)
    {
        $this->fechaInicio = $fechaInicio;
    }

    /**
     * Get fechaInicio
     *
     * @return datetime 
     */
    public function getFechaInicio()
    {
        return $this->fechaInicio;
    }

    /**
     * Set fechaFin
     *
     * @param datetime $fechaFin
     */
    public function setFechaFin($fechaFin)
    {
        $this->fechaFin = $fechaFin;
    }

    /**
     * Get fechaFin
     *
     * @return datetime 
     */
    public function getFechaFin()
    {
        return $this->fechaFin;
    }
    
    /**
     * Set evento
     *
     *
     * @param Cpm\JovenesBundle\Entity\Evento $evento
     */
    public function setEvento(Cpm\JovenesBundle\Entity\Evento $evento)
    {
        $this->evento = $evento;
    }

    /**
     * Get evento
     *
     * @return object 
     */
    public function getEvento()
    {
        return $this->evento;
    }

    /**
     * Add invitaciones
     *
     * @param Cpm\JovenesBundle\Entity\Invitacion $invitaciones
     */
    public function addInvitacion(\Cpm\JovenesBundle\Entity\Invitacion $invitaciones)
    {
        $this->invitaciones[] = $invitaciones;
    }

    /**
     * Get invitaciones
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getInvitaciones()
    {
        return $this->invitaciones;
    }
}