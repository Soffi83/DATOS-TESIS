<?php
session_start();
require_once("../conexion.php");

// 🔐 Seguridad
if (!isset($_SESSION['rol']) || $_SESSION['rol'] != "2") {
    header("Location: ../login.php");
    exit();
}

// 🔎 FILTROS
$cliente = isset($_GET['cliente']) ? $_GET['cliente'] : "";
$estado = isset($_GET['estado']) ? $_GET['estado'] : "";
$fecha = isset($_GET['fecha']) ? $_GET['fecha'] : "";

// 🧠 QUERY BASE
$query = "SELECT 
    td.idTurnos_Dados,
    c.Nombre AS cliente,
    c.email,
    f.fechaN AS fecha,
    h.hora,
    tt.nombre AS tipo_turno,
    td.estado
FROM turnos_dados td
JOIN cliente c ON td.cliente_idcliente = c.idcliente
JOIN fecha f ON td.fecha_idfecha = f.idfecha
JOIN hora h ON td.hora_idhora = h.idhora
JOIN tipo_turno tt ON td.tipo_turno_idtipo_turno = tt.idtipo_turno
WHERE 1=1";

// 📌 FILTROS DINÁMICOS
if (!empty($cliente)) {
    $cliente = mysqli_real_escape_string($conexion, $cliente);
    $query .= " AND c.Nombre LIKE '%$cliente%'";
}

if (!empty($estado)) {
    $estado = mysqli_real_escape_string($conexion, $estado);
    $query .= " AND td.estado = '$estado'";
}

if (!empty($fecha)) {
    $fecha = mysqli_real_escape_string($conexion, $fecha);
    $query .= " AND f.fechaN = '$fecha'";
}

$query .= " ORDER BY c.Nombre, f.fechaN DESC, h.hora DESC";

$resultado = mysqli_query($conexion, $query);
?>

<h2>Historial completo de turnos</h2>

<!-- 🔍 FILTROS -->
<form method="GET">
    <label>Cliente:</label>
<select name="cliente">
    <option value="">Todos</option>

    <?php
    $clientes = mysqli_query($conexion, "SELECT Nombre FROM cliente");

    while ($c = mysqli_fetch_assoc($clientes)) {
        $selected = ($cliente == $c['Nombre']) ? "selected" : "";
        echo "<option value='{$c['Nombre']}' $selected>{$c['Nombre']}</option>";
    }
    ?>
</select>

    <label>Estado:</label>
    <select name="estado">
        <option value="">Todos</option>
        <option value="pendiente">Pendiente</option>
        <option value="confirmado">Confirmado</option>
        <option value="cancelado">Cancelado</option>
    </select>

    <label>Fecha:</label>
    <input type="date" name="fecha" value="<?php echo $fecha; ?>">

    <button type="submit">Filtrar</button>
</form>

<br>

<table border="1">
<tr>
    <th>ID</th>
    <th>Cliente</th>
    <th>Email</th>
    <th>Fecha</th>
    <th>Hora</th>
    <th>Tipo</th>
    <th>Estado</th>
</tr>

<?php while($fila = mysqli_fetch_assoc($resultado)) { ?>
<tr>
    <td><?php echo $fila['idTurnos_Dados']; ?></td>
    <td><?php echo $fila['cliente']; ?></td>
    <td><?php echo $fila['email']; ?></td>
    <td><?php echo $fila['fecha']; ?></td>
    <td><?php echo $fila['hora']; ?></td>
    <td><?php echo $fila['tipo_turno']; ?></td>
    <td><?php echo $fila['estado']; ?></td>
</tr>
<?php } ?>
</table>

<br>
<a href="../DASHBOARD/empleado_dashboard.php">Volver</a>