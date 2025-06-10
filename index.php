<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();

header('Content-Type: text/html; charset=utf-8');

$arquivo_usuarios = 'data/usuarios.json';
if (!file_exists(dirname($arquivo_usuarios))) {
    mkdir(dirname($arquivo_usuarios), 0777, true);
}
if (!file_exists($arquivo_usuarios) || filesize($arquivo_usuarios) == 0) {
    file_put_contents($arquivo_usuarios, json_encode([]));
}

$logado = isset($_SESSION['ID_usuario']);
$nome_usuario = $logado ? $_SESSION['nome_usuario'] : '';

$messageHtml = '';
if (isset($_SESSION['message'])) {
    $type = $_SESSION['message']['type']; 
    $text = htmlspecialchars($_SESSION['message']['text']);
    $messageHtml = "<div class='message $type'>$text</div>";
    unset($_SESSION['message']); 
}

$livros = [];
$caminho_livros = 'data/livros.json';

if (file_exists($caminho_livros)) {
    $json_livros = file_get_contents($caminho_livros);
    $livros = json_decode($json_livros, true);
}

function livrosPorCategoria($livros, $categoria) {
    if ($categoria === 'Todos') {
        return $livros;
    }
    return array_filter($livros, function($livro) use ($categoria) {
        return strtolower($livro['categoria']) === strtolower($categoria);
    });
}

?>



<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Alexandria Biblioteca</title>
    <link rel="icon" href="icons/LogoMiniaturaClaro.png" type="image/x-icon">
    <link rel="stylesheet" href="style.css">
</head>

