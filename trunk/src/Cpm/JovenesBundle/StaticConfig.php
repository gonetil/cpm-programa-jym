<?php
namespace Cpm\JovenesBundle;


class StaticConfig {
	
	public static function getEtapas() {
	$etapas = array ();
	$etapas[]=array(
	    'numero' => 1,
	    'nombre' => 'Etapa 1 - Preparación',
	    'proyectos_activos_filter' => 'proyecto' //no borrar, se utilizará por default
	);
	$etapas[]=array (
		'numero' => 2,
	    'nombre' => 'Etapa 2 - Inscripción de propuestas'
	  );
	  $etapas[]=array (
	    'numero' => 3,
	    'nombre' => 'Etapa 3 - Desarrollo de proyectos'
	  );
	  $etapas[]=array (
	    'numero' => 4,
	    'nombre' => 'Etapa 4 - Reenvio de proyectos rehacer'
	  );
	  $etapas[]=array (
	    'numero' => 5,
	    'nombre' => 'Etapa 5 - Presentación de proyectos',
	    'proyectos_activos_filter' => 'proyectos_presentados'
	  );
	  $etapas[]=array (
	    'numero' => 6,
	    'nombre' => 'Etapa 6 - Preparación para Chapadmalal',
	    'proyectos_activos_filter' => 'proyectos_aprobados'
	  );
	  $etapas[]=array (
	    'numero' => 7,
	    'nombre' => 'Etapa 7 - Durante Chapadmalal'
	  );
	  $etapas[]=array (
	    'numero' => 8,
	    'nombre' => 'Etapa 8 - Etapa final'
	  );
		return $etapas;
	}

}
