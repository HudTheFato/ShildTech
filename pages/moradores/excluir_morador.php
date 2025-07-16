<?php
include("../../conectarbd.php");

$id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);

if ($id) {
    $sql = "DELETE FROM tb_moradores WHERE id_moradores = $id";
    
    if (mysqli_query($conn, $sql)) {
        echo "<script>alert('Morador excluído com sucesso!'); window.location = 'consultar_moradores.php';</script>";
    } else {
        echo "<script>alert('Erro ao excluir morador: " . mysqli_error($conn) . "'); window.location = 'consultar_moradores.php';</script>";
    }
} else {
    echo "<script>alert('ID inválido!'); window.location = 'consultar_moradores.php';</script>";
}

mysqli_close($conn);
?>