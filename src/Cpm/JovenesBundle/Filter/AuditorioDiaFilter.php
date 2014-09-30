<?php
namespace Cpm \ JovenesBundle \ Filter;

use Cpm \ JovenesBundle \ Entity \ AuditorioDia;

class AuditorioDiaFilter extends AuditorioDia implements ModelFilter {

	private $cicloFilter;
	
	public function createForm(\Cpm\JovenesBundle\Service\JYM $jym){
		return new AuditorioDiaFilterForm($this, $jym, '_auditoriodia');
	}
	
	public function getTargetEntity() {
		return 'CpmJovenesBundle:AuditorioDia';
	}
	
	public function getSortFields() {
		return array('id'=>'Id');
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
?>