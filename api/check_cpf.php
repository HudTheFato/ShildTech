<?php
header('Content-Type: application/json');
include("../conectarbd.php");

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action']) && $_POST['action'] == 'check_cpf') {
    $cpf = mysqli_real_escape_string($conn, $_POST["cpf"]);
    $table = mysqli_real_escape_string($conn, $_POST["table"]);
    $exclude_id = isset($_POST['exclude_id']) ? mysqli_real_escape_string($conn, $_POST['exclude_id']) : null;
    
    // Validar CPF no servidor
    if (!validarCPF($cpf)) {
        echo json_encode(['exists' => false, 'error' => 'CPF inválido']);
        exit;
    }
    
    // Definir campos e tabelas baseado no tipo
    $field_mapping = [
        'moradores' => ['table' => 'tb_moradores', 'id_field' => 'id_moradores', 'cpf_field' => 'cpf'],
        'funcionarios' => ['table' => 'tb_funcionarios', 'id_field' => 'id_funcionarios', 'cpf_field' => 'cpf'],
        'visitantes' => ['table' => 'tb_visitantes', 'id_field' => 'id_visitantes', 'cpf_field' => 'num_documento']
    ];
    
    if (!isset($field_mapping[$table])) {
        echo json_encode(['error' => 'Tabela inválida']);
        exit;
    }
    
    $config = $field_mapping[$table];
    
    // Construir query
    $sql = "SELECT {$config['id_field']} FROM {$config['table']} WHERE {$config['cpf_field']} = '$cpf'";
    if ($exclude_id) {
        $sql .= " AND {$config['id_field']} != '$exclude_id'";
    }
    
    $result = mysqli_query($conn, $sql);
    $exists = mysqli_num_rows($result) > 0;
    
    echo json_encode(['exists' => $exists]);
} else {
    echo json_encode(['error' => 'Requisição inválida']);
}

/**
 * Função para validar CPF no servidor
 * @param string $cpf
 * @return bool
 */
function validarCPF($cpf) {
    // Remove caracteres não numéricos
    $cpf = preg_replace('/\D/', '', $cpf);
    
    // Verifica se tem 11 dígitos
    if (strlen($cpf) != 11) return false;
    
    // Verifica se todos os dígitos são iguais
    if (preg_match('/^(\d)\1{10}$/', $cpf)) return false;
    
    // Validação do primeiro dígito verificador
    $sum = 0;
    for ($i = 0; $i < 9; $i++) {
        $sum += intval($cpf[$i]) * (10 - $i);
    }
    $remainder = ($sum * 10) % 11;
    if ($remainder == 10 || $remainder == 11) $remainder = 0;
    if ($remainder != intval($cpf[9])) return false;
    
    // Validação do segundo dígito verificador
    $sum = 0;
    for ($i = 0; $i < 10; $i++) {
        $sum += intval($cpf[$i]) * (11 - $i);
    }
    $remainder = ($sum * 10) % 11;
    if ($remainder == 10 || $remainder == 11) $remainder = 0;
    if ($remainder != intval($cpf[10])) return false;
    
    return true;
}

mysqli_close($conn);
?>