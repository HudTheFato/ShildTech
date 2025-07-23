<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Encomenda - ShieldTech</title>
    <link rel="stylesheet" href="../css/style.css">
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
        $morador = mysqli_real_escape_string($conn, $_POST["nome_morador"]);
        $descricao = mysqli_real_escape_string($conn, $_POST["descricao"]);
        $data_recebimento = mysqli_real_escape_string($conn, $_POST["data_recebimento"]);
        $status = mysqli_real_escape_string($conn, $_POST["status"]);
        
        $sql = "INSERT INTO tb_encomendas (nome_morador, descricao, data_recebimento, status) 
                VALUES ('$morador', '$descricao', '$data_recebimento', '$status')";
        
        if (mysqli_query($conn, $sql)) {
            echo "<script>alert('encomenda cadastrado com sucesso!'); window.location = 'consultar_encomendas.php';</script>";
        } else {
            echo "<script>alert('Erro ao cadastrar encomenda: " . mysqli_error($conn) . "');</script>";
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
                <li><a href="../encomendas/cadastro_encomendas.php"><i class="fas fa-box"></i> encomendas</a></li>
                <li class="dropdown">
                    <a href="#" class="dropbtn"><i class="fas fa-gear"></i> Cadastros</a>
                    <div class="dropdown-content">
                        <a href="../moradores/cadastro_moradores.php">Moradores</a>
                        <a href="../funcionarios/cadastro_funcionarios.php">Funcionários</a>
                        <a href="../cargos/cadastro_cargos.php">Cargos</a>
                        <a href="../animais/cadastro_animais.php">Animais</a>
                    </div>
                </li>
            </ul>
        </nav>
    </header>

    <main>
        <h2>Gestão de Encomendas</h2>

        <section class="form-section">
            <h3>Cadastro de Encomendas</h3>
            <form method="post" action="">
                <div class="form-group">
                    <label for="nome">Morador:</label>
                    <input type="text" id="nome" name="nome_morador" required>
                </div>

                <div class="form-group">
                    <label for="cpf">Descricao:</label>
                    <input type="text" id="cpf" name="descricao" placeholder="" required>
                </div>

                <div class="form-group">
                    <label for="rg">Data_recebimento:</label>
                    <input type="date" id="rg" name="data_recebimento" required>
                </div>

<div style="display: flex; flex-direction: column;">
        <label>Status:</label>
        <select name="status" class="form-control" required>
            <option value="">-- Selecione --</option>
            <option value="Pendente">Pendente</option>
            <option value="Entregue">Entregue</option>
            <option value="Cancelado">Cancelado</option>
        </select>
    </div>

                <div class="form-actions">
                    <button type="submit" class="btn-primary">
                        <i class="fas fa-save"></i> Cadastrar Encomendas
                    </button>
                    <a href="consultar_encomendas.php" class="btn-secondary">
                        <i class="fas fa-list"></i> Ver Encomendas
                    </a>
                </div>
            </form>
        </section>
    </main>

    <footer>
        <p>&copy; 2025 ShieldTech. Todos os direitos reservados.</p>
    </footer>

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
    </script>
    
    <style>
  /* Variáveis */
:root {
    --primary-color: #2c3e50;
    --secondary-color: #34495e;
    --accent-color: #3498db;
    --success-color: #2ecc71;
    --error-color: #e74c3c;
    --warning-color: #f1c40f;
    --text-color: #2c3e50;
    --light-text: #7f8c8d;
    --border-color: #d1d5db;
    --background-color: #f8fafc;
    --white: #ffffff;
    --shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
    --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
    --transition: all 0.3s ease;
}

/* Reset e Estilos Base */
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    font-family: 'Poppins', system-ui, -apple-system, sans-serif;
}

body {
    background-color: var(--background-color);
    color: var(--text-color);
    line-height: 1.5;
}

/* Container */
.container {
    min-height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 1rem;
    background:#2c3e50;
}

/* Form Container */
.form-container {
    background: var(--white);
    width: 100%;
    max-width: 28rem;
    border-radius: 1rem;
    box-shadow: var(--shadow-lg);
    padding: 2rem;
}

/* Header */
.header {
    text-align: center;
    margin-bottom: 2rem;
}

.header h1 {
    font-size: 1.875rem;
    font-weight: bold;
    color: var(--text-color);
    margin-bottom: 0.5rem;
}

.header p {
    color: var(--light-text);
}

/* Form Fields */
.input-field {
    margin-bottom: 1.5rem;
}

