<?php
namespace Cpm\JovenesBundle\EntityDummy;

/**
 * formulario de envio de correos masivos
 */

class CorreoBatch extends ProyectoBatch{
	
	public $ccCoordinadores;
	public $ccColaboradores;
	public $ccEscuelas;
	
	public $asunto;
	public $cuerpo;
	
	public $plantilla;
	public $preview;
	public $previewText;
	public $archivos;
	
	public function __construct() {
		parent::__construct();
		$this->ccCoordinadores = true;
		$this->ccColaboradores = false;
		$this->ccEscuelas = false;
		$this->preview = true;		
		$this->previewText = "";		
	}
	
	public function getCcCoordinadores() {
		return $this->ccCoordinadores ;
	}
	
	public function setCcCoordinadores($x) {
		$this->CcCoordinadores = $x;
	}
	
	public function getCcColaboradores() {
		return $this->ccColaboradores ;
	}
	
	public function setCcColaboradores($x) {
		$this->ccColaboradores = $x;
	}
	
	public function getCcEscuelas() {
		return $this->ccEscuelas ;
	}
	public function setCcEscuelas($x) {
		$this->ccEscuelas = $x;
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
