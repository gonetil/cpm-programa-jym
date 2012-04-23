<?php
namespace Cpm\JovenesBundle;


class StaticConfig {
	
	public static function getEtapas() {
	$etapas = array ();
	$etapas[]=array (
	    'nombre' => 'Etapa 1 - Preparación',
	    'accionesUsuario' => array (),
	    'accionesProyecto' => array (),
	);
	$etapas[]=array (
	    'nombre' => 'Etapa 2 - Inscripción de propuestas',
	    'accionesUsuario' => 
	    array (
	      'crear_proyecto' => 
	      array (
	        'href' => 'proyecto_wizzard',
	        'label' => 'Inscribir escuela'
	      ),
	    ),
	    'accionesProyecto' => 
	    array (
	      'modificar_proyecto' => 
	      array (
	        'href' => 'proyecto_edit_wizzard',
	        'label' => 'Modificar inscripción'
	      ),
	    ),
	  );
	  $etapas[]=array (
	    'nombre' => 'Etapa 3 - Desarrollo de proyectos',
	    'accionesUsuario' => array (),
	    'accionesProyecto' => array ( 
		    'modificar_proyecto' => 
		      array (
		        'href' => 'proyecto_presentar',
		        'label' => 'Enviar proyecto'
		      ) 
		    ), 
	  );
	  $etapas[]=array (
	    'nombre' => 'Etapa 4 - Presentación de proyectos',
	    'accionesUsuario' => array (),
	    'accionesProyecto' => array (),
	  );
	  $etapas[]=array (
	    'nombre' => 'Etapa 5 - Etapa final',
	    'accionesUsuario' => array (),
	    'accionesProyecto' => array (),
	  );
		return $etapas;
	}

}