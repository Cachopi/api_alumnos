# Documentacion API

### Definiciones

+ **API** : Es una interfaz que permite la comunicacion, intercambio de datos y funcionalidades entre aplicaciones de forma estandarizada. 

+ **REST** : Es una recomendacion, 6 principios que respetar en la implementacion de la API.

+ **REST FULL** : Es una API que aparte de seguir los 6 principios
utiliza el protocolo http para su implementacion.

+ **RECURSO** : Son los registros de las tablas que contienen la informacion con la que trabaja.

+ **REPRESENTACION** : la forma en la que se ve un recurso al enviarlo o recibirlo. Un JSON generalmente.

+ **Throattle** : middleware para limitar el numero de solicitudas.

+ **SubstituteBindings** : se utiliza para cargar automáticamente los modelos
basados en los parámetros de ruta.

### Estructura JSON

``` json
{
  "data": [],
  "errors": [],
  "meta": [],
  "included": [],
  "links": [],
  "jsonapi": []
}

```
 + **data y errors son excluyentes entre si** 
 + **data es el principal y contiene la informacion**


## Creación de la API

### 1º Realizacíon
En un directorio a gusto del usuario 
abriendo un terminal creamos la API 

```bash
        laravel new api_alumno
``` 

seguimos el proceso de creacion dandole a siguiente y finalmente seleccionamos mysql como base de datos. 


    
 
### 2º  Modelo y Controlador   

+ Creamos el modelo correspondiente a la tabla alumnos que queremos crear.
+ Necesitamos la **migracion** y la **factory** para poblar la tabla y el controlador con los metodos de gestion .

Vamos a la carpeta del proyecto
```bash
        php artisan make:model Alumno  -fm  --api
```
*--api inidica quee se un modleo para utilizarlo en una api*

Esto nos genera 4 clases:
- la Migracion
- el Controlador
- el Modelo
- la Factory

### 4º Resources

Vamos a generar los resources que sera lo que entregemos a las peticiones.

```bash

       php artisan make:AlumnoResourece
       php artisan make AlumnoCollection --collection

```
### 5º FormRequest
Generamos la clase form request que se encargara de validar las peticiones **HTTP** con formularios.

```bash

       php artisan make:request AlumnoFormRequest

```
### 6º Rutas
Gestionamos las rutas para los metodos del controlador.


En el fichero api.php



```php

      Route::apiResource("alumnos", AlumnoController::class); 

```
**comprobamos las rutas**

```bash

     php artisan route:list  --path='api/alumnos'

```




### 7º Creamos Ficheros

Creamos el docker-compose.yaml y el .env   

#### .env

```php
    APP_NAME=Laravel
	APP_ENV=local
	APP_KEY=base64:2LsZA1WpEGaIoFovyisJ8NXwi+oFyqCmn9nRmLk/Sxg=
	APP_DEBUG=true
	APP_URL=http://localhost

	LOG_CHANNEL=stack
	LOG_DEPRECATIONS_CHANNEL=null
	LOG_LEVEL=debug

	DB_CONNECTION=mysql
	DB_HOST=127.0.0.1
	DB_PORT=23306
	DB_DATABASE=instituto
	DB_USERNAME=alumno
	DB_PASSWORD=alumno
	DB_PASSWORD_ROOT=root
	DB_PORT_PHPMYADMIN=8080

	BROADCAST_DRIVER=log
	CACHE_DRIVER=file
	FILESYSTEM_DISK=local
	QUEUE_CONNECTION=sync
	SESSION_DRIVER=file
	SESSION_LIFETIME=120

	MEMCACHED_HOST=127.0.0.1

	REDIS_HOST=127.0.0.1
	REDIS_PASSWORD=null
	REDIS_PORT=6379

	MAIL_MAILER=smtp
	MAIL_HOST=mailpit
	MAIL_PORT=1025
	MAIL_USERNAME=null
	MAIL_PASSWORD=null
	MAIL_ENCRYPTION=null
	MAIL_FROM_ADDRESS="hello@example.com"
	MAIL_FROM_NAME="${APP_NAME}"

	AWS_ACCESS_KEY_ID=
	AWS_SECRET_ACCESS_KEY=
	AWS_DEFAULT_REGION=us-east-1
	AWS_BUCKET=
	AWS_USE_PATH_STYLE_ENDPOINT=false

	PUSHER_APP_ID=
	PUSHER_APP_KEY=
	PUSHER_APP_SECRET=
	PUSHER_HOST=
	PUSHER_PORT=443
	PUSHER_SCHEME=https
	PUSHER_APP_CLUSTER=mt1

	VITE_APP_NAME="${APP_NAME}"
	VITE_PUSHER_APP_KEY="${PUSHER_APP_KEY}"
	VITE_PUSHER_HOST="${PUSHER_HOST}"
	VITE_PUSHER_PORT="${PUSHER_PORT}"
	VITE_PUSHER_SCHEME="${PUSHER_SCHEME}"
	VITE_PUSHER_APP_CLUSTER="${PUSHER_APP_CLUSTER}"

	LANG_FAKE ="es_ES"

```

