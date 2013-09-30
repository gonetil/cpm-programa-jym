
var Tanda = $resource('tanda/:tandaId', {}, {});

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
	this.auditoriosDias = new Array();
	
	for(var i=0;i<this.dias.length;i++) { 
		for(var j=0;j<this.dias[i].auditoriosDia.length;j++) {
			this.auditoriosDias[this.dias[i].auditoriosDia[j].id] = this.dias[i].auditoriosDia[j];
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

