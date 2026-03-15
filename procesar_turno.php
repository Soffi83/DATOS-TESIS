<?php
session_start();
include("../conexion.php");

/* PROTEGER ACCESO */
if (!isset($_SESSION['idusuario']) || 
   ($_SESSION['rol'] != 1 && $_SESSION['rol'] != 2)) {
    header("Location: ../login.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $tipo = $_POST['tipo_cliente'] ?? '';

    if ($tipo == "registrado") {

        $cliente_id = $_POST['cliente_id'];

    } elseif ($tipo == "anonimo") {

        $nombre = $_POST['nombre_anonimo'];
        $telefono = $_POST['telefono_anonimo'];

        mysqli_query($conexion, "
            INSERT INTO cliente (Nombre, telefono, anonimo)
            VALUES ('$nombre', '$telefono', 1)
        ");

        $cliente_id = mysqli_insert_id($conexion);
    }

    echo "✅ Cliente ID usado: " . $cliente_id;
}
?>

<h2>Asignar Turno</h2>

<form method="POST">

    <label>Tipo de cliente:</label>
    <select name="tipo_cliente" id="tipo_cliente" required>
        <option value="">Seleccionar</option>
        <option value="registrado">Cliente registrado</option>
        <option value="anonimo">Cliente anónimo</option>
    </select>

    <hr>

    <!-- CLIENTE REGISTRADO -->
    <div id="bloque_registrado" style="display:none;">
        <h3>Cliente registrado</h3>
        <input type="name" name="nomusuario" placeholder="Nombre">
        <h3>Fecha</h3>
        <input type="date" name="fechaN" required>

        <h3>Hora</h3>
        <select name="hora_id" required>
            <?php
            $horas = mysqli_query($conexion, "SELECT idhora, hora FROM hora");
            while ($h = mysqli_fetch_assoc($horas)) {
                echo "<option value='{$h['idhora']}'>{$h['hora']}</option>";
            }
            ?>
        </select>

        <br><br>
        <input type="submit" value="Reservar">
    </form>
    </div>

    <!-- CLIENTE ANÓNIMO -->
    <div id="bloque_anonimo" style="display:none;">
        <h3>Cliente anónimo</h3>
        <input type="text" name="nombre_anonimo" placeholder="Nombre">
        <input type="text" name="telefono_anonimo" placeholder="Teléfono">
        <h3>Fecha</h3>
        <input type="date" name="fechaN" required>

        <h3>Hora</h3>
        <select name="hora_id" required>
            <?php
            $horas = mysqli_query($conexion, "SELECT idhora, hora FROM hora");
            while ($h = mysqli_fetch_assoc($horas)) {
                echo "<option value='{$h['idhora']}'>{$h['hora']}</option>";
            }
            ?>
        </select>

        <br><br>
        <input type="submit" value="Reservar">
    </form>
    </div>

    <br>
    <button type="submit">Guardar turno</button>

</form>

<script>
document.getElementById("tipo_cliente").addEventListener("change", function() {

    let tipo = this.value;

    let registrado = document.getElementById("bloque_registrado");
    let anonimo = document.getElementById("bloque_anonimo");

    if (tipo === "registrado") {
        registrado.style.display = "block";
        anonimo.style.display = "none";
    } 
    else if (tipo === "anonimo") {
        registrado.style.display = "none";
        anonimo.style.display = "block";
    } 
    else {
        registrado.style.display = "none";
        anonimo.style.display = "none";
    }
});
</script>
