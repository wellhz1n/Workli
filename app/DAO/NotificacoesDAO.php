<?php
@require_once("../Classes/Notificacao.php");
class NotificacoesDAO
{
private Notificacao $Not;
    public function Teste(){

        //  $Not= GetByIdGeneric('notificacoes',Notificacao::class,1);
            $this->Not = GetByIdGeneric('notificacoes',Notificacao::class,1);
            
    }
    #region Busca Notificações
    public function BuscaNotificações($idUsuario)
    {
    }
    public function NumeroNotificacoesNaoVistas($idUsuario)
    {
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
