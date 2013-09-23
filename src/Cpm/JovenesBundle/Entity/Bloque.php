<?php

namespace Cpm\JovenesBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Cpm\JovenesBundle\Entity\Bloque
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Cpm\JovenesBundle\Entity\BloqueRepository")
 */
class Bloque
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
     * @var integer $posicion
     *
     * @ORM\Column(name="posicion", type="integer")
     */
    private $posicion;

    /**
     * @var datetime $horaInicio
     *
     * @ORM\Column(name="horaInicio", type="datetime")
     */
    private $horaInicio;

    /**
     * @var integer $duracion
     *
     * @ORM\Column(name="duracion", type="integer")
     */
    private $duracion;

 	/**
     *  @ORM\ManyToOne(targetEntity="AuditorioDia")
     */
    private $auditorioDia;
    
   /**
     * @var string $nombre
     *
     * @ORM\Column(name="nombre", type="string")
     */
    private $nombre;

   /**
     * @var boolean tienePresentaciones
     *
     * @ORM\Column(name="tienePresentaciones", type="boolean", nullable=true)
     */
    private $tienePresentaciones;



    /**
     * @ORM\OneToMany(targetEntity="Presentacion", mappedBy="bloque")
     */
	private $presentaciones = array();



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
     * Set posicion
     *
     * @param integer $posicion
     */
    public function setPosicion($posicion)
    {
        $this->posicion = $posicion;
    }

    /**
     * Get posicion
     *
     * @return integer 
     */
    public function getPosicion()
    {
        return $this->posicion;
    }

    /**
     * Set horaInicio
     *
     * @param datetime $horaInicio
     */
    public function setHoraInicio($horaInicio)
    {
        $this->horaInicio = $horaInicio;
    }

    /**
     * Get horaInicio
     *
     * @return datetime 
     */
    public function getHoraInicio()
    {
        return $this->horaInicio;
    }

    /**
     * Set duracion
     *
     * @param integer $duracion
     */
    public function setDuracion($duracion)
    {
        $this->duracion = $duracion;
    }

    /**
     * Get duracion
     *
     * @return integer 
     */
    public function getDuracion()
    {
        return $this->duracion;
    }
    
    public function getAuditorioDia() {
		return $this->auditorioDia;
	}
	public function setAuditorioDia($d) {
		$this->auditorioDia = $d;
	}
	
	  /**
     * Set nombre
     *
     * @param string $nombre
     */
    public function setNombre($nombre)
    {
        $this->nombre = $nombre;
    }

    /**
     * Get nombre
     *
     * @return string 
     */
    public function getNombre()
    {
        return $this->nombre;
    }
 
 
 	public function getTienePresentaciones() {
 		return $this->tienePresentaciones;
 	}
 	
 	public function setTienePresentaciones($lasTiene) {
 		$this->tienePresentaciones = $lasTiene;
 	}
 	   
 	public function getPresentaciones() {
 		return $this->presentaciones;
 	}
 	
 	public function setPresentaciones($p) {
 		$this->presentaciones = $p;
 	}
    
    public function __toString() {
    	return "Bloque ".$this->posicion.": ".$this->nombre."; Tanda ".$this->getAuditorioDia()->getDia()->getTanda()."";
    }
    
    public function toArray($recursive_depth) {
 		if ($recursive_depth == 0)
    		return $this->getId();
    	
		$presentaciones = array();
    	foreach ( $this->getPresentaciones() as $p )
       		$presentaciones[] = $p->toArray($recursive_depth-1);

		return array(
							'id' => $this->id , 
 							 'posicion' => $this->posicion,
 							 'tienePresentaciones' => $this->tienePresentaciones,
 							 'duracion' => $this->duracion,
 							 'horaInicio' => $this->horaInicio,
 							 'auditorioDia' => $this->auditorioDia->getId(),
 							 'presentaciones' => $presentaciones
 		);				 
    }
}