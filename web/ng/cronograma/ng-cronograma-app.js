console.log("creamos nuestro modulo llamado cronograma_de_tanda");
//http://docs.angularjs.org/api/angular.Module
var app = angular.module("cronograma", ['ngDragDrop', 'ngResource','$strap.directives']);


app.factory('Logger', function(){
	Logger= {buffer:[], level:'info', bufferSize:1};
	Logger.push=function(level, message){
		if (message.message)
			message=message.message;
		var now=new Date(); 
		now=now.getHours()+":"+now.getMinutes()+":"+now.getSeconds();
		this.buffer.unshift({level:level, message:message, time:now});
		this.buffer.splice(this.bufferSize,1);
    };
    Logger.info=function(message){
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

app.run(function ($rootScope, Logger, Tanda) {
	//declaro las variables y funciones globales
    $rootScope.asset = asset;
    $rootScope.Logger = Logger;
    
    $rootScope.getTandaActual = function(){
    	return $rootScope.tanda;
    }
    $rootScope.getTanda = function(tandaId){
    	//Gestiona la currentTanda
    	if ($rootScope.tanda && $rootScope.tanda.id != tandaId){
    		console.log("La tanda actual ("+$rootScope.tanda.id+") es diferente a la pedida ("+tandaId + ") ==> Hago $rootScope.tanda = null");
    		$rootScope.tanda = null;
    	}
        if (!$rootScope.tanda && tandaId){
    		Logger.debug("La tanda actual estaba vacia, levanto la tanda "+tandaId);
    		$rootScope.tanda = Tanda.get(
    				{tandaId: tandaId},
    				function(tanda){
		    				tanda.initialize();
		    				console.log("Se cargo e inicializó correctamente la tanda "+tandaId);
		    		}, 
		    		function(error){Logger.error("Se produjo un error al recuperar la tanda "+ tandaId); Logger.error(error.data);}
    		);
    	}
    	return $rootScope.tanda;
    };
	
    
});

//hacemos el ruteo de nuestra aplicación
app.config(['$routeProvider', function($routeProvider, $rootScope) {
	$routeProvider.
		when('/', {templateUrl: asset('tanda-list.html'), controller: TandaListCtrl}).
		when('/tanda/:tandaId', {templateUrl: asset('tanda-show.html'), controller: TandaShowCtrl}).
		when('/tanda/:tandaId/distribuir', {templateUrl: asset('tanda-distribuir-presentaciones-form.html'), controller: TandaDistribuirPresentacionesCtrl}).
		when('/tanda/:tandaId/reinicializar', {templateUrl: asset('item-remove.html'), controller: TandaResetearPresentacionesCtrl}).
		when('/dia/new/tanda/:tandaId', {template:'no-template', controller: DiaNewCtrl}).
		when('/dia/:diaId/remove', {templateUrl: asset('item-remove.html'), controller: DiaRemoveCtrl}).
		when('/dia/:diaId/duplicar', {template:'no-template', controller: DiaDuplicarCtrl}).
		when('/auditorioDia/new/dia/:diaId', {templateUrl:asset('auditorioDia-form.html'), controller: AuditorioDiaNewCtrl}).
		when('/auditorioDia/:auditorioDiaId/edit', {templateUrl:asset('auditorioDia-form.html'), controller: AuditorioDiaEditCtrl}).
		when('/auditorioDia/:auditorioDiaId/remove', {templateUrl: asset('item-remove.html'), controller: AuditorioDiaRemoveCtrl}).
		when('/bloque/new/auditorioDia/:auditorioDiaId', {templateUrl: asset('bloque-form.html'), controller: BloqueNewCtrl}).
		when('/bloque/:bloqueId/edit', {templateUrl: asset('bloque-form.html'), controller: BloqueEditCtrl}).
		when('/bloque/:bloqueId/remove', {templateUrl: asset('item-remove.html'), controller: BloqueRemoveCtrl}).
		when('/presentacion/new/tanda/:tandaId', {templateUrl: asset('presentacion-form.html'), controller: PresentacionNewCtrl}).
		when('/presentacion/:presentacionId/edit', {templateUrl: asset('presentacion-form.html'), controller: PresentacionEditCtrl}).
		when('/presentacion/:presentacionId/remove', {templateUrl: asset('item-remove.html'), controller: PresentacionRemoveCtrl}).
		
		otherwise({template: function (){
	    	console.log('Se realiza una redirección a / desde '+location.hash);
	    	return 'Se realiza una redirección a / porque se recibio un path desconocido '+location.hash;
	    }});
	}]
);


function asset(filename){
	//Esta funcion se pone suelta, fuera del $rootScope porque desde la inicialización del routeProvider no se puede 
	// ver $rootScope (dado que aún no fue inicializado)
	var ng_path=BASE_PATH+"/ng/cronograma/templates/";
	var cacheKey="";//new Date().toLocaleString();
	return ng_path + filename+ "?" + cacheKey;
	
};
app.filter('toArray', function () {
    'use strict';

    return function (obj) {
        if (!(obj instanceof Object)) {
            return obj;
        }

        return Object.keys(obj).map(function (key) {
            return Object.defineProperty(obj[key], '$key', {__proto__: null, value: key});
        });
    }
});
