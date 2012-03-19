<?php

namespace Cpm\JovenesBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Cpm\JovenesBundle\Entity\Evento
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Cpm\JovenesBundle\Entity\EventoRepository")
 */
class Evento
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
     * @var string $titulo
     *
     * @ORM\Column(name="titulo", type="string", length=255)
     */
    private $titulo;

    /**
     * @var string $descripcion
     *
     * @ORM\Column(name="descripcion", type="text")
     */
    private $descripcion;

    /**
     * @var smallint $pedirNumeroAsistentes
     *
     * @ORM\Column(name="pedir_numero_asistentes", type="boolean")
     */
    private $pedirNumeroAsistentes;

    /**
     * @var boolean $permitirSuplente
     *
     * @ORM\Column(name="permitir_suplente", type="boolean")
     */
    private $permitirSuplente;

    /**
     * @var boolean $ofrecerHospedaje
     *
     * @ORM\Column(name="ofrecer_hospedaje", type="boolean")
     */
    private $ofrecerHospedaje;

    /**
     * @var boolean $ofrecerViaje
     *
     * @ORM\Column(name="ofrecer_viaje", type="boolean")
     */
    private $ofrecerViaje;

    /**
     * @var boolean $permitirObservaciones
     *
     * @ORM\Column(name="permitir_observaciones", type="boolean")
     */
    private $permitirObservaciones;

    /**
     * @var Cpm\JovenesBundle\Entity\InstanciaEvento  $instancias
     *
     * @ORM\OneToMany(targetEntity="InstanciaEvento", mappedBy="evento")
     */
    private $instancias;
    
	public function __construct(){
		$this->pedirNumeroAsistentes = false;
		$this->permitirSuplente = false;
		$this->ofrecerHospedaje = false;
		$this->ofrecerViaje = false;
		$this->permitirObservaciones = false;
		
		$this->instancias = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set titulo
     *
     * @param string $titulo
     */
    public function setTitulo($titulo)
    {
        $this->titulo = $titulo;
    }

    /**
     * Get titulo
     *
     * @return string 
     */
    public function getTitulo()
    {
        return $this->titulo;
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
     * Set pedirNumeroAsistentes
     *
     * @param smallint $pedirNumeroAsistentes
     */
    public function setPedirNumeroAsistentes($pedirNumeroAsistentes)
    {
        $this->pedirNumeroAsistentes = $pedirNumeroAsistentes;
    }

    /**
     * Get pedirNumeroAsistentes
     *
     * @return smallint 
     */
    public function getPedirNumeroAsistentes()
    {
        return $this->pedirNumeroAsistentes;
    }

    /**
     * Set permitirSuplente
     *
     * @param boolean $permitirSuplente
     */
    public function setPermitirSuplente($permitirSuplente)
    {
        $this->permitirSuplente = $permitirSuplente;
    }

    /**
     * Get permitirSuplente
     *
     * @return boolean 
     */
    public function getPermitirSuplente()
    {
        return $this->permitirSuplente;
    }

    /**
     * Set ofrecerHospedaje
     *
     * @param boolean $ofrecerHospedaje
     */
    public function setOfrecerHospedaje($ofrecerHospedaje)
    {
        $this->ofrecerHospedaje = $ofrecerHospedaje;
    }

    /**
     * Get ofrecerHospedaje
     *
     * @return boolean 
     */
    public function getOfrecerHospedaje()
    {
        return $this->ofrecerHospedaje;
    }

    /**
     * Set ofrecerViaje
     *
     * @param boolean $ofrecerViaje
     */
    public function setOfrecerViaje($ofrecerViaje)
    {
        $this->ofrecerViaje = $ofrecerViaje;
    }

    /**
     * Get ofrecerViaje
     *
     * @return boolean 
     */
    public function getOfrecerViaje()
    {
        return $this->ofrecerViaje;
    }

    /**
     * Set permitirObservaciones
     *
     * @param boolean $permitirObservaciones
     */
    public function setPermitirObservaciones($permitirObservaciones)
    {
        $this->permitirObservaciones = $permitirObservaciones;
    }

    /**
     * Get permitirObservaciones
     *
     * @return boolean 
     */
    public function getPermitirObservaciones()
    {
        return $this->permitirObservaciones;
    }
    
    /**
     * Add instancias
     *
     * @param Cpm\JovenesBundle\Entity\InstanciaEvento $instancias
     */
    public function addInstanciaEvento(\Cpm\JovenesBundle\Entity\InstanciaEvento $instancias)
    {
        $this->instancias[] = $instancias;
    }

    /**
     * Get instancias
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getInstancias()
    {
        return $this->instancias;
    }
    
    public function __toString()
    {
    	return $this->titulo;
    }
}