-- Criação do banco de dados
CREATE DATABASE IF NOT EXISTS academiaDJ;

-- Uso do banco de dados
USE academiaDJ;

-- Criação da tabela 'pessoas'
CREATE TABLE IF NOT EXISTS pessoas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(255) NOT NULL,
    tipo ENUM('aluno', 'professor') NOT NULL,
    email VARCHAR(255) NOT NULL,
    telefone VARCHAR(15) NOT NULL,
    peso DECIMAL(5,2) NOT NULL,
    cpf VARCHAR(11) NOT NULL UNIQUE,  -- Adicionando restrição de unicidade no CPF
    data_nascimento DATE NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Criação da tabela 'exercicios'
CREATE TABLE IF NOT EXISTS exercicios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(255) NOT NULL,
    descricao TEXT NOT NULL,
    duracao INT NOT NULL,  -- Duração em segundos
    dificuldade ENUM('Fácil', 'Médio', 'Difícil') NOT NULL,
    categoria ENUM('musculação', 'pilates', 'yoga', 'crossfit', 'zumba') NOT NULL,
    data_criacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Criação da tabela 'pessoa_exercicio' para o relacionamento entre pessoas e exercícios
CREATE TABLE IF NOT EXISTS pessoa_exercicio (
    pessoa_id INT,
    exercicio_id INT,
    PRIMARY KEY (pessoa_id, exercicio_id),
    FOREIGN KEY (pessoa_id) REFERENCES pessoas(id) ON DELETE CASCADE,  -- Se pessoa for deletada, apaga os exercícios associados
    FOREIGN KEY (exercicio_id) REFERENCES exercicios(id) ON DELETE CASCADE  -- Se exercício for deletado, apaga a associação
);




