<?php
@require_once("../Classes/Usuario.php");
@require_once("../DAO/UsuarioDAO.php");
@require_once("../functions/ImageUtils.php");
@require_once("../functions/EnumUtils.php");
if (!class_exists(SituacaoEnum::class, false))
    @require_once("../Enums/SecoesEnum.php");
@require_once("../functions/Session.php");

if (isset($_POST['metodo']) && !empty($_POST['metodo'])) {
    $metodo = $_POST['metodo'];
    $userBO =  new UsuarioBO;
    if ($metodo == "GetUsuarioTable")
        $userBO->GetUsuarioTable();
    if ($metodo == "GetUsuarioAdmTable")
        $userBO->GetUsuarioAdmTable();
    if ($metodo == "GetUsuarioNivelSelect")
        $userBO->GetUsuarioNivelSelect();
    if ($metodo == "DeleteUsuario") {
        $id = $_POST['ID'];
        $userBO->DeleteUsuario($id);
    }
    if ($metodo == "CadastraUsuario") {
        $_usr = json_decode($_POST['Usuario'], true);
        $userBO->CadastraUsuario($_usr);
    }
    if ($metodo == "RegistraUsuarioGoogle") {
        $_usr = $_POST['Usuario'];
        $userBO->RegistraUsuarioGoogle($_usr);
    }
    if ($metodo == "VerificaEmailExiste") {
        $em = $_POST['EMAIL'];
        $userBO->VerificaSeEmailExiste($em);
    }
    if ($metodo == "RegistraUsuarioAdm") {
        $_usr = $_POST['Usuario'];
        $userBO->RegistraUsuarioAdm($_usr);
    }
    if ($metodo == "EditaUsuario") {
        $usuarioDados = $_POST["UsuarioDados"];
        $userBO->EditaUsuario($usuarioDados);
    }
    if ($metodo == "SalvaImagem") {
        $img = $_POST['IMAGEM'];
        $userBO->SalvaImagem($img);
    }

    if ($metodo == "SalvaImagemBanner") {
        $img = $_POST['IMAGEM'];
        $userBO->SalvaImagemBanner($img);
    }
    if ($metodo == "Logar") {
        $userBO->Logar($_POST['email'], $_POST['senha']);
    }
    if ($metodo == "Logout") {
        $userBO->Logout();
    }
    if ($metodo == "GetUsuarioById") {
        $userBO->GetUsuarioById($_POST["ID"]);
    }
    if ($metodo == "GetFuncionarioById") {
        $userBO->GetFuncionarioById($_POST["ID"]);
    }

    if ($metodo == "GetFuncionarioByIdDataEdit") {
        $userBO->GetFuncionarioByIdDataEdit($_POST["ID"]);
    }

    if ($metodo == "GetBannerById") {
        // $id = BuscaSecaoValor(SecoesEnum::IDUSUARIOCONTEXTO);
        $id = isset($_POST["idUsuario"]) ? $_POST["idUsuario"] : 0;
        $userBO->GetBannerById($id);
    }

    if ($metodo == "GetImagemUserById") {
        // $id = BuscaSecaoValor(SecoesEnum::IDUSUARIOCONTEXTO);
        $id = isset($_POST["idUsuario"]) ? $_POST["idUsuario"] : 0;
        $userBO->GetImagemUserById($id);
    }

    if ($metodo == "BuscaNumeroUsuarios") {
        $userBO->BuscaNumeroUsuarios();
    }

    if ($metodo == "SetDadoUsuario") {
        $dados = $_POST["dados"];

        if (!isset($dados["sessao"])) {
            $dados["sessao"] = false;
        }
        if (!isset($dados["tabela"])) {
            $dados["tabela"] = "usuarios";
        }

        $userBO->SetDadoUsuario($dados["ID"], $dados["coluna"], $dados["dado"], $dados["sessao"], $dados["tabela"]);
    }

    if ($metodo == "GetNivelUsuarioById") {
        $id = isset($_POST["idUsuario"]) ? $_POST["idUsuario"] : 0;
        $userBO->GetNivelUsuarioById($id);
    }

    if ($metodo == "GetPlanoById") {
        $id = isset($_POST["idUsuario"]) ? $_POST["idUsuario"] : 0;
        $userBO->GetPlanoById($id);
    }

    if ($metodo == "BuscarUsuarios") {
        $P = empty($_POST["P"]) ? 1 : $_POST["P"];
        $filtro = empty($_POST["filtro"]) ? "" : $_POST["filtro"];
        $userBO->BuscarUsuarios($P, $filtro);
    }

    if ($metodo == "BuscarProfissoes") {
        $query = empty($_POST["queryProf"]) ? "" : $_POST["queryProf"];
        $userBO->BuscarProfissoes($query);
    }

    if ($metodo == "GetFuncionarioByIdProjeto") {
        $userBO->GetFuncionarioByIdProjeto($_POST["IDPROJETO"]);
    }
    if ($metodo == "GetDadosDeCima") {
        try {
            $id = $_POST["id"];
            $tipo = $_POST["tipo"];
        } catch (\Throwable $th) {
            echo "Falta dados para a execução.";
        }
        $userBO->GetDadosDeCima($id, $tipo);
    }
}
class UsuarioBO
{
    public $usuarioDAO;
    function __construct()
    {
        $this->usuarioDAO = new UsuarioDAO();
    }
    public function GetUsuarioById($id)
    {

        $saida =  $this->usuarioDAO->GetUsuarioCompletobyId($id)->resultados;
        $saida[0]["imagem"] = ConvertBlobToBase64($saida[0]["imagem"]);
        echo json_encode($saida[0]);
    }

