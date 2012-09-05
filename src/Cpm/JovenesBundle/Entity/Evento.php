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
     * @ORM\Column(name="descripcion", type="text",nullable="true")
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

    /**
     * @var string action
     *
     * @ORM\Column(name="action", type="string", nullable="true")
     */
    private $action;
    
    /**
     *  @ORM\ManyToOne(targetEntity="Ciclo")
     */
    private $ciclo;
   
    
    /**
     * @var boolean $preguntarSolicitaTren
     *
     * @ORM\Column(name="preguntar_solicita_tren", type="boolean")
     */
    private $preguntarSolicitaTren;
    
    
    /**
     * @var boolean $solicitarListaInvitados
     *
     * @ORM\Column(name="solicitar_lista_invitados", type="boolean")
     */
    private $solicitarListaInvitados;
    /**
     * @var integer $numeroMaximoInvitados
     *
     * @ORM\Column(name="numero_maximo_invitados", type="integer")
     */
    private $numeroMaximoInvitados;
    
    /**
     * @var boolean $solicitarDuracionPresentacion
     *
     * @ORM\Column(name="solicitar_duracion_presentacion", type="boolean")
     */
    private $solicitarDuracionPresentacion;
    
    /**
     * @var boolean $permitirModificarLaInvitacion
     *
     * @ORM\Column(name="permitir_modificar_invitacion", type="boolean")
     */   
    private $permitirModificarLaInvitacion;
    
    
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

    /**
     * Set action
     *
     * @param string $action
     */
    public function setAction($action)
    {
        $this->action = $action;
    }

    /**
     * Get action
     *
     * @return string 
     */
    public function getAction()
    {
        return $this->action;
    }

    /**
     * Get ciclo
     *
     * @return Cpm\JovenesBundle\Entity\Ciclo
     */
    public function getCiclo()
    {
        return $this->ciclo;
    }

    /**
     * Set ciclo
     *
     * @param Cpm\JovenesBundle\Entity\Ciclo $ciclo
     */
    public function setCiclo(\Cpm\JovenesBundle\Entity\Ciclo $ciclo)
    {
        $this->ciclo = $ciclo;
    }
 
 
	public function setNumeroMaximoInvitados($numero)
	{
		$this->numeroMaximoInvitados = $numero;
	}
	
	public function getNumeroMaximoInvitados() 
    {
    	return $this->numeroMaximoInvitados;
    }
    
    public function setSolicitarListaInvitados($aBool)
    {
    	$this->solicitarListaInvitados = $aBool;
    }

    public function getSolicitarListaInvitados()
	{
		return $this->solicitarListaInvitados;
	}
	
	public function setPreguntarSolicitaTren($aBool)
	{
		$this->preguntarSolicitaTren = $aBool;
	}
	
	public function getPreguntarSolicitaTren()
	{
		return $this->preguntarSolicitaTren;
	}
    
 	public function getSolicitarDuracionPresentacion()
 	{
 		return $this->solicitarDuracionPresentacion;
 	}

 	public function setSolicitarDuracionPresentacion($aBool)
 	{
 		$this->solicitarDuracionPresentacion = $aBool;
 	}

	public function getPermitirModificarLaInvitacion() { 
		return $this->permitirModificarLaInvitacion;
	}
	
	public function setPermitirModificarLaInvitacion($aBool) { 
		$this->permitirModificarLaInvitacion = $aBool;
	}
}