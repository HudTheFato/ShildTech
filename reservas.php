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
    
    // Processar formulário se foi enviado
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $local = mysqli_real_escape_string($conn, $_POST["local"]);
        $data = mysqli_real_escape_string($conn, $_POST["data"]);
        $horario = mysqli_real_escape_string($conn, $_POST["horario"]);
        $tempo_duracao = mysqli_real_escape_string($conn, $_POST["tempo_duracao"]);
        $descricao = mysqli_real_escape_string($conn, $_POST["descricao"]);
        $id_moradores = mysqli_real_escape_string($conn, $_POST["id_moradores"]);
        
        // Verificar se já existe reserva para o mesmo local, data e horário
        $verificar = mysqli_query($conn, "SELECT * FROM tb_reservas WHERE local='$local' AND data='$data' AND horario='$horario'");
        
        if (mysqli_num_rows($verificar) > 0) {
            echo "<script>alert('Já existe uma reserva para este local, data e horário!');</script>";
        } else {
            $sql = "INSERT INTO tb_reservas (local, data, horario, tempo_duracao, descricao, id_moradores) 
                    VALUES ('$local', '$data', '$horario', '$tempo_duracao', '$descricao', '$id_moradores')";
            
            if (mysqli_query($conn, $sql)) {
                // Buscar dados do morador para enviar email
                $morador_query = mysqli_query($conn, "SELECT nome, email FROM tb_moradores WHERE id_moradores = $id_moradores");
                $morador = mysqli_fetch_array($morador_query);
                
                if ($morador && $morador['email']) {
                    // Aqui você pode implementar o envio de email
                    // Por exemplo, usando PHPMailer ou mail() do PHP
                    $nome_morador = $morador['nome'];
                    $email_morador = $morador['email'];
                    
                    // Exemplo básico de email (você pode melhorar isso)
                    $assunto = "Confirmação de Reserva - ShieldTech";
                    $mensagem = "
                    Olá $nome_morador,
                    
                    Sua reserva foi confirmada com sucesso!
                    
                    Detalhes da reserva:
                    - Local: $local
                    - Data: " . date('d/m/Y', strtotime($data)) . "
                    - Horário: $horario
                    - Duração: $tempo_duracao
                    - Observações: $descricao
                    
                    Atenciosamente,
                    Equipe ShieldTech
                    ";
                    
                    $headers = "From: noreply@shieldtech.com\r\n";
                    $headers .= "Reply-To: contato@shieldtech.com\r\n";
                    $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";
                    
                    // Enviar email (descomente a linha abaixo quando configurar o servidor de email)
                    // mail($email_morador, $assunto, $mensagem, $headers);
                }
                
                echo "<script>alert('Reserva realizada com sucesso! Confirmação enviada por email.');</script>";
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
                <li class="dropdown">
                    <a href="#" class="dropbtn"><i class="fas fa-gear"></i> Cadastros</a>
                    <div class="dropdown-content">
                        <a href="../moradores/cadastro_moradores.php">Moradores</a>
                        <a href="../funcionarios/cadastro_funcionarios.php">Funcionários</a>
                        <a href="../cargos/cadastro_cargos.php">Cargos</a>
                        <a href="../animais/cadastro_animais.php">Animais</a>
                    </div>
                </li>
                <li><a href="reservas.php"><i class="fas fa-calendar-alt"></i> Reservas</a></li>
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
                            <label for="id_moradores">Morador:</label>
                            <select id="id_moradores" name="id_moradores" required>
                                <option value="">Selecione o morador</option>
                                <?php
                                $moradores = mysqli_query($conn, "SELECT id_moradores, nome, bloco, torre FROM tb_moradores ORDER BY nome");
                                while ($morador = mysqli_fetch_array($moradores)) {
                                    echo "<option value='" . $morador["id_moradores"] . "'>" . $morador["nome"] . " - Bloco " . $morador["bloco"] . "/" . $morador["torre"] . "</option>";
                                }
                                ?>
                            </select>
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
                            <li>Confirmação será enviada por email</li>
                        </ul>
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
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $hoje = date('Y-m-d');
                        $proximas = mysqli_query($conn, "
                            SELECT r.*, m.nome as nome_morador, m.bloco, m.torre 
                            FROM tb_reservas r 
                            LEFT JOIN tb_moradores m ON r.id_moradores = m.id_moradores 
                            WHERE r.data >= '$hoje' 
                            ORDER BY r.data, r.horario 
                            LIMIT 10
                        ");
                        
                        if (mysqli_num_rows($proximas) > 0) {
                            while ($reserva = mysqli_fetch_array($proximas)) {
                                echo "<tr>";
                                echo "<td>" . $reserva["local"] . "</td>";
                                echo "<td>" . date('d/m/Y', strtotime($reserva["data"])) . "</td>";
                                echo "<td>" . $reserva["horario"] . "</td>";
                                echo "<td>" . $reserva["tempo_duracao"] . "</td>";
                                echo "<td>" . $reserva["nome_morador"] . " - Bloco " . $reserva["bloco"] . "/" . $reserva["torre"] . "</td>";
                                echo "<td><span class='status-ativo'>Confirmada</span></td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='6' style='text-align: center;'>Nenhuma reserva encontrada</td></tr>";
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
    </script>
</body>
</html>







<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Moradores - ShieldTech</title>
    <link rel="stylesheet" href="../../css/style.css">
    <link rel="stylesheet" href="../../css/validation.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <?php
    include("../../conectarbd.php");
    
    // Processar formulário se foi enviado
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $nome = mysqli_real_escape_string($conn, $_POST["nome"]);
        $cpf = mysqli_real_escape_string($conn, $_POST["cpf"]);
        $rg = mysqli_real_escape_string($conn, $_POST["rg"]);
        $data_nascimento = mysqli_real_escape_string($conn, $_POST["data_nascimento"]);
        $sexo = mysqli_real_escape_string($conn, $_POST["sexo"]);
        $telefone = mysqli_real_escape_string($conn, $_POST["telefone"]);
        $email = mysqli_real_escape_string($conn, $_POST["email"]);
        $bloco = mysqli_real_escape_string($conn, $_POST["bloco"]);
        $torre = mysqli_real_escape_string($conn, $_POST["torre"]);
        $andar = mysqli_real_escape_string($conn, $_POST["andar"]);
        $veiculo = mysqli_real_escape_string($conn, $_POST["veiculo"]);
        $foto = mysqli_real_escape_string($conn, $_POST["foto"]);
        $data_cadastro = date('Y-m-d H:i:s');
        
        // Validar email
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            echo "<script>alert('Email inválido! Por favor, digite um email válido.');</script>";
        } else {
            // Verificar se email já existe
            $verificar_email = mysqli_query($conn, "SELECT * FROM tb_moradores WHERE email = '$email'");
            if (mysqli_num_rows($verificar_email) > 0) {
                echo "<script>alert('Este email já está cadastrado!');</script>";
            } else {
                $sql = "INSERT INTO tb_moradores (nome, cpf, rg, data_nascimento, sexo, telefone, email, bloco, torre, andar, veiculo, foto, data_cadastro) 
                        VALUES ('$nome', '$cpf', '$rg', '$data_nascimento', '$sexo', '$telefone', '$email', '$bloco', '$torre', '$andar', '$veiculo', '$foto', '$data_cadastro')";
                
                if (mysqli_query($conn, $sql)) {
                    echo "<script>alert('Morador cadastrado com sucesso!'); window.location = 'consultar_moradores.php';</script>";
                } else {
                    echo "<script>alert('Erro ao cadastrar morador: " . mysqli_error($conn) . "');</script>";
                }
            }
        }
        
        if (mysqli_query($conn, $sql)) {
            echo "<script>alert('Morador cadastrado com sucesso!'); window.location = 'consultar_moradores.php';</script>";
        } else {
            echo "<script>alert('Erro ao cadastrar morador: " . mysqli_error($conn) . "');</script>";
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
        <h2>Gestão de Moradores</h2>

        <div class="form-grid">
            <section class="form-section">
                <h3>Cadastro de Morador</h3>
                <form method="post" action="">
                    <div class="form-row">
                        <div class="form-group">
                            <label for="nome">Nome Completo:</label>
                            <input type="text" id="nome" name="nome" required>
                        </div>

                        <div class="form-group">
                            <label for="cpf">CPF:</label>
                            <input type="text" id="cpf" name="cpf" placeholder="000.000.000-00" required>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="rg">RG:</label>
                            <input type="text" id="rg" name="rg" required>
                        </div>

                        <div class="form-group">
                            <label for="data_nascimento">Data de Nascimento:</label>
                            <input type="date" id="data_nascimento" name="data_nascimento" required>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="sexo">Sexo:</label>
                            <select id="sexo" name="sexo" required>
                                <option value="">Selecione</option>
                                <option value="Masculino">Masculino</option>
                                <option value="Feminino">Feminino</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="telefone">Telefone:</label>
                            <input type="tel" id="telefone" name="telefone" placeholder="(00) 00000-0000" required>
                        </div>
                    </div>

                          <div class="form-group">
                        <label for="email">Email:</label>
                        <input type="email" id="email" name="email" placeholder="exemplo@email.com" required>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="bloco">Bloco:</label>
                            <input type="text" id="bloco" name="bloco" required>
                        </div>

                        <div class="form-group">
                            <label for="torre">Torre:</label>
                            <input type="text" id="torre" name="torre">
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="andar">Andar:</label>
                            <input type="text" id="andar" name="andar">
                        </div>

                        <div class="form-group">
                            <label for="veiculo">Veículo:</label>
                            <input type="text" id="veiculo" name="veiculo" placeholder="Marca/Modelo - Placa">
                        </div>
                    </div>

                    <div class="form-group full-width">
                        <label for="foto">Foto (URL):</label>
                        <input type="text" id="foto" name="foto" placeholder="URL da foto">
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="btn-primary">
                            <i class="fas fa-save"></i> Cadastrar Morador
                        </button>
                        <a href="consultar_moradores.php" class="btn-secondary">
                            <i class="fas fa-list"></i> Ver Moradores
                        </a>
                    </div>
                </form>
            </section>

            <section class="info-section">
                <h3>Acesso Rápido</h3>
                <div class="quick-actions">
                    <a href="../animais/cadastro_animais.php" class="quick-action-card">
                        <i class="fas fa-paw"></i>
                        <h4>Cadastrar Animal</h4>
                        <p>Registre animais de estimação dos moradores</p>
                    </a>
                    
                    <a href="../funcionarios/cadastro_funcionarios.php" class="quick-action-card">
                        <i class="fas fa-user-tie"></i>
                        <h4>Cadastrar Funcionário</h4>
                        <p>Adicione novos funcionários ao sistema</p>
                    </a>
                    
                    <a href="../visitantes/visitantes.php" class="quick-action-card">
                        <i class="fas fa-user-friends"></i>
                        <h4>Registrar Visitante</h4>
                        <p>Controle de acesso de visitantes</p>
                    </a>

              

                    
                    </div>
                </div>

                <div class="stats-section">
                    <h4>Estatísticas</h4>
                    <?php
                    $total_moradores = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM tb_moradores"));
                    $total_blocos = mysqli_num_rows(mysqli_query($conn, "SELECT DISTINCT bloco FROM tb_moradores WHERE bloco IS NOT NULL AND bloco != ''"));
                    $com_veiculos = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM tb_moradores WHERE veiculo IS NOT NULL AND veiculo != ''"));
                    ?>
                    
                    <div class="stat-item">
                        <span class="stat-number"><?= $total_moradores ?></span>
                        <span class="stat-label">Total de Moradores</span>
                    </div>
                    
                    <div class="stat-item">
                        <span class="stat-number"><?= $total_blocos ?></span>
                        <span class="stat-label">Blocos Ocupados</span>
                    </div>
                    
                    <div class="stat-item">
                        <span class="stat-number"><?= $com_veiculos ?></span>
                        <span class="stat-label">Com Veículos</span>
                    </div>
                </div>
            </section>
        </div>
    </main>

    <footer>
        <p>&copy; 2025 ShieldTech. Todos os direitos reservados.</p>
    </footer>

    <script src="../../js/validation.js"></script>
    <script>
        // Máscara para CPF
        document.getElementById('cpf').addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            if (value.length <= 11) {
                value = value.replace(/^(\d{3})(\d{3})(\d{3})(\d{2}).*/, '$1.$2.$3-$4');
                e.target.value = value;
            }
        });

        // Máscara para telefone
        document.getElementById('telefone').addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            if (value.length <= 11) {
                value = value.replace(/^(\d{2})(\d{5})(\d{4}).*/, '($1) $2-$3');
                e.target.value = value;
            }
        });

        // Configurar validação de email em tempo real
        document.addEventListener('DOMContentLoaded', () => {
            EmailValidator.setupEmailValidation('email', 'email-error');
            
            // Adicionar ícone de validação
            const emailInput = document.getElementById('email');
            const emailIcon = document.getElementById('email-icon');
            
            emailInput.addEventListener('input', () => {
                emailIcon.innerHTML = '<div class="email-loading"></div>';
            });
            
            // Atualizar ícone baseado na validação
            const originalSetup = EmailValidator.setupEmailValidation;
            EmailValidator.setupEmailValidation = function(inputId, errorElementId) {
                originalSetup.call(this, inputId, errorElementId);
                
                const input = document.getElementById(inputId);
                const icon = document.getElementById('email-icon');
                
                input.addEventListener('input', () => {
                    setTimeout(() => {
                        if (input.classList.contains('valid')) {
                            icon.innerHTML = '<i class="fas fa-check-circle valid"></i>';
                        } else if (input.classList.contains('invalid')) {
                            icon.innerHTML = '<i class="fas fa-times-circle invalid"></i>';
                        } else {
                            icon.innerHTML = '';
                        }
                    }, 600);
                });
            };
        });
    </script>
</body>
</html>