label {
    display: block;
    font-size: 0.875rem;
    font-weight: 500;
    color: var(--text-color);
    margin-bottom: 0.5rem;
}

.input-container {
    position: relative;
}

.input-container i {
    position: absolute;
    left: 0.75rem;
    top: 50%;
    transform: translateY(-50%);
    color: var(--light-text);
    width: 1.25rem;
    height: 1.25rem;
}

input {
    width: 100%;
    padding: 0.75rem 0.75rem 0.75rem 2.5rem;
    border: 1px solid var(--border-color);
    border-radius: 0.5rem;
    outline: none;
    transition: var(--transition);
    font-size: 0.875rem;
}

input:focus {
    border-color: var(--accent-color);
    box-shadow: 0 0 0 3px rgba(52, 152, 219, 0.2);
}

/* Error Message */
.error-message {
    display: none;
    color: var(--error-color);
    font-size: 0.875rem;
    margin-bottom: 1rem;
    padding: 0.5rem;
    border-radius: 0.25rem;
    background-color: rgba(231, 76, 60, 0.1);
}

/* Submit Button */
.submit-button {
    width: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
    background-color: var(--primary-color);
    color: var(--white);
    padding: 0.75rem 1rem;
    border: none;
    border-radius: 0.5rem;
    font-weight: 500;
    cursor: pointer;
    transition: var(--transition);
}

.submit-button:hover {
    background-color: var(--secondary-color);
}

.submit-button:disabled {
    opacity: 0.7;
    cursor: not-allowed;
}

.submit-button i {
    width: 1.25rem;
    height: 1.25rem;
}

/* Toggle Container */
.toggle-container {
    margin-top: 1.5rem;
    text-align: center;
}

.toggle-button {
    background: none;
    border: none;
    color: var(--accent-color);
    font-weight: 500;
    cursor: pointer;
    transition: var(--transition);
    text-decoration: none;
}

.toggle-button:hover {
    color: var(--primary-color);
}

/* Loading Animation */
.animate-spin {
    animation: spin 1s linear infinite;
}

@keyframes spin {
    from {
        transform: rotate(0deg);
    }
    to {
        transform: rotate(360deg);
    }
}

/* Responsive Design */
@media (max-width: 640px) {
    .form-container {
        padding: 1.5rem;
    }
    
    .header h1 {
        font-size: 1.5rem;
    }
}

/* Notifications */
.notification {
    position: fixed;
    top: 1rem;
    right: 1rem;
    padding: 1rem;
    border-radius: 0.5rem;
    background: var(--white);
    box-shadow: var(--shadow-lg);
    display: flex;
    align-items: center;
    gap: 0.5rem;
    z-index: 1000;
    animation: slideIn 0.3s ease-out;
}

.notification.success {
    border-left: 4px solid var(--success-color);
}

.notification.error {
    border-left: 4px solid var(--error-color);
}

.notification.warning {
    border-left: 4px solid var(--warning-color);
}

@keyframes slideIn {
    from {
        transform: translateX(100%);
        opacity: 0;
    }
    to {
        transform: translateX(0);
        opacity: 1;
    }
}

/* Loading States */
.loading {
    position: relative;
    pointer-events: none;
}

.loading::after {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(255, 255, 255, 0.7);
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: inherit;
}

/* Form Validation */
input:invalid {
    border-color: var(--error-color);
}

input:invalid:focus {
    box-shadow: 0 0 0 3px rgba(231, 76, 60, 0.2);
}

/* Accessibility */
.visually-hidden {
    position: absolute;
    width: 1px;
    height: 1px;
    padding: 0;
    margin: -1px;
    overflow: hidden;
    clip: rect(0, 0, 0, 0);
    white-space: nowrap;
    border: 0;
}

/* Reset e variáveis */
:root {
    --primary-color: #2c3e50;
    --secondary-color: #3498db;
    --success-color: #27ae60;
    --warning-color: #f39c12;
    --danger-color: #e74c3c;
    --light-bg: #f8f9fa;
    --dark-bg: #2c3e50;
    --text-color: #2c3e50;
    --light-text: #ffffff;
    --border-radius: 8px;
    --shadow: 0 2px 4px rgba(0,0,0,0.1);
    --transition: all 0.3s ease;
}

* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    font-family: 'Roboto', Arial, sans-serif;
    line-height: 1.6;
    color: var(--text-color);
    background-color: #f5f6fa;
    min-height: 100vh;
    display: flex;
    flex-direction: column;
}

/* Header e navegação */
header {
    background-color: var(--dark-bg);
    color: var(--light-text);
    padding: 1rem;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    position: fixed;
    width: 100%;
    top: 0;
    z-index: 1000;
}

