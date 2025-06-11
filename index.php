<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();

function contarLivrosPorCategoria($livros, $categoria)
{
    return count(livrosPorCategoria($livros, $categoria));
}

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

function livrosPorCategoria($livros, $categoria)
{
    if ($categoria === 'Todos') {
        return $livros;
    }
    return array_filter($livros, function ($livro) use ($categoria) {
        return strtolower($livro['categoria']) === strtolower($categoria);
    });
}

$livros_filtrados = [];
$busca_ativa = false;

if (isset($_GET['busca']) && !empty(trim($_GET['busca']))) {
    $busca = strtolower(trim($_GET['busca']));
    $livros_filtrados = array_filter($livros, function ($livro) use ($busca) {
        return strpos(strtolower($livro['nome']), $busca) !== false;
    });
    $busca_ativa = true;
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
        <button class="toggle-btnL"><img draggable="false" src="img/LogoEscuro.png" alt="Logo Alexandria"
                class="logo-sidebar"></button>

        <button class="mode-toggle">
            <img draggable="false" id="theme-icon" src="img/Escuro.png" alt="Tema" class="tema-icone">
        </button>
        <button class="download-button"><img draggable="false" id="download-icon" src="img/DownloadEscuro.png"
                class="download-icon" alt="Baixados"></button>
        <button class="saved-button"><img draggable="false" id="saved-icon" src="img/SavedEscuro.png" class="saved-icon"
                alt="salvos"></button>
        <button class="visua-button"><img draggable="false" id="visua-icon" src="img/EyeEscuro.png" class="visua-icon"
                alt="já lidos"></button>
        <button class="info-button"><img draggable="false" id="info-icon" src="img/infoEscuro.png" class="info-icon"
                alt="informação"></button>
    </div>

    <div class="main-content">

        <header>
            <div class="search-container">
                <input type="text" placeholder="Buscar livro..." />
                <div class="search-results hidden"></div>
            </div>

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

        <?php if (!$busca_ativa): ?>
            <nav class="categories">
                <div class="category active" data-category="Todos">
                    <img draggable="false" src="icons/todos.png" alt="Todos">
                    Todos (<?php echo contarLivrosPorCategoria($livros, 'Todos'); ?>)
                </div>

                <div class="category" data-category="Em Alta">
                    <img draggable="false" src="icons/em-alta.png" alt="Em alta">
                    Em alta (<?php echo contarLivrosPorCategoria($livros, 'Em Alta'); ?>)
                </div>

                <div class="category" data-category="Novos">
                    <img draggable="false" src="icons/novos.png" alt="Novos">
                    Novos (<?php echo contarLivrosPorCategoria($livros, 'Novos'); ?>)
                </div>

                <div class="category" data-category="Ação">
                    <img draggable="false" src="icons/acao.png" alt="Ação">
                    Ação (<?php echo contarLivrosPorCategoria($livros, 'Ação'); ?>)
                </div>

                <div class="category" data-category="Fantasia">
                    <img draggable="false" src="icons/fantasia.png" alt="Fantasia">
                    Fantasia (<?php echo contarLivrosPorCategoria($livros, 'Fantasia'); ?>)
                </div>

                <div class="category" data-category="Romance">
                    <img draggable="false" src="icons/romance.png" alt="Romance">
                    Romance (<?php echo contarLivrosPorCategoria($livros, 'Romance'); ?>)
                </div>

                <div class="category" data-category="Suspense">
                    <img draggable="false" src="icons/ficcao.png" alt="Ficcao">
                    Suspense (<?php echo contarLivrosPorCategoria($livros, 'Suspense'); ?>)
                </div>
            </nav>
        <?php endif; ?>


        <?php if ($busca_ativa): ?>
            <section class="highlight">
                <h2>Resultados da busca por "<?php echo htmlspecialchars($_GET['busca']); ?>"</h2>
                <?php if (!empty($livros_filtrados)): ?>
                    <div class="book-list">
                        <?php foreach ($livros_filtrados as $livro): ?>
                            <div class="book">
                                <img src="<?php echo htmlspecialchars($livro['capa']); ?>"
                                    alt="Capa do livro <?php echo htmlspecialchars($livro['nome']); ?>" width="120">
                                <h3><?php echo htmlspecialchars($livro['nome']); ?></h3>
                                <p><strong>Autor:</strong> <?php echo htmlspecialchars($livro['autor']); ?></p>
                                <p><strong>Editora:</strong> <?php echo htmlspecialchars($livro['editora']); ?></p>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <p>Nenhum resultado encontrado.</p>
                <?php endif; ?>
            </section>
        <?php else: ?>
            <section class="highlight" data-category="Todos">
                <h2>Todos</h2>
                <div class="book-list">
                    <?php
                    $todos = livrosPorCategoria($livros, 'Todos');
                    foreach ($todos as $livro) {
                        echo '<div onclick="openRightSidebar()" class="book">';
                        echo '<img draggable="false" src="' . htmlspecialchars($livro['capa']) . '" alt="Capa do livro ' . htmlspecialchars($livro['nome']) . '" width="120">';
                        echo '<div class="detalhes">';
                        echo '<h3>' . htmlspecialchars($livro['nome']) . '</h3>';
                        echo '<p><strong>Autor:</strong> ' . htmlspecialchars($livro['autor']) . '</p>';
                        echo '<p><strong>Editora:</strong> ' . htmlspecialchars($livro['editora']) . '</p>';
                        echo '</div>';
                        echo '</div>';

                    }
                    ?>
                </div>
            </section>

            <br>

            <section class="highlight" data-category="Em Alta">
                <h2>Em Alta</h2>
                <div class="book-list">
                    <?php
                    $emAlta = livrosPorCategoria($livros, 'Em Alta');
                    foreach ($emAlta as $livro) {
                        echo '<div onclick="openRightSidebar()" class="book">';
                        echo '<img draggable="false" src="' . htmlspecialchars($livro['capa']) . '" alt="Capa do livro ' . htmlspecialchars($livro['nome']) . '" width="120">';
                        echo '<div class="detalhes">';
                        echo '<h3>' . htmlspecialchars($livro['nome']) . '</h3>';
                        echo '<p><strong>Autor:</strong> ' . htmlspecialchars($livro['autor']) . '</p>';
                        echo '<p><strong>Editora:</strong> ' . htmlspecialchars($livro['editora']) . '</p>';
                        echo '</div>';
                        echo '</div>';
                    }
                    ?>
                </div>
            </section>

            <br>

            <section class="highlight" data-category="Novos">
                <h2>Novos</h2>
                <div class="book-list">
                    <?php
                    $novos = livrosPorCategoria($livros, 'Novos');
                    foreach ($novos as $livro) {
                        echo '<div onclick="openRightSidebar()" class="book">';
                        echo '<img draggable="false" src="' . htmlspecialchars($livro['capa']) . '" alt="Capa do livro ' . htmlspecialchars($livro['nome']) . '" width="120">';
                        echo '<div class="detalhes">';
                        echo '<h3>' . htmlspecialchars($livro['nome']) . '</h3>';
                        echo '<p><strong>Autor:</strong> ' . htmlspecialchars($livro['autor']) . '</p>';
                        echo '<p><strong>Editora:</strong> ' . htmlspecialchars($livro['editora']) . '</p>';
                        echo '</div>';
                        echo '</div>';
                    }
                    ?>
                </div>
            </section>

            <br>

            <section class="highlight" data-category="Ação">
                <h2>Ação</h2>
                <div class="book-list">
                    <?php
                    $acao = livrosPorCategoria($livros, 'Ação');
                    foreach ($acao as $livro) {
                        echo '<div onclick="openRightSidebar()" class="book">';
                        echo '<img draggable="false" src="' . htmlspecialchars($livro['capa']) . '" alt="Capa do livro ' . htmlspecialchars($livro['nome']) . '" width="120">';
                        echo '<div class="detalhes">';
                        echo '<h3>' . htmlspecialchars($livro['nome']) . '</h3>';
                        echo '<p><strong>Autor:</strong> ' . htmlspecialchars($livro['autor']) . '</p>';
                        echo '<p><strong>Editora:</strong> ' . htmlspecialchars($livro['editora']) . '</p>';
                        echo '</div>';
                        echo '</div>';
                    }
                    ?>
                </div>
            </section>

            <br>

            <section class="highlight" data-category="Fantasia">
                <h2>Fantasia</h2>
                <div class="book-list">
                    <?php
                    $fantasia = livrosPorCategoria($livros, 'Fantasia');
                    foreach ($fantasia as $livro) {
                        echo '<div onclick="openRightSidebar()" class="book">';
                        echo '<img draggable="false" src="' . htmlspecialchars($livro['capa']) . '" alt="Capa do livro ' . htmlspecialchars($livro['nome']) . '" width="120">';
                        echo '<div class="detalhes">';
                        echo '<h3>' . htmlspecialchars($livro['nome']) . '</h3>';
                        echo '<p><strong>Autor:</strong> ' . htmlspecialchars($livro['autor']) . '</p>';
                        echo '<p><strong>Editora:</strong> ' . htmlspecialchars($livro['editora']) . '</p>';
                        echo '</div>';
                        echo '</div>';
                    }
                    ?>
                </div>
            </section>

            <br>

            <section class="highlight" data-category="Romance">
                <h2>Romance</h2>
                <div class="book-list">
                    <?php
                    $romance = livrosPorCategoria($livros, 'Romance');
                    foreach ($romance as $livro) {
                        echo '<div onclick="openRightSidebar()" class="book">';
                        echo '<img draggable="false" src="' . htmlspecialchars($livro['capa']) . '" alt="Capa do livro ' . htmlspecialchars($livro['nome']) . '" width="120">';
                        echo '<div class="detalhes">';
                        echo '<h3>' . htmlspecialchars($livro['nome']) . '</h3>';
                        echo '<p><strong>Autor:</strong> ' . htmlspecialchars($livro['autor']) . '</p>';
                        echo '<p><strong>Editora:</strong> ' . htmlspecialchars($livro['editora']) . '</p>';
                        echo '</div>';
                        echo '</div>';
                    }
                    ?>
                </div>
            </section>

            <br>

            <section class="highlight" data-category="Suspense">
                <h2>Suspense</h2>
                <div class="book-list">
                    <?php
                    $suspense = livrosPorCategoria($livros, 'Suspense');
                    foreach ($suspense as $livro) {
                        echo '<div onclick="openRightSidebar()" class="book">';
                        echo '<img draggable="false" src="' . htmlspecialchars($livro['capa']) . '" alt="Capa do livro ' . htmlspecialchars($livro['nome']) . '" width="120">';
                        echo '<div class="detalhes">';
                        echo '<h3>' . htmlspecialchars($livro['nome']) . '</h3>';
                        echo '<p><strong>Autor:</strong> ' . htmlspecialchars($livro['autor']) . '</p>';
                        echo '<p><strong>Editora:</strong> ' . htmlspecialchars($livro['editora']) . '</p>';
                        echo '</div>';
                        echo '</div>';
                    }
                    ?>
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
                        <p>Explore uma vasta coleção de livros em PDF gratuitamente. Conhecimento ao alcance de todos.
                        </p>
                        <br>
                    </div>

                    <div class="footer-social">
                        <h3>Nos siga</h3>
                        <br>
                        <div class="social-icons">
                            <a href="https://www.facebook.com/profile.php?id=61576951933968"><img src="img/faceClaro.png"
                                    alt="Facebook" /></a>
                            <a href="https://www.instagram.com/bibl.iotecaalexandria/"><img src="img/instaClaro.png"
                                    alt="Instagram" /></a>
                            <a href="https://x.com/_Alexandria_Lib"><img src="img/XClaro.png" alt="Twitter" /></a>
                        </div>
                    </div>
                </div>

                <div class="footer-bottom">
                    <p>&copy; 2025 Alexandria Biblioteca. Todos os direitos reservados.</p>
                </div>
                <br>
            </footer>

        <?php endif; ?>

        <?php echo $messageHtml; ?>

    </div>

    <div class="right-sidebar" id="rightSidebar"></div>

    <br>


    <script src="script.js"></script>

</body>

</html>