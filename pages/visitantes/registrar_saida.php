<?php
include("../../conectarbd.php");

$id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);

if ($id) {
    $sql = "UPDATE tb_visitantes SET status = 'Saiu' WHERE id_visitantes = $id";
    
    if (mysqli_query($conn, $sql)) {
        echo "<script>alert('Saída registrada com sucesso!'); window.location = 'visitantes.php';</script>";
    } else {
        echo "<script>alert('Erro ao registrar saída: " . mysqli_error($conn) . "'); window.location = 'visitantes.php';</script>";
    }
} else {
    echo "<script>alert('ID inválido!'); window.location = 'visitantes.php';</script>";
}

mysqli_close($conn);
?>