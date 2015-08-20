#Diccionario de datos para generar la app

# Entidades #
## Periodo Actual ##
  * anioActual : Integer
  * proyectos : [Proyecto](DiccionarioDeDatos#Proyecto.md)+

## Proyecto ##
  * titulo : String
  * coordinador : Docente
  * colaboradores : [Persona](DiccionarioDeDatos#Persona.md)+
  * escuela : [Escuela](DiccionarioDeDatos#Escuela.md)
  * esPrimerVezDocente : boolean
  * esPrimeraVezEscuela : boolean
  * esPrimeraVezAlumnos : boolean
  * anioAlumnos : Integer(1..6)
  * temaPrincipal : [Tema](DiccionarioDeDatos#Tema.md)
  * produccionFinal : [Produccion](DiccionarioDeDatos#Produccion.md)


## Persona ##
  * nombre : String
  * apellido : String
  * email : String
  * anulado : boolean


## Docente ##
  * parent : [Persona](DiccionarioDeDatos#Persona.md)
  * dni : String  ---> tipoyNumero
  * telefonoFijo : String
  * telefonoCelular : String ---> opcional
  * domicilio : String
  * codigoPostal : String
  * localidad : [Localidad](DiccionarioDeDatos#Localidad.md)
  * distrito : [Distrito](DiccionarioDeDatos#Distrito.md)
  * regionEducativa : [Region Educativa](DiccionarioDeDatos#Region_Educativa.md)
  * correos : [Correo](DiccionarioDeDatos#Correo.md)+ ---> en ppio, representa los correos recibidos

## Administrador ##
  * parent : [Persona](DiccionarioDeDatos#Persona.md)
  * correos : [Correo](DiccionarioDeDatos#Correo.md)+  ---> en ppio, representa los correos enviados

## Usuario ##
  * usario : String
  * clave : String
  * ultimoAcceso : Time
  * persona : [Persona](DiccionarioDeDatos#Persona.md)

## Escuela ##
  * tipoEscuela : [Tipo Escuela](DiccionarioDeDatos#Tipo_Escuela.md)  ---> confirmar que esto no es lo mismo que tipoInstitucion
  * tipoInstitucion : [Tipo Institucion](DiccionarioDeDatos#Tipo_Institucion.md)
  * otroTipoInstitucion : String  ---> para cuando el tipo no estaba cargado en la BBDD
  * email : String ---> opcional
  * telefono : String  ---> opcional
  * domicilio : String
  * localidad : [Localidad](DiccionarioDeDatos#Localidad.md)
  * codigoPostal : String
  * distrito : [Distrito](DiccionarioDeDatos#Distrito.md)
  * regionEducativa : [Region Educativa](DiccionarioDeDatos#Region_Educativa.md)
  * director : String

## Tipo Escuela ##
  * nombre : String (EPB, ESB,...)

## Tipo Institucion ##
  * nombre : String ---> (Escuela Pública, Escuela Privada, Organización Social, Otro)

## Localidad ##
  * nombre : String

## Distrito ##
  * nombre : String

## Region Educativa ##
  * nombre : String

## Tema ##
#representa el tema principal del proyecto
  * nombre : String
  * anulado : boolean

## Produccion ##
#representa los tipos de produccion que pueden realizarse en los proyectos como Producción Final
  * nombre : String

## Correo ##
  * fecha : Time
  * asunto : String
  * cuerpo : Text

## Plantilla ##
  * codigo : String
  * asunto : String
  * cuerpo : Text
  * puedeBorrarse : boolean