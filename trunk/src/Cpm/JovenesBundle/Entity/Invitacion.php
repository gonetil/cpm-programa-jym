<?php

namespace Cpm\JovenesBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Cpm\JovenesBundle\Entity\InstanciaEvento;
use Cpm\JovenesBundle\Entity\Proyecto;

/**
 * Cpm\JovenesBundle\Entity\Invitacion
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Cpm\JovenesBundle\Entity\InvitacionRepository")
 */
class Invitacion
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
     * @var datetime $fechaCreacion
     *
     * @ORM\Column(name="fecha_creacion", type="datetime")
     */
    private $fechaCreacion;

    /**
     * @var boolean $aceptoInvitacion
     *
     * @ORM\Column(name="acepto_invitacion", type="boolean", nullable="true")
     */
    private $aceptoInvitacion;

    /**
     * @var smallint $numeroAsistentes
     *
     * @ORM\Column(name="numero_asistentes", type="smallint")
     */
    private $numeroAsistentes;

    /**
     * @var boolean $solicitaViaje
     *
     * @ORM\Column(name="solicita_viaje", type="boolean")
     */
    private $solicitaViaje;

    /**
     * @var boolean $solicitaHospedaje
     *
     * @ORM\Column(name="solicita_hospedaje", type="boolean")
     */
    private $solicitaHospedaje;

    /**
     * @var text $observaciones
     *
     * @ORM\Column(name="observaciones", type="text", nullable="true")
     */
    private $observaciones;

    /**
     * @var string $suplente
     *
     * @ORM\Column(name="suplente", type="string", length=255, nullable="true")
     */
    private $suplente;

    /**
     * @var boolean $asistio
     *
     * @ORM\Column(name="asistio", type="boolean", nullable="true")
     */
    private $asistio;

    /**
     * @var Cpm\JovenesBundle\Entity\Proyecto $proyecto
     * 
     * @ORM\ManyToOne(targetEntity="Proyecto")
     * @ORM\JoinColumn(name="proyecto_id", referencedColumnName="id", nullable="false")
     */
    private $proyecto;

    /**
     * @var Cpm\JovenesBundle\Entity\InstanciaEvento $instanciaEvento
     * 
     * @ORM\ManyToOne(targetEntity="InstanciaEvento", inversedBy="invitaciones")
     * @ORM\JoinColumn(name="instancia_evento_id", referencedColumnName="id", nullable="false")
     */
    private $instanciaEvento;

	public function __construct(){
		$this->numeroAsistentes=1;
		$this->solicitaViaje = false;
		$this->solicitaHospedaje = false;
		
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
     * Set fechaCreacion
     *
     * @param datetime $fechaCreacion
     */
    public function setFechaCreacion($fechaCreacion)
    {
        $this->fechaCreacion = $fechaCreacion;
    }

    /**
     * Get fechaCreacion
     *
     * @return datetime 
     */
    public function getFechaCreacion()
    {
        return $this->fechaCreacion;
    }

    /**
     * Set aceptoInvitacion
     *
     * @param boolean $aceptoInvitacion
     */
    public function setAceptoInvitacion($aceptoInvitacion)
    {
        $this->aceptoInvitacion = $aceptoInvitacion;
    }

    /**
     * Get aceptoInvitacion
     *
     * @return boolean 
     */
    public function getAceptoInvitacion()
    {
        return $this->aceptoInvitacion;
    }

    /**
     * Set numeroAsistentes
     *
     * @param smallint $numeroAsistentes
     */
    public function setNumeroAsistentes($numeroAsistentes)
    {
        $this->numeroAsistentes = $numeroAsistentes;
    }

    /**
     * Get numeroAsistentes
     *
     * @return smallint 
     */
    public function getNumeroAsistentes()
    {
        return $this->numeroAsistentes;
    }

    /**
     * Set solicitaViaje
     *
     * @param boolean $solicitaViaje
     */
    public function setSolicitaViaje($solicitaViaje)
    {
        $this->solicitaViaje = $solicitaViaje;
    }

    /**
     * Get solicitaViaje
     *
     * @return boolean 
     */
    public function getSolicitaViaje()
    {
        return $this->solicitaViaje;
    }

    /**
     * Set solicitaHospedaje
     *
     * @param boolean $solicitaHospedaje
     */
    public function setSolicitaHospedaje($solicitaHospedaje)
    {
        $this->solicitaHospedaje = $solicitaHospedaje;
    }

    /**
     * Get solicitaHospedaje
     *
     * @return boolean 
     */
    public function getSolicitaHospedaje()
    {
        return $this->solicitaHospedaje;
    }

    /**
     * Set observaciones
     *
     * @param text $observaciones
     */
    public function setObservaciones($observaciones)
    {
        $this->observaciones = $observaciones;
    }

    /**
     * Get observaciones
     *
     * @return text 
     */
    public function getObservaciones()
    {
        return $this->observaciones;
    }

    /**
     * Set suplente
     *
     * @param string $suplente
     */
    public function setSuplente($suplente)
    {
        $this->suplente = $suplente;
    }

    /**
     * Get suplente
     *
     * @return string 
     */
    public function getSuplente()
    {
        return $this->suplente;
    }

    /**
     * Set asistio
     *
     * @param boolean $asistio
     */
    public function setAsistio($asistio)
    {
        $this->asistio = $asistio;
    }

    /**
     * Get asistio
     *
     * @return boolean 
     */
    public function getAsistio()
    {
        return $this->asistio;
    }

    /**
     * Set proyecto
     *
     * @param Cpm\JovenesBundle\Entity\Proyecto $proyecto
     */
    public function setProyecto($proyecto)
    {
        $this->proyecto = $proyecto;
    }

    /**
     * Get proyecto
     *
     * @return object 
     */
    public function getProyecto()
    {
        return $this->proyecto;
    }

    /**
     * Set instanciaEvento
     *
     * @param Cpm\JovenesBundle\Entity\InstanciaEvento $instanciaEvento
     */
    public function setInstanciaEvento($instanciaEvento)
    {
        $this->instanciaEvento = $instanciaEvento;
    }

    /**
     * Get instanciaEvento
     *
     * @return object 
     */
    public function getInstanciaEvento()
    {
        return $this->instanciaEvento;
    }
}