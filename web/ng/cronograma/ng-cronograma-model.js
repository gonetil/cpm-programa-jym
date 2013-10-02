

app.factory('AuditorioDia', function($resource){//
	return $resource('auditorioDia/:auditorioDiaId', {auditorioDiaId:'@id'}, {});
});

app.factory('Dia', function($resource){
	Dia= $resource('dia/:diaId', {tandaId:'@tanda',diaId:'@id',numeroDia:'@numero'}, {});
	return Dia;
});

app.factory('TipoPresentacion', function($resource){
	return $resource('tipoPresentacion/:tpId', {tpId:'@id'}, {});
});
app.factory('AreaReferencia', function($resource){
	return $resource('areaReferencia/:areaId', {areaId:'@id'}, {});
});
app.factory('EjeTematico', function($resource){
	return $resource('ejeTematico/:ejeId', {ejeId:'@id'}, {});
});


app.factory('Auditorio', function($resource){
	Auditorio = $resource('auditorio/:auditorioId', {auditorioId:'@id'}, {});
	
	return Auditorio;
});



app.factory('Bloque', function($resource){
	Bloque = $resource('bloque/:bloqueId', {bloqueId:'@id'}, {});
	
	Bloque.prototype.tieneEje = function(eje){
		for(var i=0;i<this.ejesTematicos.length;i++)
			if (eje == this.ejesTematicos[i].id) {
				return true;
			}
		return false;
	};
	Bloque.prototype.tieneArea = function(area){
		for(var i=0;i<this.areasReferencia.length;i++)
			if (area == this.areasReferencia[i].id)
				return true;
		return false;
	};
	
	Bloque.prototype.duracionEstimada = function() { //suma las duraciones de todas las presentaciones
			count=0;
			for(var i=0;i<this.presentaciones.length;i++) {
				count += this.presentaciones[i].duracion(); //funcion que retorna la duracion del tipo de produccion de la presentacion 
			}
			return count;
	};
	
	Bloque.prototype.contarPresentacionesConValoracion = function(valoracion) {
		var count=0;
		for(var i=0;i<this.presentaciones.length;i++) {
			if (this.presentaciones[i].valoracion == valoracion) 
				count++;
		}
		return count;
	};

	Bloque.prototype.quitarPresentacion = function(presentacion) {
		for(var i=0;i<this.presentaciones.length;i++) {
			if (this.presentaciones[i].id == presentacion.id) {
				this.presentaciones.splice(i,1);
				presentacion.liberarBloque();
				return i;
			}	
		}
		return -1;
	};

	/**
	 * Agrega la presentacion al final de la lista de presentaciones del bloque, o a la posicion position si se especifica (desplaza el resto)
	 * @param presentacion
	 * optional @param position
	 */
	Bloque.prototype.agregarPresentacion = function(presentacion,position) {
		if (arguments.length == 1)
			this.presentaciones.push(presentacion);
		else { 
			if ((position > this.presentaciones.length + 1) || (position < 0))
				position = this.presentaciones.length;
			this.presentaciones.splice(position,0,presentacion);
		}
	};
	return Bloque;
});

