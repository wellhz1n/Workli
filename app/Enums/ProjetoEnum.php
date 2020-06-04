<?php
abstract class NivelFuncionario{
    const Iniciante = 0;
    const Intermediário =1;
    const Avançado = 2;
};
abstract class NivelProjeto {
  const Apenas_a_Ideia = 0;
    const Pequeno = 1;
    const Medio = 2;
    const Grande = 3;
};
abstract class Valores {
    const Vinte_A_Cem = 0;
    const CEM_A_TREZENTOS = 1;
    const TREZENTOS_ASEISCENTOS = 2;
    const SEISCENTOS_A_MIL = 3;
    const MIL_A_MILL_E_QUINHENTOS = 4;
    const MIL_E_QUINHENTOS_A_DOIS_MIL = 5;
    const DOIS_MIL_A_CINCO_MIL = 6;

}