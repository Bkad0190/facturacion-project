<!DOCTYPE html>
<html>
  <head>
  <link rel="stylesheet" href="css/styledef.css">
  </head>
  <body>
    <?php

if (!empty($_POST['btningresar'])) {
    // 1. Verificar que ambos campos estén llenos
    if (!empty($_POST['usr']) && !empty($_POST['password'])) {
        // 2. Sanitizar las entradas para prevenir ataques como XSS (Cross-Site Scripting)
        $usuario_ingresado = htmlspecialchars($_POST['usr'], ENT_QUOTES, 'UTF-8');
        $password_ingresada = htmlspecialchars($_POST['password'], ENT_QUOTES, 'UTF-8');

        $usuario_valido_bd = "admin";
        $password_hash_almacenada_bd = "123admin"; 
        // 3. Verificar las credenciales
        // Primero, verifica el nombre de usuario
        if ($usuario_ingresado === $usuario_valido_bd) {
            // Si el usuario coincide, verifica la contraseña hasheada de forma segura
            if ($password_ingresada === $password_hash_almacenada_bd) {
                // Las credenciales son correctas: ¡Inicio de sesión exitoso!
                // Redirigir al usuario a la página principal
                header('location: home.php');
                exit; // Esto detiene la ejecución del script para evitar que se envíe más contenido.
            } else {
                // Contraseña incorrecta
                echo '<div class="mensaje-error">Contraseña incorrecta.</div>';
            }
        } else {
            // Usuario incorrecto
            // Es buena práctica usar un mensaje genérico para no dar pistas a atacantes.
            echo '<div class="mensaje-error">Usuario o contraseña incorrectos.</div>';
        }
  
    } else {
        // Mensaje si no se llenan todos los campos
        echo '<div class="mensaje-advertencia">Por favor, debes llenar todos los campos.</div>';
    }
}
?>
    <header>
    </header>
    <h2>Ingresar</h2>
    
    <form action= "" method="post">
      <label for="usr">Correo:</label>
      <input type="text" name="usr" class="input">
      <label for="password">Contraseña:</label>
      <input type="password" name="password" class="input">
      <input type="submit" value="ENTRAR" class="btn" name="btningresar">
    </form>
</body>
</html>