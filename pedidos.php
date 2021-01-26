<?php

  session_start();

  if (isset($_SESSION['user_id'])) {
    require 'db.php';
    $msg = '';
    $records = $conn->prepare('SELECT * FROM seguridad WHERE id = :id');
    $records->bindParam(':id', $_SESSION['user_id']);
    $records->execute();
    $results = $records->fetch(PDO::FETCH_ASSOC);

    $user = null;

    if (count($results) > 0) {
      $user = $results;
    }

    if (isset($_POST['addPedido'])) {
      $sql2 = "INSERT INTO pedido (total, fecha, id_cliente, id_comercial)
               VALUES (:total, :fecha, :id_cliente, :id_comercial)";
      $decInsPed = $conn->prepare($sql2);
      $decInsPed->bindParam(':total', $_POST['total']);
      $decInsPed->bindParam(':id_cliente',$_POST['idCliente']); 
      $decInsPed->bindParam(':id_comercial', $_POST['idComercial']); 
      $decInsPed->bindParam(':fecha', $_POST['fecha']); 
      $decInsPed->execute();
      $message = "Pedido guardado con exito";
    }
  }
?>

<?php include('includes/header.php')?>
  <body>
    <?php if(!empty($user)): ?>
      
      <div class="nav nav-expanded" id="nav">
        <div class="icon-nav" id="icon-nav">
        <span><?= $user['usuario']; ?></span><i class="icon-menu"></i>
        </div>
        <div class="img-nav">
          <a href="#"><img src="./img/image 1.jpg"></a>
        </div>
        <div class="collection-buttons">
          <ul class="section-buttons">
            <a href="#">
              <li>
                <i class="icon-home"></i>
                <span>Pedidos</span>
              </li>
            </a>
            <a href="#">
              <li>
                <i class="icon-library"></i>
                <span>Clientes</span>
              </li>
            </a>
            <a href="#">
              <li>
                <i class="icon-images"></i>
                <span>Comerciales</span>
              </li>
            </a>
            <a href="cambiarContra.php">
              <li>
                <i class="icon-folder-open"></i>
                <span>Cambiar Contra</span>
              </li>
            </a>
            <a href="signup.php">
              <li>
                <i class="icon-home"></i>
                <span>Agregar Admin</span>
              </li>
            </a>
          </ul>
        </div>
			<a href="logout.php">
				<div class="btn-exit">
					<span>Desconectarse</span><i class="icon-exit"></i>
				</div>
			</a>
		</div>
        <div class="contenido">
            <h2>Pedidos</h2>
            <?php if(!empty($message)): ?>
               <p class="msgPedido"> <?= $message ?></p>
            <?php endif; ?>
            <div class="contenido-contenido">
              <div class="addPedido">
                <h1>Nuevo pedido</h1>
                <form action="pedidos.php" method="POST">
                <input name="total" type="number" step="0.01" min=0  placeholder="Introduzca el total del pedido" requerid>
                <input type="date" name="fecha" required>
                <select name="idCliente" id="">
                  <option value="">Elige un cliente</option>
                  <?php    
                    $sentencia2 = $conn->query("SELECT id, nombre FROM cliente");
                    $clientes = $sentencia2->fetchAll(PDO::FETCH_OBJ);
                    
                    foreach($clientes as $cliente){
                      ?>
                        <option value="<?php print($cliente->id); ?>"><?php print($cliente->nombre); ?></option>
                      <?php    
                    }
                  ?>
                </select>
                <select name="idComercial" id="">
                  <option value="">Elige un comercial</option>
                  <?php    
                    $sentencia3 = $conn->query("SELECT id, nombre FROM comercial");
                    $comerciales = $sentencia3->fetchAll(PDO::FETCH_OBJ);
                    
                    foreach($comerciales as $comercial){
                      ?>
                        <option value="<?php print($comercial->id); ?>"><?php print($comercial->nombre); ?></option>
                      <?php    
                    }
                  ?>
                </select>
                <input name='addPedido' type="submit" value="Enviar">
                </form>  
              </div>
              <div class="tablaPedidos">
                <table class="tablita">
                    <thead>
                    <tr>
                        <th>ID</th>
                        <th>Total</th>
                        <th>Fecha</th>
                        <th>id_cliente</th>
                        <th>id_comercial</th>
                    </tr>
                    </thead>
                    <tbody>
                      <?php
                        
                        $sentencia = $conn->query("SELECT * FROM pedido");
                        $row = $sentencia->fetchAll(PDO::FETCH_OBJ);
                        
                        foreach($row as $dato){
                          ?>
                            <tr>
                              <td><?php echo $dato->id; ?></td>
                              <td><?php echo $dato->total; ?></td>
                              <td><?php echo $dato->fecha; ?></td>
                              <td><?php echo $dato->id_cliente; ?></td>
                              <td><?php echo $dato->id_comercial; ?></td>
                            </tr>
                          <?php
                        }
                      ?>
                    </tbody>
                </table>
              </div>
            </div>
        </div>
    <?php else: ?>
      <?php require 'partials/header.php' ?>
      <?php header('Location:login.php'); ?>
    <?php endif; ?>
  </body>
</html>
