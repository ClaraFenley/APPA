<?php
($_POST);
$_ENV=parse_ini_file('.env');
 $mysqli = mysqli_init();
 $mysqli->ssl_set(NULL, NULL, "./cacert.pem", NULL, NULL);
 $mysqli->real_connect($_ENV["HOST"], $_ENV["USERNAME"], $_ENV["PASSWORD"], $_ENV["DATABASE"]);
 include("Funciones.php");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
<form action="./Iniciodesesion.php"method="POST">
    Ingrese su nombre de usuario o mail con el que se registro: <br>
    <input type="text" name="NombreUsuario" ><br>
    Ingrese au contraseña: <br>
    <input type ="password" name="Contrasenia"><br>
    <input type ="submit" name= "boton2" value="Ingresar">
    No tenes cuenta? <h4><a href="./Registrarse.php" >Registrate </a> </h4>
    <?php
    if ($_SERVER['REQUEST_METHOD']==='POST'){
        $nombreomailingresado=$_POST["NombreUsuario"];
        $contraseniaingresada=$_POST["Contrasenia"];
        $boton2 = "";
        if (isset($_POST["boton2"])) {
          $boton2 = $_POST["boton2"];
        }
        if($boton2){
            if (empty($nombreomailingresado)|| empty($contraseniaingresada)){
                echo 'Faltan datos';
                exit();
              }
            else if (!isset($nombreomailingresado, $contraseniaingresada)){
                echo 'Faltan datos';
                exit();
            }
    
            else{
                $query_check_password_mail="SELECT * FROM Cuentas WHERE (MailUsuario=? OR NombreUsuario=?) AND Contrasenia=?";
                $stmt_check_password_mail=$mysqli->prepare ($query_check_password_mail);
                $stmt_check_password_mail->bind_param("sss", $nombreomailingresado, $nombreomailingresado, $contraseniaingresada);
                $stmt_check_password_mail->execute();
                $stmt_check_password_mail->store_result();
                if ($stmt_check_password_mail->num_rows>0){
                        echo "has podido ingresar sesión";
                        header("location:Home.php");
                    }
                else {
                    echo"Usuario o contraseña incorrecto. Intente de nuevo";

                }
            }
        }
    }   
    ?>
</body>
</html>
