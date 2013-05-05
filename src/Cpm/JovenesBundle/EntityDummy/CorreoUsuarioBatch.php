<?php
/*
 * Created on 26/03/2013
 * @author gonetil
 * project jym
 * Copyleft 2013
 * 
 */
namespace Cpm\JovenesBundle\EntityDummy;

/**
 * formulario de envio de correos masivos a Usuarios
 */

class CorreoUsuarioBatch extends UsuarioBatch{
	
	public $asunto;
	public $cuerpo;
	
	public $plantilla;
	public $preview;
	public $previewText;
	public $archivos;
	
	public function __construct() {
		parent::__construct();
		$this->preview = true;		
		$this->previewText = "";		
	}
	
	public function getAsunto() {
		return $this->asunto;
	}
	public function setAsunto($x) {
		$this->asunto = $x;
	}
	
	public function getCuerpo() {
		return $this->cuerpo;
	}
	public function setCuerpo($x) {
		$this->cuerpo = $x;
	}
	
	public function getPlantilla() {
		return $this->plantilla;
	}
	
	public function setPlantilla($plantilla) {
		$this->plantilla = $plantilla;
	}
	
	public function getPreview() {
		return $this->preview;
	}
	public function setPreview($preview) {
		$this->preview = $preview;
	}
	
	public function getPreviewText() {
		return $this->previewText;
	}
	public function setPreviewText($previewText) {
		$this->previewText = $previewText;
	}
	
	public function getArchivos() {
		return $this->archivos;
	}
	public function setArchivos($archivos) {
		$this->archivos = $archivos;
	}
}
