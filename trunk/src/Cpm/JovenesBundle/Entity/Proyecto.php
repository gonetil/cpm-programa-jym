<?php

namespace Cpm\JovenesBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Cpm\JovenesBundle\Entity\Proyecto
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Cpm\JovenesBundle\Entity\ProyectoRepository")
 */
class Proyecto
{

    /**
     * @var integer $id
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    public $id;
    
    /**
     * @var string $titulo
     *
     * @ORM\Column(name="titulo", type="string")
     */
    private $titulo;

    /**
     * @var integer $nroAlumnos
     * @Assert\Type(type="integer", message="El valor {{ value }} no es un número válido.")
     * @ORM\Column(name="nroAlumnos", type="integer")
     */
    private $nroAlumnos;

   
    /**
     * @var boolean $esPrimeraVezEscuela
     *
     * @ORM\Column(name="esPrimeraVezEscuela", type="boolean")
     */
    private $esPrimeraVezEscuela=false;

    /**
     * @var boolean $esPrimeraVezAlumnos
     *
     * @ORM\Column(name="esPrimeraVezAlumnos", type="boolean")
     */
    private $esPrimeraVezAlumnos=false;
    
     /**
     * @var boolean $recibioCapacitacion
     * @ORM\Column(name="recibioCapacitacion", type="boolean")
     * indica si recibió capacitación en años anteriores
     */        
    private $recibioCapacitacion=false;     
    
    /**
     * @ORM\OneToOne(targetEntity="Escuela", inversedBy="proyecto",cascade={"all"})
     * @ORM\JoinColumn(name="escuela_id", referencedColumnName="id")
     */
    private $escuela;
    
    /**
    *  @ORM\ManyToOne(targetEntity="Usuario", inversedBy="proyectosCoordinados")
    *  
    */    
    private $coordinador;
    
    /**
     * @ORM\ManyToMany(targetEntity="Usuario", inversedBy="colaboraEn",cascade={"persist"})
     * @ORM\JoinTable(name="ColaboradorProyecto")
     **/
    private $colaboradores;
    
    
    /**
    *  @ORM\ManyToOne(targetEntity="Tema")
     *  @ORM\JoinColumns({
    *   @ORM\JoinColumn(name="tema_id", referencedColumnName="id")
    * })
    */
    private $temaPrincipal;
    
    /**
    *  @ORM\ManyToOne(targetEntity="Produccion")
    *  @ORM\JoinColumns({
    *   @ORM\JoinColumn(name="produccion_id", referencedColumnName="id")
    * })
    */
    private $produccionFinal;


    /**
    *  @ORM\ManyToOne(targetEntity="Eje")
    *  @ORM\JoinColumns({
    *   @ORM\JoinColumn(name="eje_id", referencedColumnName="id")
    * })
    */
    private $eje;
        
    /**
    * @var string $deQueSeTrata
    *
    * @ORM\Column(name="de_que_se_trata", type="text")
    */
    
    private $deQueSeTrata;
    
    /**
     * @var string $motivoRealizacion
     *
     * @ORM\Column(name="motivo_realizacion", type="text")
     */
    
    private $motivoRealizacion;
    
    /**
     * @var string $impactoBuscado
     *
     * @ORM\Column(name="impacto_buscado", type="text")
     */
    private $impactoBuscado;

    
    /**
     *  @ORM\ManyToOne(targetEntity="Ciclo")
     */
    private $ciclo;
    
    /**
     * archivo con el proyecto completo 
     * @ORM\Column(name="archivo", type="string", length=255, nullable=true)
     */
    private $archivo;
    
	/**
     * @ORM\OneToMany(targetEntity="Invitacion", mappedBy="proyecto", cascade={"all"})
     **/
    private $invitaciones;
    
    /**
     *    
    *  @ORM\OneToOne(targetEntity="EstadoProyecto",cascade={"all"})
    *  @ORM\JoinColumns({
    *   @ORM\JoinColumn(name="estadoActual_id", referencedColumnName="id", nullable=true, onDelete="SET NULL", onUpdate="SET NULL")
    * })
    */
    private $estadoActual;
    
    /**
     * @var string $requerimientosDeEdicion
     *
     * @ORM\Column(name="requerimientos_de_edicion", type="text", nullable=true)
     */
    
    private $requerimientosDeEdicion;
    
    /**
    * @var string $color
    *
    * @ORM\Column(name="color", type="string", nullable=true)
    */
    private $color;
    
    
    /**
    * @var string $transporte
    *
    * @ORM\Column(name="transoprte", type="string", nullable=true)
    */
    private $transporte;


    /**
     * @var string $observaciones
     *
     * @ORM\Column(name="observaciones", type="text", nullable=true)
     */
    