nav {
    display: flex;
    justify-content: space-between;
    align-items: center;
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 1rem;
}

.logo h1 {
    font-size: 1.5rem;
    font-weight: 700;
    color: var(--light-text);
}

.menu {
    display: flex;
    gap: 1rem;
    align-items: center;
    margin: 0;
    padding: 0;
    list-style: none;
}

.menu li {
    position: relative;
}

nav {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 1rem 2rem;
    background-color: var(--primary-color);
}

.logo h1 {
    color: var(--white);
    font-size: 1.5rem;
    margin: 0;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.menu a {
    color: var(--light-text);
    text-decoration: none;
    font-weight: 400;
    padding: 0.5rem 1rem;
    border-radius: var(--border-radius);
    transition: var(--transition);
}

.menu a:hover {
    background-color: rgba(255, 255, 255, 0.1);
}

/* Main content */
main {
    max-width: 1200px;
    margin: 6rem auto 2rem;
    padding: 0 1rem;
    flex: 1;
}

/* Dashboard */
.dashboard {
    margin-bottom: 2rem;
}

.dashboard h2 {
    color: var(--text-color);
    margin-bottom: 1.5rem;
    font-size: 1.8rem;
}

.cards {
    display: flex;
    justify-content: space-between;
    gap: 1.5rem;
    margin-top: 1rem;
}

.card {
    background-color: white;
    padding: 1.2rem;
    border-radius: var(--border-radius);
    box-shadow: var(--shadow);
    transition: var(--transition);
    flex: 1;
}

.card:hover {
    transform: translateY(-5px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.15);
}

.card h3 {
    color: var(--primary-color);
    font-size: 1.1rem;
    margin-bottom: 1rem;
}

.card p {
    font-size: 2rem;
    font-weight: bold;
    color: var(--secondary-color);
}

/* Botões */
.botoes {
    display: flex;
    gap: 1rem;
    margin-top: 2rem;
    flex-wrap: wrap;
}

button {
    padding: 0.8rem 1.5rem;
    border: none;
    border-radius: var(--border-radius);
    cursor: pointer;
    font-weight: 500;
    transition: var(--transition);
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

button:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.15);
}

.btn-primary {
    background-color: var(--secondary-color);
    color: white;
}

.btn-success {
    background-color: var(--success-color);
    color: white;
}

.btn-danger {
    background-color: var(--danger-color);
    color: white;
}

/* Formulários */
.form-section {
    background-color: white;
    padding: 2rem;
    border-radius: var(--border-radius);
    box-shadow: var(--shadow);
    margin-bottom: 2px;
    
 
}

.form-section h3 {
    color: var(--text-color);
    margin-bottom: 1.5rem;
    font-size: 1.5rem;
    
}


.form-group {
    margin-bottom: 1.5rem;  
    
}

.form-group label {
    margin-bottom: 0.5rem;
    font-weight: 500;
    color: var(--text-color);
    margin-top: 2rem;
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 1rem;
    
}

.form-group input,
.form-group select {
    width: 100%;
    padding: 0.8rem;
    border: 1px solid #ddd;
    border-radius: var(--border-radius);
    font-size: 1rem;
    transition: var(--transition);
    
}

.form-group input:focus,
.form-group select:focus {
    outline: none;
    border-color: var(--secondary-color);
    box-shadow: 0 0 0 2px rgba(52, 152, 219, 0.2);
}

/* Listas */
.lista-section {
    margin-top: 2rem;
}

.lista-section h3 {
    color: var(--text-color);
    margin-bottom: 1rem;
    font-size: 1.5rem;
}

.lista-item {
    background-color: white;
    padding: 1.5rem;
    border-radius: var(--border-radius);
    margin-bottom: 1rem;
    box-shadow: var(--shadow);
    transition: var(--transition);
}

.lista-item:hover {
    transform: translateX(5px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.15);
}

.lista-item h4 {
    color: var(--primary-color);
    margin-bottom: 0.5rem;
    font-size: 1.2rem;
}

.lista-item p {
    color: #666;
    margin-bottom: 0.5rem;
}

/* Cards de Funcionários */
.cards-funcionarios {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
    gap: 1.5rem;
    margin-top: 1rem;
}

.card-funcionario {
    background-color: white;
    border-radius: var(--border-radius);
    box-shadow: var(--shadow);
    overflow: hidden;
    transition: var(--transition);
    border-left: 4px solid var(--secondary-color);
}

.card-funcionario:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 16px rgba(0,0,0,0.1);
}

