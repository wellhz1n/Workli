<?php
@require_once("../Classes/Projeto.php");
@require_once("../Classes/Proposta.php");


class PropostaDAO
{

    #region CRUD
    public function Salvar(Proposta $proposta)
    {
        $result = Insert(
            "insert INTO proposta (id,idFuncionario,idCliente,idServico,valor,descricao,upgrades)
                            VALUES (?,?,?,?,?,?,?)",
            [
                $proposta->Id,
                $proposta->IdFuncionario,
                $proposta->IdCliente,
                $proposta->IdServico,
                $proposta->Valor,
                $proposta->Descricao,
                $proposta->Upgrades
            ]
        );

        return $result;
    }
    public function RecusarProposta($idProposta)
    {
        $sql = "update proposta set situacao = 3 where id = ?";
        $result = Update($sql, [$idProposta]);
        return $result;
    }
    public function AprovarProposta($idProposta)
    {
        $sql = "update proposta set situacao = 1 where id = ?";
        $result = Update($sql, [$idProposta]);
        return $result;
    }
    public function GetPropostasParaoMesmoProjetoExecetoId($idProposta)
    {
        $sql = "select idServico from proposta where id = ?";
        $resultado = Sql($sql, [$idProposta])->resultados[0];
        $idsParaRecusar = Sql("select id from proposta where idServico = ? and id <> ?", [$resultado["idServico"], $idProposta])->resultados;
        return $idsParaRecusar;
    }
    #endregion
    #region Aba Proposta
    public function GetPropostaParaNotificacaoPendente($idUsuario, $idProjeto = null)
    {
        $filtraProjeto = "";
        if ($idProjeto != null)
            $filtraProjeto = " and S.id = {$idProjeto}";
        $sql = "select distinct
         P.id,
         S.nome as Titulo,
         P.descricao,
         P.valor,
         UF.nome as funcionario,
         TS.nome as categoria,
         F.avaliacao_media,
         IU.imagem,
           case P.upgrades
                             when 0 then 0
                             when 3 then 1
                             when 2 then 0
                             else 1
                             end  as patrocinado,
                             case P.upgrades
                             when 0 then 0
                             when 3 then 1
                             when 1 then 0
                             else 1
                             end  as destacado
         from proposta P
         inner join servico S on S.id = P.idServico and S.situacao = 0
         inner join funcionario F on F.id = P.idFuncionario
         inner join usuarios UF on UF.id = F.id_usuario
         left join imagem_usuario IU on  IU.id_usuario = UF.id
         inner join tipo_servico TS on TS.id = S.id_tipo_servico and TS.ativo = 0
         where P.idCliente = ? and P.situacao = 0 {$filtraProjeto}
         order by P.data_criacao desc";
        $result = Sql($sql, [$idUsuario]);
        return $result->resultados;
    }

    #region BuscaPropostasFuncionario

    public function BuscaPropostaFuncionarioTab($idFunciorario, $filtros = [], $pagina = 1)
    {
        $filtrosSql = count($filtros) > 0 ? "and P.situacao in(" . join($filtros, ',') . ")" : null;

        $sql = " 
        SELECT P.ID,
		S.NOME AS TITULO,
		TS.NOME AS CATEGORIA,
		P.DESCRICAO,
		U.NOME AS CLIENTE,
		IU.IMAGEM,
        P.SITUACAO,
        P.VALOR,
		DATE_FORMAT(P.DATA_CRIACAO,'%d/%m/%Y') AS DATAPROPOSTA
        FROM PROPOSTA P 
        INNER JOIN SERVICO S ON S.ID = P.IDSERVICO
        INNER JOIN TIPO_SERVICO TS ON TS.ID = S.ID_TIPO_SERVICO
        INNER JOIN USUARIOS U ON U.ID = S.ID_USUARIO
        LEFT JOIN IMAGEM_USUARIO IU ON IU.ID_USUARIO = U.ID
        WHERE P.IDFUNCIONARIO = ? {$filtrosSql}
        ORDER BY P.DATA_CRIACAO DESC
        ";
        $result = Sql($sql, [$idFunciorario]);

        //PARADA QUE FAZ A PAGINAÇÃO 
        $paginas = count($result->resultados) > 0 ? ceil((count($result->resultados)) / 6) : 1;
        $paginas = $paginas == 0 ? 1 : $paginas;
        $fimArray = (floor(count($result->resultados) / $paginas) * $pagina);
        $inicioArr = (($pagina - 1) * 6);
        $novoArr = [];
        for ($i = $inicioArr; $i < count($result->resultados); $i++) {
            if ($i >= $inicioArr && $i <= $fimArray)
                array_push($novoArr, $result->resultados[$i]);
        }
        return [$paginas, $novoArr];
    }
    public function RetornaValorPropostaMedia($idServico)
    {
        $sql = "
            SELECT ROUND(AVG(valor), 2) AS soma FROM proposta AS p
            WHERE idServico = ?
        ";
        $result = Sql($sql, [$idServico]);
        return $result->resultados;
    }
    
    #endregion
    #endregion
}