<body>

    <div class="sidebar">
        <button class="toggle-btnL"><img draggable="false" src="img/LogoEscuro.png" alt="Logo Alexandria" class="logo-sidebar"></button>

        <button class="mode-toggle">
            <img draggable="false" id="theme-icon" src="img/Escuro.png" alt="Tema" class="tema-icone">
        </button>
        <button class="download-button"><img draggable="false" id="download-icon" src="img/DownloadEscuro.png" class="download-icon" alt="Baixados"></button>
        <button class="saved-button"><img draggable="false" id="saved-icon" src="img/SavedEscuro.png" class="saved-icon" alt="salvos"></button>
        <button class="visua-button"><img draggable="false" id="visua-icon" src="img/EyeEscuro.png" class="visua-icon" alt="já lidos"></button>
        <button class="info-button"><img draggable="false" id="info-icon" src="img/infoEscuro.png" class="info-icon" alt="informação"></button>

    </div>

    <div class="main-content">

        <header>
            <input type="text" placeholder="Pesquise o nome do livro">
            <button class="search-btn"></button>

            <div class="user-info-container">
                <?php if ($logado): ?>
                    <span class="welcome-message">Olá, <?php echo htmlspecialchars($nome_usuario); ?>!</span>
                    <button class="user-btn" id="logoutBtn">
                        <img draggable="false" src="img/Conta.png" style="width: 25px; height: 25px;">
                    </button>
                    <div class="user-form-bubble hidden" id="logoutBubble">
                        <p>Você está logado como: <strong><?php echo htmlspecialchars($nome_usuario); ?></strong></p>
                        <button onclick="window.location.href='logout.php'">Sair</button>
                    </div>
                <?php else: ?>
                    <button class="user-btn" id="userBtn">
                        <img draggable="false" src="img/Conta.png" style="width: 25px; height: 25px;">
                    </button>
                    <div class="user-form-bubble hidden" id="userForm">
                        <h2 id="form-title">Cadastro</h2>
                        <form action="auth.php" method="POST" id="auth-form">
                            <input type="hidden" name="acao" value="cadastrar" id="acao">

                            <div id="nome-field">
                                <input type="text" name="nome" placeholder="Nome completo" required>
                            </div>

                            <input type="email" name="email" placeholder="E-mail" required>
                            <input type="password" name="senha" placeholder="Senha" required>

                            <div class="form-buttons">
                                <button type="submit" id="submit-button">Cadastrar</button>
                                <button type="button" onclick="toggleForm()">Fechar</button>
                            </div>
                        </form>

                        <hr style="margin: 10px 0;">

                        <button type="button" id="alternarFormularioBtn">Já tem uma conta? Entrar</button>
                    </div>
                <?php endif; ?>
            </div>
        </header>

        <nav class="categories">
            <div class="category active"><img draggable="false" src="icons/todos.png" alt="Todos">Todos</div>
            <div class="category"><img draggable="false" src="icons/em-alta.png" alt="Em alta">Em alta</div>
            <div class="category"><img draggable="false" src="icons/novos.png" alt="Novos">Novos</div>
            <div class="category"><img draggable="false" src="icons/acao.png" alt="Ação">Ação</div>
            <div class="category"><img draggable="false" src="icons/fantasia.png" alt="Fantasia">Fantasia</div>
            <div class="category"><img draggable="false" src="icons/ficcao.png" alt="Ficcao">Ficção</div>
            <div class="category"><img draggable="false" src="icons/romance.png" alt="Romance">Romance</div>
        </nav>

        <section class="highlight">
            <h2>Todos</h2>
            <div class="book-list">
            <?php    
            $todos = livrosPorCategoria($livros, 'Todos');
            ?>
            </div>
        </section>

        <br>
        
        <section class="highlight">
            <h2>Em Alta</h2>
            <div class="book-list">
            <?php    
            $emAlta = livrosPorCategoria($livros, 'Em Alta');
            ?>
            </div>
        </section>

        <br>

        <section class="highlight">
            <h2>Novos</h2>
            <div class="book-list">
                
            </div>
        </section>

        <br>

        <section class="highlight">
            <h2>Ação</h2>
            <div class="book-list">
            <?php
        $romance = livrosPorCategoria($livros, 'Ação');
        foreach ($romance as $livro) {
            echo '<div class="book-item">';
            echo '<img src="'.htmlspecialchars($livro['capa']).'" alt="Capa do livro '.htmlspecialchars($livro['nome']).'" width="120">';
            echo '<h3>'.htmlspecialchars($livro['nome']).'</h3>';
            echo '<p><strong>Autor:</strong> '.htmlspecialchars($livro['autor']).'</p>';
            echo '<p><strong>Editora:</strong> '.htmlspecialchars($livro['editora']).'</p>';
            echo '</div>';
        }
        ?>    
        </section>

        <br>

        <section class="highlight">
            <h2>Romance</h2>
            <div class="book-list">
                 <?php
        $romance = livrosPorCategoria($livros, 'Romance');
        foreach ($romance as $livro) {
            echo '<div class="book-item">';
            echo '<img src="'.htmlspecialchars($livro['capa']).'" alt="Capa do livro '.htmlspecialchars($livro['nome']).'" width="120">';
            echo '<h3>'.htmlspecialchars($livro['nome']).'</h3>';
            echo '<p><strong>Autor:</strong> '.htmlspecialchars($livro['autor']).'</p>';
            echo '<p><strong>Editora:</strong> '.htmlspecialchars($livro['editora']).'</p>';
            echo '</div>';
        }
        ?>
            </div>
        </section>

        <br>

        <section class="highlight">
            <h2>Baixados</h2>
            <div class="book-list">
               
                
            </div>
        </section>

        <br>

        <div class="creditos-container">
            <div class="criador">
                <img src="img/flavio.jpg" alt="Flávio H.">
                <span>Flávio H.</span>
            </div>
            <div class="criador">
                <img src="img/gabriel.png" alt="Gabriel S.">
                <span>Gabriel S.</span>
            </div>
            <div class="criador">
                <img src="img/gustavo.png" alt="Gustavo A.">
                <span>Gustavo A.</span>
            </div>
            <div class="criador">
                <img src="img/luiz.png" alt="Luiz F.">
                <span>Luiz F.</span>
            </div>
        </div>

        <br>

        <footer class="footer">
            <div class="footer-container">
                <div class="footer-about">
                    <h2>Alexandria Biblioteca</h2>
                    <p>Explore uma vasta coleção de livros em PDF gratuitamente. Conhecimento ao alcance de todos.</p>
                    <br>
                </div>

                <div class="footer-social">
                    <h3>Nos siga</h3>
                    <br>
                    <div class="social-icons">
                        <a href="https://www.facebook.com/profile.php?id=61576951933968"><img src="img/faceClaro.png" alt="Facebook" /></a>
                        <a href="https://www.instagram.com/bibl.iotecaalexandria/"><img src="img/instaClaro.png" alt="Instagram" /></a>
                        <a href="https://x.com/_Alexandria_Lib"><img src="img/XClaro.png" alt="Twitter" /></a>
                    </div>
                </div>
            </div>

            <div class="footer-bottom">
                <p>&copy; 2025 Alexandria Biblioteca. Todos os direitos reservados.</p>
            </div>
            <br>

   <div class="right-sidebar" id="rightSidebar"></div>
        </div>

    <?php if (!empty($mensagem_sucesso)): ?>
        <div class="message success">
            <?php echo htmlspecialchars($mensagem_sucesso); ?>
        </div>
    <?php endif; ?>

    <?php if (!empty($mensagem_erro)): ?>
        <div class="message error">
            <?php echo htmlspecialchars($mensagem_erro); ?>
        </div>
    <?php endif; ?>

        
        </footer>


    <script src="script.js"></script>


</body>
</html>
