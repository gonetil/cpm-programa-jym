<?php
namespace Cpm \ JovenesBundle \ Filter;

use Cpm \ JovenesBundle \ Entity \ Escuela;

class EscuelaFilter extends Escuela implements ModelFilter {

	public $regionDesde;
	public $regionHasta;
	public $distrito;
	public $region;
	private $cicloFilter;
	
	public function createForm(Cpm\JovenesBundle\Service\JYM $jym){
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
	
	   /**
     * Set distrito
     *
     * @param Cpm\JovenesBundle\Entity\Distrito $distrito
     */
    public function setDistrito($distrito)
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
     * Set region
     *
     * @param Cpm\JovenesBundle\Entity\RegionEducativa $region
    */
    public function setRegion($region)
    {
    	$this->region=$region;
    }
 
    /**
     * Get region
     *
     * @return Cpm\JovenesBundle\Entity\RegionEducativa 
     */
    public function getRegion()
    {
        return $this->region;
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