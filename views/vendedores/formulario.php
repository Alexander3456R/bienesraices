<fieldset>
    <legend>Información General</legend>
    <label for="nombre">Nombre: </label>
    <input type="text" id="nombre" name="vendedor[nombre]" placeholder="Nombre Del Vendedor" value="<?php echo s($vendedor->nombre); ?>">

    <label for="apellido">Apellido: </label>
    <input type="text" id="apellido" name="vendedor[apellido]" placeholder="Apellido Del Vendedor" value="<?php echo s($vendedor->apellido); ?>">
</fieldset>

<fieldset>
    <legend>Información Personal</legend>
    <label for="telefono">Telefono: </label>
    <input type="text" id="telefono" name="vendedor[telefono]" placeholder="Telefono Del Vendedor" value="<?php echo s($vendedor->telefono); ?>">

    <label for="email">E-mail: </label>
    <input type="text" id="email" name="vendedor[email]" placeholder="E-mail Del Vendedor" value="<?php echo s($vendedor->email); ?>">

    <label for="imagen">Imagen del vendedor: </label>
                <input type="file" id="imagen" accept="image/jpeg, image/png" name="vendedor[imagen]">
                <?php if($vendedor->imagen): ?>
                    <img src="/imagenes/<?php echo $vendedor->imagen ?>" class="imagen-small">
                <?php endif; ?>
</fieldset>
