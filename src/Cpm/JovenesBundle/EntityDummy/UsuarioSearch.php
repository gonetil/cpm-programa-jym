<?php

namespace Cpm\JovenesBundle\EntityDummy;

/**
 * Formulario de busqueda y filtrado de usuarios
 */
class UsuarioSearch
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
    private $email;
	private $apellido;
	private $habilitados = true;
	private $coordinadores = false;
	
	public function getEmail()  { return $this->email; }
	public function getApellido() { return $this->apellido; }
	public function setEmail($x) { $this->email = $x; }
	public function setApellido($x) { $this->apellido = $x; }
	public function getHabilitados() { return $this->habilitados;}
	public function setHabilitados($x) { $this->habilitados = $x; }
	public function getCoordinadores() { return $this->coordinadores; }
	public function setCoordinadores($x) { $this->coordinadores = $x; }
}
?>