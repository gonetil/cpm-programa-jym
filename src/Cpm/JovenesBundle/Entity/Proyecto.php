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
    private $id;

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
     * @var boolean $esPrimeraVezDocente
     *
     * @ORM\Column(name="esPrimeraVezDocente", type="boolean")
     */
    private $esPrimeraVezDocente=false;

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
    *  @ORM\OneToOne(targetEntity="Escuela",cascade={"persist"})
    *  @ORM\JoinColumns({
    *   @ORM\JoinColumn(name="escuela_id", referencedColumnName="id")
    * })
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
    * @var string $estado
    *
    * @ORM\Column(name="estado", type="string")
    */
    private $estado = Proyecto::__ESTADO_INICIADO;
    
    /**
     *  @ORM\ManyToOne(targetEntity="Ciclo")
     */
    private $ciclo;
    
    
    const __ESTADO_INICIADO = 'Iniciado';
    const __ESTADO_RECHAZADO = 'Rechazado';
    
    

            
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

    /**
     * Set esPrimeraVezDocente
     *
     * @param boolean $esPrimeraVezDocente
     */
    public function setEsPrimeraVezDocente($esPrimeraVezDocente)
    {
        $this->esPrimeraVezDocente = $esPrimeraVezDocente;
    }

    /**
     * Get esPrimeraVezDocente
     *
     * @return boolean 
     */
    public function getEsPrimeraVezDocente()
    {
        return $this->esPrimeraVezDocente;
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
    public function setEscuela(\Cpm\JovenesBundle\Entity\Escuela $escuela)
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
    public function __construct()
    {
        $this->colaboradores = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
    /**
     * Set coordinador
     *
     * @param Cpm\JovenesBundle\Entity\Usuario $coordinador
     */
    public function setCoordinador(\Cpm\JovenesBundle\Entity\Usuario $coordinador)
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

    public function getEstado()
    {
    	return $this->estado;
    }
    
    public function setEstado($estado)
    {
    	$this->estado = $estado;
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
}