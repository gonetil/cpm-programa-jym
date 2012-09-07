<?php

namespace Cpm\JovenesBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Cpm\JovenesBundle\Entity\InstanciaEvento;
use Cpm\JovenesBundle\Entity\Proyecto;
use Gedmo\Mapping\Annotation as GEDMO;
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
     * @var DateTime $fechaCreacion
     *
     * @ORM\Column(type="datetime", name="fecha_creacion")
     * @GEDMO\Timestampable(on="create")
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


    /**
     * @var boolean $solicitaTren
     *
     * @ORM\Column(name="solicitaTren", type="boolean", nullable="true")
     */

	private $solicitaTren;
    
    
    /**
     * @var text $invitados
     *
     * @ORM\Column(name="invitados", type="text", nullable = true)
     */
    private $invitados;
    
    /**
     * @var integer $duracion
     * @ORM\Column(name="duracion", type="integer", nullable = true)
     * 
     **/
    private $duracion;
    
    
    
    private $embeddedForm	;  //
    
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
    
    public function getRechazoInvitacion() { 
    	return ($this->aceptoInvitacion === false);
    }
    public function getSinResponder() {
    	
    	return is_null($this->aceptoInvitacion);
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
    
    public function estaPendiente(){
    	return ((! $this->instanciaEvento->fue()) && ($this->aceptoInvitacion === null));
    }
    
    public function estaVigente(){
    	return $this->instanciaEvento->estaAbiertaInscripcion();
    }
 
    public function __toString()
    {
    	return "{$this->id}: proyecto ".$this->proyecto->getId()." / Instancia: ". $this->instanciaEvento->getTitulo();
    }

	
	public function setInvitados($invitados) {
		$this->invitados = $invitados;
	}
	public function getInvitados()
	{
		return $this->invitados;
	}
	
	public function setSolicitaTren($aBool)
	{
		$this->solicitaTren = $aBool;
	}
	
	public function getSolicitaTren()
	{
		return $this->solicitaTren;
	}
	
	public function getDuracion()
	{ 
		return $this->duracion;
	}
	
	public function setDuracion($duracion)
	{
		$this->duracion = $duracion;
	}
	
	public function getEmbeddedForm() 
	{
		
		if ($this->embeddedForm != null) { 
			return $this->embeddedForm;
		}
		else {  
			if ($subAction = $this->getInstanciaEvento()->getEvento()->getAction())	
				{ 
					$subAction = "Cpm\\JovenesBundle\\EntityDummy\\".$subAction;
					$this->embeddedForm = new $subAction($this);
					return $this->embeddedForm;
				}
				else throw new \Exception("No hay embedded form");
		}
	}
	
	public function setEmbeddedForm($form) 
	{
		$this->embeddedForm = $form;
	}
	
}
