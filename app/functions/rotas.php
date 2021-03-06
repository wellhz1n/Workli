<?php
// Enums
@include("../app/Enums/ClientPagesEnum.php");
@include("../app/Enums/FuncPagesEnum.php");
@include("../app/Enums/AdmPagesEnum.php");
@include("../app/Enums/ProgPagesEnum.php");
@include("../app/Enums/GeralPagesEnum.php");
@include("../app/functions/EnumUtils.php");

// Classes
@include("../app/Classes/Usuario.php");

function load()
{
    $page = filter_input(INPUT_GET, 'page', FILTER_SANITIZE_STRING);
    $home = filter_input(INPUT_GET, 'page', FILTER_SANITIZE_STRING);
    $page = (!$page) ? 'pages/home/index.php' : "pages/{$page}/index.php";


    if (!file_exists($page)) {

        // ini_set(“display_errors”, 0 );
        $logado = Logado();
        $page ="pages/404/index.php";
        return [$page, ucwords($home), $logado];
    } else {

        $logado = Logado();
        $home = (!$home) ? 'perfilUsuario' : $home;

        if ($logado[0] == true && $logado[1] != null) {
            //AQUI VERIFICA O NIVEL DO USUARIO E PERMITE OS ACESSOS DAS PAGINAS PARA OS MESMOS

            //USUARIO
            if ($logado[1] == NivelUsuario::Cliente) {
                $page =  ReturnPage($home, ClientPagesEnum::class, ClientPagesEnum::Home);
            }
            //FUNCIONARIO
            if ($logado[1] == NivelUsuario::Funcionario) {
                $page =  ReturnPage($home, FuncPagesEnum::class, FuncPagesEnum::Home);
            }
            //ADM
            if ($logado[1] == NivelUsuario::Adm) {
                $page =  ReturnPage($home, AdmPagesEnum::class, AdmPagesEnum::Home);
            }
            //PROGRAMADOR
            if ($logado[1] == NivelUsuario::Programador) {
                $page =  ReturnPage($home, ProgPagesEnum::class, ProgPagesEnum::Home);
            }

            if($logado[0] == true && strtolower($home) == strtolower("home")) {
                $page = "pages/perfilUsuario/index.php";
            }
        } else if (
            strtolower($home) == strtolower("home") || strtolower($home) == strtolower('login') ||
            strtolower($home) == strtolower('registro') ||  strtolower($home) == strtolower('produto')
        )
            $page = "pages/{$home}/index.php";
        else
            $page = "pages/home/index.php";

        return [$page, ucwords($home), $logado];
    }
}

//SEMPRE LEMBRAR DE COLOCAR O ::CLASS NA CLASSE
function ReturnPage($page, $enum1, $paginaInicial)
{
    $enum = array_merge(EnumParaArray($enum1), EnumParaArray(GeralPagesEnum::class));
    $pagina = "";
    if (strtolower($page) == strtolower("home"))
        $pagina = "pages/{$paginaInicial}/index.php";
    else
        foreach ($enum as $key => $value) {
            if (strtolower($page) == strtolower($value)) {
                $pagina = "pages/{$page}/index.php";
                break;
            } else
                $pagina = "pages/{$paginaInicial}/index.php";
        }
    return $pagina;
}
