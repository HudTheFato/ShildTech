<?php
/**
 * Arquivo de teste para o sistema de email
 * Use este arquivo para testar se o envio de emails está funcionando
 */

require_once 'email-sender.php';
require_once 'email-template.php';

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
    'email' => 'teste@exemplo.com', // SUBSTITUA pelo seu email para teste
    'bloco' => 'A',
    'torre' => '1'
];

echo "<h1>Teste do Sistema de Email - ShieldTech</h1>";

// Teste 1: Verificar configurações
echo "<h2>1. Verificando Configurações</h2>";
$config = EmailConfig::getConfig();
$isConfigured = EmailConfig::isSmtpConfigured();

echo "<p><strong>SMTP Configurado:</strong> " . ($isConfigured ? "✅ Sim" : "❌ Não (usando mail() nativo)") . "</p>";
echo "<p><strong>Email Remetente:</strong> " . $config['from_email'] . "</p>";
echo "<p><strong>Nome Remetente:</strong> " . $config['from_name'] . "</p>";

// Teste 2: Gerar template
echo "<h2>2. Gerando Template de Email</h2>";
$template = new EmailTemplate();
$htmlContent = $template->getReservaConfirmationTemplate($reservaData, $moradorData);
echo "<p>✅ Template gerado com sucesso!</p>";

// Teste 3: Enviar email de teste (descomente para testar)
/*
echo "<h2>3. Enviando Email de Teste</h2>";
$emailSender = new EmailSender(false); // false = mail() nativo, true = PHPMailer

if ($emailSender->sendReservaConfirmation($reservaData, $moradorData)) {
    echo "<p style='color: green;'>✅ Email enviado com sucesso!</p>";
} else {
    echo "<p style='color: red;'>❌ Erro ao enviar email. Verifique os logs do servidor.</p>";
}
*/

echo "<h2>4. Preview do Email</h2>";
echo "<div style='border: 1px solid #ccc; padding: 10px; margin: 10px 0;'>";
echo $htmlContent;
echo "</div>";

echo "<h2>5. Instruções</h2>";
echo "<ol>";
echo "<li>Para testar o envio, descomente o código na seção 'Teste 3'</li>";
echo "<li>Substitua o email de teste pelo seu email real</li>";
echo "<li>Configure SMTP em php/email-config.php para melhor confiabilidade</li>";
echo "<li>Verifique os logs do servidor em caso de erro</li>";
echo "</ol>";

echo "<h2>6. Configuração SMTP (Recomendado)</h2>";
echo "<p>Para usar Gmail SMTP:</p>";
echo "<ol>";
echo "<li>Ative a verificação em 2 etapas na sua conta Google</li>";
echo "<li>Gere uma senha de app específica</li>";
echo "<li>Configure as credenciais em php/email-config.php</li>";
echo "<li>Use EmailSender(true) para ativar SMTP</li>";
echo "</ol>";
?>