<?php

$conexion=mysqli_connect("localhost","root","","canchaalmen3");

$fecha=$_GET['fecha'];
$hora=$_GET['hora'];

$res=mysqli_query($conexion,"SELECT idfecha FROM fecha WHERE fechaN='$fecha'");

if(mysqli_num_rows($res)>0){

$row=mysqli_fetch_assoc($res);
$id_fecha=$row['idfecha'];

$verificar=mysqli_query($conexion,"SELECT * FROM turnos_dados
WHERE fecha_idfecha=$id_fecha AND hora_idhora=$hora");

if(mysqli_num_rows($verificar)>0){

echo "<span style='color:red;'>Turno ocupado</span>";

}else{

echo "<span style='color:green;'>Turno disponible</span>";

}

}else{

echo "<span style='color:green;'>Turno disponible</span>";

}

?>
