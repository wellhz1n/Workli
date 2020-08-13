<?php


abstract class SecoesEnum
{
    #region USUARIO
    const LOGIN = "LOGIN";
    const NOME = "NOME";
    const EMAIL = "EMAIL";
    const CPF = "CPF";
    const NIVEL_USUARIO = "NIVELUSUARIO";
    const FOTO_USUARIO = "FOTOUSUARIO";
    const IDUSUARIOCONTEXTO = "IDUSUARIOCONTEXTO";

    #region Funcionario
    const IDFUNCIONARIOCONTEXTO = "IDFUNCIONARIOCONTEXTO";
    const CURRICULO = "CURRICULO";
    const NUMERO_TELEFONE = "NUMEROTELEFONE";
    const AVALIACAO_MEDIA = "AVALIACAOMEDIA";
    #endregion

    #endregion
    const SERVICOS = "SERVICOS";
    #region Notificacoes
    const NUMNOTIFICACOES = "NUMNOTIFICACOES";
    const NOTIFICACOES = "NOTIFICACOES";

    #endregion

}
