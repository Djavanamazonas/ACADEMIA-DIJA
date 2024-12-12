<?php
include 'db.php';
include 'functions.php';

// Verificar se o ID e o tipo foram passados na URL (pessoa ou exercicio)
if (isset($_GET['id']) && isset($_GET['tipo'])) {
    $id = $_GET['id'];
    $tipo = $_GET['tipo'];

    // Se o tipo for 'pessoa', deletar a pessoa
    if ($tipo === 'pessoa') {
        if (deletarPessoa($id)) {
            // Redireciona para a página principal após a exclusão
            header('Location: index.php');
            exit;
        } else {
            echo "Erro ao excluir pessoa.";
        }
    }
    // Se o tipo for 'exercicio', deletar o exercício
    elseif ($tipo === 'exercicio') {
        if (deletarExercicio($id)) {
            // Redireciona para a página principal após a exclusão
            header('Location: index.php');
            exit;
        } else {
            echo "Erro ao excluir exercício.";
        }
    } else {
        echo "Tipo de exclusão inválido.";
    }
} else {
    // Caso não tenha sido passado um ID ou tipo na URL, exibe uma mensagem de erro
    echo "ID ou tipo não fornecido.";
}
?>