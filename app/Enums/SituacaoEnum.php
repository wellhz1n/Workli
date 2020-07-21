<?php

abstract class SituacaoEnum{
    const Novo = 0;
    const Em_Andamento = 1;
    const Concluida = 2;
    const Cancelado = 3;
}
abstract class IconeSituacao{
    const Novo = "fa-project-diagram";
    const Em_Andamento =  "fa-tasks";
    const Concluida = "fa-check-double";
    const Cancelado = "fa-times";

}