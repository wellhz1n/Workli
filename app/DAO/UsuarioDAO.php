<?php
require("../../bootstrap.php");
class UsuarioDAO
{


    public function GetUsuarios()
    {
        $resultado = Sql("select id,nome,email,cpf,nivel_usuario FROM usuarios");
        return $resultado;
    }
    public function VerificaEmail($email)
    {
        $resultado = Sql("select id FROM usuarios where email = ? limit 1", [$email]);
        if (count($resultado->resultados) == 1)
            return true;
        return false;
    }
    public function GetUsuarioIdPorEmail($email)
    {
        $resultado = Sql("select id FROM usuarios where email = ? limit 1", [$email]);
        return $resultado->resultados[0]['id'];
    }
    public function CadastraUsuario($usuario)
    {
        $resultado = Insert(
            "insert into usuarios(nome, email, cpf, senha, nivel_usuario)
                          values(?,?,?,?,?)",
            [
                $usuario['nome'], $usuario['email'], $usuario['cpf'], $usuario['senha'],
                $usuario['nivel']
            ]
        );
        if ($usuario["nivel"] == "1") {
            $id = Sql("select id from usuarios where cpf = ?", [$usuario['cpf']]);
            if (count($id->resultados) > 0) {
                $id = $id->resultados[0]['id'];
                $resultado = Insert(
                    "insert into funcionario (id_usuario,curriculo,numero_telefone) values(?,?,?)",
                    [$id, null, null]
                );
            }
        }
        return $resultado;
    }
    public function UpdateUsuario($usuario)
    {
        $resultado = Update(
            "update usuarios set
            nome = ?,
            email = ?,
            nivel_usuario = ?
            where id = ? ",
            [$usuario["nome"], $usuario["email"], $usuario["nivel"], $usuario["id"]]
        );
        return $resultado;
    }
    public function DeleteUser($id)
    {
        $temImagem = Sql("select id from imagem_usuario where id_usuario = ?", [$id]);
        $temImagem = count($temImagem->resultados) > 0 ? $temImagem->resultados[0]["id"] : false;
        if ($temImagem != false)
            DeleteGenerico("imagem_usuario", $temImagem);

        $resultado = DeleteGenerico("usuarios", $id);
        return $resultado;
    }
    public function EditaUsuario($nomeCampo, $valorCampo, $idUsuario, $tabelaParaEditar)
    {

        $idPrimeiraVez = "";

        $idFuncionario = Sql("SELECT id FROM funcionario WHERE id_usuario =?", [$idUsuario]);
        $idFuncionario = isset($idFuncionario) ? $idFuncionario->resultados[0]["id"] : "";

        $totalServicos = Sql("SELECT COUNT( * )
                              FROM servicos_funcionario
                              WHERE id_funcionario = ? AND
                              Situacao = 1", [$idFuncionario]);

        $idValor = $idUsuario;
        if ($tabelaParaEditar == "usuarios") {
            $idPrimeiraVez = "id";
        } else if ($tabelaParaEditar == "servicos_funcionario") {
            $idValor = $idFuncionario;

            $idPrimeiraVez = "id_funcionario";
        } else {
            $idPrimeiraVez = "id_usuario";
        }

        $primeiraVez = Sql("select id FROM {$tabelaParaEditar} WHERE {$idPrimeiraVez} = ?", [$idValor]); //Checa para ver se é a primeira vez que é colocado algum dado dentro da tabela

        if (count($primeiraVez->resultados) == 1) { // Se ja existir algum dado dentro da tabela
            if ($tabelaParaEditar == "servicos_funcionario") {
                $resultado = Update("UPDATE servicos_funcionario AS sf
                                    INNER JOIN funcionario AS f ON f.id = sf.id_funcionario
                                    SET {$nomeCampo} = ?,
                                        numeros_servicos = ?
                                    WHERE f.id_usuario = ?
                            ", [$valorCampo, $totalServicos, $idUsuario]);
                $resultado = $valorCampo;
            } else if ($tabelaParaEditar != "usuarios") {
                $resultado = Update("UPDATE {$tabelaParaEditar} 
                                 SET {$nomeCampo} = ?
                                 WHERE id_usuario = ?", [$valorCampo, $idUsuario]);
            } else {
                $resultado = Update("UPDATE {$tabelaParaEditar} *
                                 SET {$nomeCampo} = ?
                                 WHERE id = ?", [$valorCampo, $idUsuario]);
            }
        } else { // Se não existir nada na tabela
            if ($tabelaParaEditar == "servicos_funcionario") {



                $resultado = Insert("insert INTO {$tabelaParaEditar} ($nomeCampo, numeros_servicos, id_funcionario) 
                                 VALUES (?,?,?)", [$valorCampo, $totalServicos, $idFuncionario]);
            } else {
                $resultado = Insert("insert INTO {$tabelaParaEditar} ({$nomeCampo}, id_usuario) 
                                 VALUES (?,?)", [$valorCampo, $idUsuario]);
            }
        }


        return $resultado;
    }
    public function SalvarOuAtualizarImagem($img, $idUsuario)
    {
        $Busca = Sql("select id from imagem_usuario where id_usuario =?", [$idUsuario]);
        if (count($Busca->resultados) == 1)
            $resultado = Insert("update  imagem_usuario set imagem = ? where id_usuario = ? ", [$img, $idUsuario]);
        else
            $resultado = Insert("insert into imagem_usuario(imagem, id_usuario)
        values(?, ?)", [$img, $idUsuario]);

        return $resultado;
    }
    public function GetUsuarioCompletobyId($id)
    {
        $retorno  = Sql("select u.id,u.nome,u.email,u.cpf,u.nivel_usuario,im.imagem from usuarios as u
                        left join imagem_usuario as im on im.id_usuario = u.id  
                        where u.id = ? ", [$id]);
        return $retorno;
    }

    public function GetFuncionarioCompletobyId($id) {
        $retorno = Sql("SELECT u.id,u.nome,u.email,u.cpf,u.nivel_usuario,im.imagem, func.avaliacao_media FROM usuarios AS u
                        LEFT JOIN imagem_usuario AS im ON im.id_usuario = u.id  
                        LEFT JOIN funcionario AS func ON func.id_usuario = u.id
                        where u.id = ? ", [$id]);
        return $retorno;
    }

    public function BuscaNumeroUsuarios() 
    {
        $retorno = Sql("SELECT COUNT(id) FROM usuarios", []);
        return $retorno->resultados[0];
    }

}
// $USR->example();
