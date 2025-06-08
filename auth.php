<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();

header('Content-Type: text/html; charset=utf-8');

$arquivo_usuarios = 'data/usuarios.json';

if (!file_exists($arquivo_usuarios) || filesize($arquivo_usuarios) == 0) {
    file_put_contents($arquivo_usuarios, json_encode([]));
}

function carregarUsuarios($arquivo) {
    $conteudo = file_get_contents($arquivo);
    $usuarios = json_decode($conteudo, true);
    if (!is_array($usuarios)) {
        return [];
    }
    return $usuarios;
}

function salvarUsuarios($arquivo, $usuarios) {
    file_put_contents($arquivo, json_encode($usuarios, JSON_PRETTY_PRINT));
}

function gerarNovoId($usuarios) {
    if (empty($usuarios)) {
        return 1;
    }
    $ids = array_column($usuarios, 'ID_usuario');
    return max($ids) + 1;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $acao = $_POST['acao'] ?? '';

    if ($acao === 'cadastrar') {
        $nome = htmlspecialchars(trim($_POST['nome'] ?? ''));
        $email = htmlspecialchars(trim($_POST['email'] ?? ''));
        $senha = $_POST['senha'] ?? '';

        if (empty($nome) || empty($email) || empty($senha)) {
            header('Location: index.php?erro=' . urlencode('Por favor, preencha todos os campos!'));
            exit();
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            header('Location: index.php?erro=' . urlencode('Formato de e-mail inválido!'));
            exit();
        }

        if (strlen($senha) < 6 || strlen($senha) > 16) {
            header('Location: index.php?erro=' . urlencode('A senha deve ter entre 6 e 16 caracteres!'));
            exit();
        }

        $usuarios = carregarUsuarios($arquivo_usuarios);

        foreach ($usuarios as $usuario) {
            if ($usuario['email_usuario'] === $email) {
                header('Location: index.php?erro=' . urlencode('Este e-mail já está cadastrado!'));
                exit();
            }
        }

        $senha_hash = password_hash($senha, PASSWORD_DEFAULT);

        $novo_usuario = [
            'ID_usuario' => gerarNovoId($usuarios),
            'nome_usuario' => $nome,
            'email_usuario' => $email,
            'senha_usuario' => $senha_hash,
            'livros_baixados' => [] 
        ];

        $usuarios[] = $novo_usuario;
        salvarUsuarios($arquivo_usuarios, $usuarios);

        $_SESSION['ID_usuario'] = $novo_usuario['ID_usuario'];
        $_SESSION['nome_usuario'] = $novo_usuario['nome_usuario'];
        $_SESSION['email_usuario'] = $novo_usuario['email_usuario'];

        header('Location: index.php?sucesso=' . urlencode('Cadastro realizado com sucesso! Você foi logado.'));
        exit();

    } elseif ($acao === 'login') {
        $email = htmlspecialchars(trim($_POST['email'] ?? ''));
        $senha = $_POST['senha'] ?? '';

        if (empty($email) || empty($senha)) {
            header('Location: index.php?erro=' . urlencode('Por favor, preencha todos os campos para fazer login!'));
            exit();
        }

        $usuarios = carregarUsuarios($arquivo_usuarios);
        $usuario_encontrado = null;

        foreach ($usuarios as $usuario) {
            if ($usuario['email_usuario'] === $email) {
                $usuario_encontrado = $usuario;
                break;
            }
        }

        if ($usuario_encontrado && password_verify($senha, $usuario_encontrado['senha_usuario'])) {
            $_SESSION['ID_usuario'] = $usuario_encontrado['ID_usuario'];
            $_SESSION['nome_usuario'] = $usuario_encontrado['nome_usuario'];
            $_SESSION['email_usuario'] = $usuario_encontrado['email_usuario'];

            header('Location: index.php?sucesso=' . urlencode('Login realizado com sucesso! Bem-vindo, ' . $_SESSION['nome_usuario'] . '!'));
            exit();
        } else {
            header('Location: index.php?erro=' . urlencode('E-mail ou senha inválidos!'));
            exit();
        }
    }
}

header('Location: index.php');
exit();

?>