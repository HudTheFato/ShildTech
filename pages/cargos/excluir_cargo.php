<?php
include("../../conectarbd.php");

$id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);

if ($id) {
    $sql = "DELETE FROM tb_cargo WHERE id_cargos = $id";
    
    if (mysqli_query($conn, $sql)) {
        echo "<script>alert('Cargo excluído com sucesso!'); window.location = 'consultar_cargos.php';</script>";
    } else {
        echo "<script>alert('Erro ao excluir cargo: " . mysqli_error($conn) . "'); window.location = 'consultar_cargos.php';</script>";
    }
} else {
    echo "<script>alert('ID inválido!'); window.location = 'consultar_cargos.php';</script>";
}

mysqli_close($conn);
?>