<?php
namespace Cpm \ JovenesBundle \ Filter;

use Cpm \ JovenesBundle \ Entity \ Escuela;

class EscuelaFilter extends Escuela implements ModelFilter {

	public $regionDesde;
	public $regionHasta;
	public $distritos;
	public $regiones;
    public $localidades;

	private $cicloFilter;
	
	public function createForm(\Cpm\JovenesBundle\Service\JYM $jym){
		return new EscuelaFilterForm($this, $jym, '_escuela');
	}
	
	public function getSortFields() {
		return array("e.id" => "Id","e.nombre" => "Nombre","e.numero"  => "Numero");
	}
	
	public function getTargetEntity() {
		return 'CpmJovenesBundle:Escuela';
	}
	
	public function getRegionDesde() {
		return $this->regionDesde;
	}

	public function getRegionHasta() {
		return $this->regionHasta;
	}
	
    public function getLocalidades()
    {
        return $this->localidades;
    }

    public function setLocalidades($localidades)
    {
        $this->localidades = $localidades;
    }

    public function setDistritos($distritos)
    {
        $this->distritos = $distritos;
    }  

    public function getDistritos()
    {
    	return $this->distritos;

    }

    public function setRegiones($regiones)
    {
    	$this->regiones=$regiones;
    }
 
    public function getRegiones()
    {
        return $this->regiones;
    }

	
	public function getCicloFilter()
	{
		return $this->cicloFilter;
	}
	
	public function setCicloFilter($ciclo)
	{
		$this->cicloFilter = $ciclo;
	}
}