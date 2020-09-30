<?php

if (!class_exists(Imagem::class, false))
    require_once("../Classes/Imagem.php");
require_once("../functions/Session.php");
if (!class_exists(SituacaoEnum::class, false))
    require_once("../Enums/SecoesEnum.php");


// @include("../functions/Conexao.php");
class ProjetoDAO
{
    #region CRUD
    public function SetProjetoSituacao($idProjeto, $situacao)
    {
        $sql = "update servico set situacao = ? where id =?";
        return Update($sql, [$situacao, $idProjeto]);
    }
    public function SalvarOuAtulizarProjeto(Projeto $proj, $Novo = false)
    {
        $retorno = false;
        $proj->Descricao = addslashes($proj->Descricao);

        if ($Novo) {
            $retorno = Insert(
                "insert into servico(id,id_usuario,nome,descricao,
                                        id_tipo_servico,nivel_profissional,
                                        nivel_projeto,
                                        valor)
                                        values(?,?,?,?,?,?,?,?)
            ",
                [
                    $proj->id,
                    $proj->idusuario,
                    $proj->Nome,
                    $proj->Descricao,
                    $proj->Categoria,
                    $proj->NivelDoProfissional,
                    $proj->NivelDoProjeto,
                    $proj->Valor
                ]
            );
            if ($retorno && count($proj->imagens) > 0)
                $this->SalvarImagemServico($proj->imagens, $proj->id);
        } else {
            $retorno = Update(
                "update servico set
                                    nome = ?,
                                    descricao = ?,
                                    nivel_profissional = ?,
                                    nivel_projeto = ?,
                                    valor = ?,
                                    situacao = ?
                                       where id =
            ",
                [
                    $proj->Nome,
                    $proj->Descricao,
                    $proj->NivelDoProfissional,
                    $proj->NivelDoProjeto,
                    $proj->Valor,
                    $proj->situacao,
                    $proj->id
                ]
            );
            if ($retorno && count($proj->imagens) > 0)
                $this->SalvarImagemServico($proj->imagens, $proj->id);
        }
        return $retorno;
    }

    private function SalvarImagemServico($img = [], $idServico)
    {
        for ($i = 0; $i < count($img); $i++) {
            $im = new Imagem();
            foreach ($img[$i] as $key => $value) {
                if ($key != "img")
                    $im->$key = json_decode($value);
                else
                    $im->$key = $value;
            }
            $im->principal = $im->principal ? 1 : 0;
            if ($im->id == -1 && !$im->deletado) {
                Insert("insert into foto_servico(imagem,id_servico,principal) values(?,?,?)", [$im->img, $idServico, $im->principal]);
            } else if ($im->deletado && $im->id != -1) {
                DeleteGenerico("foto_servico", $im->id);
            }
        }
    }

    public function GetProjetosPorUsuario($idUsuario)
    {
        $sql = "select S.id,S.nome from servico S where S.id_usuario = ? and S.situacao = 0";
        $result = Sql($sql, [$idUsuario]);
        return $result->resultados;
    }
    public function GetTituloProjetoPorId($idProjeto)
    {
        $sql = "select S.nome from servico S where S.id = ? ";
        $result = Sql($sql, [$idProjeto]);
        return $result->resultados[0];
    }
    #endregion
    #region Busca Dependencias
    public function BuscaProjeto($categoria = [], $q = "", $pg = 1)
    {

        $sqlcategoria = count($categoria) > 0 ? " and s.id_tipo_servico in(" . join(",", $categoria) . ")" : null;
        $like = $q != "" ? "  and s.nome like'%$q%'" : null;
        $paginas = Sql("
            select  ceil(count(s.id)/6) as paginas from servico s
            inner join tipo_servico  ts on ts.id = s.id_tipo_servico  and ts.ATIVO = 0
            where s.situacao = 0
            {$sqlcategoria}
            {$like}
        ");
        if (count($paginas->resultados) > 0) {
            if (json_decode($paginas->resultados[0]['paginas']) == 1)
                $pg = 1;
        }
        $IdFuncionaro = BuscaSecaoValor(SecoesEnum::IDFUNCIONARIOCONTEXTO);
        $sqlPropostaAcima = $IdFuncionaro != null ? ",p.propostaFuncionario" : null;
        $sqlPropostaParaFuncionario =  $IdFuncionaro != null ? " ,case when pp.idFuncionario = {$IdFuncionaro} 
                                        then 1
                                        else 0
                                        end as propostaFuncionario" : null;
        $pg = (json_decode($pg) - 1) * 6;
        $retorno = Sql("
        select ceil(count(p.id)/6) as paginas,
               p.id,p.titulo,p.descricao,
               p.categoria,
               p.nivel_projeto,
               p.nivel_profissional,
               p.valor,p.postado,
               p.usuario,
               p.id_usuario,
               p.img,
               count(p.propostas)as propostas
               {$sqlPropostaAcima}
               from
                    (select s.id ,
                    s.nome as titulo,
                    s.descricao,
                    ts.nome as categoria,
                    s.nivel_projeto,
                    s.nivel_profissional,
                    s.valor, 
                    pp.id as propostas,
                    cast(time_format(TIMEDIFF(current_timestamp,s.data_cadastro),'%H') as int) as postado,
                    u.nome as usuario,
                    u.id as id_usuario,
                    iu.imagem as img
                    {$sqlPropostaParaFuncionario}
                    from servico s 
                    inner join tipo_servico  ts on ts.id = s.id_tipo_servico and ts.ATIVO = 0
                    inner join  usuarios  u on u.id = s.id_usuario 
                    left join proposta pp on pp.idServico = s.id and pp.situacao = 0
                    left join imagem_usuario iu on iu.id_usuario =u.id 
                    where s.situacao = 0
                    {$sqlcategoria}
                    {$like}
                    order by postado,id asc
                    limit  6
                    offset {$pg}
                    ) as p
                    GROUP BY 2 
                    order by p.postado asc

        ");
        foreach ($retorno->resultados as $key => $value) {
            $retorno->resultados[$key]["descricao"] = nl2br($retorno->resultados[$key]["descricao"]);
        }
        return [$retorno->resultados, $paginas->resultados[0]["paginas"]];
    }

    public function BuscaDependenciasModal($id)
    {
        $retorno = Sql("select fs.id, fs.imagem, fs.principal 
                        from foto_servico fs where fs.id_servico = ? ", [$id]);
        return $retorno->resultados;
    }


    public function BuscaNumeroProjetos()
    {
        $retorno = Sql("SELECT COUNT(id) FROM servico", []);
        return $retorno->resultados[0];
    }
    #endregion
    #region MeusProjetos
    public function BuscaProjetosPorIdUsuario($idUsuario, $q = null, $p = 1, $categoria = null, $situacao = null)
    {

        $situacaoSql = $situacao != null ? "AND SITUACAO = {$situacao}" : null;
        $categoriaSql = $categoria != null ? "AND ID_TIPO_SERVICO = {$categoria}" : null;

        $paginas = Sql("
        SELECT floor(count(id)/6) as PAGINAS FROM PROJETOS_VIEW 
        WHERE ID_USUARIO = ?
        AND NOME LIKE'%{$q}%'
        {$situacaoSql}
        {$categoriaSql}", [$idUsuario])->resultados[0]["PAGINAS"];
        $p = $paginas == 1 ? 1 : $p;
        $p = (json_decode($p) - 1) * 6;

        $sql = "
        SELECT * FROM PROJETOS_VIEW 
        WHERE ID_USUARIO = ?
        AND NOME LIKE'%{$q}%'
        {$situacaoSql}
        {$categoriaSql}
        LIMIT 6
        OFFSET {$p}";
        $resultados = Sql($sql, [$idUsuario]);
        foreach ($resultados->resultados as $key => $value) {
            $resultados->resultados[$key]["descricao"] = nl2br($resultados->resultados[$key]["descricao"]);
        }
        return [$resultados->resultados, $paginas];
    }


    public function CancelaProjeto($idProjeto){
        $sql = "update servico set situacao = 3 where id = ?";
        return Update($sql,[$idProjeto]);
    }

    
    public function GetProjetoByIdComView($id,$idUsuario){
        $sql = "SELECT * FROM PROJETOS_VIEW WHERE ID = ? AND id_usuario = ?";
        $resultados = Sql($sql,[$id,$idUsuario]);
        return count($resultados->resultados)  == 1?$resultados->resultados[0]:null;
    }

    public function BuscaMeusProjetosAtribuirP($idUsuario, $idDestinatario)
    {
        $incNotificacao = "";
        if($idDestinatario) {
            $incNotificacao = "AND id_usuario = ${idDestinatario}";
        }
        $parametrosNotificacao = Sql("SELECT parametros FROM notificacoes WHERE tipo = 6 AND id_usuario_criacao = ${idUsuario} ${incNotificacao}");

        $idProjetos = [];
        for ($i=0; $i < count($parametrosNotificacao->resultados); $i++) { 

            $parametros = explode(";", $parametrosNotificacao->resultados[$i]["parametros"]);
            foreach ($parametros  as $key => $value) {
                $parametros[$key] = explode("=", $value);
                if($parametros[$key][0] == "idProjeto") {
                    array_push($idProjetos, $parametros[$key][1]);
                }
            }

        }
        $idProjetos = implode(", ", $idProjetos);
        $excIds = "AND id NOT IN(${idProjetos})";

        $sql = "
        SELECT id, nome, valor, nivel_projeto, postado FROM PROJETOS_VIEW 
        WHERE ID_USUARIO = ? AND situacao = 0 $excIds";
        $resultados = Sql($sql, [$idUsuario]);
        return $resultados->resultados;
    }

    public function BuscaProjetoPorIdBuscaServico($idUsuario)
    {

        $sql = "
        SELECT * FROM PROJETOS_VIEW 
        WHERE id = ?";
        $resultados = Sql($sql, [$idUsuario]);

        $resultados->resultados[0]["descricao"] = nl2br($resultados->resultados[0]["descricao"]);

        return [$resultados->resultados[0]];
    }

    #endregion
}
