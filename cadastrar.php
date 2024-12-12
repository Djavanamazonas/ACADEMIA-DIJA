<?php
include 'db.php';
include 'functions.php';

// Mostrar erros
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Verificar se o parâmetro 'tipo' foi passado pela URL
$tipo = isset($_GET['tipo']) ? $_GET['tipo'] : 'aluno'; // Default é 'aluno'

// Variáveis para mensagens de erro e sucesso
$erro = '';
$sucesso = '';

// Verificar se o formulário foi enviado
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Verificar se o formulário é de cadastro de pessoa
    if (isset($_POST['tipo']) && ($_POST['tipo'] == 'aluno' || $_POST['tipo'] == 'professor')) {
        $nome = $_POST['nome'];
        $tipo = $_POST['tipo'];
        $email = $_POST['email'];
        $telefone = $_POST['telefone'];
        $peso = $_POST['peso'];
        $cpf = $_POST['cpf'];
        $data_nascimento = $_POST['data_nascimento'];

        // Chamar a função para cadastrar a pessoa
        if (cadastrarPessoa($nome, $tipo, $email, $telefone, $peso, $cpf, $data_nascimento)) {
            $sucesso = "Cadastro de pessoa realizado com sucesso!";
            $id_pessoa = $pdo->lastInsertId(); // Pega o ID da pessoa cadastrada

            // Verificar se o formulário de exercício foi enviado
            if (isset($_POST['nome_exercicio']) && isset($_POST['categoria'])) {
                // Cadastrar o exercício associado à pessoa
                $nome_exercicio = $_POST['nome_exercicio'];
                $descricao = $_POST['descricao'];
                
                // Pega a duração em minutos e segundos
                $duracao_minutos = $_POST['duracao_minutos'];
                
                
                $dificuldade = $_POST['dificuldade'];
                $categoria = $_POST['categoria'];

                // Chama a função para cadastrar o exercício
                if (cadastrarExercicio($nome_exercicio, $descricao, $duracao_minutos, $duracao_segundos, $dificuldade, $categoria, $id_pessoa)) {
                    $sucesso .= " E o exercício foi cadastrado com sucesso!";
                } else {
                    $erro = "Erro ao cadastrar o exercício. Tente novamente.";
                }
            }

            header('Location: index.php?status=success');
            exit;
        } else {
            $erro = "Erro ao cadastrar pessoa. Tente novamente.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastrar Pessoa ou Exercício</title>
    <link rel="stylesheet" href="style.css"> <!-- Link para o CSS -->
</head>

<body>
    <div class="container">
        <!-- Mostrar mensagens de erro ou sucesso -->
        <?php if (!empty($erro)): ?>
        <div class="alert alert-danger"><?= $erro ?></div>
        <?php endif; ?>
        <?php if (!empty($sucesso)): ?>
        <div class="alert alert-success"><?= $sucesso ?></div>
        <?php endif; ?>

        <!-- Formulário de Cadastro de Pessoa -->
        <h1>Cadastrar <?= ucfirst($tipo) ?></h1>

        <form method="POST">
            <div class="mb-3">
                <label for="nome" class="form-label">Nome</label>
                <input type="text" class="form-control" id="nome" name="nome" required>
            </div>
            <div class="mb-3">
                <label for="tipo" class="form-label">Tipo</label>
                <input type="text" class="form-control" id="tipo" name="tipo" value="<?= $tipo ?>" readonly required>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" name="email" required>
            </div>
            <div class="mb-3">
                <label for="telefone" class="form-label">Telefone</label>
                <input type="text" class="form-control" id="telefone" name="telefone" required>
            </div>
            <div class="mb-3">
                <label for="peso" class="form-label">Peso (kg)</label>
                <input type="number" class="form-control" id="peso" name="peso" step="0.01" min="0" required>
            </div>
            <div class="mb-3">
                <label for="cpf" class="form-label">CPF</label>
                <input type="text" class="form-control" id="cpf" name="cpf" pattern="\d{11}" required>
            </div>
            <div class="mb-3">
                <label for="data_nascimento" class="form-label">Data de Nascimento</label>
                <input type="date" class="form-control" id="data_nascimento" name="data_nascimento" required>
            </div>

            <!-- Formulário de Cadastro de Exercício -->
            <h2></h2>
            <div class="mb-3">
                <label for="nome_exercicio" class="form-label">Nome do Exercício</label>
                <input type="text" class="form-control" id="nome_exercicio" name="nome_exercicio" required>
            </div>
            <div class="mb-3">
                <label for="descricao" class="form-label">Descrição</label>
                <textarea class="form-control" id="descricao" name="descricao" required
                    style="height: 200px; width: 100%;"></textarea>
            </div>

            <div class="mb-3">
                <label for="duracao_minutos" class="form-label">Duração (Minutos)</label>
                <input type="number" class="form-control" id="duracao_minutos" name="duracao_minutos" min="0" required>
            </div>
            <div class="mb-3">
                <label for="dificuldade" class="form-label">Dificuldade</label>
                <select class="form-control" id="dificuldade" name="dificuldade" required>
                    <option value="Fácil">Fácil</option>
                    <option value="Médio">Médio</option>
                    <option value="Difícil">Difícil</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="categoria" class="form-label">Categoria</label>
                <select class="form-control" id="categoria" name="categoria" required>
                    <option value="musculação">Musculação</option>
                    <option value="pilates">Pilates</option>
                    <option value="yoga">Yoga</option>
                    <option value="crossfit">Crossfit</option>
                    <option value="zumba">Zumba</option>
                </select>
            </div>
            <button type="submit" class="btn">Cadastrar Pessoa e Exercício</button>
        </form>
    </div>
</body>

</html>