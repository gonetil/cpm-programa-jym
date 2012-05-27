<?php
namespace Cpm\JovenesBundle;


class StaticConfig {
	
	public static function getEtapas() {
	$etapas = array ();
	$etapas[]=array (
	    'numero' => 1,
	    'nombre' => 'Etapa 1 - Preparación',
	    'accionesUsuario' => array (),
	    'accionesProyecto' => array (),
	);
	$etapas[]=array (
		'numero' => 2,
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
	    'numero' => 3,
	    'nombre' => 'Etapa 3 - Desarrollo de proyectos',
	    'accionesUsuario' => array (),
	    'accionesProyecto' => array ( 
		    'modificar_proyecto' => 
		      array (
		        'href' => 'proyecto_presentar',
		        'label' => 'Enviar proyecto'
		      ),
		    'modificar_colaboradores' => 
		      array (
		        'href' => 'proyecto_edit_colaboradores',
		        'label' => 'Agregar o elminar colaboradores'
		      ) 
		       
		    ), 
	  );
	  $etapas[]=array (
	    'numero' => 4,
	    'nombre' => 'Etapa 4 - Reenvio de proyectos rehacer',
	    'accionesUsuario' => array (),
	    'accionesProyecto' => array ( 
		    'modificar_proyecto' => 
		      array (
		        'href' => 'proyecto_presentar',
		        'label' => 'Enviar proyecto'
		      ),
		    'modificar_colaboradores' => 
		      array (
		        'href' => 'proyecto_edit_colaboradores',
		        'label' => 'Agregar o elminar colaboradores'
		      ) 
		       
		    ), 
	  );
	  $etapas[]=array (
	    'numero' => 5,
	    'nombre' => 'Etapa 5 - Presentación de proyectos',
	    'accionesUsuario' => array (),
	    'accionesProyecto' => array (),
	  );
	  $etapas[]=array (
	    'numero' => 6,
	    'nombre' => 'Etapa 6 - Etapa final',
	    'accionesUsuario' => array (),
	    'accionesProyecto' => array (),
	  );
		return $etapas;
	}

}