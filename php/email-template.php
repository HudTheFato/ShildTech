<?php
/**
 * Templates de Email para o Sistema ShieldTech
 * Classe responsável por gerar os templates HTML dos emails
 */

class EmailTemplate {
    
    /**
     * Template base para todos os emails
     * @param string $title Título do email
     * @param string $content Conteúdo principal
     * @return string
     */
    private function getBaseTemplate($title, $content) {
        return "
        <!DOCTYPE html>
        <html lang='pt-BR'>
        <head>
            <meta charset='UTF-8'>
            <meta name='viewport' content='width=device-width, initial-scale=1.0'>
            <title>$title</title>
            <style>
                body {
                    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
                    line-height: 1.6;
                    color: #333;
                    margin: 0;
                    padding: 0;
                    background-color: #f4f4f4;
                }
                .container {
                    max-width: 600px;
                    margin: 0 auto;
                    background-color: #ffffff;
                    box-shadow: 0 0 10px rgba(0,0,0,0.1);
                }
                .header {
                    background: linear-gradient(135deg, #2c3e50, #3498db);
                    color: white;
                    padding: 30px 20px;
                    text-align: center;
                }
                .header h1 {
                    margin: 0;
                    font-size: 28px;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    gap: 10px;
                }
                .header .shield-icon {
                    font-size: 32px;
                }
                .content {
                    padding: 30px 20px;
                }
                .details-box {
                    background: #f8f9fa;
                    border-left: 4px solid #3498db;
                    padding: 20px;
                    margin: 20px 0;
                    border-radius: 0 8px 8px 0;
                }
                .details-box h3 {
                    margin-top: 0;
                    color: #2c3e50;
                    font-size: 18px;
                }
                .detail-item {
                    display: flex;
                    margin-bottom: 10px;
                    align-items: center;
                }
                .detail-label {
                    font-weight: bold;
                    min-width: 120px;
                    color: #2c3e50;
                }
                .detail-value {
                    color: #555;
                }
                .icon {
                    margin-right: 8px;
                    color: #3498db;
                    width: 16px;
                }
                .alert-box {
                    background: #e8f4fd;
                    border: 1px solid #3498db;
                    border-radius: 8px;
                    padding: 15px;
                    margin: 20px 0;
                }
                .alert-box.warning {
                    background: #fff3cd;
                    border-color: #ffc107;
                    color: #856404;
                }
                .alert-box.success {
                    background: #d4edda;
                    border-color: #28a745;
                    color: #155724;
                }
                .alert-box.danger {
                    background: #f8d7da;
                    border-color: #dc3545;
                    color: #721c24;
                }
                .footer {
                    background: #2c3e50;
                    color: white;
                    padding: 20px;
                    text-align: center;
                    font-size: 14px;
                }
                .footer a {
                    color: #3498db;
                    text-decoration: none;
                }
                .btn {
                    display: inline-block;
                    padding: 12px 24px;
                    background: #3498db;
                    color: white;
                    text-decoration: none;
                    border-radius: 6px;
                    margin: 10px 0;
                    font-weight: bold;
                }
                .btn:hover {
                    background: #2980b9;
                }
                .rules-list {
                    background: #f8f9fa;
                    padding: 15px;
                    border-radius: 8px;
                    margin: 15px 0;
                }
                .rules-list ul {
                    margin: 0;
                    padding-left: 20px;
                }
                .rules-list li {
                    margin-bottom: 8px;
                    color: #555;
                }
                @media (max-width: 600px) {
                    .container {
                        margin: 0;
                        box-shadow: none;
                    }
                    .content {
                        padding: 20px 15px;
                    }
                    .detail-item {
                        flex-direction: column;
                        align-items: flex-start;
                    }
                    .detail-label {
                        min-width: auto;
                        margin-bottom: 5px;
                    }
                }
            </style>
        </head>
        <body>
            <div class='container'>
                <div class='header'>
                    <h1>
                        <span class='shield-icon'>🛡️</span>
                        ShieldTech
                    </h1>
                    <p style='margin: 10px 0 0 0; opacity: 0.9;'>$title</p>
                </div>
                <div class='content'>
                    $content
                </div>
                <div class='footer'>
                    <p>Este é um email automático, não responda.</p>
                    <p>© " . date('Y') . " ShieldTech - Sistema de Controle de Acesso</p>
                    <p>Dúvidas? Entre em contato: <a href='mailto:contato@shieldtech.com'>contato@shieldtech.com</a></p>
                </div>
            </div>
        </body>
        </html>";
    }
    
    /**
     * Template para confirmação de reserva
     * @param array $reserva Dados da reserva
     * @param array $morador Dados do morador
     * @return string
     */
    public function getReservaConfirmationTemplate($reserva, $morador) {
        $dataFormatada = date('d/m/Y', strtotime($reserva['data']));
        
        $content = "
            <p>Olá <strong>{$morador['nome']}</strong>,</p>
            <p>Sua reserva foi <strong>confirmada com sucesso</strong>! 🎉</p>
            
            <div class='details-box'>
                <h3>📋 Detalhes da Reserva</h3>
                <div class='detail-item'>
                    <span class='icon'>📍</span>
                    <span class='detail-label'>Local:</span>
                    <span class='detail-value'>{$reserva['local']}</span>
                </div>
                <div class='detail-item'>
                    <span class='icon'>📅</span>
                    <span class='detail-label'>Data:</span>
                    <span class='detail-value'>$dataFormatada</span>
                </div>
                <div class='detail-item'>
                    <span class='icon'>🕐</span>
                    <span class='detail-label'>Horário:</span>
                    <span class='detail-value'>{$reserva['horario']}</span>
                </div>
                <div class='detail-item'>
                    <span class='icon'>⏱️</span>
                    <span class='detail-label'>Duração:</span>
                    <span class='detail-value'>{$reserva['tempo_duracao']}</span>
                </div>";
        
        if (!empty($reserva['descricao'])) {
            $content .= "
                <div class='detail-item'>
                    <span class='icon'>📝</span>
                    <span class='detail-label'>Observações:</span>
                    <span class='detail-value'>{$reserva['descricao']}</span>
                </div>";
        }
        
        $content .= "
            </div>
            
            <div class='alert-box success'>
                <strong>✅ Reserva Confirmada!</strong><br>
                Sua reserva está garantida. Lembre-se de chegar no horário marcado.
            </div>
            
            <div class='rules-list'>
                <h4>📋 Lembrete Importante:</h4>
                <ul>
                    <li>Chegue no horário marcado</li>
                    <li>Deixe o local limpo após o uso</li>
                    <li>Em caso de cancelamento, avise com antecedência mínima de 12h</li>
                    <li>Respeite as regras do condomínio</li>
                    <li>Dúvidas? Entre em contato conosco</li>
                </ul>
            </div>
            
            <div class='alert-box'>
                <strong>💡 Dica:</strong> Salve este email para consultar os detalhes da sua reserva.
            </div>
            
            <p>Agradecemos por utilizar nossos serviços!</p>
            <p><strong>Equipe ShieldTech</strong></p>";
        
        return $this->getBaseTemplate('Confirmação de Reserva', $content);
    }
    
    /**
     * Template para cancelamento de reserva
     * @param array $reserva Dados da reserva
     * @param array $morador Dados do morador
     * @return string
     */
    public function getReservaCancelamentoTemplate($reserva, $morador) {
        $dataFormatada = date('d/m/Y', strtotime($reserva['data']));
        
        $content = "
            <p>Olá <strong>{$morador['nome']}</strong>,</p>
            <p>Informamos que sua reserva foi <strong>cancelada</strong>.</p>
            
            <div class='details-box'>
                <h3>📋 Detalhes da Reserva Cancelada</h3>
                <div class='detail-item'>
                    <span class='icon'>📍</span>
                    <span class='detail-label'>Local:</span>
                    <span class='detail-value'>{$reserva['local']}</span>
                </div>
                <div class='detail-item'>
                    <span class='icon'>📅</span>
                    <span class='detail-label'>Data:</span>
                    <span class='detail-value'>$dataFormatada</span>
                </div>
                <div class='detail-item'>
                    <span class='icon'>🕐</span>
                    <span class='detail-label'>Horário:</span>
                    <span class='detail-value'>{$reserva['horario']}</span>
                </div>
                <div class='detail-item'>
                    <span class='icon'>⏱️</span>
                    <span class='detail-label'>Duração:</span>
                    <span class='detail-value'>{$reserva['tempo_duracao']}</span>
                </div>
            </div>
            
            <div class='alert-box danger'>
                <strong>❌ Reserva Cancelada</strong><br>
                O horário está novamente disponível para outros moradores.
            </div>
            
            <div class='alert-box'>
                <strong>💡 Quer fazer uma nova reserva?</strong><br>
                Acesse o sistema ou entre em contato conosco para agendar um novo horário.
            </div>
            
            <p>Se você não solicitou este cancelamento, entre em contato conosco imediatamente.</p>
            <p><strong>Equipe ShieldTech</strong></p>";
        
        return $this->getBaseTemplate('Cancelamento de Reserva', $content);
    }
    
    /**
     * Template para lembrete de reserva (24h antes)
     * @param array $reserva Dados da reserva
     * @param array $morador Dados do morador
     * @return string
     */
    public function getReservaReminderTemplate($reserva, $morador) {
        $dataFormatada = date('d/m/Y', strtotime($reserva['data']));
        
        $content = "
            <p>Olá <strong>{$morador['nome']}</strong>,</p>
            <p>Este é um lembrete sobre sua reserva para <strong>amanhã</strong>! ⏰</p>
            
            <div class='details-box'>
                <h3>📋 Detalhes da Sua Reserva</h3>
                <div class='detail-item'>
                    <span class='icon'>📍</span>
                    <span class='detail-label'>Local:</span>
                    <span class='detail-value'>{$reserva['local']}</span>
                </div>
                <div class='detail-item'>
                    <span class='icon'>📅</span>
                    <span class='detail-label'>Data:</span>
                    <span class='detail-value'>$dataFormatada</span>
                </div>
                <div class='detail-item'>
                    <span class='icon'>🕐</span>
                    <span class='detail-label'>Horário:</span>
                    <span class='detail-value'>{$reserva['horario']}</span>
                </div>
                <div class='detail-item'>
                    <span class='icon'>⏱️</span>
                    <span class='detail-label'>Duração:</span>
                    <span class='detail-value'>{$reserva['tempo_duracao']}</span>
                </div>
            </div>
            
            <div class='alert-box warning'>
                <strong>⏰ Lembrete:</strong> Sua reserva é amanhã! Não se esqueça.
            </div>
            
            <p>Estamos ansiosos para que você aproveite o espaço reservado!</p>
            <p><strong>Equipe ShieldTech</strong></p>";
        
        return $this->getBaseTemplate('Lembrete de Reserva', $content);
    }
}
?>