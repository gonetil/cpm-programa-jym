<?php

namespace Cpm\JovenesBundle\Service;

class PerfilDinamico {
	
	private $jym;
	
	private static $_acciones_usuario;
	private static $_acciones_proyecto;	
	private static $_mensajes_usuario;	
	private static $_mensajes_proyecto;	
	
	public function __construct($jym)
	{
		$this->jym=$jym;
	}
	
	public function getAccionesDeUsuario() { 
		if (!isset(self::$_acciones_usuario))
			self::$_acciones_usuario=self::_loadAccionesUsuario();
			
		return $this->evaluar_funciones_usuario(self::$_acciones_usuario);						 
	}								 
						
									 								 
	public function getAccionesDeProyecto($proyecto) {
		if (!isset(self::$_acciones_proyecto))
			self::$_acciones_proyecto=self::_loadAccionesProyecto();
			
		return $this->evaluar_funciones_proyecto(self::$_acciones_proyecto,$proyecto);
	}
	
	public function getMensajesDeUsuario() {
		if (!isset(self::$_mensajes_usuario))
			self::$_mensajes_usuario=self::_loadMensajesUsuario();
			
		return $this->evaluar_funciones_usuario(self::$_mensajes_usuario);
	}
	
	public function getMensajesDeProyecto($proyecto) {
		if (!isset(self::$_mensajes_proyecto))
			self::$_mensajes_proyecto=self::_loadMensajesProyecto();
			
		return $this->evaluar_funciones_proyecto(self::$_mensajes_usuario,$proyecto);
	}
	
	/* ********************************************************************* */
	/* ********************************************************************* */
	/* ********************************************************************* */
	
	private function evaluar_funciones_usuario($funciones) {
		$accionesDeEstaEtapa = $this->jym->getEtapaActual()->getAccionesDeUsuario();
		
		$resultado = array();
		foreach ( $funciones as $accion => $fn ) {
			if (in_array($accion, $accionesDeEstaEtapa)){
				$val = call_user_func($fn, $this->jym);
	       		if ($val) $resultado[] = $val;
			}						 
		}
       return $resultado;
	}
	
	private function evaluar_funciones_proyecto($funciones, $proyecto) {
		$resultado = array();
		
		$accionesDeEstaEtapa = $this->jym->getEtapaActual()->getAccionesDeProyecto();
		foreach ( $funciones as $accion =>$fn ) {
			if (in_array($accion, $accionesDeEstaEtapa)){
				$val = call_user_func($fn, $this->jym, $proyecto);
       			if ($val) $resultado[] = $val;
			}
		}						 
       return $resultado;
	}
	/* ********************************************************************* */
	/* ********************************************************************* */
	/* ********************************************************************* */
	
	public static function _loadAccionesUsuario()
	{
		$acciones=array();
		
		//etapa 2
		$acciones['cargar_proyecto'] = function($jym){
			$ciclo = $jym->getCicloActivo();
			$usuario = $jym->getLoggedInUser();
			
			return array(
				'path' => 'proyecto_wizzard', 
				'label'=>'Nueva inscripción',
				'validation'=>  (count( $usuario->getProyectosCoordinados($ciclo)) > 0 ) ? 
					" Usted ya inscribió una institución ¿Está seguro que desea inscribir otra?" : false 
			); 
		};
		return $acciones;
	}
	
	public static function _loadAccionesProyecto()
	{
		$acciones=array();
		//etapa 2
		$acciones['editar_proyecto'] = function($jym, $proyecto)  {
			if (!$jym->puedeEditar($proyecto))
				return null;
			return array('path' => 'proyecto_edit_wizzard', 'label'=>'Modificar inscripción','validation'=>false); 
		};

		//etapa4
		$acciones['presentar_proyecto'] = function($jym, $proyecto) {
			if (!$jym->puedeEditar($proyecto))
				return null;
			return array(
				'path' => 'proyecto_presentar', 
				'label'=>'Enviar proyecto',
				'validation'=> ($proyecto->hasArchivo() ) ? "Este proyecto ya fue enviado. ¿está seguro que desea enviarlo nuevamente?" : false
			); 
		};
										 
		//etapa 4 y 5
		$acciones['modificar_colaboradores'] = function($jym, $proyecto) {
			if (!$jym->puedeEditar($proyecto))
				return null;
			return array(	
				'path' => 'proyecto_edit_colaboradores', 
				'label'=>'Agregar o elminar colaboradores',
				'validation'=>false
			); 
		};
		
		//etapa 6
		$acciones['representar_proyecto'] = function($jym, $proyecto) { 
			
			if (!$jym->puedeEditar($proyecto))
				return null;
			
			if  ( ($proyecto->estaEnEstadoActual(ESTADO_REHACER)) || ($proyecto->estaEnEstadoActual(ESTADO_PRESENTADO) ) ) {  
				return array(
					'path' => 'proyecto_presentar', 
					'label'=>'Reenviar proyecto',
					'validation'=> false
				); 
			}
			else 
				return null;
		};
		
				//etapa4
		$acciones['proyecto_confirmar_prechapa'] = function($jym, $proyecto) {
			if (!$jym->puedeEditar($proyecto))
				return null;
			return array(
				'path' => 'proyecto_confirmar_prechapa', 
				'label'=>'Confirmar datos',
				'validation'=> false
			); 
		};
		
		return $acciones;
	}
	
	public static function _loadMensajesUsuario()
	{
		$mensajes=array();
		
		//etapa1 y 2
		$mensajes['conserve_usuario_y_clave'] = function($jym) { 
			return array('info-message' => 'Por favor, conserve su nombre de usuario y contraseña. Trabajaremos juntos todo el año, y está página será nuestro canal de comunicación principal.' );
		};
							
		//etapa 5,6
		$mensajes['sera_la_proxima'] = function($jym) {
			$usuario = $jym->getLoggedInUser();
			foreach ( $usuario->getProyectosCoordinados() as $proyecto) {
       			if ($proyecto->hasArchivo()) return null;
			}

			return array('info-message' => 'Estimado/a '.$usuario->getNombre().', no ha cargado el proyecto de investigación en la ' .
				'fecha establecida por lo que su escuela ya no participa en la Convocatoria de este año. ' .
				'El año que viene podrá inscribirse nuevamente con su mismo usuario. Los esperamos en la próxima Convocatoria.'
			);
		};
													
		$mensajes['invitaciones_pendientes'] = function($jym) {
			$usuario = $jym->getLoggedInUser();
			$proyectos = $usuario->getProyectosCoordinados();
			$pendientes = 0;
			foreach ( $proyectos as $proyecto) {
       			$pendientes += count($proyecto->getInvitacionesPendientes()); 
			} 
			if ($pendientes == 1)
				return array('warning-message' => 'Posee una invitación pendiente de confirmación en alguno de sus proyectos');
			elseif ($pendientes > 1)
				return array('warning-message' => 'Posee '.$pendientes.' invitaciones pendientes de confirmación en sus proyectos');
			else		
				return null;
		};
		
		return $mensajes;
	}
	
	public static function _loadMensajesProyecto()
	{
		$mensajes=array();
		return $mensajes;
	}
	
}

