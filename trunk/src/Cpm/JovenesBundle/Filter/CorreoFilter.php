<?php

namespace Cpm\JovenesBundle\Filter;

use Cpm\JovenesBundle\Entity\Correo;

class CorreoFilter extends Correo
{
	private $fechaMin;
	private $fechaMax;
	private $sort;
	
	public function getFechaMin(){
		return $this->fechaMin;	
	}
	
	public function setFechaMin($fechaMin){
		$this->fechaMin=$fechaMin;	
	}
	
	public function getFechaMax(){
		return $this->fechaMax;
	}

	public function setFechaMax($fechaMax){
		$this->fechaMax=$fechaMax;
	}
	
	public function getSort(){
		if (empty($this->sort))
			$this->sort=new SortFilter('fecha');
		return $this->sort;
	}

	public function setSort($sort){
		$this->sort=$sort;
	}
}