.card-header {
    background-color: #f8f9fa;
    padding: 4rem;
    border-bottom: 1px solid #eee;
    display: flex;
    align-items: center;
    gap: 0.8rem;
}

.card-header i {
    font-size: 1.5rem;
    color: var(--secondary-color);
}

.card-header h4 {
    color: var(--primary-color);
    font-size: 1.2rem;
    margin: 0;
    flex: 1;
}

.card-body {
    padding: 1rem;
}

.card-body p {
    margin-bottom: 0.8rem;
    color: #555;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.card-body p i {
    color: var(--secondary-color);
    width: 20px;
    text-align: center;
}

.card-footer {
    padding: 1rem;
    background-color: #f8f9fa;
    border-top: 1px solid #eee;
    display: flex;
    justify-content: flex-end;
    gap: 0.8rem;
}

.btn-editar, .btn-remover {
    padding: 0.5rem 1rem;
    border-radius: var(--border-radius);
    font-size: 0.9rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
    cursor: pointer;
    transition: var(--transition);
}

.btn-editar {
    background-color: #f8f9fa;
    color: var(--secondary-color);
    border: 1px solid var(--secondary-color);
}

.btn-remover {
    background-color: #f8f9fa;
    color: var(--danger-color);
    border: 1px solid var(--danger-color);
}

.btn-editar:hover {
    background-color: var(--secondary-color);
    color: white;
}

.btn-remover:hover {
    background-color: var(--danger-color);
    color: white;
}

.sem-registros {
    text-align: center;
    padding: 2rem;
    background-color: #f8f9fa;
    border-radius: var(--border-radius);
    color: #777;
    font-style: italic;
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 1rem;
}

.sem-registros i {
    font-size: 2rem;
    color: var(--secondary-color);
}

/* Tabelas de relatório */
.tabela-relatorio {
    width: 100%;
    border-collapse: collapse;
    margin-top: 1rem;
    background-color: white;
    border-radius: var(--border-radius);
    overflow: hidden;
    box-shadow: var(--shadow);
}

.tabela-relatorio th,
.tabela-relatorio td {
    padding: 1rem;
    text-align: left;
    border-bottom: 1px solid #eee;
}

.tabela-relatorio th {
    background-color: var(--primary-color);
    color: white;
    font-weight: 500;
}

.tabela-relatorio tr:hover {
    background-color: #f5f6fa;
}

/* Footer */
footer {
    background-color: var(--dark-bg);
    color: var(--light-text);
    text-align: center;
    padding: 1rem;
    width: 100%;
    margin-top: auto;
    position: sticky;
    bottom: 0;
}

/* Responsividade */
@media (max-width: 768px) {
    .menu {
        display: none;
        position: absolute;
        top: 100%;
        left: 0;
        right: 0;
        background-color: var(--dark-bg);
        padding: 1rem;
        flex-direction: column;
        gap: 1rem;
    }

    .menu.active {
        display: flex;
    }

    .menu-toggle {
        display: block;
        background: none;
        border: none;
        color: white;
        font-size: 1.5rem;
        cursor: pointer;
    }

    main {
        margin-top: 5rem;
        padding: 0 1rem 1rem;
    }

    .cards {
        flex-direction: column;
    }

    .botoes {
        flex-direction: column;
    }

    button {
        width: 100%;
    }
}

/* Animações */
@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.card, .lista-item, .form-section {
    animation: fadeIn 0.5s ease-out;
} 

  form {
    margin-top: 2rem;
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 1rem;
  }

  /* Dropdown Menu */
.dropdown {
    position: relative;
    display: inline-block;
}

.dropdown-content {
    display: none;
    position: absolute;
    background-color: var(--white);
    min-width: 200px;
    box-shadow: var(--shadow-lg);
    z-index: 100;
    border-radius: 0.5rem;
    margin-top: 0.5rem;
    border: 1px solid var(--border-color);
    right: 0;
}

.dropdown:hover .dropdown-content {
    display: block;
}

.dropbtn {
    color: var(--white);
    text-decoration: none;
    display: flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.5rem 1rem;
}

.dropdown-content a {
    color: var(--primary-color);
    padding: 12px 16px;
    text-decoration: none;
    display: block;
    font-weight: 500;
    transition: all 0.3s ease;
}

.dropdown-content a:hover {
    background-color: var(--background-color);
    color: var(--accent-color);
    padding-left: 20px;
}

.menu .dropdown {
    position: relative;
}

.menu .dropdown .dropbtn:hover {
    color: var(--accent-color);
}
</style>
</body>
</html>