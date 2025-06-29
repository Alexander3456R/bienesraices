<?php

namespace Model;

class Vendedor extends ActiveRecord {
    protected static $tabla = 'vendedores';
    protected static $columnasDB = ['id', 'nombre', 'apellido', 'telefono', 'imagen', 'email'];

    public $id;
    public $nombre;
    public $apellido;
    public $telefono;
    public $imagen;
    public $email;


     public function __construct($args = [])
    {
        $this->id = $args['id'] ?? null;
        $this->nombre = $args['nombre'] ?? '';
        $this->apellido = $args['apellido'] ?? '';
        $this->telefono = $args['telefono'] ?? '';
        $this->imagen = $args['imagen'] ?? '';
        $this->email = $args['email'] ?? '';
        
    }


    public function validar() {
        if(!$this->nombre) {
            self::$errores[] = "Debes añadir un nombre";
        }

         if(!$this->apellido) {
            self::$errores[] = "Debes añadir un apellido";
        }

         if(!$this->telefono) {
            self::$errores[] = "Debes añadir un teléfono";
        }

         if(!$this->imagen) {
            self::$errores[] = "Debes añadir una imagen";
        }

         if(!$this->email) {
            self::$errores[] = "Debes añadir un E-mail";
        }

        if(!preg_match('/[0-9]{10}/', $this->telefono)) {
             self::$errores[] = "Formato de teléfono no válido";
        }

        return self::$errores;
    }
}