    public function GetFuncionarioById($id)
    {
        $saida = $this->usuarioDAO->GetFuncionarioCompletobyId($id)->resultados;
        $saida[0]["imagem"] = ConvertBlobToBase64($saida[0]["imagem"]);
        echo json_encode($saida[0]);
    }

    public function GetFuncionarioByIdDataEdit($id)
    {
        $saida = $this->usuarioDAO->GetFuncionarioDataEdit($id)->resultados;
        echo json_encode($saida[0]);
    }

    public function GetBannerById($id)
    {
        $saida = $this->usuarioDAO->GetImagemBannerbyId($id)->resultados;
        if (isset($saida[0])) {
            $saida[0]["imagem_banner"] = ConvertBlobToBase64($saida[0]["imagem_banner"]);
            echo json_encode($saida[0]);
        } else {
            echo 1;
        }
    }

    public function GetImagemUserById($id)
    {
        $saida = $this->usuarioDAO->GetImagemUserById($id)->resultados;
        if (isset($saida[0])) {
            $saida[0]["imagem"] = ConvertBlobToBase64($saida[0]["imagem"]);
            echo json_encode($saida[0]["imagem"]);
        } else {
            echo 1;
        }
    }

    public function GetNivelUsuarioById($id)
    {
        $saida = $this->usuarioDAO->GetNivelUsuarioById($id)->resultados;
        if (isset($saida[0])) {
            echo json_encode($saida[0]["nivel_usuario"]);
        } else {
            echo 1;
        }
    }

    public function GetPlanoById($id)
    {
        $saida = $this->usuarioDAO->GetPlanoById($id)->resultados;
        if (isset($saida[0])) {
            echo json_encode($saida[0]["plano"]);
        } else {
            echo 1;
        }
    }

