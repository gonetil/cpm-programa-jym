<?php

namespace Cpm\JovenesBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as GEDMO;
/**
 * Cpm\JovenesBundle\Entity\EstadoProyecto
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Cpm\JovenesBundle\Entity\EstadoProyectoRepository")
 */
class EstadoProyecto
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
	     * @ORM\Column(type="datetime", name="created_at")
	     * @GEDMO\Timestampable(on="create")
	     * @var datetime $fecha
	     */
		private $fecha; 
		
		/**
		 * @ORM\Column(type="integer", name="estado")
		 */
		private $estado;
		
		/**
	     * @ORM\Column(type="text", name="observaciones", nullable=true)
	     */
		private $observaciones;

		/**
	     * @ORM\Column(name="archivo", type="string", length=255, nullable=true)
	     */
		private $archivo;

		/**
	     * @ORM\ManyToOne(targetEntity="Usuario")
     	 *  @ORM\JoinColumn(name="usuario_id", referencedColumnName="id", nullable="true", onDelete="SET NULL")
	     */
		private $usuario; 

		/**
	     * @ORM\ManyToOne(targetEntity="Proyecto")
     	 *  @ORM\JoinColumn(name="proyecto_id", referencedColumnName="id", nullable="false", onDelete="CASCADE")
	     */
		private $proyecto;
		
		/**
		 * @ORM\Column(name="valoracion",type="string",length="30",nullable=true)
		 */
		private $valoracion; //muy bueno, bueno, regular
		
		/**
		 * Algunos cambios de estado generan correos. Se asocia el mensaje enviado con el nuevo estado
		 * @ORM\ManyToOne(targetEntity="Correo")
     	 * @ORM\JoinColumn(name="correo_id", referencedColumnName="id", onDelete="SET NULL", nullable=true)
		 */
		private $correoEnviado;
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
     * Set fecha
     *
     * @param datetime $fecha
     */
    public function setFecha($fecha)
    {
        $this->fecha = $fecha;
    }

    /**
     * Get fecha
     *
     * @return datetime 
     */
    public function getFecha()
    {
        return $this->fecha;
    }

    /**
     * Set estado
     *
     * @param integer $estado
     */
    public function setEstado($estado)
    {
        $this->estado = $estado;
    }

    /**
     * Get estado
     *
     * @return integer 
     */
    public function getEstado()
    {
        return $this->estado;
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
     * Set archivo
     *
     * @param string $archivo
     */
    public function setArchivo($archivo)
    {
        $this->archivo = $archivo;
    }

    /**
     * Get archivo
     *
     * @return string 
     */
    public function getArchivo()
    {
        return $this->archivo;
    }

    /**
     * Set usuario
     *
     * @param Cpm\JovenesBundle\Entity\Usuario $usuario
     */
    public function setUsuario( $usuario)
    {
        $this->usuario = $usuario;
    }

    /**
     * Get usuario
     *
     * @return Cpm\JovenesBundle\Entity\Usuario 
     */
    public function getUsuario()
    {
        return $this->usuario;
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
     * @return Cpm\JovenesBundle\Entity\Proyecto 
     */
    public function getProyecto()
    {
        return $this->proyecto;
    }
    
    public function getValoracion() {
    	return $this->valoracion;
    }
    
    public function setValoracion($valoracion) {
    	$this->valoracion = $valoracion;
    }
 
 	public function setCorreoEnviado($correo) { 
 		$this->correoEnviado = $correo;	
 	} 
 	public function getCorreoEnviado() {
 		return $this->correoEnviado;
 	}
 	   
 	public function __toString() {
 		$estado = "...";
		
 		switch ( $this->getEstado() ) {
			case ESTADO_PRESENTADO: $estado = "Presentado"; break;
			case ESTADO_APROBADO: $estado = "Aprobado"; break;
			case ESTADO_APROBADO_CLINICA: $estado =  "Aprobado C"; break;
			case ESTADO_DESAPROBADO: $estado =  "Desaprobado"; break;
			case ESTADO_FINALIZADO: $estado =  "Finalizado"; break;
			case ESTADO_INICIADO: $estado =  "Iniciado"; break;
			case ESTADO_REHACER: $estado =  "Rehacer"; break;
			case ESTADO_ANULADO: $estado =  "Anulado"; break;
			default: $estado = "ERROR"; break; //FIXME 
		}
		
		return $estado;
 	}

}