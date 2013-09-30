console.log("creamos nuestro modulo llamado cronograma_de_tanda");
//http://docs.angularjs.org/api/angular.Module
var app = angular.module("cronograma", ['ngDragDrop', 'ngResource', 'ngynSelectKey']);

app.factory('Bloque', function($resource){
	return $resource('bloque/:bloqueId', {bloqueId:'@id'}, {});
});

app.factory('AuditorioDia', function($resource){//
	return $resource('auditorioDia/:auditorioDiaId', {auditorioDiaId:'@id'}, {});
});

app.factory('Dia', function($resource){
	Dia= $resource('dia/:diaId', {tandaId:'@tanda',diaId:'@id',numeroDia:'@numero'}, {});
	return Dia;
});

app.factory('Tanda', function($resource){
	Tanda = $resource('tanda/:tandaId', {}, {
		//query: {method:'GET', params:{bloqueId:'phones'}, isArray:true}
	});
	return Tanda;
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
	return $resource('auditorio/:auditorioId', {auditorioId:'@id'}, {});
});
app.factory('Presentacion', function($resource){
	Presentacion = $resource('presentacion/:presentacionId', {presentacionId:'@id'}, {
		mover: {method:'POST', params:{origen:'', destino:''}, isArray:false}
	});
	return Presentacion;
});


app.factory('Logger', function(){
	Logger= {buffer:[], level:'debug', bufferSize:2};
	Logger.push=function(level, message){
		if (message.message)
			message=message.message;
		var now=new Date(); 
		now=now.getHours()+":"+now.getMinutes()+":"+now.getSeconds();
		this.buffer.unshift({level:level, message:message, time:now});
		this.buffer.splice(this.bufferSize,1);
		//alert(level+": "+message);
    };
	Logger.log=function(message){
		Logger.push('info', message);
    };

	Logger.error=function(message){
		Logger.push('error', message);
	};
	Logger.success=function(message){
		Logger.push('success', message);
	};
    Logger.debug=function(message){
    	if (this.level != 'debug')
			return; 
    	Logger.push('info', message);
    };
	return Logger;
});

app.run(function ($rootScope, Logger) {
	//declaro las variables y funciones globales
    $rootScope.asset = asset;
    $rootScope.Logger = Logger;
});

//hacemos el ruteo de nuestra aplicación
app.config(['$routeProvider', function($routeProvider, $rootScope) {
	$routeProvider.
		when('/', {templateUrl: asset('tanda-list.html'), controller: TandaListCtrl}).
		when('/tanda/:tandaId', {templateUrl: asset('tanda-show.html'), controller: TandaShowCtrl}).
		when('/dia/new/tanda/:tandaId', {template:'no-template', controller: DiaNewCtrl}).
		when('/dia/:diaId/remove', {templateUrl: asset('item-remove.html'), controller: DiaRemoveCtrl}).
		when('/auditorioDia/new/dia/:diaId', {templateUrl:asset('auditorioDia-form.html'), controller: AuditorioDiaNewCtrl}).
		when('/auditorioDia/:auditorioDiaId/edit', {templateUrl:asset('auditorioDia-form.html'), controller: AuditorioDiaEditCtrl}).
		when('/auditorioDia/:auditorioDiaId/remove', {templateUrl: asset('item-remove.html'), controller: AuditorioDiaRemoveCtrl}).
		when('/bloque/new/auditorioDia/:auditorioDiaId', {templateUrl: asset('bloque-form.html'), controller: BloqueNewCtrl}).
		when('/bloque/:bloqueId/edit', {templateUrl: asset('bloque-form.html'), controller: BloqueEditCtrl}).
		when('/bloque/:bloqueId/remove', {templateUrl: asset('item-remove.html'), controller: BloqueRemoveCtrl}).
		otherwise({template: function (){
	    	console.log('Se realiza una redirección a / desde '+location.hash);
	    	return 'Se realiza una redirección a / porque se recibio un path desconocido '+location.hash;
	    }});
	}]
);

function asset(filename){
	//Esta funcion se pone suelta, fuera del $rootScope porque desde la inicialización del routeProvider no se puede 
	// ver $rootScope (dado que aún no fue inicializado)
	var ng_path=BASE_PATH+"/ng/cronograma/";
	return ng_path + filename;
};
