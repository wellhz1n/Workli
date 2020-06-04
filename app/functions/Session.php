<?php

session_start();

function FecharTodasAsSessions()
{
    session_destroy();
}

function CriaSecao($session, $value)
{
    $_SESSION[$session] = $value;
}
function DestruirSecao($secao)
{
    unset($_SESSION[$secao]);
}
function BuscaSecao($secao)
{
    if (isset($_SESSION[$secao]) && $_SESSION[$secao] != null)
        return true;
    return false;
}
function BuscaSecaoValor($secao)
{
    if (BuscaSecao(($secao)))
        return $_SESSION[$secao];
    return null;
}
