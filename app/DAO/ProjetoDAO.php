<?php
@include("../Classes/Imagem.php");
// @include("../functions/Conexao.php");
class ProjetoDAO
{
    #region CRUD
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
                                    Ativo = ?
                                       where id =
            ",
                [
                    $proj->Nome,
                    $proj->Descricao,
                    $proj->NivelDoProfissional,
                    $proj->NivelDoProjeto,
                    $proj->Valor,
                    $proj->Ativo,
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
    #endregion
    #region Busca Dependencias
    public function BuscaProjeto($categoria = [], $q = "", $pg = 1)
    {

        $sqlcategoria = count($categoria) > 0 ? " and s.id_tipo_servico in(" . join(",", $categoria) . ")" : null;
        $like = $q != "" ? " and s.nome like'%$q%'" : null;
        $likep = $q != "" ? " and s.nome like'%$q%'" : null;
        $paginas = Sql("
        select  ceil(count(s.id)/6) as paginas from servico s
        inner join tipo_servico  ts on ts.id = s.id_tipo_servico  and ts.ATIVO = 1
        where s.Ativo = 1
        {$sqlcategoria}
        {$likep}
        ");
        if(count($paginas->resultados) > 0){
            if(json_decode($paginas->resultados[0]['paginas']) == 1)
                $pg = 1;
        }
        
        $pg = (json_decode($pg) - 1) * 6;
        $retorno = Sql("
        select ceil(count(p.id)/6) as paginas,
               p.id,p.titulo,p.descricao,
               p.categoria,p.nivel_projeto,
               p.nivel_profissional,
               p.valor,p.postado,
               p.usuario,
               p.id_usuario,
               p.img 
               from
                    (select s.id ,
                    s.nome as titulo,
                    s.descricao,
                    ts.nome as categoria,
                    s.nivel_projeto,
                    s.nivel_profissional,
                    s.valor, 
                    cast(time_format(TIMEDIFF(current_timestamp,s.data_cadastro),'%H') as int) as postado,
                    u.nome as usuario,
                    u.id as id_usuario,
                    iu.imagem as img 
                    from servico s 
                    inner join tipo_servico  ts on ts.id = s.id_tipo_servico and ts.ATIVO = 1
                    inner join  usuarios  u on u.id = s.id_usuario 
                    left join imagem_usuario iu on iu.id_usuario =u.id 
                    where s.Ativo  = 1
                    {$like}
                    {$sqlcategoria}
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
    #endregion
}
