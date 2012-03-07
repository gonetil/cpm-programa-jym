<?php

namespace Cpm\JovenesBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Cpm\JovenesBundle\Entity\ProyectoSearch
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Cpm\JovenesBundle\Entity\ProyectoSearchRepository")
 */
class ProyectoSearch
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
     * @var boolean $esPrimeraVezDocente
     *
     */
    private $esPrimeraVezDocente;

    /**
     * @var boolean $esPrimeraVezAlumnos
     *
     */
    private $esPrimeraVezAlumnos;

    /**
     * @var boolean $esPrimeraVezEscuela
     *
     */
    private $esPrimeraVezEscuela;

	
    /**
     * @var integer temaPrincipal
     */
    private $temaPrincipal;
    
    /**
     * @var integer produccionFinal
     */
    private $produccionFinal;
    
    
    /**
     * @var integer localidad
     */
    private $localidad;
    
    /**
     * @var integer distrito
     */
    private $distrito;
    
    /**
    * @var integer region
    */
    public $region;
    
    /**
    * @var integer $regionDesde
    */
    public $regionDesde;
    
    /**
    * @var integer $regionHasta
    */
    public $regionHasta;
    
    /**
    * @var integer $tipoInstitucion
    */
    public $tipoInstitucion;
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
     * Set esPrimeraVezDocente
     *
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
    
    
    public function getTemaPrincipal()
    { 
    	return $this->temaPrincipal;
    }
    public function setTemaPrincipal($tema) 
    {
    	$this->temaPrincipal = $tema;	
    }
    
    public function getProduccionFinal()
    {
    	return $this->produccionFinal;
    }
    
    public function setProduccionFinal($produccion) {
    	$this->produccionFinal = $produccion;
    }

    
    public function getLocalidad() 
    {
    	return $this->localidad;
    }
    
    public function setLocalidad($localidad) 
    {
    	$this->localidad = $localidad;
    }
    
    public function getDistrito() 
    { 
    	return $this->distrito;
    }
    
    public function setDistrito($distrito)
    {
    	$this->distrito = $distrito;
    }
    
    public function getRegion() 
    {
    	return $this->region;
    }
    
    public function setRegion($region)
    {
    	$this->region = $region;
    }
    
    
    public function getRegionDesde() {
    	return $this->regionDesde;
    }
    
    public function getRegionHasta() {
    	return $this->regionHasta;
    }
    
    public function getTipoInstitucion() {
    	return $this->tipoInstitucion;
    }
    
    public function setRegionDesde($regionDesde) {
    	$this->regionDesde = $regionDesde;
    }
    
    public function setRegionHasta($regionHasta) {
    	$this->regionHasta = $regionHasta;
    }
    
    public function setTipoInstitucion($tipoInstitucion) {
    	$this->tipoInstitucion = $tipoInstitucion;
    }
}