<?php
session_start();
require_once("../conexion.php");

// 🔐 Seguridad
if (!isset($_SESSION['rol']) || $_SESSION['rol'] != "2") {
    header("Location: ../login.php");
    exit();
}

// 🧠 Query historial (NO pendientes)
$query = "SELECT 
    td.idTurnos_Dados,
    c.Nombre AS cliente,
    f.fechaN AS fecha,
    h.hora AS hora,
    tt.nombre AS tipo_turno,
    td.estado
FROM turnos_dados td
JOIN cliente c ON td.cliente_idcliente = c.idcliente
JOIN fecha f ON td.fecha_idfecha = f.idfecha
JOIN hora h ON td.hora_idhora = h.idhora
JOIN tipo_turno tt ON td.tipo_turno_idtipo_turno = tt.idtipo_turno
WHERE td.estado IN ('confirmado','cancelado')
ORDER BY f.fechaN DESC, h.hora DESC";

$resultado = mysqli_query($conexion, $query);
?>

<h2>Historial de Turnos</h2>

<table border="1">
<tr>
    <th>ID</th>
    <th>Cliente</th>
    <th>Fecha</th>
    <th>Hora</th>
    <th>Tipo</th>
    <th>Estado</th>
</tr>

<?php while($fila = mysqli_fetch_assoc($resultado)) { ?>
<tr>
    <td><?php echo $fila['idTurnos_Dados']; ?></td>
    <td><?php echo $fila['cliente']; ?></td>
    <td><?php echo $fila['fecha']; ?></td>
    <td><?php echo $fila['hora']; ?></td>
    <td><?php echo $fila['tipo_turno']; ?></td>
    <td><?php echo $fila['estado']; ?></td>
</tr>
<?php } ?>
</table>

<br>
<a href="../DASHBOARD/empleado_dashboard.php">Volver</a>