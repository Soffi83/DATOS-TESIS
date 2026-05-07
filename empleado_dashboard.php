<?php
session_start();

if (!isset($_SESSION['rol']) || $_SESSION['rol'] != "2") {
    header("Location: ../login.php");
    exit();
}
?>

<h1>Panel Empleado</h1>
<p>Bienvenido <?php echo $_SESSION['usuario']; ?></p>

<a href="../EMPLEADO/ver_turnos_empleado.php">Ver agenda de turnos</a>
<br>
<a href="../EMPLEADO/historial_turnos.php">Historial de turnos</a>
<br>
<a href="../EMPLEADO/mi_cuenta.php">Mi cuenta</a>
<br>
<a href="../EMPLEADO/historial_cliente.php">📊 Historial completo de usuarios</a>
<br>
<a href="../SESION/logout.php">Cerrar sesión</a>
