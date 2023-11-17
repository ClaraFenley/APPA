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
<form action="./Teldeemergencia.php"method="POST">
    Nombre: <br>
    <input type ="text" name="nom"><br>
    Parentesco:
    <input type ="text" name="paren"><br>

    Número de telefono <br>
    <input type="text" name="num" ><br>
    
    <input type ="submit" name= "guardar" value="Ingresar">
    <?php
    if ($_SERVER['REQUEST_METHOD']==='POST'){
    $nombre=$_POST["nom"];
    $paren=$_POST["paren"];
    $num=$_POST["num"];
    $botonguardar = "";
    if (isset($_POST["guardar"])) {
      $botonguardar = $_POST["guardar"];
    }
    if($botonguardar){ 
        if (empty($nombre)|| empty($paren)||empty($num)){
            echo 'Faltan datos';
            exit();
          }
        else if (!isset($nombre, $paren, $num)){
            echo 'Faltan datos';
            exit();
        }

        else{
            $query_num="INSERT INTO Teldeemergencia (Nombre, Parentesco, NumeroContacto) VALUES (?,?,?)";
            $stmt_num = $mysqli->prepare($query_num);
            $stmt_num->bind_param("sss", $nombre, $paren, $num);
            if ($stmt_num->execute()){
                echo "Se ha guardado con exito";
                $stmt_num->store_result();
            }
            else{
                echo"no";
            }
        }
    }
}
    ?>
