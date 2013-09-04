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
 * Cpm\JovenesBundle\Entity\Presentacion
 *
 * @ORM\Table
 * @ORM\Entity(repositoryClass="Cpm\JovenesBundle\Entity\PresentacionRepository")
 */
class PresentacionInterna extends Presentacion
{
	  
    /**
    * @ORM\ManyToOne(targetEntity="Proyecto")
    */
    private $proyecto;
    
    /**
    * @ORM\ManyToOne(targetEntity="Distrito")
    */
    private $distrito;

    /**
    * @ORM\ManyToOne(targetEntity="Localidad")
    */
    private $localidad;
    

	static function createFromProyecto($proyecto) {
		$presentacion = new PresentacionInterna();
		$presentacion->setProyecto($proyecto);
		$presentacion->setLocalidad( $proyecto->getEscuela()->getLocalidad() );
		$presentacion->setDistrito( $proyecto->getEscuela()->getLocalidad()->getDistrito() );
		$presentacion->setTitulo($proyecto->getTitulo());
		$presentacion->setEjeTematico($proyecto->getTemaPrincipal());
		
		$presentacion->setAreaReferencia($proyecto->getEje());
		
		$presentacion->setTipoPresentacion($proyecto->getProduccionFinal());
		
		return $presentacion;
		
	}
    /**
     * Set proyecto
     *
     * @param Cpm\JovenesBundle\Entity\Proyecto $proyecto
     */
    public function setProyecto(\Cpm\JovenesBundle\Entity\Proyecto $proyecto)
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

    /**
     * Set distrito
     *
     * @param Cpm\JovenesBundle\Entity\Distrito $distrito
     */
    public function setDistrito(\Cpm\JovenesBundle\Entity\Distrito $distrito)
    {
        $this->distrito = $distrito;
    }

    /**
     * Get distrito
     *
     * @return Cpm\JovenesBundle\Entity\Distrito 
     */
    public function getDistrito()
    {
        return $this->distrito;
    }

    /**
     * Set localidad
     *
     * @param Cpm\JovenesBundle\Entity\Localidad $localidad
     */
    public function setLocalidad(\Cpm\JovenesBundle\Entity\Localidad $localidad)
    {
        $this->localidad = $localidad;
    }

    /**
     * Get localidad
     *
     * @return Cpm\JovenesBundle\Entity\Localidad 
     */
    public function getLocalidad()
    {
        return $this->localidad;
    }
    
    
}