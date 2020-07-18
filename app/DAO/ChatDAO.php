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
                    time_format(cm.data_hora,'%H:%i:%s') as time,  
                    cm.visualizado 
                    from chat_mensagens as cm where id_chat = ? 
                    and id_usuario_remetente = ?
                    AND id_usuario_destinatario = ?)
                    union all
                    (select cm.id_chat_mensagens as id_chat_mensagen,
                    cm.id_chat,
                    cm.msg,
                    cm.id_usuario_destinatario,
                    cm.id_usuario_remetente ,
                    DATE(cm.data_hora) as date,
                    time_format(cm.data_hora,'%H:%i:%s') as time,
                    cm.visualizado  
                    from chat_mensagens as cm where id_chat = ? 
                    and id_usuario_remetente = ?
                    AND id_usuario_destinatario = ?)
                    ) as chatM
                    order by chatM.date,chatM.time;
        ", [$id_chat, $id_usuario1,$id_usuario2, $id_chat, $id_usuario2,$id_usuario1]);
        return count($saida->resultados) > 0 ? $saida->resultados : [];
    }
    public function SetVizualizado($ids = []){
        $sql = "update chat_mensagens set visualizado = 1 where id_chat_mensagens in (?)";
        $ids = join(',',$ids);
        $saida = Update($sql,[$ids]);
        return $saida;
    }
    #endregion
    #region TelaChat
    public function GetServicosComChatFuncionario($id_usuario)
    {
            $sql = Sql("
            select distinct
            SV.id as id_servico,
            CM.id_chat,
            SV.nome as titulo,
            FSV.imagem as imagem_servico,
            U.id as id_usuario,
            U.nome,
            IU.imagem as imagem_usuario,
            (select count(MCH.id_chat_mensagens) from chat_mensagens MCH where MCH.id_chat = CH.id_chat and 
            (MCH.id_usuario_remetente = ? or MCH.id_usuario_destinatario = ?) ) as MSG,
            cast(time_format(TIMEDIFF(current_timestamp,SV.data_cadastro),'%H') as int) as postado
            from chat_mensagens  CM 
            inner join chat  CH on CH.id_chat = CM.id_chat
            inner join servico SV on SV.id = CH.id_servico 
            left join foto_servico FSV on FSV.id_servico = SV.id and FSV.principal = 1
            inner join usuarios U on U.id =  SV.id_usuario
            left join imagem_usuario IU on IU.id_usuario = U.id  
            where CM.id_usuario_remetente = ?
            order by MSG DESC,postado
            ",[$id_usuario,$id_usuario,$id_usuario]);
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
            CH.id_chat,
            IU.imagem as imagem_usuario,
            cast(time_format(TIMEDIFF(current_timestamp,SV.data_cadastro),'%H') as int) as postado
            from servico SV 
            left join foto_servico FSV on FSV.id_servico = SV.id and FSV.principal = 1
            inner join usuarios U on U.id =  SV.id_usuario
            left join imagem_usuario IU on IU.id_usuario = U.id
            left join chat CH on CH.id_servico = SV.id
            where SV.id_usuario = ?
            order by postado
            ",[$id_usuario]);
        foreach ($sql->resultados as $key => $value) {
                $ms = $this->GetContagemDeContatos($value["id_chat"],$id_usuario);
                $sql->resultados[$key]["MSG"] = $ms;
        }
        return $sql->resultados;
    }
    private function GetContagemDeContatos($id_chat,$id_usuario){
        $sql = Sql("
        SELECT  COUNT(*) AS MSG from (SELECT DISTINCT id_usuario_remetente from chat_mensagens where 
				id_chat = ? AND 
				id_usuario_destinatario  = ?) as M 
        ",[$id_chat,$id_usuario]);
        return count($sql->resultados)> 0 ?$sql->resultados[0]["MSG"]:0;
    }
    public function GetListaDeContatosConversa($id_chat,$id_usuario){
        $sql = Sql("
        select distinct  u.id,u.nome,im.imagem from chat_mensagens cm 
        inner join usuarios u on u.id  = cm.id_usuario_remetente 
        left join imagem_usuario im  on im.id_usuario  = u.id 
        where cm.id_chat  = ? and cm.id_usuario_destinatario = ?",
        [$id_chat,$id_usuario]);
    return $sql->resultados;
    }
    #endregion
}
