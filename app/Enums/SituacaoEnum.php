<?php

abstract class SituacaoEnum{
    const Novo = 0;
    const Aguardando_Funcionario_Iniciar = 1;
    const Em_Andamento = 2;
    const Concluida = 4;
    const Cancelado = 3;
}
abstract class IconeSituacao{
    const Novo = "fa-project-diagram";
    const Aguardando_Funcionario_Iniciar = "fa-clock";
    const Em_Andamento =  "fa-tasks";
    const Concluida = "fa-check-double";
    const Cancelado = "fa-times";

}
abstract class TituloSituacao{
    const Aguardando_Funcionario_Iniciar = "Aguardando Funcionário Iniciar";

}