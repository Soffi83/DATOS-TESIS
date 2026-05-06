<?php
session_start();
require_once("../conexion.php");
define("BASE_URL", "/tesis_prueba/");

// después
header("Location: " . BASE_URL . "EMPLEADO/ver_turnos_empleado.php");

// 🔐 Validar rol
if (!isset($_SESSION['rol']) || $_SESSION['rol'] != "2") {
    die("Acceso denegado");
}

// 🔎 Validar parámetros
if (!isset($_GET['id']) || !isset($_GET['accion'])) {
    die("Faltan datos");
}

$id = intval($_GET['id']);
$accion = $_GET['accion'];

// 🎯 Determinar estado
if ($accion == "confirmar") {
    $nuevo_estado = "confirmado";
} elseif ($accion == "cancelar") {
    $nuevo_estado = "cancelado";
} else {
    die("Acción inválida");
}

// 🧠 QUERY
$query = "UPDATE turnos_dados 
          SET estado = '$nuevo_estado' 
          WHERE idTurnos_Dados = $id";

// 🚨 EJECUTAR CON CONTROL DE ERROR
$resultado = mysqli_query($conexion, $query);

if (!$resultado) {
    die("Error en la consulta: " . mysqli_error($conexion));
}

// 🧪 VERIFICAR SI REALMENTE ACTUALIZÓ
if (mysqli_affected_rows($conexion) == 0) {
    die("No se actualizó ningún registro (ID inexistente o mismo estado)");
}

// ✅ TODO OK
header("Location: ver_turnos_empleado.php");
exit();
