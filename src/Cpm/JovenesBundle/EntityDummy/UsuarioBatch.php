<?php
/*
 * Created on 26/03/2013
 * @author gonetil
 * project jym
 * Copyleft 2013
 * 
 */

namespace Cpm\JovenesBundle\EntityDummy;


class UsuarioBatch {
	
	private $usuarios;
	
	public function __construct(){
		$this->usuarios = new \Doctrine\Common\Collections\ArrayCollection();
	}
	public function getUsuarios() {
		return $this->usuarios ;
	}
	public function setUsuarios($x) {
		$this->proyectos = $x;
	}

}
