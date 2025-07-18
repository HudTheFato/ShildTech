<?php
include("../../conectarbd.php");

$id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);

if ($id) {
    $sql = "DELETE FROM tb_funcionarios WHERE id_funcionarios = $id";
    
    if (mysqli_query($conn, $sql)) {
        echo "<script>alert('Funcionário excluído com sucesso!'); window.location = 'consultar_funcionarios.php';</script>";
    } else {
        echo "<script>alert('Erro ao excluir funcionário: " . mysqli_error($conn) . "'); window.location = 'consultar_funcionarios.php';</script>";
    }
} else {
    echo "<script>alert('ID inválido!'); window.location = 'consultar_funcionarios.php';</script>";
}

mysqli_close($conn);
?>