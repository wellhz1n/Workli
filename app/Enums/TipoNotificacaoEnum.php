<?php
abstract class TipoNotificacaoEnum
{
    const DEFAULT = 0;
    const PROPOSTA = 1; // Nunca Usa,pois não Grava No Banco
    const CHAT = 2; // Nunca Usa,pois não Grava No Banco
    const ALERT = 3;
    const ERROR = 4;
    const SUCCESS = 5;
}