    public function VerificaSeEmailExiste($email)
    {
        $teste = $this->usuarioDAO->VerificaEmail($email);
        echo $teste;
    }
    public function GetUsuario()
    {
        $teste = $this->usuarioDAO->GetUsuarios();
        echo json_encode($teste);
    }
    public function GetUsuarioTable()
    {
        $teste = $this->usuarioDAO->GetUsuarios();

        $data = new StdClass;
        $data->data = $teste->resultados;
        echo json_encode($data);
        return json_encode($data);
    }
    public function GetUsuarioNivelSelect()
    {
        $nivelArr = EnumParaArray(NivelUsuario::class);
        $nivelIconeArr = EnumParaArray(NivelUsuarioIcone::class);
        $resultado = array();
        foreach ($nivelArr as $key => $value) {
            if ($key != "Funcionario") {
                $cl = new stdClass;
                $cl->id = $value;
                $cl->nome = $key == "Adm" ? "Administrador" : $key;
                $cl->icone = $nivelIconeArr[$key];
                array_push($resultado, $cl);
            }
        }
        echo json_encode($resultado);
    }
    public function GetUsuarioAdmTable()
    {
        $teste = $this->usuarioDAO->GetUsuarios();

        $data = new StdClass;
        foreach ($teste->resultados as $key => $value) {
            $teste->resultados[$key]["NivelIcone"] = array();
            if ($value["nivel_usuario"] == '0')
                array_push($teste->resultados[$key]["NivelIcone"], "<i class='fas fa-user' style='position:relative;left:40%;' />");
            else if ($value["nivel_usuario"] == '1')
                array_push($teste->resultados[$key]["NivelIcone"], "<i class='fas fa-user-tie' style='position:relative;left:40%;' />");
            else if ($value["nivel_usuario"] == '2')
                array_push($teste->resultados[$key]["NivelIcone"], "<i class='fas fa-user-shield' style='position:relative;left:40%;' />");
            else if ($value["nivel_usuario"] == '3')
                array_push($teste->resultados[$key]["NivelIcone"], "<i class='fas fa-user-astronaut' style='position:relative;left:40%;' />");
        }
        $data->data = $teste->resultados;
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }
    public function CadastraUsuario($usuario)
    {
        try {
            // $usuario = json_decode($usuario, true);
            foreach ($usuario as $key => $valor) {
                if ($valor == "" && $key != "id") {
                    throw new Exception("Preencha o campo " . ucwords($key)); //ucwords capitaliza as palavras
                }
            }
            if (!filter_var($usuario["email"], FILTER_VALIDATE_EMAIL)) {
                throw new Exception("Preencha o campo de email corretamente");
            }

            $usuario["cpf"] = str_replace(".", "", $usuario["cpf"]);
            $usuario["cpf"] = str_replace("-", "", $usuario["cpf"]); // Substitui os pontos e traços

            if (strlen($usuario["cpf"]) != 11) {
                throw new Exception("Preencha todo o campo de cpf");
            }

            if ($usuario["senha"] !== $usuario["repetirSenha"]) {
                throw new Exception("As duas senhas devem ser iguais.");
            }


            $usuario['senha'] = md5($usuario['senha']);
            $usuario['nivel'] = isset($usuario['checkFuncionario']) ? 1 : 0;
            $usuario['avaliacaoMedia'] = 3;
            $Insert = $this->usuarioDAO->CadastraUsuario($usuario);

            echo $Insert;
        } catch (Throwable $th) {
            $msg = new stdClass();
            $msg->error = $th->getMessage();
            echo json_encode($msg->error);
        }
    }
    public function RegistraUsuarioAdm($usuario)
    {
        try {
            if (strlen($usuario["cpf"]) < 11)
                throw new Exception("Cpf Invalido");

            if ($usuario['id'] == -1) {

                $usuario['senha'] = md5(123);
                $Insert = $this->usuarioDAO->CadastraUsuario($usuario);
                echo $Insert;
            } else {
                $Up = $this->usuarioDAO->UpdateUsuario($usuario);
                echo $Up;
            }
        } catch (Throwable $th) {
            $msg = new stdClass();
            $msg->error = $th->getMessage();
            echo json_encode($msg->error);
        }
    }
    public function RegistraUsuarioGoogle($usuario)
    {
        try {
            if (strlen($usuario["cpf"]) < 11)
                throw new Exception("Cpf Invalido");


            $usuario['senha'] = md5('');
            $Insert = $this->usuarioDAO->CadastraUsuario($usuario);
            $id = $this->usuarioDAO->GetUsuarioIdPorEmail($usuario['email']);
            $Insert = $this->usuarioDAO->SalvarOuAtualizarImagem(ConvertBase64ToBlob($usuario['imagem']), $id);

            echo $Insert;
        } catch (Throwable $th) {
            $msg = new stdClass();
            $msg->error = $th->getMessage();
            echo json_encode($msg->error);
        }
    }
    public function DeleteUsuario($id)
    {
        try {
            if ($id == BuscaSecaoValor(SecoesEnum::IDUSUARIOCONTEXTO))
                throw new Exception("Não é possivel Remover o Usuario atual.");
            $delete = $this->usuarioDAO->DeleteUser($id);
            echo $delete;
        } catch (Throwable $th) {
            $msg = new stdClass();
            $msg->error = $th->getMessage();
            echo json_encode($msg->error);
        }
    }
    public function EditaUsuario($usuarioDados)
    {

        try {

            $usuarioDados["nome"] = trim($usuarioDados["nome"]); //Tira os espaços do começo e fim

            if (strlen($usuarioDados["nome"]) < 3) { //Checa o length.
                throw new Exception("O \"Nome\" é muito pequeno.");
            }


            $partes = explode(',', $usuarioDados["tags"]);
            $partes = array_map('trim', $partes);
            $usuarioDados["tags"] = implode(',', $partes);

            $Insert = $this->usuarioDAO->EditaUsuario($usuarioDados);

            CriaSecao(SecoesEnum::NOME, $usuarioDados["nome"]);
            CriaSecao(SecoesEnum::PROFISSAO, $usuarioDados["profissao"]);

            echo $Insert;
        } catch (Throwable $th) {
            $msg = new stdClass();
            $msg->error = $th->getMessage();
            echo json_encode($msg->error);
        }
    }