#### docker-compose.yaml

```php
        #Nombre de la version
version: "3.8"
services:
  mysql:
    # image: mysql <- Esta es otra opcion si no hacemos el build
    image: mysql

    # Para no perder los datos cuando destryamos el contenedor, se guardara en ese derectorio
    volumes:
      - ./datos:/var/lib/mysql
    ports:
      - ${DB_PORT}:3306
    environment:
      - MYSQL_USER=${DB_USERNAME}
      - MYSQL_PASSWORD=${DB_PASSWORD}
      - MYSQL_DATABASE=${DB_DATABASE}
      - MYSQL_ROOT_PASSWORD=${DB_PASSWORD_ROOT}

  phpmyadmin:
    image: phpmyadmin
    container_name: phpmyadmin  #Si no te pone por defecto el nombre_directorio-nombre_servicio
    ports:
      - ${DB_PORT_PHPMYADMIN}:80
    depends_on:
      - mysql
    environment:
      PMA_ARBITRARY: 1 #Para permitir acceder a phpmyadmin desde otra maquina
      PMA_HOST: mysql

```



### 8º Arrancamos docker-compose

Paramos los anteriores y los eliminamos

```bash
        docker stop $(docker ps -a -q)  

        docker rm $(docker ps -a -q)
```

Lo arrancamos

```bash
        docker compose up -d  
```

Iniciamos el servidor

```bash
        php artisan serve
```


### 9º Base de datos


En la migracion de Alumnos añadimos en el metodo up los campos que queremos que tenga nuestra tabla.

```php
        Schema::create('alumnos', function (Blueprint $table) {
                $table->id();
                $table->string("nombre");
                $table->string("direccion");
                $table->string("email");
                $table->timestamps();
            });
```

En factory creamos los registros con los que generaremos datos para poblar la tabla.

```php
        public function definition(): array
        {
                return [
                "nombre" =>fake()->name(),
                "direccion" =>fake()->address(),
                "email" =>fake()->email()
                //
                ];
        }
```

En DatabaseSeeder.php

```php
        Alumnofactory::factory(20)->create();
```


En *config/app.php* cambiamos el idioma para que al usar la libreria FAKER PHP los registros sean es español

```php
        'faker_locale' => 'es_ES',  
```



Poblamos la base de datos 

```bash
        php artisan migrate --seed 
```     


### 10º Editando Resource

La clase AlumnoResource contiene los metodos que dan forma al la informacion que va devolverse en la respuesta en este caso tendra formato JSON.


Vamos a reescribir el metodo to Array :




```php
         public function toArray(Request $request): array
    {
        return [
            "id" => (string)$this->id,
            "type" => "Alumnos",
            "attributes" => [

                "nombre" => $this->nombre,
                "direccion"=>$this->direccion,
                "email" => $this->email
            ],
            'links' => [
                'self' => url('api/alumnos/' . $this->id)
            ]];
    }
```   

