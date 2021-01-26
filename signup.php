<?php

  session_start();
 
  if (!empty($_POST['usuario']) && !empty($_POST['password'])) {

    require 'db.php';
    $message = '';
     
    $sql = "SELECT usuario FROM seguridad WHERE usuario = :usuario";
    $declaracion = $conn->prepare($sql);
    $declaracion->bindParam(':usuario', $_POST['usuario']);
    $declaracion->execute();
    $results3 = $declaracion->fetch(PDO::FETCH_ASSOC);

    if (empty($results3['usuario'])) {
      if ($_POST['password'] == $_POST['confirm_password']) {
        $sql = "INSERT INTO seguridad (ape_paterno, ape_materno, nombres, dni, fecha_alta, fecha_baja, usuario, contraseña) 
                VALUES (:ape_paterno, :ape_materno, :nombres, :dni, :fecha_alta, :fecha_baja, :usuario, :password)";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':ape_paterno', $_POST['ape_paterno']);
        $stmt->bindParam(':ape_materno', $_POST['ape_materno']);
        $stmt->bindParam(':nombres', $_POST['nombres']);
        $stmt->bindParam(':dni', $_POST['dni']);
        $date1 = date('d-m-Y');
        $date2 = '';
        $stmt->bindParam(':fecha_alta', $date1);
        $stmt->bindParam(':fecha_baja', $date2);
        $stmt->bindParam(':usuario', $_POST['usuario']);
        $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
        $stmt->bindParam(':password', $password);
        
        if ($stmt->execute()) {
          $message = 'Cuenta creada satisfactoriamente';
        } else {
          $message = 'Lo sentimos, debe haber un problema al crear su cuenta';
        }
      }
      else{
        $message = "Las dos contraseñas no coinciden";
      }
    }else{
      $message = 'Ese usuario ya existe, elige otro';
    }
  }
?>
<?php include('includes/header.php')?>
    <?php require 'partials/header.php' ?>

    <?php if(!empty($message)): ?>
      <p> <?= $message ?></p>
    <?php endif; ?>
    <div class="contenedor">
      <h1>Registrarse</h1>
      <h4>o <a href="login.php">Atras</a></h4>
      <form action="signup.php" method="POST">
        <input name="usuario" type="text" placeholder="Introduzca nuevo usuario" required>
        <input name="ape_paterno" type="text" placeholder="Apellido paterno" required>
        <input name="ape_materno" type="text" placeholder="Apellido materno" required>
        <input name="nombres" type="text" placeholder="Nombres" required>
        <input name="dni" type="text" placeholder="DNI" required>
        <input name="password" type="password" placeholder="Introduzca su contraseña" required>
        <input name="confirm_password" type="password" placeholder="Confirme su contraseña" required>
        <input type="submit" value="Enviar">
      </form>
    </div>
    
  </body>
</html>
