<?php
/*
 * Created on 04/09/2013
 * @author gonetil
 * project jym
 * Copyleft 2013
 * 
 */
namespace Cpm\JovenesBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Cpm\JovenesBundle\Entity\PresentacionExterna
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Cpm\JovenesBundle\Entity\PresentacionRepository")
 */
class PresentacionExterna extends Presentacion
{

	private static $provincias = array("Ciudad de Buenos Aires", "Buenos Aires", "Catamarca", "Chaco", 
		"Chubut", "Córdoba", "Corrientes", "Entre Ríos", "Formosa", "Jujuy", "La Pampa", "La Rioja", "Mendoza", 
		"Misiones", "Neuquén", "Río Negro", "Salta", "San Juan", "San Luis", "Santa Cruz", "Santa Fe", 
		"Santiago del Estero", "Tierra del Fuego, Antártida e Islas del Atlántico Sur", "Tucumán");

	public static function provincias(){
		return self::$provincias;
	}
	
	/**
     * @var string $titulo
     *
     * @ORM\Column(name="titulo", type="string", length=255)
     */
    public $titulo = "(sin titulo)";
 
 	/**
    *  @ORM\ManyToOne(targetEntity="Tema")
    *  @ORM\JoinColumn(name="tema_id", referencedColumnName="id", nullable=true, onDelete="RESTRICT")
    */
    public  $ejeTematico;
    
    /**
    *  @ORM\ManyToOne(targetEntity="Eje")
    *  @ORM\JoinColumn(name="eje_id", referencedColumnName="id", nullable=true, onDelete="RESTRICT")
    */
    public  $areaReferencia;

    /**
    *  @ORM\ManyToOne(targetEntity="Produccion")
    *  @ORM\JoinColumn(name="produccion_id", referencedColumnName="id", nullable=true, onDelete="RESTRICT")
    */
    public  $tipoPresentacion;
   
    /**
     * @var string $escuela
     *
     * @ORM\Column(name="escuela", type="string", length=255)
     */
    public  $escuela = "Escuela desconocida";	
    
        /**
     * @var string $provincia
     *
     * @ORM\Column(name="provincia", type="string", length=255)
     */
    public  $provincia;
    
    /**
     * @var string $localidad
     *
     * @ORM\Column(name="localidad", type="string", length=255)
     */
    public  $localidad = "Localidad Desconocida";

    /**
     * @var string $apellido_coordinador
     *
     * @ORM\Column(name="apellido_coordinador", type="string", length=255)
     */
    public  $apellido_coordinador= "Coordinador desconocido";	

    /**
     * @var string $nombre_coordinador
     *
     * @ORM\Column(name="nombre_coordinador", type="string", length=255)
     */
    public  $nombre_coordinador= "Coordinador desconocido";


   	/**
     * @var integer $personas_confirmadas;
     *
     * @ORM\Column(name="personas_confirmadas", type="integer")
     */
    public  $personas_confirmadas = 0;
    
    
    /**
     * @var string $valoracion
     *
     * @ORM\Column(name="valoracion", type="string", length=255)
     */
    public  $valoracion = "bueno";

	public function getTipo() { return 'externa'; }

    /**
     * Set escuela
     *
     * @param string $escuela
     */
    public function setEscuela($escuela)
    {
        $this->escuela = $escuela;
    }

    /**
     * Get escuela
     *
     * @return string 
     */
    public function getEscuela()
    {
        return $this->escuela;
    }

    /**
     * Set provincia
     *
     * @param string $provincia
     */
    public function setProvincia($provincia)
    {
    	if (!empty($provincia) && (array_search($provincia, self::$provincias) === false)){
    		throw new \InvalidArgumentException("La provincia $provincia no es valida");
    	}
    	
        $this->provincia = $provincia;
    }

    /**
     * Get provincia
     *
     * @return string 
     */
    public function getProvincia()
    {
        return $this->provincia;
    }
	
	public function getDistrito() {
		return $this->getProvincia();
	}
    /**
     * Set localidad
     *
     * @param string $localidad
     */
    public function setLocalidad($localidad)
    {
        $this->localidad = $localidad;
    }

    /**
     * Get localidad
     *
     * @return string 
     */
    public function getLocalidad()
    {
        return $this->localidad;
    }

    /**
     * Set apellido_coordinador
     *
     * @param string $apellidoCoordinador
     */
    public function setApellidoCoordinador($apellidoCoordinador)
    {
        $this->apellido_coordinador = $apellidoCoordinador;
    }

    /**
     * Get apellido_coordinador
     *
     * @return string 
     */
    public function getApellidoCoordinador()
    {
        return $this->apellido_coordinador;
    }

    /**
     * Set nombre_coordinador
     *
     * @param string $nombreCoordinador
     */
    public function setNombreCoordinador($nombreCoordinador)
    {
        $this->nombre_coordinador = $nombreCoordinador;
    }

    /**
     * Get nombre_coordinador
     *
     * @return string 
     */
    public function getNombreCoordinador()
    {
        return $this->nombre_coordinador;
    }
    
    public function getPersonasConfirmadas() {
    	return $this->personas_confirmadas;
    }
    
    public function setPersonasConfirmadas($pc) {
    	$this->personas_confirmadas = $pc;
    }
    

	public function getValoracion() {
		return $this->valoracion;
	}
	
	public function setValoracion($v) {
		$this->valoracion = $v;
	}

    /**
     * Set ejeTematico
     *
     * @param Cpm\JovenesBundle\Entity\Tema $ejeTematico
     */
    public function setEjeTematico(\Cpm\JovenesBundle\Entity\Tema $ejeTematico)
    {
        $this->ejeTematico = $ejeTematico;
    }

    /**
     * Get ejeTematico
     *
     * @return Cpm\JovenesBundle\Entity\Tema 
     */
    public function getEjeTematico()
    {
        return $this->ejeTematico;
    }

    /**
     * Set areaReferencia
     *
     * @param Cpm\JovenesBundle\Entity\Eje $areaReferencia
     */
    public function setAreaReferencia(\Cpm\JovenesBundle\Entity\Eje $areaReferencia)
    {
        $this->areaReferencia = $areaReferencia;
    }

    /**
     * Get areaReferencia
     *
     * @return Cpm\JovenesBundle\Entity\Eje 
     */
    public function getAreaReferencia()
    {
        return $this->areaReferencia;
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
     * Set tipoPresentacion
     *
     * @param Cpm\JovenesBundle\Entity\Produccion $tipoPresentacion
     */
    public function setTipoPresentacion(\Cpm\JovenesBundle\Entity\Produccion $tipoPresentacion)
    {
        $this->tipoPresentacion = $tipoPresentacion;
    }

    /**
     * Get tipoPresentacion
     *
     * @return Cpm\JovenesBundle\Entity\Produccion 
     */
    public function getTipoPresentacion()
    {
        return $this->tipoPresentacion;
    }
}