<?php
@require_once("../Classes/ChatMensagem.php");
class ChatDAO
{

    #region CHAT CRUD
    public function NovoChat($id_chat, $id_servico)
    {
        $saida =  Insert("insert into chat(id_chat,id_servico) values(?,?)", [$id_chat, $id_servico]);
        return $saida;
    }
    public function GetChatPorServico($id_servico)
    {
        $saida = Sql("select * from chat where id_servico = ?", [$id_servico]);
        return $saida->resultados;
    }
    #endregion
    #region CHAT_MENSAGENS CRUD
    public function NovaMensagem(ChatMensagem $NMensagem)
    {
        $saida = Insert(
            "insert into chat_mensagens
                        (id_chat_mensagens,id_chat,id_usuario_remetente,id_usuario_destinatario,
                        msg,data_hora,visualizado) 
                         values(?,?,?,?,?,?,?)",
            [
                $NMensagem->id_chat_mensagem,
                $NMensagem->id_chat,
                $NMensagem->id_usuario_remetente,
                $NMensagem->id_usuario_destinatario,
                $NMensagem->msg,
                $NMensagem->date . " " . $NMensagem->time,
                $NMensagem->visualizado
            ]
        );
        return $saida;
    }
    public function GetMensagensProjeto($id_chat, $id_usuario1, $id_usuario2)
    {
        $saida = Sql("
        select * from (
            (select cm.id_chat_mensagens as id_chat_mensagen,
                    cm.id_chat,
                    cm.msg,
                    cm.id_usuario_destinatario,
                    cm.id_usuario_remetente,
                    DATE(cm.data_hora) as date,
                    time_format(cm.data_hora,'%H:%i:%s') as time  from chat_mensagens as cm where id_chat = ? 
                    and id_usuario_remetente = ?
                    AND id_usuario_destinatario = ?
                    )
                    union all
                    (select cm.id_chat_mensagens as id_chat_mensagen,
                    cm.id_chat,
                    cm.msg,
                    cm.id_usuario_destinatario,
                    cm.id_usuario_remetente ,
                    DATE(cm.data_hora) as date,
                    time_format(cm.data_hora,'%H:%i:%s') as time from chat_mensagens as cm where id_chat = ? 
                    and id_usuario_remetente = ?
                    AND id_usuario_destinatario = ?
                    )
                    ) as chatM
                    order by date,time;
        ", [$id_chat, $id_usuario1,$id_usuario2, $id_chat, $id_usuario2,$id_usuario1]);
        return count($saida->resultados) > 0 ? $saida->resultados : [];
    }
    #endregion
    #region TelaChat
    public function GetServicosComChatFuncionario($id_usuario)
    {
            $sql = Sql("
            select distinct
            SV.id as id_servico,
            cm.id_chat,
            SV.nome as titulo,
            FSV.imagem as imagem_servico,
            U.nome,
            IU.imagem as imagem_usuario
            from chat_mensagens  CM 
            
            inner join CHAT  CH on CH.id_chat = CM.id_chat
            inner join servico SV on SV.id = CH.id_servico
            inner join foto_servico FSV on FSV.id_servico = SV.id and FSV.principal = 1
            inner join usuarios U on U.id =  SV.id_usuario
            inner join imagem_usuario IU on iu.id_usuario = u.id
            where id_usuario_remetente = ?
            ",[$id_usuario]);
        return $sql->resultados;
    }
    public function GetServicosComChatCliente($id_usuario)
    {
            $sql = Sql("
            select distinct
            SV.id as id_servico,
            SV.nome as titulo,
            FSV.imagem as imagem_servico,
            U.nome,
            IU.imagem as imagem_usuario
            from servico SV 
            inner join foto_servico FSV on FSV.id_servico = SV.id and FSV.principal = 1
            inner join usuarios U on U.id =  SV.id_usuario
            inner join imagem_usuario IU on iu.id_usuario = u.id
            where SV.id_usuario = ?
            ",[$id_usuario]);
        return $sql->resultados;
    }
    #endregion
}
