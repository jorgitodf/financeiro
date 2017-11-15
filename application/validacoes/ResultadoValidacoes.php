<?php
 
class ResultadoValidacoes
{
    private $erros = [];
     
    public function addErro($mensagem) {
        $this->erros[] = $mensagem;
    }
 
    public function getErros() {
        return $this->erros;
    }
 
}