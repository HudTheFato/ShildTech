<?php
/**
 * Classe para envio de emails do Sistema ShieldTech
 * Suporta tanto PHPMailer quanto mail() nativo
 */

require_once 'email-config.php';

class EmailSender {
    private $config;
    private $usePhpMailer;
    
    public function __construct($usePhpMailer = false) {
        $this->config = EmailConfig::getConfig();
        $this->usePhpMailer = $usePhpMailer;
    }
    
    /**
     * Envia email usando PHPMailer (recomendado)
     * @param string $to Email do destinatário
     * @param string $subject Assunto
     * @param string $body Corpo do email
     * @param string $toName Nome do destinatário
     * @return bool
     */
    private function sendWithPhpMailer($to, $subject, $body, $toName = '') {
        // Verificar se PHPMailer está disponível
        if (!class_exists('PHPMailer\PHPMailer\PHPMailer')) {
            error_log('PHPMailer não encontrado. Usando mail() nativo.');
            return $this->sendWithNativeMail($to, $subject, $body, $toName);
        }
        
        try {
            $mail = new PHPMailer\PHPMailer\PHPMailer(true);
            
            // Configurações do servidor
            $mail->isSMTP();
            $mail->Host = $this->config['smtp_host'];
            $mail->SMTPAuth = true;
            $mail->Username = $this->config['smtp_username'];
            $mail->Password = $this->config['smtp_password'];
            $mail->SMTPSecure = $this->config['smtp_secure'];
            $mail->Port = $this->config['smtp_port'];
            $mail->CharSet = $this->config['charset'];
            
            // Configurações do email
            $mail->setFrom($this->config['from_email'], $this->config['from_name']);
            $mail->addAddress($to, $toName);
            $mail->addReplyTo($this->config['reply_to'], $this->config['from_name']);
            
            $mail->isHTML($this->config['is_html']);
            $mail->Subject = $subject;
            $mail->Body = $body;
            
            $mail->send();
            return true;
            
        } catch (Exception $e) {
            error_log("Erro ao enviar email com PHPMailer: {$mail->ErrorInfo}");
            return false;
        }
    }
    
    /**
     * Envia email usando mail() nativo do PHP
     * @param string $to Email do destinatário
     * @param string $subject Assunto
     * @param string $body Corpo do email
     * @param string $toName Nome do destinatário
     * @return bool
     */
    private function sendWithNativeMail($to, $subject, $body, $toName = '') {
        try {
            // Cabeçalhos do email
            $headers = [];
            $headers[] = "MIME-Version: 1.0";
            $headers[] = "Content-Type: text/html; charset=" . $this->config['charset'];
            $headers[] = "From: " . $this->config['from_name'] . " <" . $this->config['from_email'] . ">";
            $headers[] = "Reply-To: " . $this->config['reply_to'];
            $headers[] = "X-Mailer: PHP/" . phpversion();
            $headers[] = "X-Priority: 3";
            
            $headerString = implode("\r\n", $headers);
            
            // Enviar email
            $result = mail($to, $subject, $body, $headerString);
            
            if (!$result) {
                error_log("Erro ao enviar email com mail() nativo para: $to");
            }
            
            return $result;
            
        } catch (Exception $e) {
            error_log("Erro ao enviar email: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Método principal para envio de email
     * @param string $to Email do destinatário
     * @param string $subject Assunto
     * @param string $body Corpo do email
     * @param string $toName Nome do destinatário
     * @return bool
     */
    public function send($to, $subject, $body, $toName = '') {
        // Validar email
        if (!filter_var($to, FILTER_VALIDATE_EMAIL)) {
            error_log("Email inválido: $to");
            return false;
        }
        
        // Escolher método de envio
        if ($this->usePhpMailer && EmailConfig::isSmtpConfigured()) {
            return $this->sendWithPhpMailer($to, $subject, $body, $toName);
        } else {
            return $this->sendWithNativeMail($to, $subject, $body, $toName);
        }
    }
    
    /**
     * Envia email de confirmação de reserva
     * @param array $reservaData Dados da reserva
     * @param array $moradorData Dados do morador
     * @return bool
     */
    public function sendReservaConfirmation($reservaData, $moradorData) {
        $template = new EmailTemplate();
        
        $subject = "Confirmação de Reserva - ShieldTech";
        $body = $template->getReservaConfirmationTemplate($reservaData, $moradorData);
        
        return $this->send(
            $moradorData['email'],
            $subject,
            $body,
            $moradorData['nome']
        );
    }
    
    /**
     * Envia email de cancelamento de reserva
     * @param array $reservaData Dados da reserva
     * @param array $moradorData Dados do morador
     * @return bool
     */
    public function sendReservaCancelamento($reservaData, $moradorData) {
        $template = new EmailTemplate();
        
        $subject = "Cancelamento de Reserva - ShieldTech";
        $body = $template->getReservaCancelamentoTemplate($reservaData, $moradorData);
        
        return $this->send(
            $moradorData['email'],
            $subject,
            $body,
            $moradorData['nome']
        );
    }
}
?>