<?php
/**
 * Classe para validação de CPF no servidor
 * Integrada com o sistema ShieldTech
 */

class CPFValidator {
    
    /**
     * Valida se o CPF é válido
     * @param string $cpf
     * @return bool
     */
    public static function isValid($cpf) {
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
    
    /**
     * Formata o CPF com máscara
     * @param string $cpf
     * @return string
     */
    public static function format($cpf) {
        $cpf = preg_replace('/\D/', '', $cpf);
        if (strlen($cpf) == 11) {
            return preg_replace('/^(\d{3})(\d{3})(\d{3})(\d{2})$/', '$1.$2.$3-$4', $cpf);
        }
        return $cpf;
    }
    
    /**
     * Remove formatação do CPF
     * @param string $cpf
     * @return string
     */
    public static function clean($cpf) {
        return preg_replace('/\D/', '', $cpf);
    }
    
    /**
     * Verifica se CPF já existe no banco
     * @param mysqli $conn - Conexão com o banco
     * @param string $cpf - CPF a ser verificado
     * @param string $table - Tabela (moradores, funcionarios, visitantes)
     * @param int $excludeId - ID para excluir da verificação
     * @return bool
     */
    public static function exists($conn, $cpf, $table = 'moradores', $excludeId = null) {
        $cpf = self::clean($cpf);
        
        $field_mapping = [
            'moradores' => ['table' => 'tb_moradores', 'id_field' => 'id_moradores', 'cpf_field' => 'cpf'],
            'funcionarios' => ['table' => 'tb_funcionarios', 'id_field' => 'id_funcionarios', 'cpf_field' => 'cpf'],
            'visitantes' => ['table' => 'tb_visitantes', 'id_field' => 'id_visitantes', 'cpf_field' => 'num_documento']
        ];
        
        if (!isset($field_mapping[$table])) {
            return false;
        }
        
        $config = $field_mapping[$table];
        $cpf = mysqli_real_escape_string($conn, $cpf);
        
        $sql = "SELECT {$config['id_field']} FROM {$config['table']} WHERE {$config['cpf_field']} = '$cpf'";
        if ($excludeId) {
            $excludeId = mysqli_real_escape_string($conn, $excludeId);
            $sql .= " AND {$config['id_field']} != '$excludeId'";
        }
        
        $result = mysqli_query($conn, $sql);
        return mysqli_num_rows($result) > 0;
    }
    
    /**
     * Valida CPF e retorna mensagem de erro se inválido
     * @param string $cpf
     * @return array ['valid' => bool, 'message' => string]
     */
    public static function validate($cpf) {
        if (empty($cpf)) {
            return ['valid' => false, 'message' => 'CPF é obrigatório'];
        }
        
        $cleanCpf = self::clean($cpf);
        
        if (strlen($cleanCpf) != 11) {
            return ['valid' => false, 'message' => 'CPF deve ter 11 dígitos'];
        }
        
        if (!self::isValid($cleanCpf)) {
            return ['valid' => false, 'message' => 'CPF inválido'];
        }
        
        return ['valid' => true, 'message' => 'CPF válido'];
    }
    
    /**
     * Valida CPF completo incluindo verificação de duplicidade
     * @param mysqli $conn - Conexão com o banco
     * @param string $cpf - CPF a ser validado
     * @param string $table - Tabela para verificar duplicidade
     * @param int $excludeId - ID para excluir da verificação
     * @return array ['valid' => bool, 'message' => string]
     */
    public static function validateComplete($conn, $cpf, $table = 'moradores', $excludeId = null) {
        $validation = self::validate($cpf);
        
        if (!$validation['valid']) {
            return $validation;
        }
        
        if (self::exists($conn, $cpf, $table, $excludeId)) {
            return ['valid' => false, 'message' => 'Este CPF já está cadastrado'];
        }
        
        return ['valid' => true, 'message' => 'CPF válido e disponível'];
    }
}

/**
 * Função auxiliar para validação rápida
 * @param string $cpf
 * @return bool
 */
function validarCPF($cpf) {
    return CPFValidator::isValid($cpf);
}

/**
 * Função auxiliar para formatação rápida
 * @param string $cpf
 * @return string
 */
function formatarCPF($cpf) {
    return CPFValidator::format($cpf);
}

/**
 * Função auxiliar para limpeza rápida
 * @param string $cpf
 * @return string
 */
function limparCPF($cpf) {
    return CPFValidator::clean($cpf);
}
?>