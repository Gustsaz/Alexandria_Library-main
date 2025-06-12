<?php
// Caminho do JSON atual
$caminho_json = 'data/livros.json';
$livros_existentes = file_exists($caminho_json) ? json_decode(file_get_contents($caminho_json), true) : [];

// IDs já usados
$ids_existentes = array_map(fn($livro) => $livro['id'], $livros_existentes);
$prox_id = max($ids_existentes) + 1;

// Requisição para Gutenberg API
$response = file_get_contents('https://gutendex.com/books?languages=pt&mime_type=image/jpeg');
$data = json_decode($response, true);

$gutenbergLivros = [];

foreach ($data['results'] as $book) {
    if (count($gutenbergLivros) >= 40) break;

    $gutenbergLivros[] = [
        "id" => (string) $prox_id++,
        "nome" => $book['title'],
        "autor" => isset($book['authors'][0]['name']) ? $book['authors'][0]['name'] : "Desconhecido",
        "categoria" => "Gutenberg",
        "editora" => "Projeto Gutenberg",
        "capa" => $book['formats']['image/jpeg'] ?? "img/livros/default.jpg"
    ];
}

// Junta com os existentes
$livros_final = array_merge($livros_existentes, $gutenbergLivros);

// Salva no JSON
file_put_contents($caminho_json, json_encode($livros_final, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

echo "Importação concluída com sucesso.";
?>
