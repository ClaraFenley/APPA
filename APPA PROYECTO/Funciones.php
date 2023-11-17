<?php
Function chequeocaracterescontra($cond){
    if (strlen($cond)<5){
        echo "Lo lamento pero tu contraseña es muy corta";
        exit();
    }
    else if (strlen($cond)> 40){
        echo "Tu contraseña es muy extensa, por favor ingrese otra";
        exit();
    }
}

Function chequeocaracteresusuario($cond){
    if (strlen($cond)<5){
        echo "Tu nombre de usuario es muy corto, por favor ingrese otro";
        exit();
    }
    else if (strlen($cond)>40){
        echo "El nombre de usuario que estas intentando crear es muy extenso, por favor cree otro";
        exit();
    }
}

Function chequeomail($cond){
    if (!filter_var($cond, FILTER_VALIDATE_EMAIL)){
        exit("El mail no es valido");
    }
}
?>