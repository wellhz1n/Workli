<?php
@require_once ("../Classes/Projeto.php");
@require_once ("../Classes/Proposta.php");


class PropostaDAO {

    #region CRUD
    public function Salvar(Proposta $proposta){
            $result = Sql("insert into proposta(id,idFuncionario,idCliente,idServico,valor,descricao,upgrades)
                            Values(?,?,?,?,?,?,?)",
                            [$proposta->Id,
                            $proposta->IdFuncionario,
                            $proposta->IdFuncionario,
                            $proposta->IdServico,
                            $proposta->Valor,
                            $proposta->Descricao,
                            $proposta->Upgrades]);
                            return $result;
    }
    #endregion
}


