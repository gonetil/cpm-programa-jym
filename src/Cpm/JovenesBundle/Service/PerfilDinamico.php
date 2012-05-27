<?php

namespace Cpm\JovenesBundle\Service;

class PerfilDinamico {
	
	
	private function evaluar_funciones($funciones) {
		$resultado = array();
		foreach ( $funciones as $fn ) {
       		$val = call_user_func($fn);
       		if ($val) $resultado[] = $val;
		}						 
       return $resultado;
		
	}
	public function accionesDeUsuario($usuario,$etapa_actual) { 
		$acciones = array();
		$acciones['cargar_proyecto'] = function() use ($usuario,$etapa_actual ) {
								if ($etapa_actual['numero'] == 2) 
									return array(	'path' => 'proyecto_wizzard', 
													'label'=>'Inscribir escuela',
													'validation'=>  (count( $usuario->getProyectosCoordinados()) > 0 ) ? " Usted ya inscribió una escuela ¿Está seguro que desea inscribir otra?" : false 
												); 
								else 
									return null;
								 };
								 
		return $this->evaluar_funciones($acciones);						 
	}								 
						
									 								 
	public function accionesDeProyecto($proyecto,$usuario,$etapa_actual) {
		$acciones = array();
		$acciones['editar_proyecto'] = function() use ($etapa_actual) {
								if ($etapa_actual['numero'] == 2) 
									return array('path' => 'proyecto_edit_wizzard', 'label'=>'Modificar inscripción','validation'=>false); 
								else 
									return null;
								 };

		$acciones['presentar_proyecto'] = function() use ($proyecto,$etapa_actual) {
								if ($etapa_actual['numero'] == 3) 
									return array(	'path' => 'proyecto_presentar', 
													'label'=>'Enviar proyecto',
													'validation'=> ($proyecto->hasArchivo() ) ? "Este proyecto ya fue enviado. ¿está seguro que desea enviarlo nuevamente?" : false
													); 
								else 
									return null;
								 };
										 
		$acciones['modificar_colaboradores'] = function() use ($proyecto,$etapa_actual) {
								if (($etapa_actual['numero'] >= 3) && ($etapa_actual['numero'] <= 5)) 
									return array(	'path' => 'proyecto_edit_colaboradores', 
													'label'=>'Agregar o elminar colaboradores',
													'validation'=>false); 
								else 
									return null;
								 };
		$acciones['representar_proyecto'] = function() use ($proyecto,$etapa_actual) { 
													if ( ($etapa_actual['numero'] == 4) && ($proyecto->getEstadoActual()) &&  
														  ($proyecto->getEstadoActual()->getEstado() == ESTADO_REHACER || $proyecto->getEstadoActual()->getEstado() == ESTADO_PRESENTADO ) 
														)
													return array(	'path' => 'proyecto_presentar', 
																	'label'=>'Reenviar proyecto',
																	'validation'=> false); 
												else 
													return null;
												};
												
		return $this->evaluar_funciones($acciones);										
			
	}
	
	public function mensajesDeUsuario($usuario,$etapa_actual) {
		$mensajes = array();
		
		$mensajes['conserve_usuario_y_clave'] = function() use ($etapa_actual) { 
														if ($etapa_actual['numero']  <=2) { 
															return array('info-message' => 'Por favor, conserve su nombre de usuario y contraseña. Trabajaremos juntos todo el año, y está página será nuestro canal de comunicación principal.' );
														}
														else  return null;	
													};
													
		$mensajes['sera_la_proxima'] = function() use ($etapa_actual, $usuario) {
											if ($etapa_actual['numero'] < 4) //este mensaje tiene sentido recien a partir de la etapa 4
												return null;
										
											$proyectos = $usuario->getProyectosCoordinados();
											$con_archivo = 0;
											foreach ( $proyectos as $proyecto) {
       											$con_archivo += ($proyecto->hasArchivo())?1:0;
											}

											if ($con_archivo == 0) {
												return array('info-message' => 'Estimado/a '.$usuario->getNombre().', no ha cargado el proyecto de investigación en la fecha establecida por lo que su escuela ya no participa en la Convocatoria 2012. El año que viene podrá inscribirse nuevamente con su mismo usuario. Los esperamos en la Convocatoria 2013.');
											} else return null;								
										 };													
		$mensajes['invitaciones_pendientes'] = function() use ($usuario) {
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
		
		return $this->evaluar_funciones($mensajes);
		
	}
	
	public function mensajesDeProyecto($proyecto,$usuario,$etapa_actual) {
		$mensajes = array();
		return $this->evaluar_funciones($mensajes);
	}
	
}

?>