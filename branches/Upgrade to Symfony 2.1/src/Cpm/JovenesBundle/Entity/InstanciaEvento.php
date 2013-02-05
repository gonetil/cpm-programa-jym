<?php

namespace Cpm\JovenesBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Cpm\JovenesBundle\Entity\Evento;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\ExecutionContext;

/**
 * Cpm\JovenesBundle\Entity\InstanciaEvento
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Cpm\JovenesBundle\Entity\InstanciaEventoRepository")
 * @Assert\Callback(methods={"inicioAntesQueFin"})
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
     * @ORM\Column(name="fecha_fin", type="datetime")
     */
    private $fechaFin;

    /**
     * @var datetime $fechaCierreInscripcion
     * @ORM\Column(name="fecha_cierre_inscripcion", type="datetime")
     */
    private $fechaCierreInscripcion;

    /**
     * @var boolean $fechaCierreInscripcion
     * @ORM\Column(name="cerrar_inscripcion", type="boolean")
     */
    private $cerrarInscripcion;
    
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
		$this->cerrarInscripcion = false;
		$this->invitaciones = new \Doctrine\Common\Collections\ArrayCollection();
		$this->fechaInicio = new \Datetime();
		$this->fechaFin = new \Datetime();
		$this->fechaCierreInscripcion = new \Datetime();
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
    
    public function inicioAntesQueFin(ExecutionContext $context) 
    {
    	if ($this->fechaInicio > $this->fechaFin) {
    		$propertyPath = $context->getPropertyPath() . '.fechaInicio';
    		$context->setPropertyPath($propertyPath);
    		$context->addViolation('La fecha de inicio tiene que ser anterior a la fecha de fin', array(), null);
    	}
    	
    	if ($this->fechaCierreInscripcion > $this->fechaFin) {
    		$propertyPath = $context->getPropertyPath() . '.fechaCierreInscripcion';
    		$context->setPropertyPath($propertyPath);
    		$context->addViolation('La fecha de cierre de inscripcion tiene que ser anterior a la fecha de fin', array(), null);
    	}
    }
    /**
     * Set evento
     *
     *
     * @param Cpm\JovenesBundle\Entity\Evento $evento
     */
    public function setEvento($evento)
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
    public function addInvitacion($invitaciones)
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
    public function __toString()
    {
    	return $this->getTitulo();
    }
    
    public function getTitulo()
    {
    	$referencia = $this->fechaInicio->format('d/m');
    	$dia_fin = $this->fechaFin->format('d/m');
    	if ($referencia != $dia_fin)
    		$referencia.=" al ".$dia_fin;
    	
    	return $this->evento->getTitulo()." ($referencia)";
    }
    
    public function getPeriodoComoTexto()
    {
    	$dia_inicio = $this->fechaInicio->format('d/m');
    	$hora_incio = $this->fechaInicio->format('H:i');
    	
    	$dia_fin = $this->fechaFin->format('d/m');
    	$hora_fin = $this->fechaFin->format('H:i');
    	$referencia="";
    	if ($dia_inicio == $dia_fin){
	    	$referencia .= "el día $dia_inicio";
	    	if ($hora_incio != '00:00')
	    		$referencia .=" de $hora_incio a $hora_fin";
    	}else{
    		$referencia .="desde el $dia_inicio";
    		if ($hora_incio != '00:00')
	    		$referencia .=" a las $hora_incio ";
	    	$referencia .= " hasta el $dia_fin";
	    	if ($hora_fin != '00:00')
	    		$referencia .=" a las $hora_fin ";
    	}
    	return $referencia;
    }

    /**
     * Set fechaCierreInscripcion
     *
     * @param datetime $fechaCierreInscripcion
     */
    public function setFechaCierreInscripcion($fechaCierreInscripcion)
    {
        $this->fechaCierreInscripcion = $fechaCierreInscripcion;
    }

    /**
     * Get fechaCierreInscripcion
     *
     * @return datetime 
     */
    public function getFechaCierreInscripcion()
    {
        return $this->fechaCierreInscripcion;
    }

    /**
     * Set cerrarInscripcion
     *
     * @param boolean $cerrarInscripcion
     */
    public function setCerrarInscripcion($cerrarInscripcion)
    {
        $this->cerrarInscripcion = $cerrarInscripcion;
    }

    /**
     * Get cerrarInscripcion
     *
     * @return boolean 
     */
    public function getCerrarInscripcion()
    {
        return $this->cerrarInscripcion;
    }
    
    public function fue(){
    	return $this->fechaInicio < new \DateTime();
    } 

    public function estaFinalizado(){
    	return $this->fechaFin < new \DateTime();
    } 
    
    public function estaAbiertaInscripcion(){
    	$now =new \DateTime();
    	
    	if ($this->fechaInicio < $now)
    		return false;
    	elseif (!empty($this->fechaCierreInscripcion) && $this->fechaCierreInscripcion < $now && $this->cerrarInscripcion)
    		return false;
    	else
    		return true;
    }
 
}