<?php
/*
 * Created on 03/04/2013
 * @author gonetil
 * project jym
 * Copyleft 2013
 * 
 */
namespace Cpm\JovenesBundle\EntityDummy;

/**
 * formulario de union de Usuarios
 */

class UnionUsuariosBatch extends UsuarioBatch {
	
	public $usuarioFinal;

	public function __construct(){
		parent::__construct();
	}
	
	public function getUsuarioFinal() {
		return $this->usuarioFinal;
	}	
	public function setUsuarioFinal($usuario) {
		$this->usuarioFinal = $usuario;
	}
}
