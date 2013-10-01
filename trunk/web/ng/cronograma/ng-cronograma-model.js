

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
		var auditorioDia = this.auditoriosDias[bloque.auditorioDia];
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

	Tanda.prototype.initialize = function() {
		this.auditorios = new Array();
		this.bloques = new Array();
		this.auditoriosDia = new Array();
		
		for(var i=0;i<this.dias.length;i++) { 
			for(var j=0;j<this.dias[i].auditoriosDia.length;j++) {
				this.auditoriosDia[this.dias[i].auditoriosDia[j].id] = this.dias[i].auditoriosDia[j];
				var auditorio = this.dias[i].auditoriosDia[j].auditorio; 
				
				if ((this.auditorios[auditorio.id] === undefined) || (this.auditorios[auditorio.id] === null))
					this.auditorios[auditorio.id] = auditorio;
				
				var bloques = this.dias[i].auditoriosDia[j].bloques;
				
				for(var k=0;k<bloques.length;k++) {
					var bloque = bloques[k];
					if ((this.bloques[bloque.id] === undefined) || (this.bloques[bloque.id] === null))
						this.bloques[bloque.id] = bloque;
				} //endfor k
			} //endfor j
		} //endfor i	
	}
	
	return Tanda;
});



app.factory('Auditorio', function($resource){
	Auditorio = $resource('auditorio/:auditorioId', {auditorioId:'@id'}, {});
	
	Auditorio.prototype.soportaPresentacion = function(presentacion) {
		for(var i=0;i<this.producciones.length;i++)
			if ((presentacion.tipoPresentacion != '') && (presentacion.tipoPresentacion.id==this.producciones[i].id))
				return true;
		
		return false;
	};
	
	return Auditorio;
});



app.factory('Bloque', function($resource){
	Bloque = $resource('bloque/:bloqueId', {bloqueId:'@id'}, {});
	
	Bloque.prototype.tieneEje = function(eje){
		for(var i=0;i<this.ejesTematicos.length;i++)
			if (eje.id == this.ejesTematicos[i].id)
				return true;
		return false;
	};
	Bloque.prototype.tieneArea = function(area){
		for(var i=0;i<this.areasReferencia.length;i++)
			if (area.id == this.areasReferencia[i].id)
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


	return Bloque;
});


app.factory('Presentacion', function($resource){
	Presentacion = $resource('presentacion/:presentacionId', {presentacionId:'@id'}, {
		mover: {method:'POST', params:{origen:'', destino:''}, isArray:false}
	});
	
	Presentacion.prototype.duracion = function() {
		return this.tipoPresentacion.duracion;
	};
	
	return Presentacion;
});
