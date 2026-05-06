<?php
session_start();
require_once("../conexion.php");

// 🔐 Validar rol
if (!isset($_SESSION['rol']) || $_SESSION['rol'] != "2") {
    header("Location: ../login.php");
    exit();
}

// 🔎 FILTROS
$fecha = isset($_GET['fecha']) ? $_GET['fecha'] : "";
$cliente = isset($_GET['cliente']) ? $_GET['cliente'] : "";
$estado = isset($_GET['estado']) ? $_GET['estado'] : "";

// 🧠 QUERY BASE
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
WHERE 1=1";

// 📌 FILTROS DINÁMICOS
if (!empty($fecha)) {
    $query .= " AND f.fechaN = '" . mysqli_real_escape_string($conexion, $fecha) . "'";
}

if (!empty($cliente)) {
    $query .= " AND c.Nombre LIKE '%" . mysqli_real_escape_string($conexion, $cliente) . "%'";
}

if (!empty($estado)) {
    $query .= " AND td.estado = '" . mysqli_real_escape_string($conexion, $estado) . "'";
}

$query .= " ORDER BY f.fechaN, h.hora";

$resultado = mysqli_query($conexion, $query);
?>

<h2>Agenda de Turnos</h2>

<!-- 🔍 FORMULARIO DE FILTROS -->
<form method="GET">
    <label>Fecha:</label>
    <input type="date" name="fecha" value="<?php echo $fecha; ?>">

    <label>Cliente:</label>
    <input type="text" name="cliente" value="<?php echo $cliente; ?>">

    <label>Estado:</label>
    <select name="estado">
        <option value="">Todos</option>
        <option value="pendiente">Pendiente</option>
        <option value="confirmado">Confirmado</option>
        <option value="cancelado">Cancelado</option>
    </select>

    <button type="submit">Filtrar</button>
</form>

<br>

<table border="1">
    <tr>
        <th>ID</th>
        <th>Cliente</th>
        <th>Fecha</th>
        <th>Hora</th>
        <th>Tipo</th>
        <th>Estado</th>
        <th>Acciones</th>
    </tr>

    <?php while($fila = mysqli_fetch_assoc($resultado)) { ?>
        <tr>
            <td><?php echo $fila['idTurnos_Dados']; ?></td>
            <td><?php echo $fila['cliente']; ?></td>
            <td><?php echo $fila['fecha']; ?></td>
            <td><?php echo $fila['hora']; ?></td>
            <td><?php echo $fila['tipo_turno']; ?></td>
            <td><?php echo $fila['estado']; ?></td>

            <td>
                <?php if ($fila['estado'] == 'pendiente') { ?>
                    <a href="acciones_turno.php?id=<?php echo $fila['idTurnos_Dados']; ?>&accion=confirmar">
                        Confirmar
                    </a> |

                    <a href="acciones_turno.php?id=<?php echo $fila['idTurnos_Dados']; ?>&accion=cancelar">
                        Cancelar
                    </a>
                <?php } else { ?>
                    -error-
                <?php } ?>
            </td>
        </tr>
    <?php } ?>
</table>

<br>
<a href="../DASHBOARD/empleado_dashboard.php">Volver</a>
