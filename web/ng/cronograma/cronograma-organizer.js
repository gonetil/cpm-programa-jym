/**
 * Randomize array element order in-place.
 * Using Fisher-Yates shuffle algorithm.
 */
function shuffleArray(array) {
	
    for (var i = array.length - 1; i > 0; i--) {
        var j = Math.floor(Math.random() * (i + 1));
        var temp = array[i];
        array[i] = array[j];
        array[j] = temp;
    }
    return array;
}

var MUY_BUENA = 'Muy bueno';

var ALGORITMO_ESTRICTO = 1;
var ALGORITMO_INTERMEDIO = 2;
var ALGORITMO_FORZADO = 3;

check_config = function(config) {
	if (!config) {
		config = {};
		config["level"]= ALGORITMO_ESTRICTO; //default config
	}
	return config;
};

//bloque {hora_inicio: , hora_fin: , duracion, titulo , descripcion},
function bloque_duracion(bloque) { return bloque.duracion; }

/*cuenta la cantidad de presenraciones muy buenas deun bloque*/
function bloque_countMuyBuenas(bloque) {
	var countMB = bloque.contarPresentacionesConValoracion(MUY_BUENA);
	return countMB;
}


function presentacionCabeEnBloque(presentacion,bloque) {
	return (bloque.duracionEstimada() + presentacion.tipoPresentacion.duracion <= bloque.duracion);
}

/* toma una lista de bloques, y retorna el bloque que tenga menos presentaciones muy buenas*/
function buscarBloqueConMenosMuyBuenas(bloques, presentacion, config) {
	config = check_config(config);
	bloque_min = null;
	cant_min = 1000;
	for(index in bloques) { 
		bloque = bloques[index];
		cantMB=bloque_countMuyBuenas(bloque);
		if ( (cantMB <= cant_min) && 
			 ( 
				 (config.level > ALGORITMO_ESTRICTO) //el tiempo es lo de menos 
				 ||
				 ( (config.level == ALGORITMO_ESTRICTO) && (presentacionCabeEnBloque(presentacion,bloque)) ) //si el tiempo importa, verifico que entre
				)
			)
		{
			cant_min = cantMB;
			bloque_min = bloque;
		}
	}
	
		return bloque_min;
}

/*
 * Busca el bloque con mas tiempo libre, en base a su duracion y a la sumatoria de las duraciones de sus presentaciones
 * */
function buscarBloqueMasLibre(bloques) {
	
	var tiempo_libre, max_libre = -10000; //muy negativo, por si algun bloque se pasa del tiempo estimado, retornaremos el "menos pasado"
	var bloque_max=null, bloque ;
	
	for(index in bloques) { 
		bloque = bloques[index];
		tiempo_libre =  parseInt(bloque.duracion) - bloque.duracionEstimada();
		
		if (tiempo_libre > max_libre) {
			max_libre = tiempo_libre;
			bloque_max = bloque;
		} else if ((tiempo_libre == max_libre) && (bloque_max.presentaciones.length > bloque.presentaciones.length)) //tenian el mismo tiempo libre, pero uno tenia menos presentaciones
			bloque_max = bloque;
	} 
	return { "bloque": bloque_max, "tiempo_libre" : max_libre};
}
/**
 * 
 * @param bloques array de bloques candidatos (ya sabemos que cumple con los requisitos)
 * @param presentacion una presentacion
 * Busca el mejor bloque para la presentacion, asegurandose que 1) entre en en tiempo del bloque
 * y priorizando las presentaciones muy buenas
 */
function buscarMejorBloque(bloques,presentacion, config) {
	
	config = check_config(config);
	
	if (bloques.length < 1) //chequeo simple, pero por las dudas lo dejo
		return null;
	
	if (presentacion.valoracion == MUY_BUENA) //las presentaciones muy buenas las ubico en algun bloque si o si
	{
		//console.log(presentacion.id + ' es una presentacion MB. Le buscamos un bloque: ');
		bloque_min = buscarBloqueConMenosMuyBuenas(bloques,presentacion,config);
		
		return bloque_min;
	} 
	else 
	{
		console.log("Busco el bloque mas libre entre ",bloques);
		max = buscarBloqueMasLibre(bloques); //JSON {bloque,duracion_estimada}
		    if (max.bloque == null) {
		    	//console.log('no encontre un bloque mas libre');
		    	return null;
		    } else
		    	console.log("este es el bloque mas libre: ",max);
		    
		    //max.bloque.duracion 
			if ( max.tiempo_libre - parseInt(presentacion.tipoPresentacion.duracion) >= 0   ) { //si la presentacion entra en el bloque, joya
				return max.bloque;
			} if (config.level >= ALGORITMO_INTERMEDIO) //los tiempos no nos importan tanto
				return max.bloque;
			else { 
				//console.log("Lastima que no entra: ",(max.bloque.duracion - max.tiempo_libre),parseInt(presentacion.tipoPresentacion.duracion) );
				return null; //no encontre un bloque "seguro"
			}
	
	}
}

/*
 * cuando se fuerza la distribucion, se busca si o si un bloque aunque no encaje del todo bien
 * Retorna el bloque que tenga menos presentaciones
 * */
function bloqueConMenosPresentaciones(bloques_candidatos,presentacion,todos_los_bloques) {
	
	if (bloques_candidatos.length == 0)  // no tengo posibles bloques candidatos, manoteo cualquiera que tenga presentaciones
		bloques_candidatos = todos_los_bloques; //copio todos los bloques 
	
	min_bloque = null;
	min_count = 1000;
	for(index in bloques_candidatos) {
		bloque = bloques_candidatos[index];
		if (bloque.presentaciones.length <= min_count ) {
			min_count = bloque.presentaciones.length;
			min_bloque = bloque;
		}
	}
	return bloque;
}


/**
 * verifica que una presentacion pueda incluirse en un bloque
 * */
function cumpleCondicionesPresentacion(tanda,bloque,presentacion,config) {
	
	config = check_config(config);
	
	if (!bloque.tienePresentaciones)
		return false; 
	
	
	auditorio = tanda.getAuditorioForBloque(bloque);
	if (!auditorioSoportaPresentacion(auditorio,presentacion)) { 
		return false;	//el tipo de produccion no puede realizarse en este auditorio
	} 
	
	if (!bloque.tieneEje(presentacion.ejeTematico)  && (config.level < ALGORITMO_FORZADO )) {
		return false;
	}
		
	
	if (!bloque.tieneArea(presentacion.areaReferencia) && (config.level < ALGORITMO_FORZADO )){
		return false;
	}

	if ( (config.city_limit > 0) && (bloque.cantPresentacionesEnLocalidad(presentacion.localidad) >= config.city_limit)  && (config.level < ALGORITMO_FORZADO )) {
		return false;
	}

	return true;
}




function auditorioSoportaPresentacion(auditorio,presentacion) {
	for(var i=0;i<auditorio.producciones.length;i++) {	
		if ((presentacion.tipoPresentacion != '') && (presentacion.tipoPresentacion.id==auditorio.producciones[i].id))
			return true;
	}
	
	return false;
}