y Añadimos la funcion with que añade datos relacionados en la respuesta sin provocar consultas adicionales a la base de datos.

``` php

 public function with(Request $request): array
    {
        return ["jsonapi" => ["version" => "1.0"]];
    }

``` 

### 11º Editando Collection

Esta clase se encarga de dar formato a la coleccion de modelos para entregarla en la respuesta.
 
 Para ello sobreescribimos el metodo toArray

``` php

   public function toArray(Request $request): array
    {
        return parent::toArray($request);
    }

```
y añadimos el metodo with

```php
 public function with(Request $request)
    {
        return ["jsonapi" =>
            ["version" => "1.0"]];
    }

```
### 12º Alumno Controller

En el controlador se organiza la logica del manejo de las solicitudes HTTP

**INDEX**

Esta acción se utiliza para mostrar una lista de recursos. 



```php
   
    public function index()
    {
        
        //almacenamos todas las filas de la tabla con la información
        $alumnos = Alumno::all();
        //return response()->json($alumnos);

        //devolvemos una coleccion en formato JSON
        return new AlumnoCollection($alumnos);
    }

```

**STORE**

 Esta acción se utiliza para almacenar un nuevo recurso en la base de datos. 




```php

 public function store(AlumnoFormRequest $request)
    {
        //almacenamos los valores
        $datos = $request->input("data.attributes");
        //creamos una nueva fila con los datos
        $alumno = new Alumno($datos);
        //almacenamos
        $alumno->save();
        //devolvemos los datos insertados con formato JSON
        return new AlumnoResource($alumno);
    }

```

**Assignacion Masiva**

Para prevenir la asignacion masiva determinamos los campos donde se va a insertar la informacion y evitar informacion indeseada por seguridad 
Añadimos en  en app/http/Models/Nombre.php

```php
        protected $fillable=["nombre","direccion","email"];
```     


**SHOW**

Esta acción se utiliza para mostrar un recurso específico. 


```php
public function show(int $id)

    {
        //envia un error si no existe el elemento

        $resource = Alumno::find($id);

        if (!$resource) {
            return response()->json([
                'errors' => [
                    [
                        'status' => '404',
                        'title' => 'Resource Not Found',
                        'detail' => 'The requested resource does not exist or could not be found.'
                    ]
                ]
            ], 404);
        }
        //devolvemos el elmento solicitado en formato JSON
        return new AlumnoResource($resource);
    }


```
**UPDATE**

Esta acción se utiliza para actualizar un recurso existente en la base de datos. 

* **PUT**

    Este método se utiliza para actualizar completamente un recurso o crearlo si no existe. 
* **PATCH**

    EsteEste método se utiliza para realizar una actualización parcial de un recurso.

```php
 public function update(Request $request, int $id)
    {
        $alumno = Alumno::find($id);
        //devuelve error sino se encuentra el recurso
        if (!$alumno) {
            return response()->json([
                'errors' => [
                    [
                        'status' => '404',
                        'title' => 'Resource Not Found',
                        'detail' => 'The requested resource does not exist or could not be found.'
                    ]
                ]
            ], 404);
        }

        $verbo = $request->method();
        //En función del verbo creo unas reglas de
        // validación u otras
        if ($verbo == "PUT") { //Valido por PUT
            $rules = [
                "data.attributes.nombre" => ["required", "min:5"],
                "data.attributes.direccion" => "required",
                "data.attributes.email" => ["required", "email", Rule::unique("alumnos", "email")->ignore($alumno)]
            ];

        } else { //Valido por PATCH
            if ($request->has("data.attributes.nombre"))
                $rules["data.attributes.nombre"]= ["required", "min:5"];
            if ($request->has("data.attributes.direccion"))
                $rules["data.attributes.direccion"]= ["required"];
            if ($request->has("data.attributes.email"))
                $rules["data.attributes.email"]= ["required", "email", Rule::unique("alumnos", "email")->ignore($alumno)];
        }

        $datos_validados = $request->validate($rules);
        //dump($datos_validados);

        foreach ($datos_validados['data']['attributes'] as $campo=>$valor)
            $datos[$campo] = $valor;

        $alumno->update($request->input("data.attributes"));

        return new AlumnoResource($alumno);
    }

```
    


