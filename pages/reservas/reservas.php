<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reservas - ShieldTech</title>
    <link rel="stylesheet" href="../../css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <?php
    include("../../conectarbd.php");
    
    // Incluir classes de email (Outlook local)
    require_once("../../php/outlook-email-sender.php");
    
    // Processar formulário se foi enviado
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $local = mysqli_real_escape_string($conn, $_POST["local"]);
        $data = mysqli_real_escape_string($conn, $_POST["data"]);
        $horario = mysqli_real_escape_string($conn, $_POST["horario"]);
        $tempo_duracao = mysqli_real_escape_string($conn, $_POST["tempo_duracao"]);
        $descricao = mysqli_real_escape_string($conn, $_POST["descricao"]);
        $id_morador = mysqli_real_escape_string($conn, $_POST["id_morador"]);
        
        // Verificar se já existe reserva para o mesmo local, data e horário
        $verificar = mysqli_query($conn, "SELECT * FROM tb_reservas WHERE local='$local' AND data='$data' AND horario='$horario'");
        
        if (mysqli_num_rows($verificar) > 0) {
            echo "<script>alert('Já existe uma reserva para este local, data e horário!');</script>";
        } else {
            $sql = "INSERT INTO tb_reservas (local, data, horario, tempo_duracao, descricao, id_morador) 
                    VALUES ('$local', '$data', '$horario', '$tempo_duracao', '$descricao', '$id_morador')";
            
            if (mysqli_query($conn, $sql)) {
                // Buscar dados do morador para enviar email
                $morador_query = mysqli_query($conn, "SELECT nome, email, bloco, torre FROM tb_moradores WHERE id_moradores = $id_morador");
                $morador = mysqli_fetch_array($morador_query);
                
                $email_enviado = false;
                if ($morador && $morador['email']) {
                    // Preparar dados para o email
                    $reservaData = [
                        'local' => $local,
                        'data' => $data,
                        'horario' => $horario,
                        'tempo_duracao' => $tempo_duracao,
                        'descricao' => $descricao
                    ];
                    
                    $moradorData = [
                        'nome' => $morador['nome'],
                        'email' => $morador['email'],
                        'bloco' => $morador['bloco'],
                        'torre' => $morador['torre']
                    ];
                    
                    // Criar instância do OutlookEmailSender
                    $emailSender = new OutlookEmailSender();
                    
                    // Enviar email de confirmação
                    $result = $emailSender->sendReservaConfirmation($reservaData, $moradorData);
                    $email_enviado = $result['success'];
                }
                
                if ($email_enviado) {
                    if (isset($result['download_url'])) {
                        echo "<script>
                            alert('Reserva realizada com sucesso! Arquivo de email criado.');
                            window.open('" . $result['download_url'] . "', '_blank');
                        </script>";
                    } else {
                        echo "<script>alert('Reserva realizada com sucesso! Email enviado via Outlook.');</script>";
                    }
                } else {
                    if ($morador && $morador['email']) {
                        echo "<script>alert('Reserva realizada com sucesso! Porém houve um problema ao criar o email de confirmação.');</script>";
                    } else {
                        echo "<script>alert('Reserva realizada com sucesso! Email não cadastrado para este morador.');</script>";
                    }
                }
            } else {
                echo "<script>alert('Erro ao realizar reserva: " . mysqli_error($conn) . "');</script>";
            }
        }
    }
    ?>