    public function SalvaImagem($img)
    {

        $idUsuario  = BuscaSecaoValor(SecoesEnum::IDUSUARIOCONTEXTO);
        try {
            $img = ConvertBase64ToBlob($img, false, true);
            $this->usuarioDAO->SalvarOuAtualizarImagem($img, $idUsuario);
            $img = ConvertBlobToBase64($img, true);
            CriaSecao(SecoesEnum::FOTO_USUARIO, $img);
            echo "OK";
        } catch (Throwable $th) {
            $msg = new stdClass();
            $msg->error = $th->getMessage();
            echo json_encode($msg->error);
        }
    }

    public function SalvaImagemBanner($img)
    {

        $idUsuario = BuscaSecaoValor(SecoesEnum::IDUSUARIOCONTEXTO);
        try {
            $img = ConvertBase64ToBlob($img, true);
            $this->usuarioDAO->SalvarOuAtualizarImagemBanner($img, $idUsuario);
            echo "OK";
        } catch (Throwable $th) {
            $msg = new stdClass();
            $msg->error = $th->getMessage();
            echo json_encode($msg->error);
        }
    }


    public function Logar($email, $senha)
    {
        $logou = Login($email, $senha);
        if ($logou == true)
            echo json_encode([$logou, BuscaSecaoValor(SecoesEnum::NIVEL_USUARIO)]);
        else
            echo $logou;
    }
    public function Logout()
    {
        Deslogar();
    }

    public function BuscaNumeroUsuarios()
    {
        echo json_encode($this->usuarioDAO->BuscaNumeroUsuarios());
    }

    public function SetDadoUsuario($id, $coluna, $dado, $sessao, $tabela)
    {
        try {
            $this->usuarioDAO->SetDadoUsuario($id, $coluna, $dado, $tabela);

            $arraySecEnum = EnumParaArray("SecoesEnum");
            if ($sessao) {
                CriaSecao($arraySecEnum[$sessao], $dado);
            }
            // echo BuscaSecaoValor(SecoesEnum::PLANO);
            echo 1;
        } catch (Throwable $th) {
            $msg = new stdClass();
            $msg->error = $th->getMessage();
            echo json_encode($msg->error);
        }
    }


    public function BuscarUsuarios($P = 1, $filtro)
    {
        $dados = $this->usuarioDAO->BuscaUsuarios($P, $filtro);
        $dt = $dados[0];
        foreach ($dt as $key => $value) {
            $dt[$key]["imagem"] = ConvertBlobToBase64($value["imagem"]);
        }
        $obj = new stdClass();
        $obj->lista = $dt;
        $obj->paginas = $dados[1];
        echo json_encode($obj);
    }

    public function BuscarProfissoes($query)
    {
        $dados = $this->usuarioDAO->BuscaProfissoes($query);
        $profissoes = [];

        foreach ($dados[0] as $key => $value) {
            array_push($profissoes, $value["profissao"]);
        }
        echo json_encode($profissoes);
    }

    public function GetFuncionarioByIdProjeto($idProjeto)
    {
        $dados = $this->usuarioDAO->GetFuncionarioPorIdProjeto($idProjeto);
        if ($dados != null) {
            foreach ($dados as $key => $value) {
                if ($key == "imagem" && $value !=  "")
                    $dados["imagem"] = ConvertBlobToBase64($value);
            }
        }
        echo json_encode($dados);
    }

    public function GetDadosDeCima($id, $tipo)
    {
        $dados = $this->usuarioDAO->GetDadosDeCima($id, $tipo);

        echo json_encode($dados);
    }
}
