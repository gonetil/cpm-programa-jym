<?php
namespace Cpm\JovenesBundle;


class StaticConfig {
	
	public static function getEtapas() {
	$etapas = array ();
	$etapas[]=array(
	    'numero' => 1,
	    'nombre' => 'Etapa 1 - Preparación',
	    'proyectos_activos_filter' => 'proyecto', //no borrar, se utilizará por default
	    'descripcion' => 'Etapa previa al comienzo del ciclo. Ideal para registrar eventos, agregar localidades, distritos y regiones, verificar el listado de temas, tipos de instituciones y tipos de escuelas, etc. '
	);
	$etapas[]=array (
		'numero' => 2,
	    'nombre' => 'Etapa 2 - Inscripción de propuestas',
	    'descripcion' => 'En la etapa 2, los docentes pueden registrarse como usuarios, y agregar sus propuestas de proyectos'
	  );
	  $etapas[]=array (
	    'numero' => 3,
	    'nombre' => 'Etapa 3 - Desarrollo de proyectos',
	    'descripcion' => 'Durante la tercer etapa, la carga de propuestas por parte de los docentes ya está cerrada. Los administradores pueden ahora revisar cada proyecto, corregir sus datos, y realizar la evaluación de los mismos'
	   );
	  $etapas[]=array (
	    'numero' => 4,
	    'nombre' => 'Etapa 4 - Reenvio de proyectos rehacer',
	    'descripcion' => 'En esta etapa los docentes cuyas propuestas no han sido aprobadas tendrán la oportunidad de reenviarlas. Los administradores podrán también cargar aquí las evaluaciones de estas propuestas'
	  );
	  $etapas[]=array (
	    'numero' => 5,
	    'nombre' => 'Etapa 5 - Presentación de proyectos',
	    'proyectos_activos_filter' => 'proyectos_presentados',
	    'descripcion' => 'En la quinta etapa, los docentes que posean sus propuestas aprobadas podrán cargar los proyectos completos como archivos adjuntos. Al cargarlos, se les solicita que confirmen algunos datos como título del proyecto, tema, producción final y descripción breve.'
	  );
	  $etapas[]=array (
	    'numero' => 6,
	    'nombre' => 'Etapa 6 - Preparación para Chapadmalal',
	    'proyectos_activos_filter' => 'proyectos_aprobados',
	    'descripcion' => 'Durante esta etapa los administradores organizan el encuentro en Chapadmalal, cargando las distintas instancias, enviando las invitaciones a los docentes, coordinando los viajes de las escuelas (pasajes, medios de transporte...), etcétera'
	  );
	  $etapas[]=array (
	    'numero' => 7,
	    'nombre' => 'Etapa 7 - Durante Chapadmalal',
	    'descripcion' => 'Esta etapa permanece abierta mientras se está realizando el encuentro de Chapadmalal'
	  );
	  $etapas[]=array (
	    'numero' => 8,
	    'nombre' => 'Etapa 8 - Etapa final',
	    'descripcion' => 'Una vez finalizado el encuentro en Chapadmalal, los administradores podrán cargar observaciones sobre los proyectos y presentaciones, agregar información faltante, y finalmente cerrar el ciclo actual'
	  );
		return $etapas;
	}

}
