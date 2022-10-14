<?php

$host="localhost";
$bd="SerchTec";
$usuario="root";
$contrasenia="";

try {
    $conexion=new PDO("mysql:host=$host;dbname=$bd",$usuario,$contrasenia);

} catch ( Exception $ex) {

    echo $x->getMessage();
}

?>
