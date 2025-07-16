-- Criação do banco de dados ShieldTech
CREATE DATABASE IF NOT EXISTS db_shieldtech;
USE db_shieldtech;

-- Tabela de Cargos
CREATE TABLE IF NOT EXISTS tb_cargo (
    id_cargo INT AUTO_INCREMENT PRIMARY KEY,
    nome_cargo VARCHAR(45) NOT NULL,
    descricao VARCHAR(45),
    salario_base VARCHAR(45),
    carga_horaria TIME
);

-- Tabela de Funcionários
CREATE TABLE IF NOT EXISTS tb_funcionarios (
    id_funcionarios INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(45) NOT NULL,
    cpf VARCHAR(45) UNIQUE NOT NULL,
    rg VARCHAR(45),
    data_nascimento DATE,
    sexo VARCHAR(45),
    telefone VARCHAR(45),
    email VARCHAR(45),
    endereco VARCHAR(45),
    funcao_cargo VARCHAR(45),
    salario VARCHAR(45),
    carga_horaria TIME,
    usuario_login VARCHAR(45),
    senha VARCHAR(45),
    status VARCHAR(45) DEFAULT 'Ativo',
    data_admissao DATE,
    ultimo_login VARCHAR(45)
);

-- Tabela de Moradores
CREATE TABLE IF NOT EXISTS tb_moradores (
    id_moradores INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(45) NOT NULL,
    cpf VARCHAR(45) UNIQUE NOT NULL,
    rg VARCHAR(45),
    data_nascimento DATE,
    sexo VARCHAR(45),
    telefone VARCHAR(45),
    bloco VARCHAR(45),
    torre VARCHAR(45),
    andar VARCHAR(45),
    veiculo VARCHAR(45),
    animais VARCHAR(45),
    foto VARCHAR(45),
    usuario_login VARCHAR(45),
    senha VARCHAR(45),
    status VARCHAR(45) DEFAULT 'Ativo',
    data_cadastro VARCHAR(45)
);

-- Tabela de Visitantes
CREATE TABLE IF NOT EXISTS tb_visitantes (
    id_visitantes INT AUTO_INCREMENT PRIMARY KEY,
    nome_visitante VARCHAR(45) NOT NULL,
    num_documento INT,
    telefone VARCHAR(45),
    email VARCHAR(45),
    data_nascimento DATE,
    foto TEXT,
    status VARCHAR(45) DEFAULT 'Presente'
);

-- Tabela de Veículos
CREATE TABLE IF NOT EXISTS tb_veiculos (
    id_veiculos INT AUTO_INCREMENT PRIMARY KEY,
    placa VARCHAR(10) NOT NULL,
    modelo VARCHAR(50),
    cor VARCHAR(30),
    tipo VARCHAR(30)
);

-- Tabela de Animais
CREATE TABLE IF NOT EXISTS tb_animais (
    id_animais INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(50) NOT NULL,
    tipo VARCHAR(50),
    porte VARCHAR(20),
    observacoes TEXT
);

-- Tabela de Reservas
CREATE TABLE IF NOT EXISTS tb_reservas (
    id_reservas INT AUTO_INCREMENT PRIMARY KEY,
    local VARCHAR(45),
    data VARCHAR(45),
    horario VARCHAR(45),
    tempo_duracao VARCHAR(45),
    descricao VARCHAR(45)
);

-- Tabela de Encomendas
CREATE TABLE IF NOT EXISTS tb_encomendas (
    id_encomendas INT AUTO_INCREMENT PRIMARY KEY,
    descricao TEXT,
    data_recebimento DATE,
    status VARCHAR(20) DEFAULT 'Pendente'
);

-- Tabela de Controle de Horas
CREATE TABLE IF NOT EXISTS tb_controle_de_horas (
    id_controle_de_horas INT AUTO_INCREMENT PRIMARY KEY,
    data DATE,
    total_horas_dia TIME,
    observacoes VARCHAR(45)
);

-- Inserir dados de exemplo
INSERT INTO tb_cargo (nome_cargo, descricao, salario_base, carga_horaria) VALUES
('Porteiro', 'Controle de acesso', '1500.00', '08:00:00'),
('Zelador', 'Manutenção geral', '1800.00', '08:00:00'),
('Administrador', 'Gestão do condomínio', '3000.00', '08:00:00');