

app.factory('AuditorioDia', function($resource){//
	return $resource('auditorioDia/:auditorioDiaId', {auditorioDiaId:'@id'}, {});
});

app.factory('Dia', function($resource){
	Dia= $resource('dia/:diaId', {diaId:'@id'}, {
		  duplicar: {method:'POST', params:{},url: 'dia/:diaId/duplicar'}
	});
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
app.factory('Produccion', function($resource){
	return $resource('produccion/:produccionId', {produccionId:'@id'}, {});
});

app.factory('Provincia', function($resource){
	return $resource('provincia', {}, {});
});



app.factory('Auditorio', function($resource){
	Auditorio = $resource('auditorio/:auditorioId', {auditorioId:'@id'}, {});
	
	return Auditorio;
});



app.factory('Bloque', function($resource){
	Bloque = $resource('bloque/:bloqueId', {bloqueId:'@id'}, {
//		  mover: {method:'POST', params:{posicion:0}, url: 'bloque/:bloqueId/mover'}
	});
	
	Bloque.prototype.tieneEje = function(eje){
		for(var i=0;i<this.ejesTematicos.length;i++)
			if (eje.id == this.ejesTematicos[i].id) {
				return true;
			}
		return false;
	};
	Bloque.prototype.tieneArea = function(area){
		for(var i=0;i<this.areasReferencia.length;i++)
			if (area.id == this.areasReferencia[i].id)
				return true;
		return false;
	};
	
	Bloque.prototype.duracionEstimada = function() { //suma las duraciones de todas las presentaciones
			var count=0;
			
			for(var i=0;i<this.presentaciones.length;i++) {
				count +=  parseInt(this.presentaciones[i].tipoPresentacion.duracion); //funcion que retorna la duracion del tipo de produccion de la presentacion 
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
				presentacion.bloque = '';
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

app.factory('Tanda', function($resource, Dia,AuditorioDia,Auditorio,Bloque, Presentacion){
	
	//DEFAULT DAO
	Tanda = $resource('tanda/:tandaId', {tandaId: '@id'}, {
		savePresentaciones: {method:'POST', params:{}, url:'tanda/:tandaId/savePresentaciones'},
		resetPresentaciones: {method:'POST', params:{}, url:'tanda/:tandaId/resetPresentaciones'},
		get: {method:'GET', params:{}, cache:false}
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

	Tanda.prototype.getAuditorioForBloque = function(bloque) {
		this.checkInit();
		var auditorioDia = this.auditoriosDia[bloque.auditorioDia.id];
		return this.auditorios[auditorioDia.auditorio.id];
	};

	Tanda.prototype.getBloques = function() {
		this.checkInit();
		return this.bloques;
	};
	
	Tanda.prototype.getBloquesPresentacionesArray = function() {
		result = new Array();
		for(index in this.bloquesPresentaciones) {
			result.push(this.bloques[index]);
		}
		return result;
	};
	
	Tanda.prototype.getAuditorios = function() {
		this.checkInit();
		return this.auditorios;
	};

	Tanda.prototype.initialize = function() {
		
		this.auditorios = {};
		this.bloques = {};
		this.bloquesPresentaciones = {};
		this.auditoriosDia = {};
		this.dias2 = {};  //dias indexados
		this.presentaciones2 = {}; //presentaciones indexadas
		
		for(index in this.presentaciones_libres) {
			var p = new Presentacion(this.presentaciones_libres[index]);
			
			p.bloque = ''; //por las dudas :D
			this.presentaciones2[p.id] = p;
		//console.log("piso en ",index," con ",p);
			this.presentaciones_libres[index] = p;
		}
		
		for(var i=0;i<this.dias.length;i++) {
			dia = new Dia(this.dias[i]);
			dia.auditoriosDia = {};
		
			
			
			for(var j=0;j<this.dias[i].auditoriosDia.length;j++) {
				
				var auditorioDia = new AuditorioDia(this.dias[i].auditoriosDia[j]);
				auditorioDia.bloques = {};
				dia.auditoriosDia[auditorioDia.id] = auditorioDia;
				//auditorioDia.dia = dia;
				
				var auditorio = null;
				
				if (!this.auditorios[this.dias[i].auditoriosDia[j].auditorio.id]) { 
					auditorio = new Auditorio(this.dias[i].auditoriosDia[j].auditorio);	
					//auditorio.auditorioDia = auditorioDia;
					this.auditorios[auditorio.id] = auditorio;
				} else {
					auditorio = this.auditorios[this.dias[i].auditoriosDia[j].auditorio.id];
				}

				
				auditorioDia.auditorio = auditorio;
				this.auditoriosDia[auditorioDia.id] = auditorioDia;
				
				var bloques = this.dias[i].auditoriosDia[j].bloques;
				auditorioDia.bloques= new Array;
				
				for(var k=0;k<bloques.length;k++) {
					var bloque = new Bloque(bloques[k]);
					bloque.auditorioDia = auditorioDia;
					
					if (!this.bloques[bloque.id]) 
						this.bloques[bloque.id] = bloque;

					if (bloque.tienePresentaciones == 1) { //indexo las presentaciones del bloque
						this.bloquesPresentaciones[bloque.id] = bloque;
						for(var m=0;m<bloque.presentaciones.length;m++) {
							var p = new Presentacion(bloque.presentaciones[m]);
							p.bloque = bloque;
							bloque.presentaciones[m] = p;
							this.presentaciones2[p.id] = p;
						}
					}
					auditorioDia.bloques[k] = bloque;
					//GUARDA necesito arrays, no objetos
					//auditorioDia.bloques[bloque.id] = bloque;
					
				} //endfor k
			} //endfor j
			this.dias2[dia.id] = dia;
			this.dias[i] = dia;
		} //endfor i
		
		console.log("Tanda indexada correctamente");
	};
	
	/**
	 * mueve la presentacion de donde este actualmente al bloque bloque_destino.
	 * Si no se especifica un bloque destino, la presentacion se quita del bloque en el que se encuentre y se agrega a la lista de presentaciones libres
	 **/
	Tanda.prototype.moverPresentacion = function(presentacion,bloque_destino) {

		presentacion = this.presentaciones2[presentacion.id];
		bloque_destino = this.bloques[bloque_destino.id];
		
		
		//primero me fijo si la presentacion ya tenia un bloque
		bloque_actual = (presentacion.bloque != '') ? this.bloques[presentacion.bloque.id] : '';
			
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

		if (arguments.length == 2) { // hay bloque_destino
			bloque_destino.agregarPresentacion(presentacion);
			presentacion.setBloque(bloque_destino);
			
		}
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

	Tanda.prototype.buscarBloquesCandidatos = function(presentacion,config) 
	{
		
		var candidatos = new Array();
		for(index in this.bloquesPresentaciones ){
			
			bloque = this.bloquesPresentaciones[index];
			
			if (cumpleCondicionesPresentacion(this,bloque,presentacion,config))
				candidatos.push(bloque);

		}
		console.log("los bloques candidatos para la presentacion ",presentacion," son ",candidatos);
		return candidatos;
	};
	

	/**
	 * distribuye las presentaciones de la tanda de manera automatica
	 * @param tanda
	 * @param modo (forced|best|strict)
	 * @returns presentacionesMovidas la cantida de presentaciones que el algoritmo pudo distribuir
	 */
	Tanda.prototype.distribuirPresentaciones = function(modo) {
		console.log("Comienzo la distribucion de "+this.presentaciones_libres.length+" presentaciones libres en modo "+ modo);
		
		var config = {};
		if (modo == 'forced') { 
			config['level'] = ALGORITMO_FORZADO; 
			forzar_distribucion = true;
		}
		else if (modo == 'intermediate') { 
			forzar_distribucion = false;
			config['level'] = ALGORITMO_INTERMEDIO; 
		} else
			config['level'] = ALGORITMO_ESTRICTO;
		
		var presentacionesMovidas=0;
		var bloques = this.getBloquesPresentacionesArray();
		
		var libres = shuffleArray(this.presentaciones_libres).slice();  //paso 1: barajamos las cartas
		
		var cantPresentaciones = libres.length;
		var i = -1;
		while (i < cantPresentaciones-1) { 
			i++;
			var presentacion = this.presentaciones2[libres[i].id]; //trabajo con la presentacion ya indexada			
			var mejor_bloque = null;

			var bloques_candidatos = this.buscarBloquesCandidatos(presentacion,config);
			
			if (bloques_candidatos.length > 0) {
				mejor_bloque =  buscarMejorBloque(bloques_candidatos, presentacion,config);
				
				//if (!mejor_bloque) console.log("De la lista de candidatos ",bloques_candidatos," no encontre un mejor_bloque para ",presentacion);
			}
			
			if ((!mejor_bloque) && (config.level >= ALGORITMO_INTERMEDIO)) //no encontre un buen bloque pero tengo que ubicar la presentacion en alguno si o si 
				mejor_bloque = bloqueConMenosPresentaciones(bloques_candidatos,presentacion,bloques);
			
			if (mejor_bloque != null){
				this.moverPresentacion(presentacion,mejor_bloque);
				presentacionesMovidas++;
			}else
				console.log("NUNCA encontre un bloque para la presentacion ",presentacion);
				
		} //while i presentaciones
		console.log("Finaliza la distribucion de presentaciones. De las "+cantPresentaciones+" libres, se asignaron "+ presentacionesMovidas);
		
		//console.log("La tanda quedo asi",this);
		return presentacionesMovidas;
	};
	
	/**
	 * Retorna todas las presentaciones de la tanda, en un simpla array con id de presentacion y id de  bloque (si corresponde)
	 */
	Tanda.prototype.getPresentacionesConBloqueDTO = function() {
		var presentacionConBloqueDTO = new Array();
		for(i in this.presentaciones2 ){
			presentacionConBloqueDTO.push({
				presentacion:this.presentaciones2[i].id,
				bloque:(this.presentaciones2[i].bloque?this.presentaciones2[i].bloque.id:null)
			});
		}
		return presentacionConBloqueDTO;
	};
	return Tanda;
});






app.factory('Presentacion', function($resource){
	Presentacion = $resource('presentacion/:presentacionId', {presentacionId:'@id'}, {
		mover: {method:'POST', params:{origen:'', destino:''}, isArray:false}
	});
	
	Presentacion.prototype.setBloquePersistent = function(destino){
		
		if (!destino)
			destino='';
		
		this.bloque = destino;
    	this.$save(
    		function(entity){
    			//Logger.success("Se movió la presentación "+entity.id);
    			Logger.debug("Paso la presentación "+entity.id + (entity.bloque?" al bloque "+entity.bloque:" a presentaciones libres"));
    		}, 
    		function(error){Logger.error(error.data)}
    	);
	};
	
	Presentacion.prototype.setBloque = function(bloque) {
		this.bloque = bloque;
	};

	Presentacion.prototype.esCompatibleConBolque = function(bloque) {
		if (!this.ejeTematico || !this.areaReferencia) {
			return false;
		}
		return  ( bloque.tieneEje(this.ejeTematico) && bloque.tieneArea(this.areaReferencia) );
	};
	Presentacion.prototype.esCompatibleConAuditorio = function(auditorio) {
		if (!auditorio) {
			console.warn("Se recibe un auditorio null");
			return false;
		}
		for(var i=0;i<auditorio.producciones.length;i++) {	
			if ((this.tipoPresentacion != '') && (this.tipoPresentacion.id==auditorio.producciones[i].id))
				return true;
		}
		
		return false;
	};
	
	return Presentacion;
	
	
	
});
