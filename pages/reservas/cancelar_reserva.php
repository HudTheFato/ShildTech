<?php
include("../../conectarbd.php");

$id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);

if ($id) {
    $sql = "DELETE FROM tb_reservas WHERE id_reservas = $id";
    
    if (mysqli_query($conn, $sql)) {
        echo "<script>alert('Reserva cancelada com sucesso!'); window.location = 'consultar_reservas.php';</script>";
    } else {
        echo "<script>alert('Erro ao cancelar reserva: " . mysqli_error($conn) . "'); window.location = 'consultar_reservas.php';</script>";
    }
} else {
    echo "<script>alert('ID inválido!'); window.location = 'consultar_reservas.php';</script>";
}

mysqli_close($conn);
?>