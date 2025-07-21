<?php
/**
 * Configurações de Email para o Sistema ShieldTech
 * Arquivo de configuração centralizado para envio de emails
 */

class EmailConfig {
    // Configurações SMTP (recomendado para produção)
    const SMTP_HOST = 'smtp.gmail.com'; // ou seu servidor SMTP
    const SMTP_PORT = 587;
    const SMTP_USERNAME = 'seu-email@gmail.com'; // Substitua pelo seu email
    const SMTP_PASSWORD = 'sua-senha-app'; // Substitua pela senha de app do Gmail
    const SMTP_SECURE = 'tls'; // 'tls' ou 'ssl'
    
    // Configurações do remetente
    const FROM_EMAIL = 'noreply@shieldtech.com';
    const FROM_NAME = 'ShieldTech - Sistema de Controle';
    const REPLY_TO = 'contato@shieldtech.com';
    
    // Configurações gerais
    const CHARSET = 'UTF-8';
    const IS_HTML = true;
    
    /**
     * Verifica se as configurações SMTP estão definidas
     * @return bool
     */
    public static function isSmtpConfigured() {
        return self::SMTP_USERNAME !== 'seu-email@gmail.com' && 
               self::SMTP_PASSWORD !== 'sua-senha-app';
    }
    
    /**
     * Retorna as configurações como array
     * @return array
     */
    public static function getConfig() {
        return [
            'smtp_host' => self::SMTP_HOST,
            'smtp_port' => self::SMTP_PORT,
            'smtp_username' => self::SMTP_USERNAME,
            'smtp_password' => self::SMTP_PASSWORD,
            'smtp_secure' => self::SMTP_SECURE,
            'from_email' => self::FROM_EMAIL,
            'from_name' => self::FROM_NAME,
            'reply_to' => self::REPLY_TO,
            'charset' => self::CHARSET,
            'is_html' => self::IS_HTML
        ];
    }
}
?>