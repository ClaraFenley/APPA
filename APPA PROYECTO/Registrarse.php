<?php
($_POST);
$_ENV=parse_ini_file('.env');
 $mysqli = mysqli_init();
 $mysqli->ssl_set(NULL, NULL, "./cacert.pem", NULL, NULL);
 $mysqli->real_connect($_ENV["HOST"], $_ENV["USERNAME"], $_ENV["PASSWORD"], $_ENV["DATABASE"]);
 include("Funciones.php");
  
 //$result = $mysqli->query("SELECT * FROM Clases");
   //while($row = $result->fetch_assoc()){
     //echo "...";
  // }
 
?>
        <!--HTML -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
  <form action="./Registrarse.php"method="POST">
    Nombre de Usuario:
    <input type="text" name ="Usuario"><br>
    Mail: 
    <input type="text" name ="Mail"><br>
    Contraseña:
    <input type="password" name ="Contrasenia">

    <input type="submit" name="boton1"value="Guardar"><br>
    <h4><a href="./index.php" >Volver al menú </a> </h4>

  <?php 
  if ($_SERVER['REQUEST_METHOD']==='POST'){
    $boton1 = "";
    if (isset($_POST["boton1"])) {
      $boton1 = $_POST["boton1"];
      $mail=$_POST["Mail"];
      $Contrasenia=$_POST["Contrasenia"];
      $NombreUsuario=$_POST["Usuario"];
    }
    if ($boton1){
      if (empty($NombreUsuario)|| empty( $mail) ||empty($Contrasenia)){
        echo 'Faltan datos';
        exit();
      }
      else if (!isset($NombreUsuario, $mail, $Contrasenia)){
        echo 'Faltan datos';
        exit();
      }
      else if (chequeocaracteresusuario($NombreUsuario)){
        exit();
      } 
      else if(chequeomail($mail)){
        exit();
      }
      else if (chequeocaracterescontra($Contrasenia)){
        exit();
      }
      else{
        $query_check_mail="SELECT * FROM Cuentas WHERE MailUsuario=?";
        $stmt_check_mail=$mysqli->prepare ($query_check_mail);
        $stmt_check_mail->bind_param("s", $mail);
        $stmt_check_mail->execute();
        $stmt_check_mail->store_result();
        if ($stmt_check_mail->num_rows>0 ){
          echo "Este mail ya tiene una cuenta creada";
        }
        else{
          $query= "INSERT INTO Cuentas (NombreUsuario, MailUsuario, Contrasenia) VALUES(?,?,?)";
          $stmt = $mysqli->prepare($query);
          $stmt->bind_param("sss", $NombreUsuario,$mail, $Contrasenia);
          if ($stmt->execute()) {
            echo "Nuevo registro creado";
            header("location:Home.php");
          } 
          else {
            echo "Ha ocurrido un error, no te has podido registrar";
        }
      }
    }
  }
}
  ?>
</body>
</html>