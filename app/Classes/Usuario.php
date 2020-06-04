<?php
// namespace app\Classes;

class Usuario
{
    public $id = -1;
    public $nome = '';
    public $email = '';
    public $senha = '';
    public $cpf = '';
    public $Nivel_Usuario = NivelUsuario::Cliente;
    public $foto;

    public function example()
    {
        echo $this->nome;
    }
}
abstract class NivelUsuario
{
    const Cliente = 0;
    const Funcionario = 1;
    const Adm = 2;
    const Programador = 3;
}
abstract class NivelUsuarioIcone
{
    const Cliente = "fa-user";
    const Funcionario = "fa-user-tie";
    const Adm = "fa-user-shield";
    const Programador = "fa-user-astronaut";
}