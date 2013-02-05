<?php
namespace Cpm\JovenesBundle\EntityDummy;


class ProyectoBatch {
	
	private $proyectos;
	
	public function __construct(){
		$this->proyectos = new \Doctrine\Common\Collections\ArrayCollection();
	}
	public function getProyectos() {
		return $this->proyectos ;
	}
	public function setProyectos($x) {
		$this->proyectos = $x;
	}

}
