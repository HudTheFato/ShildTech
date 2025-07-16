<?php
$servidor = "localhost";
$dbusuario = "root";
$dbsenha = "";
$dbname = "db_shieldtech";

$conn = mysqli_connect($servidor, $dbusuario, $dbsenha, $dbname);

if (!$conn) {
    die("Conexão falhou: " . mysqli_connect_error());
}

// Configurar charset para UTF-8
mysqli_set_charset($conn, "utf8");

// Incluir validador de CPF
include_once("php/cpf-validator.php");

// Verificar e criar colunas necessárias se não existirem
$check_reservas = mysqli_query($conn, "SHOW COLUMNS FROM tb_reservas LIKE 'id_morador'");
if (mysqli_num_rows($check_reservas) == 0) {
    mysqli_query($conn, "ALTER TABLE tb_reservas ADD COLUMN id_morador INT");
    mysqli_query($conn, "ALTER TABLE tb_reservas ADD FOREIGN KEY (id_morador) REFERENCES tb_moradores(id_moradores)");
}

$check_animais = mysqli_query($conn, "SHOW COLUMNS FROM tb_animais LIKE 'id_morador'");
if (mysqli_num_rows($check_animais) == 0) {
    mysqli_query($conn, "ALTER TABLE tb_animais ADD COLUMN id_morador INT");
    mysqli_query($conn, "ALTER TABLE tb_animais ADD FOREIGN KEY (id_morador) REFERENCES tb_moradores(id_moradores)");
}
?>