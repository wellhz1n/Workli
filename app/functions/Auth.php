<?php
try {

    @include("../Enums/SecoesEnum.php");
    @include("../app/Enums/SecoesEnum.php");
} catch (Exception $e) {
}
function Login($email, $senha)
{

    $saida = Sql("select 
                        u.id,
                        u.nome,
                        u.email,
                        u.senha,
                        u.cpf,
                        u.nivel_usuario,
                        u.valor_carteira,
                        im.imagem,
                        func.plano,
                        func.vales_patrocinios,
                        func.id as id_func
                  FROM usuarios AS u
                  LEFT JOIN imagem_usuario AS im ON im.id_usuario = u.id 
                  LEFT JOIN funcionario AS func ON func.id_usuario = u.id
                  WHERE 
                        u.email = ?",[$email]);
    
    if (count($saida->resultados) > 0) {
        if ($saida->resultados[0]['senha'] == md5($senha) && $saida->resultados[0]['email'] == $email) {
            $saida->resultados[0]['senha'] = null;
            CriaSecao(SecoesEnum::LOGIN, true);
            CriaSecao(SecoesEnum::NOME, $saida->resultados[0]['nome']);
            CriaSecao(SecoesEnum::NIVEL_USUARIO, $saida->resultados[0]['nivel_usuario']);
            CriaSecao(SecoesEnum::EMAIL, $saida->resultados[0]['email']);
            CriaSecao(SecoesEnum::CPF, $saida->resultados[0]['cpf']);
            CriaSecao(SecoesEnum::VALOR_CARTEIRA,$saida->resultados[0]["valor_carteira"]);
            CriaSecao(SecoesEnum::IDUSUARIOCONTEXTO, $saida->resultados[0]['id']);
            CriaSecao(SecoesEnum::FOTO_USUARIO, ConvertBlobToBase64($saida->resultados[0]['imagem']));
            CriaSecao(SecoesEnum::IDFUNCIONARIOCONTEXTO, $saida->resultados[0]['id_func']);
            CriaSecao(SecoesEnum::PLANO, $saida->resultados[0]['plano']);
            CriaSecao(SecoesEnum::VALES_PATROCINIOS, $saida->resultados[0]['vales_patrocinios']);

            return true;
        } else {
            $msg = new stdClass();
            $msg->error = "Email ou Senha Invalidos.";
            return json_encode($msg);
        }
    } else {
        $msg = new stdClass();
        $msg->error = "Usuario Inexistente.";
        return json_encode($msg);
    }
}
function Logado()
{

    if (BuscaSecao(SecoesEnum::LOGIN)) {
        return [true, BuscaSecaoValor(SecoesEnum::NIVEL_USUARIO)];
    }
    return [false, null];
}
function Deslogar()
{
    FecharTodasAsSessions();
}
