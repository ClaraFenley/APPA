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
    <input type="Button" name="botoneditar" value="Editar">
    <h4><a href="./Agregarpastilla.php" >Agregar pastilla</a> </h4>
<?php
    $botoneditar = "";
    $query_mostrarpastillas="SELECT Pastillas.*, Horarios.horarios, Horarios_siempre.Lunes,Horarios_siempre.Martes, Horarios_siempre.Miercoles, Horarios_siempre.Jueves, Horarios_siempre.Viernes FROM Pastillas INNER JOIN Horarios ON idPastillas=Horarios_Pastillas INNER JOIN Horarios_siempre ON idPastillas=Pastillas_horariossiempre"; 
    $stmt_mostrarpastillas=$mysqli->prepare ($query_mostrarpastillas);
    $stmt_mostrarpastillas-> execute();
    if (null!==("botoneditar")) {
      $botoneditar ="botoneditar";
      
    }
?>
</body> 
</html>

