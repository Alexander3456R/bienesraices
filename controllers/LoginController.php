<?php
namespace Controllers;
use MVC\Router;
use Model\Admin;




class LoginController {
   public static function login(Router $router) {
        $errores = [];

        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            $auth = New Admin($_POST);
            $errores = $auth->validar();

            if(empty($errores)) {
                // Verificar si el usuario existe
                $resultado = $auth->existeUsuario();
                if(!$resultado) {
                    // Verificar si el usuario existe o no, mensaje de error
                    $errores = Admin::getErrores();
                } else {
                    // Verificar el password
                    $autenticado = $auth->comprobarPassword($resultado);
                    if($autenticado) {
                        // Autenticar al Usuario
                        $auth->autenticar();
                    } else {
                        // Password incorrecto mensaje de erros
                         $errores = Admin::getErrores();
                    }

                
                }

            }
        }

        $router->render('/auth/login', [
            'errores' => $errores
        ]);
   }

   public static function logout() {
        session_start();
        
        $_SESSION = [];
        header('Location: /');
   }
}