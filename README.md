# Práctica 2º Trimestre
## App Symfony 3.0 // Proyecto Fct

Práctica del 2º trimestre de la asignatura de Entorno Servidor.

## Instalación

Seleccionamos un direcctorio y clonamos el proyecto con:
```sh
$ git clone git://github.com/Zaruth/proyecto_fct.git
```

Una vez instalado y dentro de dicho directorio ejecutamos:
```sh
$ composer Install
```

Comenzará la instalacción de librerías y se creará la base de
datos si no está creada. En caso de que no esté creada se pedirá que
se especifiquen sus datos de creación, como el host, el usuario,etc.

Por último, si faltan las dependencias del cliente SOAP, ejecutar
lo siguiente:
```sh
$ composer require econea/nusoap
```


Ya está lista la app para usarse, ahora falta crear un usuario y asignarle
en la base de datos el rol "ROLE_SUPER_ADMIN".

Para más información consultar la documentación contenida en el directorio "Docs".
