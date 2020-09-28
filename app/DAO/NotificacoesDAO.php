<?php
@require_once("../Classes/Notificacao.php");
class NotificacoesDAO
{
    private Notificacao $Not;

    #region Busca Notificações
    public function BuscaNotificacoes($idUsuario, $BuscaNovo = true, $SemProposta = false, $filtros = [])
    {
        $FiltraTipo = null;
        if (count($filtros) > 0) {

            $FiltraTipo = "and tipo in (" . join(',', $filtros) . ")";
        }

        $visto = $BuscaNovo ? "and visto = 0" : null;
        $SemProp = !$SemProposta ? " union  
        select  distinct * from NProposta where id_usuario = {$idUsuario} " : null;
        $sql = " 
        with Ncomum as (
            select distinct
                    N.id as id,
                    N.titulo as titulo,
                    null as subtitulo,
                    null as subdescricao,
                    N.descricao  COLLATE utf8mb4_unicode_ci as descricao ,
                    N.id_chat as id_chat,
                    N.tipo as tipo,
                    N.id_projeto as id_projeto,
                    N.id_usuario as id_usuario,
                    N.id_usuario_criacao as id_usuario_criacao,
                    N.data_hora as data_hora,
                    N.visto as visto,
                    N.parametros as parametros,
                    0 as patrocinado,
                    0 as destacado
            from notificacoes N where N.id_usuario = ? {$visto}
            ),
            NMensagem as (
            select distinct
                    -1 as id,
                    'Nova Mensagem' as titulo,
                    'enviada por:' as subtitulo,
                    U.nome as subdescricao,
                    CM.msg as descricao,
                    CM.id_chat as id_chat,
                    2 as tipo,
                    null as id_projeto,
                    CM.id_usuario_destinatario as id_usuario,
                    CM.id_usuario_remetente as id_usuario_criacao,
                    CM.data_hora as data_hora,
                    CM.visualizado as visto,
                    0 as parametros,
                    0 as patrocinado,
                    0 as destacado
            from  chat_mensagens CM
            inner join usuarios U on U.id = CM.id_usuario_remetente
            where CM.id_usuario_destinatario = ? and CM.visualizado = 0 and CM.automatica = 0
            ),
            NProposta as(
                select distinct
                P.id as id,
                'Nova Proposta' as titulo,
                'Projeto:' as subtitulo,
                S.nome as subdescricao,
                concat('<strong>Funcionario</strong>: <small>',U.nome,'</small><br/><strong>Valor</strong>: <small>R$:',P.valor,'</small><br/>',P.descricao) COLLATE utf8mb4_unicode_ci as descricao  ,
                null as id_chat,
                1 as tipo,
                S.id as id_projeto,
                UC.id as id_usuario,
                U.id as id_usuario_criacao,
                P.data_criacao  as data_hora,
                0 as parametros,
                case P.situacao 
                when 0 then 0
                else 1 
                end as visto,
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
            inner join servico S on S.id = P.idServico
            inner join funcionario F on F.id =  P.idFuncionario
            inner join usuarios U on U.id = F.id_usuario
            inner join usuarios UC on UC.id = S.id_usuario
            )
            select * from ncomum where tipo <> 1 {$FiltraTipo}
            union  
            select distinct  * from NMensagem
                {$SemProp}
            order by data_hora desc
        ";
        $result = Sql($sql, [$idUsuario, $idUsuario]);
        return $result->resultados;
    }

