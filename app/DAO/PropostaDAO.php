<?php
@require_once ("../Classes/Projeto.php");
@require_once ("../Classes/Proposta.php");


class PropostaDAO {

    #region CRUD
    public function Salvar(Proposta $proposta){
            $result = Insert("insert INTO proposta (id,idFuncionario,idCliente,idServico,valor,descricao,upgrades)
                            VALUES (?,?,?,?,?,?,?)",
                            [$proposta->Id,
                            $proposta->IdFuncionario,
                            $proposta->IdCliente,
                            $proposta->IdServico,
                            $proposta->Valor,
                            $proposta->Descricao,
                            $proposta->Upgrades]);

                            return $result;
    }
    #endregion
}


