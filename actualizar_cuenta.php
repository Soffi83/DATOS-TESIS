<?php
session_start();
require_once("../conexion.php");

if (!isset($_SESSION['rol']) || $_SESSION['rol'] != "2") {
    header("Location: ../login.php");
    exit();
}

$id = intval($_POST['idusuario']);
$usuario = mysqli_real_escape_string($conexion, $_POST['nomusuario']);
$contrasena = $_POST['contrasena'];

// 🔐 Si cambia contraseña
if (!empty($contrasena)) {
    $hash = password_hash($contrasena, PASSWORD_DEFAULT);

    $query = "UPDATE usuario 
              SET nomusuario='$usuario', contrasena='$hash'
              WHERE idusuario=$id";
} else {
    $query = "UPDATE usuario 
              SET nomusuario='$usuario'
              WHERE idusuario=$id";
}

mysqli_query($conexion, $query);

header("Location: mi_cuenta.php?ok=1");
exit();