    private $observaciones;
    
   /**
     * @var boolean $cuentanConNetebook
     * @ORM\Column(name="cuentanConNetbook", type="boolean")
     * indica si cuentan con las netbook del plan conectar igualdad
     */        
    private $cuentanConNetbook = false;
    
   /**
     * @var boolean $cuentanConPlataformaVirtual
     * @ORM\Column(name="cuentanConPlataformaVirtual", type="boolean")
     * indica si cuentan con la plataforma virtual
     */           
    private $cuentanConPlataformaVirtual = false;
    

    public function getRecibioCapacitacion()
    {
    	return $this->recibioCapacitacion;
    }
    
    public function setRecibioCapacitacion($bool)
    {
    	$this->recibioCapacitacion = $bool;
    }
    
    public function __construct()
    {
        $this->colaboradores = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set id
     *
     * @param integer $id
     */
    public function setId($id) {
		$this->id = $id;
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
     * Set nroAlumnos
     *
     * @param integer $nroAlumnos
     */
    public function setNroAlumnos($nroAlumnos)
    {
        $this->nroAlumnos = $nroAlumnos;
    }

    /**
     * Get nroAlumnos
     *
     * @return integer 
     */
    public function getNroAlumnos()
    {
        return $this->nroAlumnos;
    }
	
	public function esPrimeravezDocente() {
		 
		$anios = json_decode( $this->getCoordinador()->getAniosParticipo(), true );
		return !( (!is_null($anios)) && (count($anios) > 0));
	}
    /**
     * Set esPrimeraVezEscuela
     *
     * @param boolean $esPrimeraVezEscuela
     */
    public function setEsPrimeraVezEscuela($esPrimeraVezEscuela)
    {
        $this->esPrimeraVezEscuela = $esPrimeraVezEscuela;
    }

    /**
     * Get esPrimeraVezEscuela
     *
     * @return boolean 
     */
    public function getEsPrimeraVezEscuela()
    {
        return $this->esPrimeraVezEscuela;
    }

    /**
     * Set esPrimeraVezAlumnos
     *
     * @param boolean $esPrimeraVezAlumnos
     */
    public function setEsPrimeraVezAlumnos($esPrimeraVezAlumnos)
    {
        $this->esPrimeraVezAlumnos = $esPrimeraVezAlumnos;
    }

    /**
     * Get esPrimeraVezAlumnos
     *
     * @return boolean 
     */
    public function getEsPrimeraVezAlumnos()
    {
        return $this->esPrimeraVezAlumnos;
    }

    /**
     * Set escuela
     *
     * @param Cpm\JovenesBundle\Entity\Escuela $escuela
     */
    public function setEscuela($escuela)
    {
        $this->escuela = $escuela;
    }

    /**
     * Get escuela
     *
     * @return Cpm\JovenesBundle\Entity\Escuela 
     */
    public function getEscuela()
    {
        return $this->escuela;
    }
    
    /**
     * Set coordinador
     *
     * @param Cpm\JovenesBundle\Entity\Usuario $coordinador
     */
    public function setCoordinador($coordinador)
    {
        $this->coordinador = $coordinador;
    }

    /**
     * Get coordinador
     *
     * @return Cpm\JovenesBundle\Entity\Usuario 
     */
    public function getCoordinador()
    {
        return $this->coordinador;
    }

    /**
     * Add colaboradores
     *
     * @param Cpm\JovenesBundle\Entity\Usuario $colaboradores
     */
    public function addUsuario(\Cpm\JovenesBundle\Entity\Usuario $colaboradores)
    {
        $this->colaboradores[] = $colaboradores;
    }

    /**
     * Get colaboradores
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getColaboradores()
    {
        return $this->colaboradores;
    }

    
    /**
    * Set colaboradores
    *
    */
    public function setColaboradores($colaboradores)
    {
    	$this->colaboradores = $colaboradores;
    }
    
    
    /**
     * Set temaPrincipal
     *
     * @param Cpm\JovenesBundle\Entity\Tema $temaPrincipal
     */
    public function setTemaPrincipal(\Cpm\JovenesBundle\Entity\Tema $temaPrincipal)
    {
        $this->temaPrincipal = $temaPrincipal;
    }

    /**
     * Get temaPrincipal
     *
     * @return Cpm\JovenesBundle\Entity\Tema 
     */
    public function getTemaPrincipal()
    {
        return $this->temaPrincipal;
    }

    /**
     * Set produccionFinal
     *
     * @param Cpm\JovenesBundle\Entity\Produccion $produccionFinal
     */
    public function setProduccionFinal(\Cpm\JovenesBundle\Entity\Produccion $produccionFinal)
    {
        $this->produccionFinal = $produccionFinal;
    }

    /**
     * Get produccionFinal
     *
     * @return Cpm\JovenesBundle\Entity\Produccion 
     */
    public function getProduccionFinal()
    {
        return $this->produccionFinal;
    }

    public function setEje(\Cpm\JovenesBundle\Entity\Eje $eje)
    {
        $this->eje = $eje;
    }

    /**
     * Get eje
     *
     * @return Cpm\JovenesBundle\Entity\Eje
     */
    public function getEje()
    {
        return $this->eje;
    }
        
    /**
    * Set deQueSeTrata
    *
    * @param text $deQueSeTrata
    */
    public function setDeQueSeTrata($deQueSeTrata)
    {
    	$this->deQueSeTrata = $deQueSeTrata;
    }
    
    /**
     * Get deQueSeTrata
     *
     * @return text
     */
    public function getDeQueSeTrata()
    {
    	return $this->deQueSeTrata;
    }
    
    /**
     * Set motivoRealizacion
     *
     * @param text $motivoRealizacion
     */
    public function setMotivoRealizacion($motivoRealizacion)
    {
    	$this->motivoRealizacion = $motivoRealizacion;
    }
    
    /**
     * Get motivoRealizacion
     *
     * @return text
     */
    public function getMotivoRealizacion()
    {
    	return $this->motivoRealizacion;
    }
    
    /**
     * Set impactoBuscado
     *
     * @param text $impactoBuscado
     */
    public function setImpactoBuscado($impactoBuscado)
    {
    	$this->impactoBuscado = $impactoBuscado;
    }
    
    /**
     * Get impactoBuscado
     *
     * @return text
     */
    public function getImpactoBuscado()
    {
    	return $this->impactoBuscado;
    }

    /**
     * Set ciclo
     *
     * @param Cpm\JovenesBundle\Entity\Ciclo $ciclo
     */
    public function setEmisor(\Cpm\JovenesBundle\Entity\Ciclo $ciclo)
    {
        $this->ciclo = $ciclo;
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
    
    public function __toString() 
    { 
    	return "Escuela {$this->escuela} / Coordinador {$this->coordinador}";
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
    
    /**
     * Get invitaciones pendientes
     *
     * @return array 
     */
    public function getInvitacionesPendientes()
    {
    	$pendientes = array();
    	foreach ($this->invitaciones as $i){
        	if ($i->estaPendiente())
        		$pendientes[] = $i;
        }
        return $pendientes;
    }
    
    /**
     * Get invitaciones vigentes
     *
     * @return array 
     */
    public function getInvitacionesVigentes()
    {
    	$vigentes = array();
    	foreach ($this->invitaciones as $i){
        	if ($i->estaVigente())
        		$vigentes[] = $i;
        }
        return $vigentes;
    }
    
    public function getArchivo()
    {
    	return $this->archivo;
    }
    
    public function setArchivo($archivo) 
    {
    	$this->archivo = $archivo;
    }
 
 	public function getEstadoActual() {
 		return $this->estadoActual;
 	}   
 	
 	public function setEstadoActual($estadoActual) {
 		$this->estadoActual = $estadoActual;
 	}

	public function estaEnEstadoActual($estado){
		return (!empty($proyecto->estadoActual) && ($proyecto->getEstadoActual()->getEstado() == $estado));
	}

    public function hasArchivo() {
    		if ($this->getEstadoActual()) 
    	   		return ($this->getEstadoActual()->getEstado() >= ESTADO_PRESENTADO);
    	   	else return false;	
    }

	public function getRequerimientosDeEdicion() {
		return $this->requerimientosDeEdicion;
	}
	
	public function setRequerimientosDeEdicion($req) {
		$this->requerimientosDeEdicion = $req;
	}
	
	public function getColor() {
		return $this->color;
	}
	
	public function setColor($color) {
		$this->color = $color;
	}
	
	public function getObservaciones() {
		return $this->observaciones;
	}
	
	public function setObservaciones($obs) {
		$this->observaciones = $obs;
	}
	
	public function getTransporte() {
		return $this->transporte;
	}
	
	public function setTransporte($transporte) {
		$this->transporte = $transporte;
	}
	
	public function setCuentanConPlataformaVirtual($bool) {
		$this->cuentanConPlataformaVirtual = $bool;
	}
	
	public function getCuentanConPlataformaVirtual() {
		return $this->cuentanConPlataformaVirtual;
	} 
	
	public function setCuentanConNetbook($bool) {
		$this->cuentanConNetbook = $bool;
	}
	
	public function getCuentanConNetbook() {
		return $this->cuentanConNetbook ;
	}
	
}