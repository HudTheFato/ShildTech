# Sistema de Email - ShieldTech

Este documento explica como configurar e usar o sistema de envio de emails do ShieldTech.

## 📁 Arquivos do Sistema

- `php/email-config.php` - Configurações de email
- `php/email-sender.php` - Classe principal para envio
- `php/email-template.php` - Templates HTML dos emails
- `php/email-test.php` - Arquivo para testes

## 🚀 Configuração Rápida

### 1. Configuração Básica (mail() nativo)
O sistema já funciona com a função `mail()` nativa do PHP. Nenhuma configuração adicional é necessária.

### 2. Configuração SMTP (Recomendado)
Para melhor confiabilidade, configure SMTP no arquivo `php/email-config.php`:

```php
const SMTP_HOST = 'smtp.gmail.com';
const SMTP_PORT = 587;
const SMTP_USERNAME = 'seu-email@gmail.com';
const SMTP_PASSWORD = 'sua-senha-app';
```

## 📧 Como Usar

### Envio Básico
```php
require_once 'php/email-sender.php';

$emailSender = new EmailSender(false); // false = mail() nativo
$success = $emailSender->send(
    'destinatario@email.com',
    'Assunto',
    'Corpo do email',
    'Nome do Destinatário'
);
```

### Envio de Confirmação de Reserva
```php
$reservaData = [
    'local' => 'Churrasqueira 1',
    'data' => '2025-01-15',
    'horario' => '14:00',
    'tempo_duracao' => '3 horas',
    'descricao' => 'Observações'
];

$moradorData = [
    'nome' => 'João Silva',
    'email' => 'joao@email.com',
    'bloco' => 'A',
    'torre' => '1'
];

$emailSender = new EmailSender();
$success = $emailSender->sendReservaConfirmation($reservaData, $moradorData);
```

## 🔧 Configuração SMTP Detalhada

### Gmail
1. Ative a verificação em 2 etapas
2. Gere uma senha de app
3. Configure em `email-config.php`:
```php
const SMTP_HOST = 'smtp.gmail.com';
const SMTP_PORT = 587;
const SMTP_USERNAME = 'seu-email@gmail.com';
const SMTP_PASSWORD = 'sua-senha-app-16-digitos';
const SMTP_SECURE = 'tls';
```

### Outros Provedores
- **Outlook/Hotmail**: `smtp-mail.outlook.com:587`
- **Yahoo**: `smtp.mail.yahoo.com:587`
- **Servidor próprio**: Configure conforme seu provedor

## 🧪 Testando o Sistema

1. Acesse `php/email-test.php` no navegador
2. Verifique as configurações
3. Descomente o código de teste
4. Substitua o email de teste pelo seu
5. Execute o teste

## 📋 Templates Disponíveis

### Confirmação de Reserva
- Design responsivo
- Detalhes completos da reserva
- Regras e lembretes
- Branding ShieldTech

### Cancelamento de Reserva
- Notificação de cancelamento
- Detalhes da reserva cancelada
- Opções para nova reserva

### Lembrete de Reserva
- Enviado 24h antes
- Lembrete amigável
- Detalhes da reserva

## 🔍 Solução de Problemas

### Email não está sendo enviado
1. Verifique se o PHP está configurado para enviar emails
2. Verifique os logs do servidor
3. Teste com `php/email-test.php`
4. Configure SMTP se estiver usando mail() nativo

### Emails indo para spam
1. Configure SPF, DKIM e DMARC no seu domínio
2. Use SMTP autenticado
3. Evite palavras que ativam filtros de spam
4. Mantenha uma boa reputação do IP/domínio

### Erro de autenticação SMTP
1. Verifique usuário e senha
2. Use senha de app (Gmail)
3. Verifique se SMTP está habilitado
4. Teste configurações manualmente

## 🔒 Segurança

- Senhas são armazenadas em arquivo de configuração
- Use senhas de app específicas
- Não commite credenciais no Git
- Configure firewall para SMTP se necessário

## 📈 Melhorias Futuras

- [ ] Fila de emails para envio assíncrono
- [ ] Templates personalizáveis via admin
- [ ] Estatísticas de entrega
- [ ] Integração com serviços de email marketing
- [ ] Logs detalhados de envio

## 🆘 Suporte

Para problemas ou dúvidas:
1. Verifique este README
2. Execute `php/email-test.php`
3. Consulte logs do servidor
4. Entre em contato com o suporte técnico