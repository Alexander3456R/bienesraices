<?php 

namespace Model;

class ActiveRecord {
     // Base de datos
    protected static $db;
    protected static $columnasDB = [];
    protected static $tabla = '';

    // Errores
    protected static $errores = []; 



     // Definir la conexión a la base de datos
    public static function setDB($database) {
        self::$db = $database;
    }


   

    public function guardar() {
    if($this->id) {
        return $this->actualizar(); // Devuelve resultado de actualizar
    } else {
        return $this->crear();      // Devuelve resultado de crear
    }
}


    public function crear() {
    $sanitizar = $this->sanitizarDatos();

    $query = " INSERT INTO " . static::$tabla . " ( ";
    $query .= join(', ', array_keys($sanitizar));
    $query .= ") VALUES ('";
    $query .= join("', '", array_values($sanitizar));
    $query .= "')";

    $resultado = self::$db->query($query);
    return $resultado;
}

    

    public function actualizar() {
          // Sanitizar los datos
        
        $sanitizar = $this->sanitizarDatos();

        $valores = [];
        foreach($sanitizar as $key => $value) {
            $valores[] = "$key = '$value'";
        }
        $query = "UPDATE " . static::$tabla . " SET ";
        $query .= join(', ', $valores );
        $query .= " WHERE id = '" . self::$db->escape_string($this->id) . "' ";
        $query .= " LIMIT 1 ";

        $resultado = self::$db->query($query);
        return $resultado;
        
        if($resultado) {
            // Redireccionar al usuariooo
           header('Location: /admin?resultado=2');
       }
        
    }

    // Eliminar un registro

    public function eliminar() {
            $querry = "DELETE FROM " . static::$tabla . " WHERE id = " . self::$db->escape_string($this->id) . " LIMIT 1";
           $resultado = self::$db->query($querry);

            if($resultado) {
                    $this->borrarImagen();
                    header('Location: /admin?resultado=3');
                    exit;
                } 
    }

    public function datos() {
        $datos = [];
        foreach(static::$columnasDB as $columna) {
            if($columna === 'id') continue;
            $datos[$columna] = $this->$columna;
        }
        return $datos;
    }
    public function sanitizarDatos() {
       $datos = $this->datos();
       $sanitizado = [];
       foreach($datos as $key => $value) {
             $sanitizado[$key] = self::$db->escape_string($value);
       }
         return $sanitizado;
    }
   
    // Validacion

    public static function getErrores() {
        return static::$errores;
    }

    public function validar() {
        static::$errores = [];
        return static::$errores;
    }

  public function setImagen($imagen) {
    // Elimina la imagen previa 

    if(isset($this->id)) {
       $this->borrarImagen();
    }
    // Asignar la imagen el nombre de la imagen
    if($imagen) {
        $this->imagen = $imagen;
    }
}

    // Elimina el archivo
    public function borrarImagen() {
        // Comrprobar si existe la imagen
         $existeArchivo = CARPETA_IMAGENES . $this->imagen;
        if($existeArchivo) {
            unlink(CARPETA_IMAGENES . $this->imagen);
        }
    }

    // LIsta todos los registros
    public static function all() {
        $query = "SELECT * FROM " . static::$tabla;
        $resultado = self::consultarSQL($query);
        return $resultado;
        
        
    }

    // Obtiene determinado numero de registros
    public static function get($cantidad) {
        $query = "SELECT * FROM " . static::$tabla . " LIMIT " . $cantidad;
        $resultado = self::consultarSQL($query);
        return $resultado;
    }
    // Busca un registro por su id

    public static function find($id) {
          $query = "SELECT * FROM " . static::$tabla . " WHERE id = $id";

          $resultado = self::consultarSQL($query);
          return array_shift($resultado);
    }

    public static function consultarSQL($query) {
        // Consultar la base de datos
        $resultado = self::$db->query($query);

        // Iterar los resultados
        $array = [];
        while($registro = $resultado->fetch_assoc()) {
            $array[] = static::crearObjeto($registro);
        }
    

        // Liberar la memoria
        $resultado->free();

        // Retornar los resultados
        return $array;
    }

    protected static function crearObjeto($registro) {
        $objeto = new static;

        foreach($registro as $key => $value) {
            if(property_exists( $objeto, $key )) {
                $objeto->$key = $value;
            }
        }
        return $objeto;
    }

    // Sincronizar el objeto en memoria con los cambios realizados por el usuario
    public function sincronizar( $args = [] ) {
        foreach($args as $key => $value) {
            if(property_exists($this, $key) && !is_null($value)) {
                $this->$key = $value;
            }
        }
    }
 
}