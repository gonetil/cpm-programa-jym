<?php
namespace Cpm\JovenesBundle;


class StaticConfig {
	
	public static function getEtapas() {
	$etapas = array ();
	$etapas[]=array (
	    'numero' => 1,
	    'nombre' => 'Etapa 1 - Preparación'
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
	    'nombre' => 'Etapa 5 - Presentación de proyectos'
	  );
	  $etapas[]=array (
	    'numero' => 6,
	    'nombre' => 'Etapa 6 - Etapa final'
	  );
		return $etapas;
	}

}