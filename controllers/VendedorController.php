<?php

namespace Controllers;
use MVC\Router;
use Model\Vendedor;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager as Image;

class VendedorController {
    public static function crear(Router $router) {

        $errores = Vendedor::getErrores();
        $vendedor = new Vendedor();

        if($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Crear una nueva instancia de Vendedor
    $vendedor = new Vendedor($_POST['vendedor']);

     // Crear un nombre único para la imagen
    $nombreImagen = md5( uniqid( rand(), true ) ) . ".jpg";

    if($_FILES['vendedor']['tmp_name']['imagen']) {
        $manager = new Image(Driver::class);
        $imagen = $manager->read($_FILES['vendedor']['tmp_name']['imagen'])->cover(800, 600);
        $vendedor->setImagen($nombreImagen);
    }
    // Validar que no haya campos vacíos
    $errores = $vendedor->validar();

    // SI no hay errores, guardar el vendedor
    if(empty($errores)) {
    if($_FILES['vendedor']['tmp_name']['imagen']) {
        // Crear carpeta si no existe
        if (!is_dir(CARPETA_IMAGENES)) {
            mkdir(CARPETA_IMAGENES);
        }
        // Guardar imagen
        $imagen->toJpeg(80)->save(CARPETA_IMAGENES . $nombreImagen);
    }

    // Guardar en la base de datos
    $vendedor->guardar();

    // Redirigir
    header('Location: /admin?resultado=1');
}
        }

        $router->render('/vendedores/crear', [
            'errores' => $errores,
            'vendedor' => $vendedor
        ]);
    }

      public static function actualizar(Router $router) {

        $errores = Vendedor::getErrores();

    // Obtener el ID de la URL y validarlo
        $id = $_GET['id'] ?? null;
        $id = filter_var($id, FILTER_VALIDATE_INT);
        if(!$id) {
            header('Location: /admin');
            exit;
        }

        //Obtener datos del vendedor a actualizar
        $vendedor = Vendedor::find($id);

        if($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Asignar los valores
    $args = $_POST['vendedor'];
    // Sincronnizar objeto con lo que el usuario escriba
    $vendedor->sincronizar($args);

    // Validacion
    $errores = $vendedor->validar();

    // Crear un nombre único para la imagen
    $nombreImagen = md5( uniqid( rand(), true ) ) . ".jpg";
      if($_FILES['vendedor']['tmp_name']['imagen']) {
        $manager = new Image(Driver::class);
        $imagen = $manager->read($_FILES['vendedor']['tmp_name']['imagen'])->cover(800, 600);
        $vendedor->setImagen($nombreImagen);
      }
   if(empty($errores)) {

    if(isset($imagen)) {
            $imagen->save(CARPETA_IMAGENES . $nombreImagen);
        }
        // Guardar el registro
        $vendedor->guardar();
        header('Location: /admin?resultado=2');
        exit;
    }

}

        $router->render('vendedores/actualizar', [
            'errores' => $errores,
            'vendedor' => $vendedor
        ]);
    }

      public static function eliminar() {
        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Valida el id
            $id = $_POST['id'];
            $id = filter_var($id, FILTER_VALIDATE_INT);

            if($id) {
                 // Valida el tipo a eliminar
                $tipo = $_POST['tipo'];

                if(validarTipoContenido($tipo)) {
                    $vendedor = Vendedor::find($id);
                    $vendedor->eliminar();
                }
            }
        }
    }
}