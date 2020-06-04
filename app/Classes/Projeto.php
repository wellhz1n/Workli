<?php
@require('../Enums/ProjetoEnum.php');
class Projeto
{
    public $id;
    public $idusuario;
    public $Nome;
    public $Descricao;
    public $Ativo;
    public $imagens;
    public $Categoria;
    public $NivelDoProjeto;
    public $NivelDoProfissional;
    public $Valor;

    public function __construct(
        $id = -1,
        $idusuario = null,
        $Nome = "",
        $Descricao = "",
        $Ativo = 1,
        $imagens = [],
        $Categoria = null,
        $NivelDoProjeto = null,
        $NivelDoProfissional = null,
        $Valor = null
    ) {
        $this->id = $id;
        $this->idusuario = $idusuario;
        $this->Nome = $Nome;
        $this->Descricao = $Descricao;
        $this->Ativo = $Ativo;
        $this->imagens  = $imagens;
        $this->Categoria = $Categoria;
        $this->NivelDoProjeto = $NivelDoProfissional;
        $this->NivelDoProfissional = $NivelDoProfissional;
        $this->Valor = $Valor;
    }
}
