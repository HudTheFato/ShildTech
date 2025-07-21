<?php
/**
 * Arquivo de teste para o sistema de email com Outlook local
 */

require_once 'outlook-email-sender.php';

// Dados de teste
$reservaData = [
    'local' => 'Churrasqueira 1',
    'data' => '2025-01-15',
    'horario' => '14:00',
    'tempo_duracao' => '3 horas',
    'descricao' => 'Festa de aniversário da família'
];

$moradorData = [
    'nome' => 'João Silva',
    'email' => 'teste@exemplo.com', // SUBSTITUA pelo email real para teste
    'bloco' => 'A',
    'torre' => '1'
];

?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teste Sistema Email Outlook - ShieldTech</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; line-height: 1.6; }
        .container { max-width: 800px; margin: 0 auto; }
        .status { padding: 10px; margin: 10px 0; border-radius: 5px; }
        .success { background: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        .error { background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
        .info { background: #d1ecf1; color: #0c5460; border: 1px solid #bee5eb; }
        .warning { background: #fff3cd; color: #856404; border: 1px solid #ffeaa7; }
        .file-list { background: #f8f9fa; padding: 15px; border-radius: 5px; margin: 10px 0; }
        .file-item { padding: 8px; margin: 5px 0; background: white; border-radius: 3px; display: flex; justify-content: space-between; align-items: center; }
        .btn { padding: 8px 16px; background: #007bff; color: white; text-decoration: none; border-radius: 4px; display: inline-block; margin: 5px; }
        .btn:hover { background: #0056b3; }
        .btn-success { background: #28a745; }
        .btn-danger { background: #dc3545; }
        .btn-warning { background: #ffc107; color: #212529; }
    </style>
</head>
<body>
    <div class="container">
        <h1>🧪 Teste Sistema Email Outlook - ShieldTech</h1>
        
        <div class="info status">
            <strong>ℹ️ Informação:</strong> Este sistema funciona mesmo com servidores de email bloqueados!
        </div>

        <?php
        $outlookSender = new OutlookEmailSender();
        
        // Verificar ambiente
        echo "<h2>1. 🔍 Verificação do Ambiente</h2>";
        
        echo "<div class='status " . (PHP_OS_FAMILY === 'Windows' ? 'success' : 'warning') . "'>";
        echo "<strong>Sistema Operacional:</strong> " . PHP_OS_FAMILY;
        if (PHP_OS_FAMILY !== 'Windows') {
            echo " (COM do Outlook não disponível, mas arquivos .eml funcionarão)";
        }
        echo "</div>";
        
        echo "<div class='status " . (class_exists('COM') ? 'success' : 'info') . "'>";
        echo "<strong>COM (Outlook Integration):</strong> " . (class_exists('COM') ? '✅ Disponível' : '❌ Não disponível');
        echo "</div>";
        
        echo "<div class='status " . (is_writable(__DIR__ . '/../') ? 'success' : 'error') . "'>";
        echo "<strong>Permissão de Escrita:</strong> " . (is_writable(__DIR__ . '/../') ? '✅ OK' : '❌ Sem permissão');
        echo "</div>";
        
        // Teste de envio
        if (isset($_POST['test_send'])) {
            echo "<h2>2. 📧 Resultado do Teste de Envio</h2>";
            
            $result = $outlookSender->sendReservaConfirmation($reservaData, $moradorData);
            
            if ($result['success']) {
                echo "<div class='status success'>";
                echo "<strong>✅ Sucesso!</strong><br>";
                echo "<strong>Método:</strong> " . $result['method'] . "<br>";
                echo "<strong>Mensagem:</strong> " . $result['message'];
                
                if (isset($result['download_url'])) {
                    echo "<br><a href='" . $result['download_url'] . "' class='btn btn-success' download>📥 Baixar Email (.eml)</a>";
                    echo "<br><small>Clique duas vezes no arquivo baixado para abrir no Outlook</small>";
                }
                
                echo "</div>";
            } else {
                echo "<div class='status error'>";
                echo "<strong>❌ Erro!</strong><br>";
                echo "<strong>Método:</strong> " . $result['method'] . "<br>";
                echo "<strong>Mensagem:</strong> " . $result['message'];
                echo "</div>";
            }
        }
        
        // Listar arquivos de email
        echo "<h2>3. 📁 Arquivos de Email Criados</h2>";
        $emailFiles = $outlookSender->listEmailFiles();
        
        if (empty($emailFiles)) {
            echo "<div class='info status'>Nenhum arquivo de email encontrado.</div>";
        } else {
            echo "<div class='file-list'>";
            echo "<h4>📋 Arquivos Disponíveis (" . count($emailFiles) . "):</h4>";
            
            foreach ($emailFiles as $file) {
                echo "<div class='file-item'>";
                echo "<div>";
                echo "<strong>" . $file['filename'] . "</strong><br>";
                echo "<small>Criado: " . date('d/m/Y H:i:s', $file['created']) . " | ";
                echo "Tamanho: " . number_format($file['size'] / 1024, 2) . " KB</small>";
                echo "</div>";
                echo "<div>";
                echo "<a href='" . $file['download_url'] . "' class='btn' download>📥 Baixar</a>";
                echo "</div>";
                echo "</div>";
            }
            echo "</div>";
        }
        
        // Limpeza de arquivos antigos
        if (isset($_POST['clean_files'])) {
            $removed = $outlookSender->cleanOldEmailFiles();
            echo "<div class='status success'>";
            echo "<strong>🧹 Limpeza concluída!</strong> $removed arquivo(s) removido(s).";
            echo "</div>";
        }
        ?>
        
        <h2>4. 🎮 Ações de Teste</h2>
        
        <form method="post" style="margin: 20px 0;">
            <button type="submit" name="test_send" class="btn btn-success">
                📧 Testar Envio de Email
            </button>
            
            <button type="submit" name="clean_files" class="btn btn-warning" 
                    onclick="return confirm('Remover arquivos de email com mais de 7 dias?')">
                🧹 Limpar Arquivos Antigos
            </button>
        </form>
        
        <h2>5. 📖 Como Usar</h2>
        <div class="info status">
            <h4>🔧 Métodos Disponíveis:</h4>
            <ol>
                <li><strong>COM do Outlook (Windows):</strong> Envia diretamente pelo Outlook instalado</li>
                <li><strong>Arquivos .eml:</strong> Cria arquivos que podem ser abertos no Outlook</li>
            </ol>
            
            <h4>📋 Instruções:</h4>
            <ol>
                <li>Execute o teste acima</li>
                <li>Se usar arquivos .eml, baixe e clique duas vezes para abrir no Outlook</li>
                <li>O Outlook abrirá com o email pronto para envio</li>
                <li>Clique em "Enviar" no Outlook</li>
            </ol>
            
            <h4>⚙️ Configuração no Sistema:</h4>
            <p>Substitua <code>EmailSender</code> por <code>OutlookEmailSender</code> nas páginas de reserva.</p>
        </div>
        
        <h2>6. 🔧 Configuração Avançada</h2>
        <div class="warning status">
            <h4>Para Windows com Outlook:</h4>
            <ul>
                <li>Certifique-se que o Outlook está instalado</li>
                <li>Execute o PHP como administrador se necessário</li>
                <li>Configure o Outlook com uma conta de email</li>
            </ul>
            
            <h4>Para qualquer sistema:</h4>
            <ul>
                <li>Os arquivos .eml funcionam em qualquer cliente de email</li>
                <li>Podem ser abertos no Outlook, Thunderbird, etc.</li>
                <li>Basta clicar duas vezes no arquivo baixado</li>
            </ul>
        </div>
    </div>
</body>
</html>