function TandaShowCtrl($scope, $routeParams, Tanda, Logger, Dia, AuditorioDia, Auditorio, Bloque, Presentacion) {

	$scope.tanda = Tanda.get({tandaId: $routeParams.tandaId},function(tanda){
			tanda.initialize(Dia,AuditorioDia,Auditorio,Bloque, Presentacion);
	});

	$scope.presentacion_droppable={
			multiple:true,
			placeholder:false,
			onDrop:'presentacionDropped'
	};
    $scope.presentacion_droppable_options={
        accept:'.badge-presentacion',
        tolerance:'intersect',
        hoverClass:'badge-presentacion-hover'
    };
    $scope.presentacion_draggable_options= {
        revert:'invalid',
        cursor: 'move', 
        cursorAt:{left:50, top:10},
        helper:'clone'
    };
    

    //////////////////////////////////////////
    
    $scope.presentacionDropped=function(event, ui){
    	var presentacionId = ui.draggable.data("presentacion");
    	var origen = ui.draggable.data("bloque");
    	var destino = $(event.target).data("bloque");
    	//?"al bloque "+ origen.data("bloque"):" presentaciones libres"));
    	var presentacion = new Presentacion({id:presentacionId, bloque:(destino?destino:'')});
    	presentacion.$save(function(data){
    		Logger.success("Se movió la presentación "+presentacion.id);
    		Logger.debug("Saco la presentación "+presentacionId + " de "+ (origen?"al bloque "+origen:"a presentaciones libres") + " y la paso "+ (destino?"al bloque "+destino:"a presentaciones libres"));
    		Logger.debug(data);
   		}, Logger.error);
    }
    
    //asocia los popovers con los headers de los bloques
    $scope.initPopovers=function(){
	    $( ".caja-cronograma").on( "click", ".caja-bloque > .header-caja", function() {
	    	$( this ).popover({html:true,content:$(this).find('.popover').first().html()});
	    });
    };
}

function TandaListCtrl($scope, Tanda) {
	$scope.tandas = Tanda.query();
}


//DIA
function DiaNewCtrl($scope, $routeParams, $location, Dia, Logger){
	$scope.dia = new Dia({tanda:$routeParams.tandaId});
	$scope.dia.$save(function(){Logger.success("Se agregó el dia "+$scope.dia.numero)},Logger.error);
	$location.url("/tanda/"+$routeParams.tandaId);
}
function DiaRemoveCtrl($scope, $routeParams, $location, Dia, Logger){
	$scope.confirmMessage = "Esta seguro que desea eliminar el día "+$routeParams.diaId;
	$scope.dia = new Dia({id:$routeParams.diaId});
	$scope.confirmOk= function(){
		$scope.dia.$remove(Logger.success, Logger.error);
		history.back();
	}
}

	
//AUDITORIO_DIA
function AuditorioDiaNewCtrl($scope, $routeParams, $location, Auditorio, AuditorioDia, Logger){
	$scope.textButton = "Agregar";
	$scope.auditorios=Auditorio.query();
	$scope.auditorioDia = new AuditorioDia({dia:$routeParams.diaId});
	$scope.saveAuditorioDia=function(){
		//$scope.auditorioDia.auditorio=$scope.auditorioDia.auditorio.id;
		$scope.auditorioDia.$save(function(ad){Logger.success("Se agregó el auditorio '"+ad.auditorio.nombre+ "' al dia"); Logger.debug(ad);},Logger.error);
		history.back();
	}
}

function AuditorioDiaEditCtrl($scope, $routeParams, $location, Auditorio, AuditorioDia, Logger){
	$scope.textButton = "Modificar";
	$scope.auditorioDia = new AuditorioDia({id:$routeParams.auditorioDiaId});
	$scope.auditorios=Auditorio.query();
	$scope.saveAuditorioDia=function(){
		$scope.auditorioDia.$save(function(ad){Logger.success("Se guardó satistfactoriamente el auditorio del dia"); Logger.debug(ad);},Logger.error);
		history.back();
	}
}

function AuditorioDiaRemoveCtrl($scope, $routeParams, $location, AuditorioDia, Logger){
	$scope.confirmMessage = "Esta seguro que desea eliminar el auditorio día? Se perdera la información de sus bloques y presentaciones asociadas (las presentaciónes NO se eliminarán)";
	$scope.auditorioDia = new AuditorioDia({id:$routeParams.auditorioDiaId});
	$scope.confirmOk= function(){
		$scope.auditorioDia.$remove(Logger.success, Logger.error);
		history.back();
	}
}

//BLOQUE
function BloqueNewCtrl($scope, $routeParams, $location, Bloque,EjeTematico, AreaReferencia, Logger){
	$scope.textButton = "Crear";
	
	$scope.bloque = new Bloque({auditorioDia:$routeParams.auditorioDiaId,tienePresentaciones:true, duracion:15, posicion:-1});
	$scope.ejesTematicos=EjeTematico.query();
	$scope.areasReferencia=AreaReferencia.query();
	$scope.saveBloque=function(){
		$scope.bloque.$save(function(b){Logger.success("Nuevo bloque ("+b.id+") creado con éxito")},Logger.error);
		history.back();
	}
}

function BloqueEditCtrl($scope, $routeParams, $location, Bloque,EjeTematico, AreaReferencia, Logger){
	$scope.textButton = "Editar";
	$scope.bloque = Bloque.get({bloqueId:$routeParams.bloqueId});
	$scope.ejesTematicos=EjeTematico.query();
	$scope.areasReferencia=AreaReferencia.query();
	$scope.saveBloque = function(){
		$scope.bloque.$save(function(b){Logger.success("Bloque ("+b.id+") actualizado")},Logger.error);
		history.back();
    }
}

function BloqueRemoveCtrl($scope, $routeParams, $location, Bloque, Logger){
	$scope.confirmMessage = "Esta seguro que desea eliminar el Bloque? ";
	var bid = $routeParams.bloqueId;
	$scope.bloque = new Bloque({id:bid});
	$scope.confirmOk= function(){
		$scope.bloque.$remove(function(){Logger.success("Bloque ("+b.id+") eliminado")}, Logger.error);
		history.back();
	}
}
