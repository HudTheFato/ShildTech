<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ShieldTech - Sistema de Controle</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <header>
        <nav>
            <div class="logo">
                <h1><i class="fas fa-shield"></i> ShieldTech</h1>
            </div>
            <ul class="menu">
                <li><a href="index.php"><i class="fas fa-home"></i> Início</a></li>
                <li><a href="pages/visitantes/visitantes.php"><i class="fas fa-user-friends"></i> Visitantes</a></li>
                <li><a href="pages/relatorios/relatorios.php"><i class="fas fa-chart-bar"></i> Relatórios</a></li>
                <li><a href="pages/reservas/reservas.php"><i class="fas fa-calendar"></i> Reservas</a></li>
                <li><a href="pages/encomendas/cadastro_encomendas.php"><i class="fas fa-box"></i> Encomendas</a></li>
                <li class="dropdown">
                    <a href="#" class="dropbtn"><i class="fas fa-gear"></i> Cadastros</a>
                    <div class="dropdown-content">
                        <a href="pages/moradores/cadastro_moradores.php">Moradores</a>
                        <a href="pages/funcionarios/cadastro_funcionarios.php">Funcionários</a>
                        <a href="pages/cargos/cadastro_cargos.php">Cargos</a>
                        <a href="pages/animais/cadastro_animais.php">Animais</a>
                    </div>
                </li>
            </ul>
        </nav>
    </header>

    <main>
        <section class="welcome-section">
            <h2>Bem-vindo ao Sistema de Gestão ShieldTech</h2>
            <p>Gerencie seu condomínio de forma eficiente e segura.</p>
        </section>

        <section class="dashboard">
            <h2>Painel de Controle</h2>
            <div class="cards">
                <?php
                include("conectarbd.php");
                
                // Contar registros
                $moradores = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM tb_moradores"));
                $funcionarios = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM tb_funcionarios"));
                $visitantes = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM tb_visitantes WHERE status = 'Presente'"));
                $cargos = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM tb_cargo"));
                ?>
                
                <div class="card">
                    <h3><i class="fas fa-users"></i> Total de Moradores</h3>
                    <p><?= $moradores ?></p>
                </div>
                <div class="card">
                    <h3><i class="fas fa-user-tie"></i> Funcionários Ativos</h3>
                    <p><?= $funcionarios ?></p>
                </div>
                <div class="card">
                    <h3><i class="fas fa-user-friends"></i> Visitantes Presentes</h3>
                    <p><?= $visitantes ?></p>
                </div>
                <div class="card">
                    <h3><i class="fas fa-briefcase"></i> Cargos Cadastrados</h3>
                    <p><?= $cargos ?></p>
                </div>
            </div>
        </section>

        <section class="grafico-section">
            <h2>Movimentação Mensal</h2>
            <div class="grafico-container">
                <canvas id="graficoMovimentacao" width="800" height="400"></canvas>
            </div>
        </section>

        <section class="quick-access">
            <h3>Acesso Rápido</h3>
            <div class="quick-access-grid">
                <a href="pages/moradores/cadastro_moradores.php" class="quick-access-card">
                    <i class="fas fa-users"></i>
                    <h4>Moradores</h4>
                    <p>Cadastre e gerencie moradores</p>
                </a>
                <a href="pages/funcionarios/cadastro_funcionarios.php" class="quick-access-card">
                    <i class="fas fa-id-card"></i>
                    <h4>Funcionários</h4>
                    <p>Gerencie a equipe</p>
                </a>
                <a href="pages/visitantes/visitantes.php" class="quick-access-card">
                    <i class="fas fa-user-friends"></i>
                    <h4>Visitantes</h4>
                    <p>Controle de acesso</p>
                </a>
                <a href="pages/relatorios/relatorios.php" class="quick-access-card">
                    <i class="fas fa-chart-bar"></i>
                    <h4>Relatórios</h4>
                    <p>Visualize estatísticas</p>
                </a>
            </div>
        </section>
    </main>

    <footer>
        <p>&copy; 2025 ShieldTech. Todos os direitos reservados.</p>
    </footer>

    <script>
        // Gráfico de movimentação mensal
        const ctx = document.getElementById('graficoMovimentacao').getContext('2d');
        
        <?php
        // Dados para o gráfico dos últimos 6 meses
        $meses = [];
        $dadosMoradores = [];
        $dadosVisitantes = [];
        
        for ($i = 5; $i >= 0; $i--) {
            $data = date('Y-m', strtotime("-$i months"));
            $mesNome = date('M/Y', strtotime("-$i months"));
            $meses[] = $mesNome;
            
            // Contar moradores cadastrados no mês
            $moradoresMes = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM tb_moradores WHERE DATE_FORMAT(data_cadastro, '%Y-%m') = '$data'"));
            $dadosMoradores[] = $moradoresMes;
            
            // Contar visitantes do mês
            $visitantesMes = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM tb_visitantes WHERE DATE_FORMAT(data_nascimento, '%Y-%m') = '$data'"));
            $dadosVisitantes[] = $visitantesMes;
        }
        ?>
        
        const chart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: <?= json_encode($meses) ?>,
                datasets: [{
                    label: 'Moradores Cadastrados',
                    data: <?= json_encode($dadosMoradores) ?>,
                    borderColor: '#2ecc71',
                    backgroundColor: 'rgba(46, 204, 113, 0.1)',
                    tension: 0.4
                }, {
                    label: 'Visitantes',
                    data: <?= json_encode($dadosVisitantes) ?>,
                    borderColor: '#3498db',
                    backgroundColor: 'rgba(52, 152, 219, 0.1)',
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    title: {
                        display: true,
                        text: 'Movimentação dos Últimos 6 Meses'
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>
</body>
</html>