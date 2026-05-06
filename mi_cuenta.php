<?php
session_start();
require_once("../conexion.php");

if (!isset($_SESSION['rol']) || $_SESSION['rol'] != "2") {
    header("Location: ../login.php");
    exit();
}

$idusuario = $_SESSION['idusuario'];

// 🔎 Obtener datos
$query = "SELECT * FROM usuario WHERE idusuario = $idusuario";
$resultado = mysqli_query($conexion, $query);
$usuario = mysqli_fetch_assoc($resultado);
?>

<h2>Mi Cuenta</h2>

<form action="actualizar_cuenta.php" method="POST">
    <input type="hidden" name="idusuario" value="<?php echo $usuario['idusuario']; ?>">

    <label>Usuario:</label>
    <input type="text" name="nomusuario" value="<?php echo $usuario['nomusuario']; ?>">

    <br><br>

    <label>Nueva contraseña:</label>
    <input type="password" name="contrasena">

    <br><br>

    <button type="submit">Actualizar</button>
</form>

<br>
<a href="../DASHBOARD/empleado_dashboard.php">Volver</a>
