<?php
include 'db.php';
include 'functions.php';

// Verifica se o ID e o tipo foram passados via GET
if (isset($_GET['id']) && isset($_GET['tipo'])) {
    $id = $_GET['id'];
    $tipo = $_GET['tipo'];

    // Se o tipo for 'pessoa', buscar e editar a pessoa
    if ($tipo === 'pessoa') {
        // Buscar a pessoa a ser editada
        $sql = "SELECT * FROM pessoas WHERE id = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$id]);
        $pessoa = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$pessoa) {
            die('Pessoa não encontrada.');
        }

        // Buscar os exercícios relacionados a essa pessoa
        $sql = "SELECT e.id, e.nome, e.descricao, e.dificuldade, e.categoria, e.duracao FROM exercicios e
                JOIN pessoa_exercicio pe ON e.id = pe.exercicio_id
                WHERE pe.pessoa_id = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$id]);
        $exercicios = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Processar a atualização quando o formulário for enviado
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Recebe os dados do formulário
            $nome = $_POST['nome'];
            $tipo = $_POST['tipo'];
            $email = $_POST['email'];
            $telefone = $_POST['telefone'];
            $peso = $_POST['peso'];
            $cpf = $_POST['cpf'];
            $data_nascimento = $_POST['data_nascimento'];

            // Atualiza os dados da pessoa no banco
            if (atualizarPessoa($id, $nome, $tipo, $email, $telefone, $peso, $cpf, $data_nascimento)) {
                // Atualizar os exercícios relacionados (se houver alterações)
                if (isset($_POST['exercicios'])) {
                    // Deletar os exercícios antigos da pessoa
                    $sql = "DELETE FROM pessoa_exercicio WHERE pessoa_id = ?";
                    $stmt = $pdo->prepare($sql);
                    $stmt->execute([$id]);

                    // Adicionar os novos exercícios relacionados
                    foreach ($_POST['exercicios'] as $exercicio_id => $exercicio) {
                        // Associar o exercício à pessoa
                        $sql = "INSERT INTO pessoa_exercicio (pessoa_id, exercicio_id) VALUES (?, ?)";
                        $stmt = $pdo->prepare($sql);
                        $stmt->execute([$id, $exercicio_id]);

                        // Atualizar as informações do exercício
                        $sql = "UPDATE exercicios SET descricao = ?, dificuldade = ?, categoria = ?, duracao = ? WHERE id = ?";
                        $stmt = $pdo->prepare($sql);
                        $stmt->execute([ 
                            $exercicio['descricao'], 
                            $exercicio['dificuldade'], 
                            $exercicio['categoria'], 
                            $exercicio['duracao'],  // Atualizar com duração em minutos
                            $exercicio_id
                        ]);
                    }
                }

                header('Location: index.php');
                exit;
            } else {
                echo "Erro ao atualizar!";
            }
        }
    }
    // Se o tipo for 'exercicio', buscar e editar o exercício
    elseif ($tipo === 'exercicio') {
        // Buscar o exercício a ser editado
        $sql = "SELECT * FROM exercicios WHERE id = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$id]);
        $exercicio = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$exercicio) {
            die('Exercício não encontrado.');
        }

        // Processar a atualização quando o formulário for enviado
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Recebe os dados do formulário
            $nome = $_POST['nome'];
            $descricao = $_POST['descricao'];
            $categoria = $_POST['categoria'];
            $dificuldade = $_POST['dificuldade'];
            $duracao_minutos = $_POST['duracao_minutos']; // Recebe a duração em minutos

            // Atualiza os dados do exercício no banco
            if (atualizarExercicio($id, $nome, $descricao, $dificuldade, $categoria, $duracao_minutos)) {
                header('Location: index.php');
                exit;
            } else {
                echo "Erro ao atualizar exercício!";
            }
        }
    } else {
        die('Tipo inválido.');
    }
} else {
    die('ID ou tipo não fornecido.');
}
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar <?= ucfirst($tipo) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container">
        <h1 class="text-center mt-5">Editar <?= ucfirst($tipo) ?></h1>

        <?php if ($tipo === 'pessoa'): ?>
        <form method="POST" class="mt-4">
            <div class="mb-3">
                <label for="nome" class="form-label">Nome</label>
                <input type="text" class="form-control" id="nome" name="nome"
                    value="<?= htmlspecialchars($pessoa['nome']) ?>" required>
            </div>
            <div class="mb-3">
                <label for="tipo" class="form-label">Tipo</label>
                <select class="form-select" id="tipo" name="tipo" required>
                    <option value="aluno" <?= $pessoa['tipo'] == 'aluno' ? 'selected' : '' ?>>Aluno</option>
                    <option value="professor" <?= $pessoa['tipo'] == 'professor' ? 'selected' : '' ?>>Professor</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" name="email"
                    value="<?= htmlspecialchars($pessoa['email']) ?>" required>
            </div>
            <div class="mb-3">
                <label for="telefone" class="form-label">Telefone</label>
                <input type="tel" class="form-control" id="telefone" name="telefone"
                    value="<?= htmlspecialchars($pessoa['telefone']) ?>" required>
            </div>
            <div class="mb-3">
                <label for="peso" class="form-label">Peso (kg)</label>
                <input type="number" class="form-control" id="peso" name="peso" step="0.01" min="0"
                    value="<?= htmlspecialchars($pessoa['peso']) ?>" required>
            </div>
            <div class="mb-3">
                <label for="cpf" class="form-label">CPF</label>
                <input type="text" class="form-control" id="cpf" name="cpf" pattern="\d{11}"
                    value="<?= htmlspecialchars($pessoa['cpf']) ?>" required>
            </div>
            <div class="mb-3">
                <label for="data_nascimento" class="form-label">Data de Nascimento</label>
                <input type="date" class="form-control" id="data_nascimento" name="data_nascimento"
                    value="<?= htmlspecialchars($pessoa['data_nascimento']) ?>" required>
            </div>

            <!-- Exibição e edição dos exercícios relacionados -->
            <div class="mb-3">
                <label class="form-label"></label>
                <?php foreach ($exercicios as $exercicio): ?>
                <div class="mb-3">
                    <label for="exercicio_<?= $exercicio['id'] ?>" class="form-label">Exercício:
                        <?= htmlspecialchars($exercicio['nome']) ?></label>
                    <input type="hidden" name="exercicios[<?= $exercicio['id'] ?>][id]" value="<?= $exercicio['id'] ?>">

                    <div class="mb-3">
                        <label for="descricao_<?= $exercicio['id'] ?>" class="form-label">Descrição</label>
                        <textarea class="form-control"
                            name="exercicios[<?= $exercicio['id'] ?>][descricao]"><?= htmlspecialchars($exercicio['descricao']) ?></textarea>
                    </div>

                    <div class="mb-3">
                        <label for="dificuldade_<?= $exercicio['id'] ?>" class="form-label">Dificuldade</label>
                        <select class="form-select" name="exercicios[<?= $exercicio['id'] ?>][dificuldade]" required>
                            <option value="Fácil" <?= $exercicio['dificuldade'] == 'Fácil' ? 'selected' : '' ?>>Fácil
                            </option>
                            <option value="Médio" <?= $exercicio['dificuldade'] == 'Médio' ? 'selected' : '' ?>>Médio
                            </option>
                            <option value="Difícil" <?= $exercicio['dificuldade'] == 'Difícil' ? 'selected' : '' ?>>
                                Difícil</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="categoria_<?= $exercicio['id'] ?>" class="form-label">Categoria</label>
                        <select class="form-select" name="exercicios[<?= $exercicio['id'] ?>][categoria]" required>
                            <option value="musculacao" <?= $exercicio['categoria'] == 'musculacao' ? 'selected' : '' ?>>
                                Musculação</option>
                            <option value="pilates" <?= $exercicio['categoria'] == 'pilates' ? 'selected' : '' ?>>
                                Pilates</option>
                            <option value="yoga" <?= $exercicio['categoria'] == 'yoga' ? 'selected' : '' ?>>Yoga
                            </option>
                            <option value="crossfit" <?= $exercicio['categoria'] == 'crossfit' ? 'selected' : '' ?>>
                                Crossfit</option>
                            <option value="zumba" <?= $exercicio['categoria'] == 'zumba' ? 'selected' : '' ?>>Zumba
                            </option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="duracao_<?= $exercicio['id'] ?>" class="form-label">Duração (Minutos)</label>
                        <input type="number" class="form-control" name="exercicios[<?= $exercicio['id'] ?>][duracao]"
                            value="<?= $exercicio['duracao'] ?>" min="0" required>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>

            <button type="submit" class="btn btn-primary">Atualizar</button>
        </form>
        <?php endif; ?>
    </div>
</body>

</html>