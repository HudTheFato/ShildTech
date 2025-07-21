<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerenciador de Emails - ShieldTech</title>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .email-manager { max-width: 1200px; margin: 0 auto; padding: 20px; }
        .email-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin: 20px 0; }
        .email-card { background: white; padding: 20px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        .file-list { max-height: 400px; overflow-y: auto; }
        .file-item { 
            display: flex; justify-content: space-between; align-items: center;
            padding: 10px; margin: 5px 0; background: #f8f9fa; border-radius: 5px;
            border-left: 4px solid #007bff;
        }
        .file-info { flex: 1; }
        .file-actions { display: flex; gap: 10px; }
        .status-badge { 
            padding: 4px 8px; border-radius: 12px; font-size: 0.8em; font-weight: bold;
            display: inline-block; margin: 5px 0;
        }
        .status-success { background: #d4edda; color: #155724; }
        .status-info { background: #d1ecf1; color: #0c5460; }
        .status-warning { background: #fff3cd; color: #856404; }
        .btn-small { padding: 6px 12px; font-size: 0.8em; }
        .stats-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px; margin: 20px 0; }
        .stat-card { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 20px; border-radius: 10px; text-align: center; }
        .stat-number { font-size: 2em; font-weight: bold; display: block; }
        .stat-label { font-size: 0.9em; opacity: 0.9; }
    </style>
</head>
<body>
    <?php
    require_once("../php/outlook-email-sender.php");
    
    $outlookSender = new OutlookEmailSender();
    
    // Processar ações
    if (isset($_POST['action'])) {
        switch ($_POST['action']) {
            case 'clean_files':
                $removed = $outlookSender->cleanOldEmailFiles();
                $message = "✅ $removed arquivo(s) removido(s) com sucesso!";
                break;
                
            case 'test_email':
                $testData = [
                    'local' => 'Salão de Festas',
                    'data' => date('Y-m-d', strtotime('+1 day')),
                    'horario' => '19:00',
                    'tempo_duracao' => '4 horas',
                    'descricao' => 'Email de teste do sistema'
                ];
                
                $testMorador = [
                    'nome' => 'Usuário Teste',
                    'email' => $_POST['test_email'] ?? 'teste@exemplo.com',
                    'bloco' => 'A',
                    'torre' => '1'
                ];
                
                $result = $outlookSender->sendReservaConfirmation($testData, $testMorador);
                
                if ($result['success']) {
                    $message = "✅ Email de teste criado com sucesso! Método: " . $result['method'];
                    if (isset($result['download_url'])) {
                        $downloadLink = $result['download_url'];
                    }
                } else {
                    $message = "❌ Erro ao criar email de teste: " . $result['message'];
                }
                break;
        }
    }
    
    // Obter estatísticas
    $emailFiles = $outlookSender->listEmailFiles();
    $totalFiles = count($emailFiles);
    $totalSize = array_sum(array_column($emailFiles, 'size'));
    $oldFiles = array_filter($emailFiles, function($file) {
        return time() - $file['created'] > (7 * 24 * 60 * 60);
    });
    ?>

    <header>
        <nav>
            <div class="logo">
                <h1><i class="fas fa-shield"></i> ShieldTech</h1>
            </div>
            <ul class="menu">
                <li><a href="../index.php"><i class="fas fa-home"></i> Início</a></li>
                <li><a href="visitantes/visitantes.php"><i class="fas fa-user-friends"></i> Visitantes</a></li>
                <li><a href="relatorios/relatorios.php"><i class="fas fa-chart-bar"></i> Relatórios</a></li>
                <li><a href="reservas/reservas.php"><i class="fas fa-calendar"></i> Reservas</a></li>
                <li class="dropdown">
                    <a href="#" class="dropbtn"><i class="fas fa-gear"></i> Cadastros</a>
                    <div class="dropdown-content">
                        <a href="moradores/cadastro_moradores.php">Moradores</a>
                        <a href="funcionarios/cadastro_funcionarios.php">Funcionários</a>
                        <a href="cargos/cadastro_cargos.php">Cargos</a>
                        <a href="animais/cadastro_animais.php">Animais</a>
                    </div>
                </li>
            </ul>
        </nav>
    </header>

    <main class="email-manager">
        <h2><i class="fas fa-envelope"></i> Gerenciador de Emails</h2>
        
        <?php if (isset($message)): ?>
            <div class="alert-box <?= strpos($message, '✅') !== false ? 'success' : 'danger' ?>">
                <?= $message ?>
                <?php if (isset($downloadLink)): ?>
                    <br><a href="<?= $downloadLink ?>" class="btn btn-primary btn-small" download>📥 Baixar Email de Teste</a>
                <?php endif; ?>
            </div>
        <?php endif; ?>
        
        <!-- Estatísticas -->
        <div class="stats-grid">
            <div class="stat-card">
                <span class="stat-number"><?= $totalFiles ?></span>
                <span class="stat-label">Emails Criados</span>
            </div>
            <div class="stat-card">
                <span class="stat-number"><?= number_format($totalSize / 1024, 1) ?>KB</span>
                <span class="stat-label">Espaço Usado</span>
            </div>
            <div class="stat-card">
                <span class="stat-number"><?= count($oldFiles) ?></span>
                <span class="stat-label">Arquivos Antigos</span>
            </div>
            <div class="stat-card">
                <span class="stat-number"><?= PHP_OS_FAMILY === 'Windows' && class_exists('COM') ? 'COM' : 'EML' ?></span>
                <span class="stat-label">Método Ativo</span>
            </div>
        </div>
        
        <div class="email-grid">
            <!-- Painel de Controle -->
            <div class="email-card">
                <h3><i class="fas fa-cogs"></i> Painel de Controle</h3>
                
                <!-- Status do Sistema -->
                <div style="margin: 15px 0;">
                    <h4>Status do Sistema</h4>
                    <span class="status-badge <?= PHP_OS_FAMILY === 'Windows' ? 'status-success' : 'status-info' ?>">
                        Sistema: <?= PHP_OS_FAMILY ?>
                    </span>
                    <span class="status-badge <?= class_exists('COM') ? 'status-success' : 'status-warning' ?>">
                        COM: <?= class_exists('COM') ? 'Disponível' : 'Indisponível' ?>
                    </span>
                    <span class="status-badge <?= is_writable(__DIR__ . '/../emails/') ? 'status-success' : 'status-warning' ?>">
                        Escrita: <?= is_writable(__DIR__ . '/../emails/') ? 'OK' : 'Erro' ?>
                    </span>
                </div>
                
                <!-- Teste de Email -->
                <form method="post" style="margin: 15px 0;">
                    <h4>Teste de Email</h4>
                    <input type="hidden" name="action" value="test_email">
                    <div class="form-group">
                        <label for="test_email">Email para teste:</label>
                        <input type="email" name="test_email" id="test_email" 
                               value="teste@exemplo.com" required style="width: 100%; margin: 5px 0;">
                    </div>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-paper-plane"></i> Criar Email de Teste
                    </button>
                </form>
                
                <!-- Limpeza -->
                <form method="post" style="margin: 15px 0;">
                    <input type="hidden" name="action" value="clean_files">
                    <h4>Manutenção</h4>
                    <p style="font-size: 0.9em; color: #666;">
                        Remove arquivos com mais de 7 dias (<?= count($oldFiles) ?> encontrados)
                    </p>
                    <button type="submit" class="btn btn-warning" 
                            onclick="return confirm('Remover arquivos antigos?')">
                        <i class="fas fa-broom"></i> Limpar Arquivos Antigos
                    </button>
                </form>
                
                <!-- Links Úteis -->
                <div style="margin: 15px 0;">
                    <h4>Links Úteis</h4>
                    <a href="../php/outlook-test.php" target="_blank" class="btn btn-secondary btn-small">
                        🧪 Página de Testes
                    </a>
                    <a href="reservas/reservas.php" class="btn btn-secondary btn-small">
                        📅 Sistema de Reservas
                    </a>
                </div>
            </div>
            
            <!-- Lista de Arquivos -->
            <div class="email-card">
                <h3><i class="fas fa-folder"></i> Arquivos de Email (<?= $totalFiles ?>)</h3>
                
                <?php if (empty($emailFiles)): ?>
                    <div class="status-badge status-info">
                        Nenhum arquivo de email encontrado
                    </div>
                <?php else: ?>
                    <div class="file-list">
                        <?php foreach ($emailFiles as $file): ?>
                            <div class="file-item">
                                <div class="file-info">
                                    <strong><?= htmlspecialchars($file['filename']) ?></strong><br>
                                    <small>
                                        📅 <?= date('d/m/Y H:i', $file['created']) ?> | 
                                        📊 <?= number_format($file['size'] / 1024, 2) ?> KB
                                        <?php if (time() - $file['created'] > (7 * 24 * 60 * 60)): ?>
                                            | <span style="color: #dc3545;">⚠️ Antigo</span>
                                        <?php endif; ?>
                                    </small>
                                </div>
                                <div class="file-actions">
                                    <a href="<?= $file['download_url'] ?>" 
                                       class="btn btn-primary btn-small" download>
                                        📥 Baixar
                                    </a>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        
        <!-- Instruções -->
        <div class="email-card">
            <h3><i class="fas fa-info-circle"></i> Como Usar</h3>
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                <div>
                    <h4>🖥️ Windows com Outlook</h4>
                    <ol>
                        <li>O sistema tentará enviar via COM automaticamente</li>
                        <li>Se não funcionar, será criado arquivo .eml</li>
                        <li>Clique duas vezes no arquivo para abrir no Outlook</li>
                        <li>Clique em "Enviar" no Outlook</li>
                    </ol>
                </div>
                <div>
                    <h4>🌐 Outros Sistemas</h4>
                    <ol>
                        <li>Arquivos .eml são criados automaticamente</li>
                        <li>Baixe o arquivo usando o botão "Baixar"</li>
                        <li>Abra no seu cliente de email favorito</li>
                        <li>Envie normalmente</li>
                    </ol>
                </div>
            </div>
            
            <div style="background: #e8f4fd; padding: 15px; border-radius: 8px; margin: 15px 0; border-left: 4px solid #007bff;">
                <h4 style="margin-top: 0;">💡 Vantagens desta Solução</h4>
                <ul style="margin-bottom: 0;">
                    <li>✅ Funciona mesmo com servidores de email bloqueados</li>
                    <li>✅ Não requer configuração de SMTP</li>
                    <li>✅ Usa o Outlook local (se disponível)</li>
                    <li>✅ Compatível com qualquer cliente de email</li>
                    <li>✅ Templates HTML profissionais</li>
                </ul>
            </div>
        </div>
    </main>

    <footer>
        <p>&copy; 2025 ShieldTech. Todos os direitos reservados.</p>
    </footer>
</body>
</html>