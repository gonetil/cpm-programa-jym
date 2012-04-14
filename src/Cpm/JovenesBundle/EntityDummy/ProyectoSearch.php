<?php

namespace Cpm\JovenesBundle\EntityDummy;

/**
 * Formulario de busqueda y filtrado de proyctos
 */
class ProyectoSearch
{
    /**
     * @var integer $id
     *
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
    
    public $otroTipoInstitucion;
    
    
    public $coordinador;
    public $escuela;
    
    public $proyectos_seleccionados;
    public $batch_action;
    public $batch_action_type; //selection or all
    
  //  public $archivo;
    
    public $orderBy;
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

    public function getOtroTipoInstitucion() {
    	return $this->otroTipoInstitucion;
    }
    
    public function setOtroTipoInstitucion($o) {
    	$this->otroTipoInstitucion = $o;
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
    
    public function setCoordinador($coordinador)
    { 
    	$this->coordinador = $coordinador;
    }
    
    public function getCoordinador()
    {
    	return $this->coordinador;
    }
    
    public function setEscuela($escuela)
    {
    	$this->escuela = $escuela;
    }
    
    public function getEscuela() 
    {
    	return $this->escuela;
    }

    public function getProyectos_seleccionados() {
    	return $this->proyectos_seleccionados;
    }
    public function setProyectos_seleccionados($proyectos_seleccionados) {
    	$this->proyectos_seleccionados = $proyectos_seleccionados;
    }
    
    public function getBatch_action() {
    	return $this->batch_action;
    }
    public function setBatch_action($batch) {
    	$this->batch_action = $batch;
    }
    
    public function getBatch_action_type() { 
    	return $this->batch_action_type;
    }
    
    public function setBatch_action_type($type) {
    	return $this->batch_action_type = $type;
    }
    
/*    public function getArchivo() {
    	return $this->archivo;
    }
    public function setArchivo($archivo) {
    	$this->archivo = $archivo;
    }
  */  
    public function getOrderBy() 
    { 
    	return $this->orderBy;
    }
    public function setOrderBy($order) { 
    	$this->orderBy = $order;
    }
    
}