<?php
include 'db.php';
include 'functions.php';

// Buscar todas as pessoas cadastradas
$pessoas = getPessoas();
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestão de Pessoas - Academia</title>
    <link rel="stylesheet" href="style.css"> <!-- Link para o CSS -->
</head>

<body>

    <div class="container mt-4">
        <div class="page-header text-center mb-4">
            <h1>Academia DIJAFIT</h1>
            <p class="subtitle">Cadastre e gerencie alunos e professores com facilidade.</p>
        </div>

        <!-- Botões para Cadastrar Aluno e Professor -->
        <div class="button-container text-center mb-4">
            <a href="cadastrar.php?tipo=aluno" class="btn">Cadastrar Aluno</a>
            <a href="cadastrar.php?tipo=professor" class="btn">Cadastrar Professor</a>
        </div>

        <!-- Mensagens de Sucesso -->
        <?php if (isset($_GET['status']) && $_GET['status'] == 'success'): ?>
        <div class="alert alert-success">Cadastro realizado com sucesso!</div>
        <?php endif; ?>

        <!-- Tabela de Pessoas Cadastradas -->
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th scope="col">Nome</th>
                        <th scope="col">Tipo</th>
                        <th scope="col">Email</th>
                        <th scope="col">Telefone</th>
                        <th scope="col">Peso (kg)</th>
                        <th scope="col">CPF</th>
                        <th scope="col">Data de Nascimento</th>
                        <th scope="col">Exercícios</th>
                        <th scope="col">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($pessoas) > 0): ?>
                    <?php foreach ($pessoas as $pessoa): ?>
                    <tr>
                        <td><?= htmlspecialchars($pessoa['nome']) ?></td>
                        <td><?= ucfirst($pessoa['tipo']) ?></td>
                        <td><?= htmlspecialchars($pessoa['email']) ?></td>
                        <td><?= htmlspecialchars($pessoa['telefone']) ?></td>
                        <td><?= number_format($pessoa['peso'], 2, ',', '.') ?></td>
                        <td><?= htmlspecialchars($pessoa['cpf']) ?></td>
                        <td><?= date('d/m/Y', strtotime($pessoa['data_nascimento'])) ?></td>

                        <!-- Exibir Exercícios -->
                        <td>
                            <?php
                            // Buscar os exercícios relacionados a essa pessoa
                            $exercicios = getExerciciosByPessoa($pessoa['id']);
                            if ($exercicios) {
                                // Exibe as informações de cada exercício
                                foreach ($exercicios as $exercicio) {
                                    // A duração agora é armazenada em minutos, então não precisamos mais converter de segundos.
                                    $minutos = $exercicio['duracao']; // Duração já em minutos

                                    
                                    echo "<b>Duração:</b> {$minutos} min <br>"; // Exibe a duração em minutos
                                    echo "<b>Dificuldade:</b> {$exercicio['dificuldade']} <br>";
                                    echo "<b>Categoria:</b> {$exercicio['categoria']} <br><br>";
                                }
                            } else {
                                echo 'Nenhum exercício cadastrado';
                            }
                            ?>
                        </td>

                        <!-- Ações -->
                        <td>
                            <a href="editar.php?id=<?= $pessoa['id'] ?>&tipo=pessoa"
                                class="btn btn-warning btn-sm">Editar</a>
                            <a href="deletar.php?id=<?= $pessoa['id'] ?>&tipo=pessoa" class="btn btn-danger btn-sm"
                                onclick="return confirm('Tem certeza que deseja excluir?')">Excluir</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                    <?php else: ?>
                    <tr>
                        <td colspan="9" class="text-center">Nenhuma pessoa cadastrada.</td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

</body>

</html>