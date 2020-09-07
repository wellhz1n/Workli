<?php
@require_once("../../bootstrap.php");
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
                    "insert into funcionario (id_usuario) values(?)",
                    [$id]
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
    public function EditaUsuario($usuarioDados)
    {
        $resultado = "";
        
        /* Checa para ver se é um funcionario */
        $funcionario = Sql("SELECT nivel_usuario FROM usuarios WHERE id = ?", [$usuarioDados["ID"]]); 

        /*Checa para ver se existe a row na tabela de funcionarios*/
        $existe = Sql("SELECT EXISTS(SELECT * FROM funcionario WHERE id_usuario = ?)", [$usuarioDados["ID"]]);

        if($funcionario->resultados[0]["nivel_usuario"]) {
            if($existe->resultados) { /*Se a row ja existe na tabela de func*/
                $resultado = Update(
                    "UPDATE usuarios AS US
                    INNER JOIN funcionario AS FUNC
                    ON US.id = FUNC.id_usuario 
                    SET
                    US.nome = ?,
                    US.descricao = ?,
                    FUNC.profissao = ?,
                    FUNC.tags = ?
                    where US.id = ?",
                    [$usuarioDados["nome"], $usuarioDados["descricao"], $usuarioDados["profissao"], $usuarioDados["tags"], $usuarioDados["ID"]]
                );
            } else { /*Se a row não existir na tabela de func (da update em usuarios e insert em funcionario)*/
                $resultado = Insert(
                "BEGIN;
                UPDATE usuarios AS US set US.nome = ?, US.descricao = ? WHERE US.id = ?;
                INSERT INTO funcionario(profissao, tags, id_usuario) 
                VALUES(?, ?, ?);
                COMMIT;
                ",
                [$usuarioDados["nome"], $usuarioDados["descricao"], $usuarioDados["ID"], $usuarioDados["profissao"], $usuarioDados["tags"], $usuarioDados["ID"]]);
            }
        } else { /*Se o usuário for um cliente*/
            $resultado = Update(
                "UPDATE usuarios AS US SET US.nome = ?, US.descricao = ? WHERE US.id = ?
                ",
                [$usuarioDados["nome"], $usuarioDados["descricao"], $usuarioDados["ID"]]);   
        }
        
        return $resultado;
        // return $funcionario->resultados[0];
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

    public function SalvarOuAtualizarImagemBanner($img, $idUsuario)
    {
        $Busca = Sql("SELECT id FROM imagem_usuario WHERE id_usuario =?", [$idUsuario]);
        if (count($Busca->resultados) == 1)
            $resultado = Insert("UPDATE imagem_usuario SET imagem_banner = ? WHERE id_usuario = ? ", [$img, $idUsuario]);
        else
            $resultado = Insert("INSERT INTO imagem_usuario(imagem_banner, id_usuario)
        VALUES(?, ?)", [$img, $idUsuario]);

        return $resultado;
    }


    public function GetUsuarioCompletobyId($id)
    {
        $retorno  = Sql("select u.id,u.nome,u.email,u.cpf,u.nivel_usuario,im.imagem from usuarios as u
                        left join imagem_usuario as im on im.id_usuario = u.id  
                        where u.id = ? ", [$id]);
        return $retorno;
    }

    public function GetFuncionarioCompletobyId($id)
    {
        $retorno = Sql("SELECT u.id,u.nome,u.email,u.cpf,u.nivel_usuario, im.imagem, func.avaliacao_media FROM usuarios AS u
                        LEFT JOIN imagem_usuario AS im ON im.id_usuario = u.id  
                        LEFT JOIN funcionario AS func ON func.id_usuario = u.id
                        where u.id = ? ", [$id]);
        return $retorno;
    }

    public function GetFuncionarioDataEdit($id)
    {
        $retorno = Sql("SELECT u.id, u.nome, u.descricao, func.profissao, func.tags FROM usuarios AS u
                        LEFT JOIN funcionario AS func ON func.id_usuario = u.id
                        where u.id = ? ", [$id]);
        return $retorno;
    }

    public function GetImagemBannerbyId($id)
    {
        $retorno = Sql("SELECT u.id, img.imagem_banner FROM usuarios as u
                        LEFT JOIN imagem_usuario AS img ON img.id_usuario = u.id
                        WHERE u.id = ? ", [$id]);
        return $retorno;
    }

    public function BuscaNumeroUsuarios()
    {
        $retorno = Sql("SELECT COUNT(id) FROM usuarios");
        return $retorno->resultados[0];
    }
   
    public function SetDadoUsuario($id, $coluna, $dado, $tabela) {
        
        if($tabela == "funcionario") {
            $coluna = "FUNC.".$coluna;
            $resultado = Update(
                "UPDATE funcionario AS FUNC
                INNER JOIN usuarios AS US
                ON FUNC.id_usuario = US.id
                SET
                $coluna = ?
                WHERE FUNC.id_usuario = ?",
                [
                    $dado,
                    $id
                ]
            );
        } else if($tabela == "usuarios") {
            $coluna = "US.".$coluna;
            $resultado = Update(
                "UPDATE usuarios AS US
                SET
                $coluna = ?
                WHERE US.id = ?",
                [
                    $dado,
                    $id
                ]
            );
        }
        
    }
}
// $USR->example();
