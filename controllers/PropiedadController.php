<?php

namespace Controllers;
use MVC\Router;
use Model\Propiedad;
use Model\Vendedor;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager as Image;

class PropiedadController {
    public static function index(Router $router) {

        $propiedades = Propiedad::all();


        $vendedores = Vendedor::all();

          // Muestra mensaje condicional
        $resultado = $_GET['resultado'] ?? null;

        $router->render('/propiedades/admin', [
            'propiedades' => $propiedades,
            'resultado' => $resultado,
            'vendedores' => $vendedores
        ]);
    }

    public static function crear(Router $router) {

        $propiedad = new Propiedad;
        $vendedores = Vendedor::all();
        
        // Arreglo con mensajes de errores
        $errores = Propiedad::getErrores();

        if($_SERVER['REQUEST_METHOD'] === 'POST') {

    $propiedad = new Propiedad($_POST['propiedad']);
    
    // Crear un nombre único para la imagen
    $nombreImagen = md5( uniqid( rand(), true ) ) . ".jpg";

    if($_FILES['propiedad']['tmp_name']['imagen']) {
        $manager = new Image(Driver::class);
        $imagen = $manager->read($_FILES['propiedad']['tmp_name']['imagen'])->cover(800, 600);
        $propiedad->setImagen($nombreImagen);
    }

    
    // Validar
    $errores = $propiedad->validar();

    if(empty($errores)) {

        // ** SUBIDA DE ARCHIVOS **
        if(!is_dir(CARPETA_IMAGENES)) {
            mkdir(CARPETA_IMAGENES);
        }

        // Guardar la imagen en el servidor
        $imagen->save(CARPETA_IMAGENES . $nombreImagen);

        $exito = $propiedad->guardar();
        if($exito) {
            // Redireccionar al usuario
            header('Location: /admin?resultado=1');
            exit;
        }
    }
}

        $router->render('propiedades/crear', [
            'propiedad' => $propiedad,
            'vendedores' => $vendedores,
            'errores' => $errores
        ]);
    }

    public static function actualizar(Router $router) {
        $id = validarORedireccionar("/admin");

        $propiedad = Propiedad::find($id);

        $vendedores = Vendedor::all();

        $errores = Propiedad::getErrores();

        // Metodo POST para actualizar
        if($_SERVER['REQUEST_METHOD'] === 'POST') {
       
        //Asignar los atrubutos
        $args = $_POST['propiedad'];
        $propiedad->sincronizar($args);

        // Validacion
        $errores = $propiedad->validar();
        
        // Subida de archivos

        // Crear un nombre único para la imagen
        $nombreImagen = md5( uniqid( rand(), true ) ) . ".jpg";
        if($_FILES['propiedad']['tmp_name']['imagen']) {
            $manager = new Image(Driver::class);
            $imagen = $manager->read($_FILES['propiedad']['tmp_name']['imagen'])->cover(800, 600);
            $propiedad->setImagen($nombreImagen);
        }
    
        // Validar que el arreglo de errores esté vacío
        if(empty($errores)) {
        
            // Almacenar imagen
            if(isset($imagen)) {
                $imagen->save(CARPETA_IMAGENES . $nombreImagen);
            }
            $exito = $propiedad->guardar(); 
            if($exito) {
                // Redireccionar al usuario
                header('Location: /admin?resultado=2');
                exit;
            
            }
        }

    }

        $router->render('/propiedades/actualizar', [
            'propiedad' => $propiedad,
            'errores' => $errores,
            'vendedores' => $vendedores
        ]);
    }


    public static function eliminar() {
       if($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'];
            $id = filter_var($id, FILTER_VALIDATE_INT);


            if($id) {
                $tipo = $_POST['tipo'];
                if(validarTipoContenido($tipo)) {
                    $propiedad = Propiedad::find($id);
                    $propiedad->eliminar();
                } 
            }

        }
    }
}