    public function BuscaNotificacoesComPaginacao($idUsuario, $filtros = [], $pagina = 1)
    {
        $FiltraTipo = null;
        $mostraChat = "union  
        select distinct  * from NMensagem";
        $apenasChat =  "select * from ncomum where tipo <> 1 {$FiltraTipo}";
        $tem2 = false;
        if (count($filtros) > 0) {
            if (count($filtros) == 1 && $filtros[0] == 2) {
                $apenasChat = null;
                $mostraChat = "   
                select distinct  * from NMensagem";
                unset($filtros[0]);
            }
            //TODO ARRUMAR ESSA PARADA AQUI PARA FILTRAR O CHAT E TALS
            if (($key = array_search(2, $filtros)) !== false) {
                unset($filtros[$key]);
                $mostraChat = " union  
                select distinct  * from NMensagem";
                $tem2 = true;
            }
            if (count($filtros) > 0) {
                $mostraChat = $tem2 ? $mostraChat : null;
                $FiltraTipo = "and tipo in (" . join(',', $filtros) . ")";
                $apenasChat =  "select * from ncomum where tipo <> 1 {$FiltraTipo}";
            }
        }


        $sql = " 
        with Ncomum as (
            select distinct
                    N.id as id,
                    N.titulo as titulo,
                    null as subtitulo,
                    null as subdescricao,
                    N.descricao  COLLATE utf8mb4_unicode_ci as descricao ,
                    N.id_chat as id_chat,
                    N.tipo as tipo,
                    N.id_projeto as id_projeto,
                    N.id_usuario as id_usuario,
                    N.id_usuario_criacao as id_usuario_criacao,
                    N.data_hora as data_hora,
                    N.visto as visto,
                    0 as patrocinado,
                    0 as destacado
            from notificacoes N where N.id_usuario = ? 
            ),
            NMensagem as (
            select distinct
                    -1 as id,
                    'Nova Mensagem' as titulo,
                    'enviada por:' as subtitulo,
                    U.nome as subdescricao,
                    CM.msg as descricao,
                    CM.id_chat as id_chat,
                    2 as tipo,
                    null as id_projeto,
                    CM.id_usuario_destinatario as id_usuario,
                    CM.id_usuario_remetente as id_usuario_criacao,
                    CM.data_hora as data_hora,
                    CM.visualizado as visto,
                    0 as patrocinado,
                    0 as destacado
            from  chat_mensagens CM
            inner join usuarios U on U.id = CM.id_usuario_remetente
            where CM.id_usuario_destinatario = ? and CM.visualizado = 0 and CM.automatica = 0
            )
            {$apenasChat}
            {$mostraChat}
            order by data_hora desc;
        ";
        $result = Sql($sql, [$idUsuario, $idUsuario]);


        $paginas = count($result->resultados) > 0 ? ceil((count($result->resultados)) / 6) - 1 : 1;
        $paginas = $paginas == 0 ? 1 : $paginas;
        $fimArray = (floor(count($result->resultados) / $paginas) * $pagina);
        $inicioArr = (($pagina - 1) * 6);
        $novoArr = [];
        for ($i = $inicioArr; $i < count($result->resultados); $i++) {
            if ($i >= $inicioArr && $i <= $fimArray)
                array_push($novoArr, $result->resultados[$i]);
        }

        // FUCK YOU ARRAY_SPLICE 🤬
        // array_splice($ArrayPagina,  $fimArray>count($ArrayPagina)? (0 - count($ArrayPagina)):(0 - $fimArray),6);
        return [$paginas, $novoArr];
    }


    public function NumeroNotificacoesNaoVistas($idUsuario)
    {
        return count($this->BuscaNotificacoes($idUsuario));
    }
    #endregion

    #region Tela Proposta E Notificação


    #endregion

    #region Cria Notificação
    public function SalvarAtualizarNotificacao(Notificacao $notificacao)
    {
        $saida = false;
        if ($notificacao->id == -1) {
            $notificacao->id = GetNextID("notificacoes");
            $saida = Insert(
                "insert into 
           notificacoes(id, titulo, descricao, id_projeto, id_chat, id_usuario, id_usuario_criacao, tipo, parametros)
           values(?,?,?,?,?,?,?,?,?)",
                [
                    $notificacao->id,
                    $notificacao->titulo,
                    $notificacao->descricao,
                    $notificacao->id_projeto,
                    $notificacao->id_chat,
                    $notificacao->id_usuario,
                    $notificacao->id_usuario_criacao,
                    $notificacao->tipo,
                    $notificacao->parametros
                ]
            );
        } else {
            $saida = Update(" Update notificacoes
                                titulo = ?,
                                descricao = ?,
                                tipo = ?
                                where id = ?", [
                $notificacao->titulo,
                $notificacao->descricao,
                $notificacao->tipo,
                $notificacao->id
            ]);
        }
        return $saida;
    }
    public function UpdateVistoVariasNotificacoes($ids = [])
    {
        $result = true;
        if (count($ids) > 0) {
            $ids = join(',', $ids);
            $result = Update("update notificacoes set visto = 1 where id in({$ids})");
        }
        return $result;
    }

    #endregion
}
