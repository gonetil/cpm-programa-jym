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

var MUY_BUENA = 'muy buena';

// presentacion  : id,titulo,escuela,duracion,ubicacion,area_referencia,eje,tipo_produccion
function duracion(presentacion) { return presentacion.duracion; }
function valoracion(presentacion) { return presentacion.valoracion; }

//bloque {hora_inicio: , hora_fin: , duracion, titulo , descripcion},
function bloque_duracion(bloque) { return bloque.duracion; }

/*cuenta la cantidad de presenraciones muy buenas deun bloque*/
function bloque_countMuyBuenas(bloque) { 
	var countMB=0;
	for(var i=0;i<bloque.presentaciones.length;i++) {
		if (valoracion(bloque.presentaciones[i]) == MUY_BUENA) 
			countMB++;
	}
	return countMB;
}

/*calcula la duracion del bloque en base a la duracion de sus presentaciones*/
function bloque_calcularDuracion(bloque) {
	var count=0;
	for(var i=0;i<bloque.presentaciones.length;i++) {
		count += duracion(bloque.presentaciones[i]); 
	}
	return count;
}

/* toma una lista de bloques, y retorna el bloque que tenga menos presentaciones muy buenas*/
function buscarBloqueConMenosMuyBuenas(bloques) {
	bloque_min = null;
	cant_min = 1000;
	for(var i=bloques.length-1;i>0;i--) {
		cantMB=bloque_countMuyBuenas(bloques[i]);
		if (cantMB <= cant_min) {
			cant_min = cantMB;
			bloque_min = bloques[i];
		}
	}
	return bloque_min;
}

/*
 * Busca el bloque con mas tiempo libre, en base a su duracion y a la sumatoria de las duraciones de sus presentaciones
 * */
function buscarBloqueMasLibre(bloques) {
	bloque_max = null;
	duracion_max = 0;
	for(var i=bloques.length-1;i>0;i--) {
		duracion_bloque=bloque_calcularDuracion(bloques[i]);
		
		if (bloque_duracion(bloques[i]) + duracion_bloque >= duracion_max) {
			duracion_max = duracion_bloque;
			bloque_max = bloques[i];
		}
	}
	return { "bloque": bloque_max, "duracion" : duracion_max};
}
/**
 * 
 * @param bloques array de bloques
 * @param presentacion una presentacion
 * Busca el mejor bloque para la presentacion, asegurandose que 1) entre en en tiempo del bloque
 * y priorizando las presentaciones muy buenas
 */
function buscarMejorBloque(bloques,presentacion) {
	   
	if (valoracion(presentacion) == MUY_BUENA) //las presentaciones muy buenas las ubico en algun bloque si o si
	{
		console.log('Es una presentacion MB. Le buscamos un bloque: ');
		bloque_min = buscarBloqueConMenosMuyBuenas(bloques);
		console.log(bloque_min);
		return bloque_min;
	} 
	else 
	{
		bloque_max = buscarBloqueMasLibre(bloques); //JSON {bloque,duracion}
		
		if ( bloque_max.duracion + duracion(presentacion) <= duracion_bloque(bloque_max.bloque) ) //si la presentacion entra en el bloque, joya
			return bloque_max;
		else
			return null; //no encontre un bloque "seguro"
	}
}

/**
 * 
 * @param tanda la tanda en la cual esta la presentacion
 * @param presentacion
 * genera una lista de todos los bloques candidatos para ubicar a la presentacion, considerando:
 * - que sea un bloque de presentaciones
 * - que el auditorio del bloque soporte a la presentacion
 * - el area de referencia de la presentacion
 * - el eje tematico de la presentacion
 */

function buscarBloquesCandidatos(tanda,presentacion) 
{
	bloques = tanda.getBloques();
	candidatos = new Array();
	for(var i=0; i< bloques.length; i++) {
		if (cumpleCondicionesPresentacion(bloques[i],presentacion)) //TODO
			candidatos.push(bloques[i]);
	}
	
}

function cumpleCondicionesPresentacion(bloque,presentacion) {
	if ((bloque.tienePresentaciones) &&
		()
}

/**
 * Recorre los dias de una tanda, y los auditoriosDia de cada dia, para armar una lista de todos los bloques de una tanda con su correspondiente 
 * auditorio
 * @param tanda
 * @returns array of {bloque,auditorio}
 */
function collectBloques(tanda) {
	
	if (tanda.collectedBloques != null && tanda.collectedBloques !== undefined) //armo esta estructura una sola vez
		return tanda.collectedBloques;
	
	result = new Array();
	for(var i=0;i<tanda.dias.length;i++) 
		for(var j=0;j<tanda.dias[i].auditoriosDia.length;j++)
			for(var k=0;k<tanda.dias[i].auditoriosDia[j].bloques.length;k++) {
				result.push({
							 "auditorio" : tanda.dias[i].auditoriosDia[j].auditorio, 
							 "bloque": tanda.dias[i].auditoriosDia[j].bloques[k] 
							});
			}
	
	tanda.collectedBloques = result;
	return tanda.collectedBloques;
}