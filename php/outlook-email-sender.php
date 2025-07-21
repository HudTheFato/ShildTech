<?php
/**
 * Classe para envio de emails usando Outlook local
 * Funciona mesmo com servidores de email bloqueados
 */

class OutlookEmailSender {
    private $config;
    
    public function __construct() {
        $this->config = [
            'from_email' => 'noreply@shieldtech.com',
            'from_name' => 'ShieldTech - Sistema de Controle',
            'charset' => 'UTF-8'
        ];
    }
    
    /**
     * Envia email usando COM (Component Object Model) do Windows/Outlook
     * @param string $to Email do destinatário
     * @param string $subject Assunto
     * @param string $body Corpo do email (HTML)
     * @param string $toName Nome do destinatário
     * @return bool
     */
    public function sendWithOutlookCOM($to, $subject, $body, $toName = '') {
        try {
            // Verificar se COM está disponível (Windows)
            if (!class_exists('COM')) {
                throw new Exception('COM não está disponível. Esta funcionalidade requer Windows.');
            }
            
            // Criar instância do Outlook
            $outlook = new COM("Outlook.Application");
            $mail = $outlook->CreateItem(0); // 0 = olMailItem
            
            // Configurar email
            $mail->To = $to;
            $mail->Subject = $subject;
            $mail->HTMLBody = $body;
            
            // Enviar email
            $mail->Send();
            
            // Liberar recursos
            $mail = null;
            $outlook = null;
            
            return true;
            
        } catch (Exception $e) {
            error_log("Erro ao enviar email com Outlook COM: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Cria arquivo .eml que pode ser aberto no Outlook
     * @param string $to Email do destinatário
     * @param string $subject Assunto
     * @param string $body Corpo do email (HTML)
     * @param string $toName Nome do destinatário
     * @return string|false Caminho do arquivo criado ou false em caso de erro
     */
    public function createEMLFile($to, $subject, $body, $toName = '') {
        try {
            // Criar diretório para emails se não existir
            $emailDir = __DIR__ . '/../emails/';
            if (!is_dir($emailDir)) {
                mkdir($emailDir, 0755, true);
            }
            
            // Nome do arquivo único
            $filename = 'email_' . date('Y-m-d_H-i-s') . '_' . uniqid() . '.eml';
            $filepath = $emailDir . $filename;
            
            // Cabeçalhos do email
            $headers = [];
            $headers[] = "From: " . $this->config['from_name'] . " <" . $this->config['from_email'] . ">";
            $headers[] = "To: " . ($toName ? "$toName <$to>" : $to);
            $headers[] = "Subject: " . $subject;
            $headers[] = "Date: " . date('r');
            $headers[] = "MIME-Version: 1.0";
            $headers[] = "Content-Type: text/html; charset=" . $this->config['charset'];
            $headers[] = "Content-Transfer-Encoding: quoted-printable";
            $headers[] = "X-Mailer: ShieldTech Email System";
            $headers[] = "X-Priority: 3";
            $headers[] = "";
            
            // Codificar corpo do email
            $encodedBody = quoted_printable_encode($body);
            
            // Criar conteúdo do arquivo .eml
            $emlContent = implode("\r\n", $headers) . "\r\n" . $encodedBody;
            
            // Salvar arquivo
            if (file_put_contents($filepath, $emlContent)) {
                return $filepath;
            } else {
                return false;
            }
            
        } catch (Exception $e) {
            error_log("Erro ao criar arquivo EML: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Cria um link para download do arquivo .eml
     * @param string $to Email do destinatário
     * @param string $subject Assunto
     * @param string $body Corpo do email (HTML)
     * @param string $toName Nome do destinatário
     * @return array Informações do arquivo criado
     */
    public function createDownloadableEmail($to, $subject, $body, $toName = '') {
        $filepath = $this->createEMLFile($to, $subject, $body, $toName);
        
        if ($filepath) {
            $filename = basename($filepath);
            $downloadUrl = '../emails/' . $filename;
            
            return [
                'success' => true,
                'filepath' => $filepath,
                'filename' => $filename,
                'download_url' => $downloadUrl,
                'message' => 'Arquivo de email criado com sucesso!'
            ];
        } else {
            return [
                'success' => false,
                'message' => 'Erro ao criar arquivo de email.'
            ];
        }
    }
    
    /**
     * Método principal que tenta diferentes abordagens
     * @param string $to Email do destinatário
     * @param string $subject Assunto
     * @param string $body Corpo do email (HTML)
     * @param string $toName Nome do destinatário
     * @return array Resultado do envio
     */
    public function send($to, $subject, $body, $toName = '') {
        // Validar email
        if (!filter_var($to, FILTER_VALIDATE_EMAIL)) {
            return [
                'success' => false,
                'method' => 'validation',
                'message' => 'Email inválido: ' . $to
            ];
        }
        
        // Tentar COM primeiro (se disponível)
        if (class_exists('COM') && PHP_OS_FAMILY === 'Windows') {
            if ($this->sendWithOutlookCOM($to, $subject, $body, $toName)) {
                return [
                    'success' => true,
                    'method' => 'outlook_com',
                    'message' => 'Email enviado via Outlook COM'
                ];
            }
        }
        
        // Fallback: criar arquivo .eml
        $result = $this->createDownloadableEmail($to, $subject, $body, $toName);
        $result['method'] = 'eml_file';
        
        return $result;
    }
    
    /**
     * Envia email de confirmação de reserva
     * @param array $reservaData Dados da reserva
     * @param array $moradorData Dados do morador
     * @return array Resultado do envio
     */
    public function sendReservaConfirmation($reservaData, $moradorData) {
        require_once 'email-template.php';
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
     * @return array Resultado do envio
     */
    public function sendReservaCancelamento($reservaData, $moradorData) {
        require_once 'email-template.php';
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
    
    /**
     * Lista arquivos .eml criados
     * @return array Lista de arquivos
     */
    public function listEmailFiles() {
        $emailDir = __DIR__ . '/../emails/';
        $files = [];
        
        if (is_dir($emailDir)) {
            $fileList = scandir($emailDir);
            foreach ($fileList as $file) {
                if (pathinfo($file, PATHINFO_EXTENSION) === 'eml') {
                    $filepath = $emailDir . $file;
                    $files[] = [
                        'filename' => $file,
                        'filepath' => $filepath,
                        'size' => filesize($filepath),
                        'created' => filemtime($filepath),
                        'download_url' => '../emails/' . $file
                    ];
                }
            }
            
            // Ordenar por data de criação (mais recente primeiro)
            usort($files, function($a, $b) {
                return $b['created'] - $a['created'];
            });
        }
        
        return $files;
    }
    
    /**
     * Limpa arquivos .eml antigos (mais de 7 dias)
     * @return int Número de arquivos removidos
     */
    public function cleanOldEmailFiles() {
        $emailDir = __DIR__ . '/../emails/';
        $removed = 0;
        $maxAge = 7 * 24 * 60 * 60; // 7 dias em segundos
        
        if (is_dir($emailDir)) {
            $fileList = scandir($emailDir);
            foreach ($fileList as $file) {
                if (pathinfo($file, PATHINFO_EXTENSION) === 'eml') {
                    $filepath = $emailDir . $file;
                    if (time() - filemtime($filepath) > $maxAge) {
                        if (unlink($filepath)) {
                            $removed++;
                        }
                    }
                }
            }
        }
        
        return $removed;
    }
}
?>