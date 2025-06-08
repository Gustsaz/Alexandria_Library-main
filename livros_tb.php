<?php

class Livros {
    public $ID_livro;
    public $nome_livro;
    public $autor;
    public $categoria; // chave estrangeira

    public function __construct($ID_livro, $nome_livro, $autor, $categoria = null) {
        $this->ID_livro = $ID_livro;
        $this->nome_livro = $nome_livro;
        $this->autor = $autor;
        $this->categoria = $categoria;
    }

   
}

?>