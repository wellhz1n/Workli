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
    const VALOR_CARTEIRA = "VALORCARTEIRA";

    #region Funcionario
    const IDFUNCIONARIOCONTEXTO = "IDFUNCIONARIOCONTEXTO";
    const AVALIACAO_MEDIA = "AVALIACAOMEDIA";
    const PROFISSAO = "PROFISSAO";
    const PLANO = "PLANO";
    const VALES_PATROCINIOS = "VALESPATROCINIOS";
    #endregion

    #endregion
    const SERVICOS = "SERVICOS";
    #region Notificacoes
    const NUMNOTIFICACOES = "NUMNOTIFICACOES";
    const NOTIFICACOES = "NOTIFICACOES";

    #endregion
    #region Projeto
    const SERVICOSSELETOR = "SERVICOSELETOR";
    #endregion

}
