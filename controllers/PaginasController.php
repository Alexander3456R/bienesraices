<?php
namespace Controllers;
use MVC\Router;
use Model\Propiedad;
use PHPMailer\PHPMailer\PHPMailer;

class PaginasController {
    public static function index(Router $router) {

        $propiedades = Propiedad::get(3);
        $inicio = true;

        $router->render('paginas/index' , [
            'propiedades' => $propiedades,
            'inicio' => $inicio
        ]);
    }

    public static function nosotros(Router $router) {
        $router->render('paginas/nosotros');
    }

    public static function propiedades(Router $router) {

        $propiedades = Propiedad::all();
        $router->render('paginas/propiedades', [
            'propiedades' => $propiedades
        ]);
    }

    public static function propiedad(Router $router) {
        $id = validarORedireccionar('/propiedades');
        // Buscar la propiedad por su ID
        $propiedad = Propiedad::find($id);

        $router->render('paginas/propiedad', [
            'propiedad' => $propiedad
        ]);
    }

    public static function blog(Router $router) {
        $router->render('paginas/blog');
    }

    public static function entrada(Router $router) {
        $router->render('paginas/entrada');
    }

    public static function contacto(Router $router) {

        $mensaje = null;
        

        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            $respuestas = $_POST['contacto'];
            
           
            // Crear una instancia de PHP Mailer
            $mail = new PHPMailer();

            // Configurar SMTP
            $mail->isSMTP();
            $mail->Host = $_ENV['EMAIL_HOST'];
            $mail->SMTPAuth = true;
            $mail->Port = $_ENV['EMAIL_PORT'];
            $mail->Username = $_ENV['EMAIL_USER'];
            $mail->Password = $_ENV['EMAIL_PASS'];

            // Configurar el contenido del Emial
            $mail->SetFrom('admin@bienesraices.com');
            $mail->addAddress('admin@bienesraices.com', 'BienesRaices.com');
            $mail->Subject = 'Tienes un Nuevo Mensajes';

            // Habilitar HTML
            $mail->isHTML(true);
            $mail->CharSet = 'UTF-8';

            

            // Definir el contenido
            $contenido = '<html>';
            $contenido .=  '<p>Tienes un Nuevo Mensaje</p>';
            $contenido .=  '<p>Nombre: ' . $respuestas['nombre'] . '</p>';
          

            // Contenido condicional algunas campos emial otros telefono
            if($respuestas['contacto'] === 'telefono') {
                $telefono = $respuestas['telefono'] ?? '';
                $fecha = $respuestas['fecha'] ?? '';
                $hora = $respuestas['hora'] ?? '';
                 $contenido.= '<p>Eligió ser contactado por Teléfono:</p>';
                 $contenido .=  '<p>Télefono: ' . $telefono . '</p>';
                 $contenido .=  '<p>Fecha Contacto: ' . $fecha . '</p>';
                 $contenido .=  '<p>Hora: ' . $hora . '</p>';
                } else {
                // Es email
                 $email = $respuestas['email'] ?? '';
                 $contenido.= '<p>Eligió ser contactado por Email:</p>';
                 $contenido .=  '<p>Email: ' . $email . '</p>';
            }

           
            $contenido .=  '<p>Mensaje: ' . $respuestas['mensaje'] . '</p>';
            $contenido .=  '<p>Vende o Compra: ' . $respuestas['tipo'] . '</p>';
            $contenido .=  '<p>Precio o Presupuesto: $' . $respuestas['precio'] . '</p>';
            $contenido .=  '<p>Prefiere ser contactado por: ' . $respuestas['contacto'] . '</p>';
            $contenido .= '</html>';

            $mail->Body = $contenido;
            $mail->AltBody = 'Esto es texto alternativo sin HTML';

            // Enviar el Email
            if($mail->send()) {
                $mensaje = 'Mensaje Enviado Correctamente.';
            } else {
                $mensaje = 'El mensaje no se pudo enviar...';
            }

        }
        $router->render('paginas/contacto', [
            'mensaje' => $mensaje
        ]);
    }
}