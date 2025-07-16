<?php
header('Content-Type: application/json');
include("../conectarbd.php");

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action']) && $_POST['action'] == 'check_email') {
    $email = mysqli_real_escape_string($conn, $_POST["email"]);
    $exclude_id = isset($_POST['exclude_id']) ? mysqli_real_escape_string($conn, $_POST['exclude_id']) : null;
    
    // Construir query
    $sql = "SELECT id_moradores FROM tb_moradores WHERE email = '$email'";
    if ($exclude_id) {
        $sql .= " AND id_moradores != '$exclude_id'";
    }
    
    $result = mysqli_query($conn, $sql);
    $exists = mysqli_num_rows($result) > 0;
    
    echo json_encode(['exists' => $exists]);
} else {
    echo json_encode(['error' => 'Requisição inválida']);
}

mysqli_close($conn);
?>