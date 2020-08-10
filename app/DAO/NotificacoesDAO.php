<?php
@require_once("../Classes/Notificacao.php");
class NotificacoesDAO
{
private Notificacao $Not;
  
    #region Busca Notificações
    public function BuscaNotificações($idUsuario)
    {
        $sql =" 
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
            from notificacoes N where N.id_usuario = ?  and visto = 0
            ),
            NMensagem as (
            select distinct
                    -1 as id,
                    'Nova Mensagem' as titulo,
                    'enviada por:' as subtitulo,
                    U.nome as subdescricao,
                    CM.msg as descricao,
                    cm.id_chat as id_chat,
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
            where CM.id_usuario_destinatario = ? and CM.visualizado = 0
            ),
            NProposta as(
                select distinct
                    NC.id as id,
                    'Nova Proposta' as titulo,
                    'Projeto:' as subtitulo,
                    S.nome as subdescricao,
                    concat('<strong>Funcionario</strong>: <small>',U.nome,'</small><br/><strong>Valor</strong>: <small>R$:',P.valor,'</small><br/>',p.descricao) COLLATE utf8mb4_unicode_ci as descricao  ,
                    NC.id_chat as id_chat,
                    NC.tipo as tipo,
                    NC.id_projeto as id_projeto,
                    NC.id_usuario as id_usuario,
                    NC.id_usuario_criacao as id_usuario_criacao,
                    NC.data_hora as data_hora,
                    NC.visto as visto,
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
                from Ncomum NC 
                inner join servico S on S.id = NC.id_projeto
                inner join funcionario F on F.id_usuario =  NC.id_usuario_criacao
                inner join proposta P on p.idFuncionario =F.id and p.idServico = S.id and p.data_criacao = NC.data_hora
                inner join usuarios U on u.id = f.id_usuario
                where NC.tipo = 1
            )
            select * from ncomum where tipo <> 1
            union  
            select distinct  * from NMensagem
            union 
            select  distinct * from NProposta
            order by data_hora desc;
        ";
        $result = Sql($sql,[$idUsuario]);
        return $result->resultados;
    }
    public function NumeroNotificacoesNaoVistas($idUsuario)
    {
        return count($this->BuscaNotificações($idUsuario));
    }
    #endregion
    #region Cria Notificação
    public function SalvarAtualizarNotificacao(Notificacao $notificacao)
    {
        $saida = false;
        if ($notificacao->id == -1) {
            $notificacao->id = GetNextID("notificacoes");
           $saida = Insert("insert into 
           notificacoes(id,titulo,descricao,id_projeto,id_chat,id_usuario,id_usuario_criacao,tipo)
           values(?,?,?,?,?,?,?,?)",
            [$notificacao->id,
            $notificacao->titulo,
            $notificacao->descricao,
            $notificacao->id_projeto,
            $notificacao->id_chat,
            $notificacao->id_usuario,
            $notificacao->id_usuario_criacao,
            $notificacao->tipo]);
            
        }
        else{
           $saida = Update(" Update notificacoes
                                titulo = ?,
                                descricacao = ?,
                                tipo = ?
                                where id = ?",[
                                    $notificacao->titulo,
                                    $notificacao->descricao,
                                    $notificacao->tipo,
                                    $notificacao->id
                                ]);
        }
        return $saida;
    }

    #endregion
}