**DESTROY**

Este método se utiliza para eliminar un recurso.


```php
public function destroy(int $id)
    {
        //busca el recurso por el id
        $alumno = Alumno::find($id);
        //sino encuentra el recurso
        if (!$alumno) {
            return response()->json([
                'errors' => [
                    [
                        'status' => '404',
                        'title' => 'Resource Not Found',
                        'detail' => 'The requested resource does not exist or could not be found.'
                    ]
                ]
            ], 404);
        }
        //elimina el recurso
        $alumno->delete();
        return response()->json(null,204);
        //No devuelve contenido
        return response()->noContent();
    }
```
        




### 13º ERRORES
**DATABASE ERROR**

sobreescribimos el metodo render en *app/Exceptions/Handler.php* para manejar una respuesta en el caso de que la base datos no este operativa buscando si la excepcion es una instancia de **QueryException**

**VALIDATION ERROR**

añadimos en render si es instancia de **ValidationException** para manejar los errores cuando se intentan insertar datos en un campos que no corresponden a la tabla

**creamos la funcion en Handler.php**

```php
     
    protected function invalidJson($request, ValidationException $exception): JsonResponse
    {
        //lanza un error por cada campo erroneo
        return response()->json([
            'errors' => collect($exception->errors())->map(function ($message, $field) use ($exception) {
                return [
                    'status' => '422',
                    'title' => 'Validation Error',
                    'details' => $message[0],
                    'source' => [
                        'pointer' => '/data/attributes/' . $field
                    ]
                ];
            })->values()
        ], $exception->status);
    }





```

**modificamos el metodo render**

```php
    public function render($request, Throwable $exception)
    {

            // Manejo personalizado de ValidationException
            if ($exception instanceof ValidationException) {
                return $this->invalidJson($request, $exception);
            }
            if ($exception instanceof QueryException) {
                return response()->json([
                    'errors' => [
                        [
                            'status' => '500',
                            'title' => 'Database Error',
                            'detail' => 'Error procesando la respuesta. Inténtelo más tarde.'
                        ]
                    ]
                ], 500);

            }
            //// Delegar a la implementación predeterminada para otras excepciones no manejadas


        return parent::render($request, $exception);
    }
}
```
### Creamos un middleware

un middleware es software que se va a ejecutar entre el request y el response antes de que llegue al servidor.

Creamos un middleware Para validar el *accept*
en nuestro caso indicar al servidor que el formato de respuesta sea un JSON

En postman creamos un header key "Accept" y value "application/vnd.api+json"

```bash 
        php artisan make:middleware ValidateJsonApiContentType
```

Vamos a la carpeta app/Http/Middleware/ValidateJsonApiContentType.php y añadimos a la función handle 

```php  
        public function handle(Request $request, Closure $next): Response
    {
        if ($request->header('accept') != 'application/vnd.api+json') {
            return response()->json([
                "errors"=>[
                    "status"=>406,
                    "title"=>"Not Accetable",
                    "deatails"=>"Content File not specifed"
                ]
            ],406);
        }
        return $next($request);
    }
```

En app/Http/Kernel.php asociamos el middleware a la ruta 

```php
         protected $middlewareGroups = ['api'=> \App\Http\Middleware\ValidateJsonApiContentType::class];
```




### 14º REQUEST

   

Le damos permisos en app/Http/Requests/AlumnoRequest.php y añadimos las reglas que deben tener los campos para que permita  la inserccion

```php
        public function authorize(): bool
        {
                
                return true;
        }

         public function rules(): array
    {

        return [
            "data.attributes.nombre"=>["required","min:4"],
            "data.attributes.direccion"=>["required"],
            //nos aseguramos que el email sea unico
            "data.attributes.email"=>["required", "email", Rule::unique("alumnos", "email") ->ignore($this->alumno)]
    ];

    }
```