app.factory('Tanda', function($resource){
	
	//DEFAULT DAO
	Tanda = $resource('tanda/:tandaId', {}, {
		//query: {method:'GET', params:{bloqueId:'phones'}, isArray:true}
	});

	
	//EXTRA FUNCTIONS
	
	Tanda.prototype.checkInit = function() {
		if ( (this.auditorios === undefined) || (this.auditorios === null) ||
			 (this.bloques === undefined) || (this.bloques === null) )
			
			this.initialize();
	};

	Tanda.prototype.getAuditorio = function(id) {
		this.checkInit();
		return this.auditorios[id];
	};

	Tanda.prototype.getBloque = function(id) {
		this.checkInit();
		return this.bloques[id];
	};

	/*Tanda.prototype.getBloqueForPresentacion = function(presentacion) {
		if ((presentacion.bloque !== undefined) && (presentacion.bloque !== null) && ((presentacion.bloque !== '')))
			return this.getBloque(presentacion.bloque);
		else
			return null;
	};*/

	Tanda.prototype.getAuditorioForBloque = function(bloque) {
		this.checkInit();
		var auditorioDia = this.auditoriosDia[bloque.auditorioDia.id];
		return this.auditorios[auditorioDia.auditorio.id];
	};

	Tanda.prototype.getBloques = function() {
		this.checkInit();
		return this.bloques;
	};

	Tanda.prototype.getAuditorios = function() {
		this.checkInit();
		return this.auditorios;
	};

	Tanda.prototype.initialize = function(Dia,AuditorioDia,Auditorio,Bloque) {
		
		this.auditorios = {};
		this.bloques = {};
		this.auditoriosDia = {};
		this.dias2 = {};
		
		for(var i=0;i<this.dias.length;i++) {
			dia = new Dia(this.dias[i]);
			this.dias2[dia.id] = dia;
			
			for(var j=0;j<this.dias[i].auditoriosDia.length;j++) {
				
				var auditorioDia = new AuditorioDia(this.dias[i].auditoriosDia[j]);
				dia.auditoriosDia[auditorioDia.id] = auditorioDia;
				auditorioDia.dia = dia;
				
				var auditorio = null;
				
				if ((this.auditorios[this.dias[i].auditoriosDia[j].auditorio.id] === undefined) || (this.auditorios[this.dias[i].auditoriosDia[j].auditorio.id] === null)) { 
					auditorio = new Auditorio(this.dias[i].auditoriosDia[j].auditorio);	
					auditorio.auditorioDia = auditorioDia;
					this.auditorios[auditorio.id] = auditorio;
				} else {
					auditorio = this.auditorios[this.dias[i].auditoriosDia[j].auditorio.id];
				}
				
				auditorioDia.auditorio = auditorio;
				this.auditoriosDia[auditorioDia.id] = auditorioDia;
				
				var bloques = this.dias[i].auditoriosDia[j].bloques;
				
				for(var k=0;k<bloques.length;k++) {
					var bloque = new Bloque(bloques[k]);
					bloque.auditorioDia = auditorioDia;
					auditorioDia.bloques[bloque.id] = bloque;
					if ((this.bloques[bloque.id] === undefined) || (this.bloques[bloque.id] === null))
						this.bloques[bloque.id] = bloque;
				} //endfor k
			} //endfor j
		} //endfor i
		
		console.log(this);
	};
	
	/**
	 * mueve la presentacion de donde este actualmente al bloque bloque_destino.
	 * Si no se especifica un bloque destino, la presentacion se quita del bloque en el que se encuentre y se agrega a la lista de presentaciones libres
	 **/
	Tanda.prototype.moverPresentacion = function(presentacion,bloque_destino) {

		bloque_actual = presentacion.bloque;
		
		if ((bloque_actual != '') && (bloque_actual !== undefined)) { //la presentacion estaba asignada a un bloque
			pos = bloque_actual.quitarPresentacion(presentacion);
			if (pos == -1) 
				console.error("La presentacion " + presentacion.id + " debia quitarse del bloque " + bloque_actual.id + " pero no estaba alli");		

		} else { //la presentacion estaba entre las libres 
			for(var i=0;i<this.presentaciones_libres.length;i++) //saco la presentacion de la lista de presentaciones libres
				if (this.presentaciones_libres[i].id == presentacion.id) {
					this.presentaciones_libres.splice(i,1);
					break;
				}
		}

		if (arguments.length == 2) //no hay bloque_destino
			bloque_destino.agregarPresentacion(presentacion);
		else
			this.presentaciones_libres.push(presentacion);

	};
	
	/**
	 * 
	 * @param presentacion
	 * genera una lista de todos los bloques candidatos para ubicar a la presentacion, considerando:
	 * - que sea un bloque de presentaciones
	 * - que el auditorio del bloque soporte a la presentacion
	 * - el area de referencia de la presentacion
	 * - el eje tematico de la presentacion
	 */

	Tanda.prototype.buscarBloquesCandidatos = function(presentacion) 
	{
		
		candidatos = new Array();
		bloques = this.getBloques();
		for(index in bloques ){
			bloque = bloques[index];
			
			if (cumpleCondicionesPresentacion(this,bloque,presentacion))
				candidatos.push(bloque);
		}
	
		return candidatos;
	}
	
	
	/**
	 * distribuye las presentaciones de la tanda de manera automatica
	 * @param tanda
	 * @param forzar_distribucion
	 */
	Tanda.prototype.distribuirPresentaciones = function(forzar_distribucion) {
		
		
		if (arguments.length == 0)
			forzar_distribucion = false;
		
		
		presentaciones = shuffleArray(this.presentaciones_libres);
		count = 20; //presentaciones.length;
		for(var i=0;i<count;i++) { //FIXME usar presentaciones.forEach
			presentacion = presentaciones[i];
			bloques_candidatos = this.buscarBloquesCandidatos(presentacion);
			console.log("Busco el mejor entre los siguientes candidatos"); console.log(bloques_candidatos);
			mejor_bloque = (bloques_candidatos.length > 0) ? buscarMejorBloque(bloques_candidatos, presentacion) : null;
			console.log("Este resulto el mejor bloque"); console.log(mejor_bloque);
			if ((mejor_bloque == null) && (forzar_distribucion)) { //no encontre un buen bloque pero tengo que ubicar la presentacion en alguno si o si 
				if (bloques_candidatos.length == 0) { // no tengo posibles bloques candidatos, manoteo cualquiera que tenga presentaciones
					bloques_candidatos = this.getBloques();
					bloques_candidatos.forEach(function(bloque,index,array){
						if (!bloque.tienePresentaciones)
							bloques_candidatos.splice(index,1);
					});
				}
			
				if (bloques_candidatos.length > 0)
					mejor_bloque = bloques_candidatos[Math.floor(Math.random() * bloques_candidatos.length)]; //agarro un bloque candidato cualquiera
			}	
			
			if (mejor_bloque != null) { //TODO agregar presentacion al mejor_bloque y sacarlo de la lista de presentaciones libres de la tanda
				console.log("Muevo la presentacion... "); console.log(presentacion);
				console.log(" ... al bloque ..."); console.log(mejor_bloque);
				this.moverPresentacion(presentacion,mejor_bloque);
			}
				
		} //for i presentaciones
		
		console.log("La tanda quedo asi");
		console.log(this);
	};
	return Tanda;
});






app.factory('Presentacion', function($resource){
	Presentacion = $resource('presentacion/:presentacionId', {presentacionId:'@id'}, {
		mover: {method:'POST', params:{origen:'', destino:''}, isArray:false}
	});
	
	Presentacion.prototype.duracion = function() {
		return this.tipoPresentacion.duracion;
	};
	
	Presentacion.prototype.liberarBloque = function() {
		this.bloque = '';
	};
	return Presentacion;
});
