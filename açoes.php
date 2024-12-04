<?php
require "conexao.php"; // Inclui a conexão com o banco de dados

if (isset($_POST['create_usuario'])) {
    // Sanitização e validação dos dados
    $nome = mysqli_real_escape_string($conexao, trim($_POST['nome']));
    $email = mysqli_real_escape_string($conexao, trim($_POST['email']));
    $data_nascimento = mysqli_real_escape_string($conexao, trim($_POST['data_nascimento']));
    $senha = isset($_POST['senha']) ? mysqli_real_escape_string($conexao, trim($_POST['senha'])) : '';

    // Verificação de campos vazios
    if (empty($nome) || empty($email) || empty($data_nascimento) || empty($senha)) {
        die("Todos os campos devem ser preenchidos!");
    }

    // Hash da senha
    $senha_hash = password_hash($senha, PASSWORD_DEFAULT);

    // Query segura com Prepared Statement
    $sql = "INSERT INTO usuarios (nome, email, data_nascimento, senha) VALUES (?, ?, ?, ?)";
    $stmt = $conexao->prepare($sql);

    if (!$stmt) {
        die("Erro ao preparar a query: " . $conexao->error);
    }

    // Bind dos parâmetros
    $stmt->bind_param("ssss", $nome, $email, $data_nascimento, $senha_hash);

    // Execução da query
    if ($stmt->execute()) {
        echo "Usuário criado com sucesso!";
    } else {
        echo "Erro ao criar usuário: " . $stmt->error;
    }

    // Fechar a declaração e a conexão
    $stmt->close();
    $conexao->close();
}


if (isset($_POST['update_usuario'])) {
    // Incluindo a conexão com o banco de dados (ajuste conforme sua aplicação)
    require 'conexao.php';

    // Sanitização e validação dos dados
    $usuario_id = mysqli_real_escape_string($conexao, $_POST['usuario_id']);
    $nome = mysqli_real_escape_string($conexao, trim($_POST['nome']));
    $email = mysqli_real_escape_string($conexao, trim($_POST['email']));
    $data_nascimento = mysqli_real_escape_string($conexao, trim($_POST['data_nascimento']));
    $senha = mysqli_real_escape_string($conexao, trim($_POST['senha']));

    // Monta a query base
    $sql = "UPDATE usuarios SET 
            nome = '$nome', 
            email = '$email', 
            data_nascimento = '$data_nascimento'";

    // Adiciona a senha apenas se fornecida
    if (!empty($senha)) {
        $senha_hash = password_hash($senha, PASSWORD_DEFAULT);
        $sql .= ", senha = '$senha_hash'";
    }

    // Finaliza a query
    $sql .= " WHERE id = '$usuario_id'";

    // Executa a query
    if (mysqli_query($conexao, $sql)) {
        // Verifica se a atualização foi feita
        if (mysqli_affected_rows($conexao) > 0) {
            $_SESSION['mensagem'] = 'Usuário atualizado com sucesso';
        } else {
            $_SESSION['mensagem'] = 'Nenhuma alteração foi feita no usuário';
        }
    } else {
        $_SESSION['mensagem'] = 'Erro ao atualizar usuário: ' . mysqli_error($conexao);
    }

    // Redireciona para a página inicial
    header('Location: index.php');
    exit;
}


if (isset($_POST['delete_usuario'])) {
    require 'conexao.php';

    $sql = "DELETE FROM usuarios WHERE id = ?";
    $stmt = mysqli_prepare($conexao, $sql);
    mysqli_stmt_bind_param($stmt, 'i', $_POST['delete_usuario']);
    
    if (mysqli_stmt_execute($stmt)) {
        if (mysqli_stmt_affected_rows($stmt) > 0) {
            $_SESSION['message'] = 'Usuário deletado com sucesso';
        } else {
            $_SESSION['message'] = 'Usuário não encontrado ou já deletado';
        }
    } else {
        $_SESSION['message'] = 'Erro ao deletar usuário: ' . mysqli_error($conexao);
    }

    mysqli_stmt_close($stmt);
    header('Location: index.php');
    exit;
}

?>


