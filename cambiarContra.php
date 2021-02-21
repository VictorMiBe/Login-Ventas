<?php

  session_start();
  
  if (isset($_POST['cambio'])) {
    
    require 'db.php';
    $message2 = '';
    
    $records2 = $conn->prepare("SELECT contraseña FROM seguridad WHERE id = :id");
    $records2->bindParam(':id', $_SESSION['user_id']);
    $records2->execute();
    $results2 = $records2->fetch(PDO::FETCH_ASSOC);

    if (password_verify($_POST['passwordActual'], $results2['contraseña'])) {
      if ($_POST['passwordNew'] == $_POST['confirm_password']) {
        $pass1 = password_hash($_POST['passwordNew'], PASSWORD_BCRYPT);      
        $records3 = $conn->prepare("UPDATE seguridad SET contraseña = '$pass1' WHERE id = :id");
        $records3->bindParam(':id', $_SESSION['user_id']);
        
        if ($records3->execute()){
          $message2 = "Se ha actualizado la contraseña";
        }
      }
      else{
        $message2 = "Las dos contraseñas no coinciden";
      }
    }
    else{
      $message2 = "Tu contraseña actual no coincide";
    }
  }

?>

<?php include('includes/header.php')?>

  <?php if(isset($_SESSION['user_id'])): ?>   
    <?php require 'partials/header.php' ?>
    <?php if(!empty($message2)):?>
      <p> <?= $message2 ?></p>
    <?php endif; ?>
    <div class="contenedor">
      <h1>Cambiar Contraseña</h1>
      <h4>o <a href="login.php">Atras</a></h4>
      <form action="cambiarContra.php" method="POST">
        <input name="passwordActual" type="password" placeholder="Actual contraseña" required>
        <input name="passwordNew" type="password" placeholder="Nueva contraseña" required>
        <input name="confirm_password" type="password" placeholder="Confirma contraseña" required>
        <input name="cambio" type="submit" value="Enviar">
      </form>
    </div>
  <?php else: ?>
    <?php require 'partials/header.php' ?>
    <?php header('Location:login.php'); ?>
  <?php endif; ?>  
  </body>
</html>