<header>
        <nav>
            <div class="logo">
                <h1><i class="fas fa-shield"></i> ShieldTech</h1>
            </div>
            <ul class="menu">
                <li><a href="../../index.php"><i class="fas fa-home"></i> Início</a></li>
                <li><a href="../visitantes/visitantes.php"><i class="fas fa-user-friends"></i> Visitantes</a></li>
                <li><a href="../relatorios/relatorios.php"><i class="fas fa-chart-bar"></i> Relatórios</a></li>
                <li><a href="../reservas/reservas.php"><i class="fas fa-calendar"></i> Reservas</a></li>
                <li class="dropdown">
                    <a href="#" class="dropbtn"><i class="fas fa-gear"></i> Cadastros</a>
                    <div class="dropdown-content">
                        <a href="cadastro_moradores.php">Moradores</a>
                        <a href="../funcionarios/cadastro_funcionarios.php">Funcionários</a>
                        <a href="../cargos/cadastro_cargos.php">Cargos</a>
                        <a href="../animais/cadastro_animais.php">Animais</a>
                    </div>
                </li>
            </ul>
        </nav>
    </header>

    <main>
        <h2>Sistema de Reservas</h2>

        <div class="form-grid">
            <section class="form-section">
                <h3>Nova Reserva</h3>
                <form method="post" action="">
                    <div class="form-row">
                        <div class="form-group">
                            <label for="local">Local:</label>
                            <select id="local" name="local" required>
                                <option value="">Selecione o local</option>
                                <option value="Churrasqueira 1">Churrasqueira 1</option>
                                <option value="Churrasqueira 2">Churrasqueira 2</option>
                                <option value="Piscina">Piscina</option>
                                <option value="Salão de Festas">Salão de Festas</option>
                                <option value="Quadra Esportiva">Quadra Esportiva</option>
                                <option value="Playground">Playground</option>
                                <option value="Academia">Academia</option>
                                <option value="Sala de Jogos">Sala de Jogos</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="id_morador">Morador:</label>
                            <select id="id_morador" name="id_morador" required>
                                <option value="">Selecione o morador</option>
                                <?php
                                $moradores = mysqli_query($conn, "SELECT id_moradores, nome, bloco, torre, email FROM tb_moradores ORDER BY nome");
                                while ($morador = mysqli_fetch_array($moradores)) {
                                    $email_info = $morador["email"] ? " ✉️" : " ⚠️ Sem email";
                                    echo "<option value='" . $morador["id_moradores"] . "'>" . $morador["nome"] . " - Bloco " . $morador["bloco"] . "/" . $morador["torre"] . $email_info . "</option>";
                                }
                                ?>
                            </select>
                            <small style="color: #666; font-size: 0.8em;">✉️ = Email cadastrado | ⚠️ = Sem email cadastrado</small>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="data">Data:</label>
                            <input type="date" id="data" name="data" min="<?= date('Y-m-d') ?>" required>
                        </div>

                        <div class="form-group">
                            <label for="horario">Horário:</label>
                            <select id="horario" name="horario" required>
                                <option value="">Selecione o horário</option>
                                <option value="08:00">08:00</option>
                                <option value="09:00">09:00</option>
                                <option value="10:00">10:00</option>
                                <option value="11:00">11:00</option>
                                <option value="12:00">12:00</option>
                                <option value="13:00">13:00</option>
                                <option value="14:00">14:00</option>
                                <option value="15:00">15:00</option>
                                <option value="16:00">16:00</option>
                                <option value="17:00">17:00</option>
                                <option value="18:00">18:00</option>
                                <option value="19:00">19:00</option>
                                <option value="20:00">20:00</option>
                                <option value="21:00">21:00</option>
                                <option value="22:00">22:00</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="tempo_duracao">Duração:</label>
                            <select id="tempo_duracao" name="tempo_duracao" required>
                                <option value="">Selecione a duração</option>
                                <option value="1 hora">1 hora</option>
                                <option value="2 horas">2 horas</option>
                                <option value="3 horas">3 horas</option>
                                <option value="4 horas">4 horas</option>
                                <option value="Meio período (4h)">Meio período (4h)</option>
                                <option value="Período integral (8h)">Período integral (8h)</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group full-width">
                        <label for="descricao">Observações:</label>
                        <textarea id="descricao" name="descricao" rows="3" placeholder="Descreva o evento ou observações adicionais"></textarea>
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="btn-primary">
                            <i class="fas fa-calendar-check"></i> Fazer Reserva
                        </button>
                        <a href="consultar_reservas.php" class="btn-secondary">
                            <i class="fas fa-list"></i> Ver Reservas
                        </a>
                    </div>
                </form>
            </section>

            <section class="info-section">
                <h3>Informações sobre Reservas</h3>
                <div class="info-cards">
                    <?php
                    $hoje = date('Y-m-d');
                    $reservas_hoje = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM tb_reservas WHERE data = '$hoje'"));
                    $reservas_mes = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM tb_reservas WHERE MONTH(data) = MONTH(CURDATE()) AND YEAR(data) = YEAR(CURDATE())"));
                    $local_popular = mysqli_query($conn, "SELECT local, COUNT(*) as total FROM tb_reservas GROUP BY local ORDER BY total DESC LIMIT 1");
                    $popular = mysqli_fetch_array($local_popular);
                    ?>
                    
                    <div class="info-card">
                        <i class="fas fa-calendar-day"></i>
                        <h4>Reservas Hoje</h4>
                        <p><?= $reservas_hoje ?></p>
                    </div>
                    
                    <div class="info-card">
                        <i class="fas fa-calendar-alt"></i>
                        <h4>Reservas do Mês</h4>
                        <p><?= $reservas_mes ?></p>
                    </div>
                    
                    <div class="info-card">
                        <i class="fas fa-star"></i>
                        <h4>Local Mais Popular</h4>
                        <p><?= $popular ? $popular['local'] : 'N/A' ?></p>
                    </div>
                    
                    <div class="info-card">
                        <i class="fas fa-clock"></i>
                        <h4>Horário de Funcionamento</h4>
                        <p>08:00 - 22:00</p>
                    </div>
                </div>

                <div class="quick-actions">
                    <h4>Regras de Reserva</h4>
                    <div style="background: #f8f9fa; padding: 1rem; border-radius: 0.5rem; margin-top: 1rem;">
                        <ul style="margin: 0; padding-left: 1.5rem; color: #666;">
                            <li>Reservas devem ser feitas com antecedência mínima de 24h</li>
                            <li>Máximo de 2 reservas por morador por mês</li>
                            <li>Cancelamentos devem ser feitos até 12h antes</li>
                            <li>É obrigatório deixar o local limpo após o uso</li>
                            <li>Horário de funcionamento: 08:00 às 22:00</li>
                            <li>Confirmação será enviada por email (se cadastrado)</li>
                        </ul>
                    </div>
                </div>

                <div class="quick-actions">
                    <h4>Sistema de Email Outlook</h4>
                    <div style="background: #e8f4fd; padding: 1rem; border-radius: 0.5rem; margin-top: 1rem; border-left: 4px solid #3498db;">
                        <p style="margin: 0 0 0.5rem 0; color: #2c3e50; font-size: 0.9em;">
                            <i class="fas fa-envelope"></i> 
                            <strong>Status:</strong> 
                            <?php if (PHP_OS_FAMILY === 'Windows' && class_exists('COM')): ?>
                                <span style="color: #28a745;">✅ Outlook COM Disponível</span>
                            <?php else: ?>
                                <span style="color: #17a2b8;">ℹ️ Usando arquivos .eml</span>
                            <?php endif; ?>
                        </p>
                        <p style="margin: 0; color: #666; font-size: 0.8em;">
                            Emails funcionam mesmo com servidores bloqueados!
                            <?php if (!(PHP_OS_FAMILY === 'Windows' && class_exists('COM'))): ?>
                                <br><small>Arquivos .eml serão criados para abertura no Outlook</small>
                            <?php endif; ?>
                        </p>
                        <p style="margin: 0.5rem 0 0 0;">
                            <a href="../../php/outlook-test.php" target="_blank" style="color: #007bff; text-decoration: none;">
                                🧪 Testar Sistema de Email
                            </a>
                        </p>
                    </div>
                </div>
            </section>
        </div>

        <section class="lista-section">
            <h3>Próximas Reservas</h3>
            <div class="tabela-container">
                <table class="tabela-relatorio">
                    <thead>
                        <tr>
                            <th>Local</th>
                            <th>Data</th>
                            <th>Horário</th>
                            <th>Duração</th>
                            <th>Morador</th>
                            <th>Email</th>
                            <th>Status</th>
                            <th>Email</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $hoje = date('Y-m-d');
                        $proximas = mysqli_query($conn, "
                            SELECT r.*, m.nome as nome_morador, m.bloco, m.torre, m.email 
                            FROM tb_reservas r 
                            LEFT JOIN tb_moradores m ON r.id_morador = m.id_moradores 
                            WHERE r.data >= '$hoje' 
                            ORDER BY r.data, r.horario 
                            LIMIT 10
                        ");
                        
                        if (mysqli_num_rows($proximas) > 0) {
                            while ($reserva = mysqli_fetch_array($proximas)) {
                                $email_status = $reserva["email"] ? "✉️ Disponível" : "⚠️ Sem email";
                                $email_class = $reserva["email"] ? "status-ativo" : "status-presente";
                                
                                echo "<tr>";
                                echo "<td>" . $reserva["local"] . "</td>";
                                echo "<td>" . date('d/m/Y', strtotime($reserva["data"])) . "</td>";
                                echo "<td>" . $reserva["horario"] . "</td>";
                                echo "<td>" . $reserva["tempo_duracao"] . "</td>";
                                echo "<td>" . $reserva["nome_morador"] . " - Bloco " . $reserva["bloco"] . "/" . $reserva["torre"] . "</td>";
                                echo "<td><span class='status-ativo'>Confirmada</span></td>";
                                echo "<td><span class='$email_class'>$email_status</span></td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='8' style='text-align: center;'>Nenhuma reserva encontrada</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </section>
    </main>

    <footer>
        <p>&copy; 2025 ShieldTech. Todos os direitos reservados.</p>
    </footer>

    <script>
        // Validar data mínima (não permitir datas passadas)
        document.getElementById('data').addEventListener('change', function() {
            const hoje = new Date().toISOString().split('T')[0];
            if (this.value < hoje) {
                alert('Não é possível fazer reservas para datas passadas!');
                this.value = hoje;
            }
        });

        // Verificar disponibilidade ao selecionar local, data e horário
        function verificarDisponibilidade() {
            const local = document.getElementById('local').value;
            const data = document.getElementById('data').value;
            const horario = document.getElementById('horario').value;
            
            if (local && data && horario) {
                // Aqui você pode fazer uma requisição AJAX para verificar disponibilidade
                // Por enquanto, vamos apenas mostrar uma mensagem
                console.log('Verificando disponibilidade para:', local, data, horario);
            }
        }

        document.getElementById('local').addEventListener('change', verificarDisponibilidade);
        document.getElementById('data').addEventListener('change', verificarDisponibilidade);
        document.getElementById('horario').addEventListener('change', verificarDisponibilidade);

        // Mostrar aviso sobre email ao selecionar morador
        document.getElementById('id_morador').addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            if (selectedOption.text.includes('⚠️ Sem email')) {
                alert('Atenção: Este morador não possui email cadastrado. A confirmação não será enviada por email.');
            }
        });
    </script>
</body>
</html>