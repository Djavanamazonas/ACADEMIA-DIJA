<?php
// Função para cadastrar uma nova pessoa
function cadastrarPessoa($nome, $tipo, $email, $telefone, $peso, $cpf, $data_nascimento) {
    global $pdo;

    // Validar CPF: apenas números e deve ter 11 dígitos
    if (!preg_match("/^\d{11}$/", $cpf)) {
        return false; // CPF inválido
    }

    // Preparar a query para inserir os dados na tabela 'pessoas'
    $sql = "INSERT INTO pessoas (nome, tipo, email, telefone, peso, cpf, data_nascimento) 
            VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = $pdo->prepare($sql);
    
    // Executar a query com os dados recebidos
    return $stmt->execute([$nome, $tipo, $email, $telefone, $peso, $cpf, $data_nascimento]);
}

// Função para obter todas as pessoas (alunos ou professores)
function getPessoas() {
    global $pdo;
    $sql = "SELECT * FROM pessoas";
    $stmt = $pdo->query($sql);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Função para atualizar os dados de uma pessoa
function atualizarPessoa($id, $nome, $tipo, $email, $telefone, $peso, $cpf, $data_nascimento) {
    global $pdo;
    $sql = "UPDATE pessoas SET nome = ?, tipo = ?, email = ?, telefone = ?, peso = ?, cpf = ?, data_nascimento = ? WHERE id = ?";
    $stmt = $pdo->prepare($sql);
    return $stmt->execute([$nome, $tipo, $email, $telefone, $peso, $cpf, $data_nascimento, $id]);
}

// Função para deletar uma pessoa pelo ID
function deletarPessoa($id) {
    global $pdo;
    $sql = "DELETE FROM pessoas WHERE id = ?";
    $stmt = $pdo->prepare($sql);
    return $stmt->execute([$id]);
}

// Função para cadastrar um novo exercício e relacioná-lo com a pessoa
function cadastrarExercicio($nome, $descricao, $duracao_minutos, $duracao_segundos, $dificuldade, $categoria, $id_pessoa) {
    global $pdo;

    // Converter a duração para segundos
    $duracao = ($duracao_minutos * 60) + $duracao_segundos;

    // Inserir o exercício na tabela exercicios
    $sql = "INSERT INTO exercicios (nome, descricao, duracao, dificuldade, categoria) 
            VALUES (?, ?, ?, ?, ?)";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$nome, $descricao, $duracao, $dificuldade, $categoria]);
    $id_exercicio = $pdo->lastInsertId(); // Pega o ID do exercício inserido

    // Relacionar a pessoa ao exercício na tabela pessoa_exercicio
    $sql = "INSERT INTO pessoa_exercicio (pessoa_id, exercicio_id) VALUES (?, ?)";
    $stmt = $pdo->prepare($sql);
    return $stmt->execute([$id_pessoa, $id_exercicio]);
}

// Função para deletar um exercício pelo ID
function deletarExercicio($id) {
    global $pdo;
    $sql = "DELETE FROM exercicios WHERE id = ?";
    $stmt = $pdo->prepare($sql);
    return $stmt->execute([$id]);
}

// Função para atualizar os dados de um exercício
function atualizarExercicio($id, $nome, $descricao, $duracao_minutos, $dificuldade, $categoria) {
    global $pdo;

    // Converter a duração para segundos
    $duracao = ($duracao_minutos * 60) ;

    // Atualizar o exercício no banco de dados
    $sql = "UPDATE exercicios SET nome = ?, descricao = ?, duracao = ?, dificuldade = ?, categoria = ? WHERE id = ?";
    $stmt = $pdo->prepare($sql);
    return $stmt->execute([$nome, $descricao, $duracao, $dificuldade, $categoria, $id]);
}

// Função para obter todos os exercícios
function getExercicios() {
    global $pdo;
    $sql = "SELECT * FROM exercicios";
    $stmt = $pdo->query($sql);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Função para pegar um exercício específico pelo ID
function getExercicioById($id) {
    global $pdo;
    $sql = "SELECT * FROM exercicios WHERE id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

// Função para obter os exercícios relacionados a uma pessoa específica
function getExerciciosByPessoa($pessoa_id) {
    global $pdo;
    
    // Seleciona os exercícios e suas informações (nome, duração, dificuldade, categoria) relacionados à pessoa
    $sql = "SELECT e.nome, e.duracao, e.dificuldade, e.categoria 
            FROM exercicios e
            JOIN pessoa_exercicio pe ON e.id = pe.exercicio_id
            WHERE pe.pessoa_id = ?";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$pessoa_id]);
    $exercicios = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Retorna os dados dos exercícios em um formato adequado para exibição
    return $exercicios;
}

?>