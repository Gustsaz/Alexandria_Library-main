<?php

class Usuarios {
    public $ID_usuario;
    public $nome_usuario;
    public $email_usuario;
    public $senha_usuario;
    public $livros_baixados; // chave estrangeira
    public $livros_desejo; // chave estrangeira
    public $livros_lido; // chave estrangeira

    public function __construct($ID_usuario, $nome_usuario, $email_usuario, $senha_usuario, $livros_baixados, $livros_desejo, $livros_lido = null) {
        $this->ID_usuario = $ID_usuario;
        $this->nome_usuario = $nome_usuario;
        $this->email_usuario = $email_usuario;
        $this->senha_usuario = $senha_usuario;
        $this->livros_baixados = $livros_baixados;
        $this->livros_desejo = $livros_desejo;
        $this->livros_lido = $livros_lido;
    }

    
}

?>