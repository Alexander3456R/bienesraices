<main class="contenedor seccion contenido-centrado">
        <h1>Iniciar Sesión</h1>
        <?php foreach($errores as $error): ?>

            <div class="alerta error">
                <?php echo $error; ?>
            </div>
        <?php endforeach; ?>
            

        <form method="POST" class="formulario" novalidate action="/login">
             <fieldset>
                <legend>Email y Password</legend>

                <label for="email">E-mail</label>
                <input type="email" name="email" id="email" placeholder="Tu correo electrónico">

                <label for="password">Password</label>
                <input type="password" name="password" id="password" placeholder="Tu password">
              
            </fieldset>

            <input class="boton boton-verde" type="submit" value="Iniciar Sesión">
        </form>
    </main>
   