function TandaShowCtrl($scope, $routeParams, Tanda) {

	//$scope.tanda=get_tanda_demo();
	$scope.tanda = Tanda.get({tandaId: $routeParams.tandaId});
	
	$scope.css_cronograma_height=screen.height-150;
    $scope.css_dia_height=function(){
        var alto_dia=$scope.css_cronograma_height / $scope.tanda.dias.length;
        return "min-height:"+alto_dia+"px";
    }; 
    $scope.css_dias_height=function(){
        return "height:"+($scope.css_cronograma_height)+"px";
    };

	$scope.css_bloque_height = function(bloque) {
		return "min-height:" + bloque.duracion + "px";
	};

	// Determina si un bloque es de presentaciones o simple
	$scope.bloque_tiene_presentaciones = function(bloque) {
		return bloque.presentaciones !== false;
	};
  
    $scope.drop_options={
    };
    $scope.bloque_drop_options={
        accept:'.badge-presentacion',
        tolerance:'touch',
        hoverClass:'badge-presentacion-hover'
    };
    $scope.jquoui_drag_options= {
        revert:'invalid',
        cursor: 'move', 
        cursorAt:{left:50, top:10},
        helper:'clone'
    };
}

function TandaListCtrl($scope, Tanda) {
	$scope.tandas = Tanda.query();
}

//DIA
function DiaNewCtrl($scope, $routeParams, $location, Dia, Logger){
	$scope.dia = new Dia({tanda:$routeParams.tandaId});
	$scope.dia.$save(function(){Logger.log("Se creó satisfactoriamente el dia numero "+$scope.dia.numero)},Logger.error);
	$location.url("/tanda/"+$routeParams.tandaId);
}
function DiaRemoveCtrl($scope, $routeParams, $location, Dia, Logger){
	$scope.confirmMessage = "Esta seguro que desea eliminar el día "+$routeParams.diaId;
	$scope.dia = new Dia({id:$routeParams.diaId});
	$scope.confirmOk= function(){
		$scope.dia.$remove(Logger.log, Logger.error);
		history.back();
	}
}
//AUDITORIO_DIA
function AuditorioDiaNewCtrl($scope, $routeParams, $location, AuditorioDia, Logger){
	$scope.auditorioDia = new AuditorioDia({tanda:$routeParams.tandaId,dia:$routeParams.diaId});
	$scope.newAuditorioDia=function(){
		$scope.auditorioDia.$save(function(){Logger.log("Se creó satisfactoriamente el auditorio del dia ")},Logger.error);
		$location.url("/tanda/"+$routeParams.tandaId);
	}
}
function AuditorioDiaRemoveCtrl($scope, $routeParams, $location, AuditorioDia, Logger){
	$scope.confirmMessage = "Esta seguro que desea eliminar el auditorio día?";
	$scope.auditorioDia = new AuditorioDia({tanda:$routeParams.tandaId, dia:$routeParams.diaId, id:$routeParams.auditorioDiaId});
	$scope.confirmOk= function(){
		$scope.auditorioDia.$remove(Logger.log, Logger.error);
		$location.url("/tanda/"+$routeParams.tandaId);
	}
}

//BLOQUE
function BloqueNewCtrl($scope, $routeParams, $location, Bloque, Logger){
	$scope.bloque = new Bloque({auditorioDia:$routeParams.auditorioDiaId, duracion:15, posicion:-1});
	$scope.textButton = "Crear";
	$scope.newBloque=function(){
		$scope.bloque.$save(function(){Logger.log("Se creó satisfactoriamente el bloque")},Logger.error);
		history.back();
	}
}

function BloqueEditCtrl($scope, $routeParams, $location, Bloque, Logger){
	$scope.textButton = "Editar";
	$scope.bloque = Bloque.get({bloqueId:$routeParams.bloqueId});
	$scope.editBloque = function(){
		$scope.bloque.$save(Logger.log,Logger.error);
		history.back();
    }
}

function BloqueRemoveCtrl($scope, $routeParams, $location, Bloque, Logger){
	$scope.confirmMessage = "Esta seguro que desea eliminar el Bloque?";
	$scope.bloque = new Bloque({id:$routeParams.bloqueId});
	$scope.confirmOk= function(){
		$scope.bloque.$remove(function(){Logger.log("Se eliminó satisfactoriamente el bloque")}, Logger.error);
		history.back();
	}
}

/*
/*
 $scope.resetear_cronograma=function(){
    console.log("Se reinicia el cronograma");
    $scope.tanda.presentaciones_libres=$scope.tanda.presentaciones;
    $scope.tanda.dias.forEach(function(dia) {
      dia.auditorios_dia.forEach(function(auditorio_dia) {
        auditorio_dia.bloques.forEach(function(bloque) {
          if (bloque.presentaciones !== false)
            bloque.presentaciones = [];
        });
      });
    });
  };
*/