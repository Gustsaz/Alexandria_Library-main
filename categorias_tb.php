<?php

class Categoria {
    public $ID_categoria;
    public $categoria;

    public function __construct($ID_categoria, $categoria = null) {
        $this->ID_categoria = $ID_categoria;
        $this->categoria = $categoria;
    }

}

?>