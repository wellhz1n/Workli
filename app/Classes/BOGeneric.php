<?php
@require_once('../functions/Session.php');
@require_once('../Enums/SituacaoEnum.php');

class BOGeneric
{
    private $ID_USUARIOCONTEXTO;
    private $ID_FUNCIONARIOCONTEXTO;

    public function __construct()
    {
        $this->GetUsuarioContexto();
        $this->GetIdFuncionarioCOntexto();
    }
    public function GetUsuarioContexto()
    {
        $this->ID_USUARIOCONTEXTO = BuscaSecaoValor(SecoesEnum::IDUSUARIOCONTEXTO);
        return $this->ID_USUARIOCONTEXTO;
    }
    public function GetIdFuncionarioCOntexto()
    {
        $this->ID_FUNCIONARIOCONTEXTO = BuscaSecaoValor(SecoesEnum::IDFUNCIONARIOCONTEXTO);
        return $this->ID_FUNCIONARIOCONTEXTO;
    }
}
