<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Consultar Reservas - ShieldTech</title>
    <link rel="stylesheet" href="../../css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
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
        <h2>Reservas Cadastradas</h2>
        
        <div class="actions-bar">
            <a href="reservas.php" class="btn-primary">
                <i class="fas fa-plus"></i> Nova Reserva
            </a>
        </div>

        <section class="form-section">
            <h3>Filtros</h3>
            <form method="get" action="">
                <div class="form-row">
                    <div class="form-group">
                        <label for="filtro_local">Local:</label>
                        <select id="filtro_local" name="filtro_local">
                            <option value="">Todos os locais</option>
                            <option value="Churrasqueira 1" <?= isset($_GET['filtro_local']) && $_GET['filtro_local'] == 'Churrasqueira 1' ? 'selected' : '' ?>>Churrasqueira 1</option>
                            <option value="Churrasqueira 2" <?= isset($_GET['filtro_local']) && $_GET['filtro_local'] == 'Churrasqueira 2' ? 'selected' : '' ?>>Churrasqueira 2</option>
                            <option value="Piscina" <?= isset($_GET['filtro_local']) && $_GET['filtro_local'] == 'Piscina' ? 'selected' : '' ?>>Piscina</option>
                            <option value="Salão de Festas" <?= isset($_GET['filtro_local']) && $_GET['filtro_local'] == 'Salão de Festas' ? 'selected' : '' ?>>Salão de Festas</option>
                            <option value="Quadra Esportiva" <?= isset($_GET['filtro_local']) && $_GET['filtro_local'] == 'Quadra Esportiva' ? 'selected' : '' ?>>Quadra Esportiva</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="filtro_data">Data:</label>
                        <input type="date" id="filtro_data" name="filtro_data" value="<?= $_GET['filtro_data'] ?? '' ?>">
                    </div>

                    <div class="form-group">
                        <label for="filtro_morador">Morador:</label>
                        <select id="filtro_morador" name="filtro_morador">
                            <option value="">Todos os moradores</option>
                            <?php
                            include("../../conectarbd.php");
                            $moradores = mysqli_query($conn, "SELECT id_moradores, nome FROM tb_moradores ORDER BY nome");
                            while ($morador = mysqli_fetch_array($moradores)) {
                                $selected = (isset($_GET['filtro_morador']) && $_GET['filtro_morador'] == $morador['id_moradores']) ? 'selected' : '';
                                echo "<option value='" . $morador["id_moradores"] . "' $selected>" . $morador["nome"] . "</option>";
                            }
                            ?>
                        </select>
                    </div>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn-primary">
                        <i class="fas fa-search"></i> Filtrar
                    </button>
                    <a href="consultar_reservas.php" class="btn-secondary">
                        <i class="fas fa-refresh"></i> Limpar Filtros
                    </a>
                </div>
            </form>
        </section>

        <section class="lista-section">
            <div class="tabela-container">
                <table class="tabela-relatorio">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Local</th>
                            <th>Data</th>
                            <th>Horário</th>
                            <th>Duração</th>
                            <th>Morador</th>
                            <th>Observações</th>
                            <th>Status</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // Construir query com filtros
                        $sql = "SELECT r.*, m.nome as nome_morador, m.bloco, m.torre 
                                FROM tb_reservas r 
                                LEFT JOIN tb_moradores m ON r.id_morador = m.id_moradores 
                                WHERE 1=1";
                        
                        if (isset($_GET['filtro_local']) && $_GET['filtro_local'] != '') {
                            $filtro_local = mysqli_real_escape_string($conn, $_GET['filtro_local']);
                            $sql .= " AND r.local = '$filtro_local'";
                        }
                        
                        if (isset($_GET['filtro_data']) && $_GET['filtro_data'] != '') {
                            $filtro_data = mysqli_real_escape_string($conn, $_GET['filtro_data']);
                            $sql .= " AND r.data = '$filtro_data'";
                        }
                        
                        if (isset($_GET['filtro_morador']) && $_GET['filtro_morador'] != '') {
                            $filtro_morador = mysqli_real_escape_string($conn, $_GET['filtro_morador']);
                            $sql .= " AND r.id_morador = '$filtro_morador'";
                        }
                        
                        $sql .= " ORDER BY r.data DESC, r.horario DESC";
                        
                        $selecionar = mysqli_query($conn, $sql);
                        
                        if (mysqli_num_rows($selecionar) > 0) {
                            while ($campo = mysqli_fetch_array($selecionar)) {
                                $hoje = date('Y-m-d');
                                $data_reserva = $campo["data"];
                                $status = ($data_reserva >= $hoje) ? 'Confirmada' : 'Realizada';
                                $status_class = ($data_reserva >= $hoje) ? 'status-ativo' : 'status-presente';
                                
                                echo "<tr>";
                                echo "<td>" . $campo["id_reservas"] . "</td>";
                                echo "<td>" . $campo["local"] . "</td>";
                                echo "<td>" . date('d/m/Y', strtotime($campo["data"])) . "</td>";
                                echo "<td>" . $campo["horario"] . "</td>";
                                echo "<td>" . $campo["tempo_duracao"] . "</td>";
                                echo "<td>" . $campo["nome_morador"] . " - Bloco " . $campo["bloco"] . "/" . $campo["torre"] . "</td>";
                                echo "<td>" . ($campo["descricao"] ? substr($campo["descricao"], 0, 50) . "..." : "Sem observações") . "</td>";
                                echo "<td><span class='$status_class'>$status</span></td>";
                                echo "<td class='acoes'>";
                                
                                if ($data_reserva >= $hoje) {
                                    echo "<a href='editar_reserva.php?id=" . $campo["id_reservas"] . "' class='btn-editar'>";
                                    echo "<i class='fas fa-edit'></i> Editar</a>";
                                    echo "<a href='cancelar_reserva.php?id=" . $campo["id_reservas"] . "' class='btn-excluir' onclick='return confirm(\"Tem certeza que deseja cancelar esta reserva?\")'>";
                                    echo "<i class='fas fa-times'></i> Cancelar</a>";
                                }
                                
                                echo "</td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='9' style='text-align: center;'>Nenhuma reserva encontrada</td></tr>";
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
</body>
</html>