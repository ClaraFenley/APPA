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
<form action="./Paciente.php"method="POST">
    Nombre del adulto mayor: <br>
    <input type="text" name="nombrepac" ><br>
    Edad: <br>
    <input type ="text" name="edad"><br>
    Patalogias que sufre:
    <input type="text" name="pato">
    Dirección:
    <input type="text" name ="direc">
    <input type ="submit" name= "botong" value="Guardar">
<?php
if ($_SERVER['REQUEST_METHOD']==='POST'){
    $nombre=$_POST["nombrepac"];
    $edad=$_POST["edad"];
    $pato=$_POST["pato"];
    $direccion=$_POST["direc"];
    $bg = "";
    if (isset($_POST["botong"])) {
      $bg = $_POST["botong"];
    }
    if($bg){
        if (empty($nombre)|| empty($edad)||empty($direccion)){
            echo 'Faltan datos';
            exit();
          }
        else if (!isset($nombre, $edad, $direccion)){
            echo 'Faltan datos';
            exit();
        }

        else{
            $query_paciente="INSERT INTO Paciente (PacienteNombre,PacienteEdad,PacienteEnfermedades,PacienteDirec) VALUES (?,?,?,?)";
            $stmt_paciente=$mysqli->prepare ($query_paciente);
            $stmt_paciente->bind_param("ssss", $nombre, $edad, $pato, $direccion);
            if ($stmt_paciente->execute()){
            echo "Se ha guardado con exito";
            $stmt_paciente->store_result();
            }
            else {
                echo "Algo esta mal";
            }
        }
    }
}
?>