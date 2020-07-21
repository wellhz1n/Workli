<?php
@require_once("../Enums/UpgradesEnum.php");

class Proposta {
    public $Id;
    public $IdCliente;
    public $IdFuncionario;
    public $IdServico;
    public $Upgrades;
    public $Descricao;
    public $Valor;

    public function __construct(
        $Id = -1,
        $IdCliente = -1,
        $IdFuncionario = -1,
        $IdServico = -1,
        $Upgrades = UpgradeEnum::NDA,
        $Descricao = "",
        $Valor = ""
    ) {
        $this->Id = $Id;
        $this->IdCliente = $IdCliente;
        $this->IdFuncionario = $IdFuncionario;
        $this->IdServico = $IdServico;
        $this->Upgrades = $Upgrades;
        $this->Descricao = $Descricao;
        $this->Valor = $Valor;
    }
}