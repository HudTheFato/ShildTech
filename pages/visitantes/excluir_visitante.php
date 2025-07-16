<?php
include("../../conectarbd.php");

$id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);

if ($id) {
    $sql = "DELETE FROM tb_visitantes WHERE id_visitantes = $id";
    
    if (mysqli_query($conn, $sql)) {
        echo "<script>alert('Visitante excluído com sucesso!'); window.location = 'consultar_visitantes.php';</script>";
    } else {
        echo "<script>alert('Erro ao excluir visitante: " . mysqli_error($conn) . "'); window.location = 'consultar_visitantes.php';</script>";
    }
} else {
    echo "<script>alert('ID inválido!'); window.location = 'consultar_visitantes.php';</script>";
}

mysqli_close($conn);
?>