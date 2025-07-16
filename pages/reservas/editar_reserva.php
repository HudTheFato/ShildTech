<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Reserva - ShieldTech</title>
    <link rel="stylesheet" href="../../css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <?php
    include("../../conectarbd.php");
    
    $id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
    
    // Processar formulário se foi enviado
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $id = mysqli_real_escape_string($conn, $_POST["id"]);
        $local = mysqli_real_escape_string($conn, $_POST["local"]);
        $data = mysqli_real_escape_string($conn, $_POST["data"]);
        $horario = mysqli_real_escape_string($conn, $_POST["horario"]);
        $tempo_duracao = mysqli_real_escape_string($conn, $_POST["tempo_duracao"]);
        $descricao = mysqli_real_escape_string($conn, $_POST["descricao"]);
        $id_morador = mysqli_real_escape_string($conn, $_POST["id_morador"]);
        
        // Verificar se já existe reserva para o mesmo local, data e horário (exceto a atual)
        $verificar = mysqli_query($conn, "SELECT * FROM tb_reservas WHERE local='$local' AND data='$data' AND horario='$horario' AND id_reservas != $id");
        
        if (mysqli_num_rows($verificar) > 0) {
            echo "<script>alert('Já existe uma reserva para este local, data e horário!');</script>";
        } else {
            $sql = "UPDATE tb_reservas SET 
                    local='$local', data='$data', horario='$horario', 
                    tempo_duracao='$tempo_duracao', descricao='$descricao', id_morador='$id_morador' 
                    WHERE id_reservas=$id";
            
            if (mysqli_query($conn, $sql)) {
                echo "<script>alert('Reserva atualizada com sucesso!'); window.location = 'consultar_reservas.php';</script>";
            } else {
                echo "<script>alert('Erro ao atualizar reserva: " . mysqli_error($conn) . "');</script>";
            }
        }
    }
    
    // Buscar dados da reserva
    $selecionar = mysqli_query($conn, "SELECT * FROM tb_reservas WHERE id_reservas=$id");
    $campo = mysqli_fetch_array($selecionar);
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
        <h2>Editar Reserva</h2>

        <section class="form-section">
            <h3>Alterar Dados da Reserva</h3>
            <form method="post" action="">
                <input type="hidden" name="id" value="<?= $campo["id_reservas"] ?>">
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="local">Local:</label>
                        <select id="local" name="local" required>
                            <option value="">Selecione o local</option>
                            <option value="Churrasqueira 1" <?= $campo["local"] == "Churrasqueira 1" ? "selected" : "" ?>>Churrasqueira 1</option>
                            <option value="Churrasqueira 2" <?= $campo["local"] == "Churrasqueira 2" ? "selected" : "" ?>>Churrasqueira 2</option>
                            <option value="Piscina" <?= $campo["local"] == "Piscina" ? "selected" : "" ?>>Piscina</option>
                            <option value="Salão de Festas" <?= $campo["local"] == "Salão de Festas" ? "selected" : "" ?>>Salão de Festas</option>
                            <option value="Quadra Esportiva" <?= $campo["local"] == "Quadra Esportiva" ? "selected" : "" ?>>Quadra Esportiva</option>
                            <option value="Playground" <?= $campo["local"] == "Playground" ? "selected" : "" ?>>Playground</option>
                            <option value="Academia" <?= $campo["local"] == "Academia" ? "selected" : "" ?>>Academia</option>
                            <option value="Sala de Jogos" <?= $campo["local"] == "Sala de Jogos" ? "selected" : "" ?>>Sala de Jogos</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="data">Data:</label>
                        <input type="date" id="data" name="data" value="<?= $campo["data"] ?>" min="<?= date('Y-m-d') ?>" required>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="horario">Horário:</label>
                        <select id="horario" name="horario" required>
                            <option value="">Selecione o horário</option>
                            <?php
                            $horarios = ["08:00", "09:00", "10:00", "11:00", "12:00", "13:00", "14:00", "15:00", "16:00", "17:00", "18:00", "19:00", "20:00", "21:00", "22:00"];
                            foreach ($horarios as $horario) {
                                $selected = ($campo["horario"] == $horario) ? "selected" : "";
                                echo "<option value='$horario' $selected>$horario</option>";
                            }
                            ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="tempo_duracao">Duração:</label>
                        <select id="tempo_duracao" name="tempo_duracao" required>
                            <option value="">Selecione a duração</option>
                            <option value="1 hora" <?= $campo["tempo_duracao"] == "1 hora" ? "selected" : "" ?>>1 hora</option>
                            <option value="2 horas" <?= $campo["tempo_duracao"] == "2 horas" ? "selected" : "" ?>>2 horas</option>
                            <option value="3 horas" <?= $campo["tempo_duracao"] == "3 horas" ? "selected" : "" ?>>3 horas</option>
                            <option value="4 horas" <?= $campo["tempo_duracao"] == "4 horas" ? "selected" : "" ?>>4 horas</option>
                            <option value="Meio período (4h)" <?= $campo["tempo_duracao"] == "Meio período (4h)" ? "selected" : "" ?>>Meio período (4h)</option>
                            <option value="Período integral (8h)" <?= $campo["tempo_duracao"] == "Período integral (8h)" ? "selected" : "" ?>>Período integral (8h)</option>
                        </select>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="id_morador">Morador:</label>
                        <select id="id_morador" name="id_morador" required>
                            <option value="">Selecione o morador</option>
                            <?php
                            $moradores = mysqli_query($conn, "SELECT id_moradores, nome, bloco, torre FROM tb_moradores ORDER BY nome");
                            while ($morador = mysqli_fetch_array($moradores)) {
                                $selected = ($campo["id_morador"] == $morador["id_moradores"]) ? "selected" : "";
                                echo "<option value='" . $morador["id_moradores"] . "' $selected>" . $morador["nome"] . " - Bloco " . $morador["bloco"] . "/" . $morador["torre"] . "</option>";
                            }
                            ?>
                        </select>
                    </div>
                </div>

                <div class="form-group full-width">
                    <label for="descricao">Observações:</label>
                    <textarea id="descricao" name="descricao" rows="3"><?= $campo["descricao"] ?></textarea>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn-primary">
                        <i class="fas fa-save"></i> Salvar Alterações
                    </button>
                    <a href="consultar_reservas.php" class="btn-secondary">
                        <i class="fas fa-arrow-left"></i> Voltar
                    </a>
                </div>
            </form>
        </section>
    </main>

    <footer>
        <p>&copy; 2025 ShieldTech. Todos os direitos reservados.</p>
    </footer>
</body>
</html>