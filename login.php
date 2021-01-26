<?php

  session_start();

  if (isset($_SESSION['user_id'])) {
    header('Location: /Sistema-ventas2');
  }
  require 'db.php';

  if (!empty($_POST['usuario']) && !empty($_POST['password'])) {
    $records = $conn->prepare('SELECT id, usuario, contraseña FROM seguridad WHERE usuario = :usuario');
    $records->bindParam(':usuario', $_POST['usuario']);
    $records->execute();
    $results = $records->fetch(PDO::FETCH_ASSOC);

    $message = '';

    if (count($results) > 0 && password_verify($_POST['password'], $results['contraseña'])) {
      $_SESSION['user_id'] = $results['id'];
      header("Location: /Sistema-ventas2");
    } else {
      $message = 'El usuario y contraseña no coinciden';
    }
  }

?>

<?php include('includes/header.php')?>
  <body>
    <?php require 'partials/header.php' ?>

    <?php if(!empty($message)): ?>
      <p> <?= $message ?></p>
    <?php endif; ?>

    <div class="contenedor">
      <h1>Ingresar</h1>
      <form action="login.php" method="POST">
        <input name="usuario" type="text" placeholder="Introduzca su usuario">
        <input name="password" type="password" placeholder="Introduzca su contraseña">
        <input type="submit" value="Enviar">
      </form>  
    </div>
  </body>
</html>
