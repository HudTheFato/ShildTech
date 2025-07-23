<?php
include("../../conectarbd.php");

$id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);

if ($id) {
    $sql = "DELETE FROM tb_encomendas WHERE id_encomendas = $id";
    
    if (mysqli_query($conn, $sql)) {
        echo "<script>alert('Encomenda excluída com sucesso!'); window.location = 'consultar_encomendas.php';</script>";
    } else {
        echo "<script>alert('Erro ao excluir encomenda: " . mysqli_error($conn) . "'); window.location = 'consultar_encomendas.php';</script>";
    }
} else {
    echo "<script>alert('ID inválido!'); window.location = 'consultar_encomendas.php';</script>";
}

mysqli_close($conn);
?>