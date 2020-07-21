<?php
@require_once("../Enums/UpgradesEnum.php");
@require_once("../Enums/SituacaoEnum.php");


class Proposta {
    public $Id;
    public $IdCliente;
    public $IdFuncionario;
    public $IdServico;
    public $DataCriacao;
    public $Upgrades;
    public $Descricao;
    public $Valor;
    public $Situacao;

    public function __construct(
        $Id = -1,
        $IdCliente = -1,
        $IdFuncionario = -1,
        $IdServico = -1,
        $Upgrades = UpgradeEnum::NDA,
        $Descricao = "",
        $Valor = "",
        $Situacao = SituacaoEnum::Em_Andamento
    ) {
        $this->Id = $Id;
        $this->IdCliente = $IdCliente;
        $this->IdFuncionario = $IdFuncionario;
        $this->IdServico = $IdServico;
        $this->Upgrades = $Upgrades;
        $this->Descricao = $Descricao;
        $this->Valor = $Valor;
        $this->Situacao = $Situacao;

    }
}