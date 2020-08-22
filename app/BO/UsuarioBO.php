<?php
    @include("../Classes/Usuario.php");
    @include("../DAO/UsuarioDAO.php");
    @include("../functions/ImageUtils.php");
    @include("../functions/EnumUtils.php"); 
    @require_once("../Enums/SecoesEnum.php");
    @require_once("../functions/Session.php");

if (isset($_POST['metodo']) && !empty($_POST['metodo'])) {
    $metodo = $_POST['metodo'];
    $userBO =  new UsuarioBO;
    if ($metodo == "GetUsuarioTable")
        $userBO->GetUsuarioTable();
    if($metodo == "GetUsuarioAdmTable")
        $userBO->GetUsuarioAdmTable();
    if($metodo == "GetUsuarioNivelSelect")
        $userBO->GetUsuarioNivelSelect();
    if($metodo == "DeleteUsuario"){
        $id = $_POST['ID'];
        $userBO->DeleteUsuario($id);
        }
    if ($metodo == "CadastraUsuario") {
        $_usr = $_POST['Usuario'];
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
    if($metodo == "RegistraUsuarioAdm"){
        $_usr = $_POST['Usuario'];
        $userBO->RegistraUsuarioAdm($_usr);
    }
    if ($metodo == "EditaUsuario") {
        $nomeCampo = $_POST['nomeCampo'];
        $valorCampo = $_POST['valorCampo'];
        $idUsuario = $_POST['idUsuario'];
        $userBO->EditaUsuario($nomeCampo, $valorCampo, $idUsuario);
    }
    if($metodo == "SalvaImagem"){
        $img = $_POST['IMAGEM'];
        $userBO->SalvaImagem($img);
    }

    if($metodo == "SalvaImagemBanner"){
        $img = $_POST['IMAGEM'];
        $userBO->SalvaImagemBanner($img);
    }
    if ($metodo == "Logar") {
        $userBO->Logar($_POST['email'], $_POST['senha']);
    }
    if ($metodo == "Logout") {
        $userBO->Logout();
    }
    if($metodo == "GetUsuarioById"){
        $userBO->GetUsuarioById($_POST["ID"]);
    }
    if($metodo == "GetFuncionarioById") {
        $userBO->GetFuncionarioById($_POST["ID"]);
    }
    if($metodo == "GetBannerById") {
        $id = BuscaSecaoValor(SecoesEnum::IDUSUARIOCONTEXTO);
        $userBO->GetBannerById($id);
    }

    if($metodo == "BuscaNumeroUsuarios") {
        $userBO->BuscaNumeroUsuarios();
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
    public function GetBannerById($id)
    {
        $saida = $this->usuarioDAO->GetImagemBannerbyId($id)->resultados;
        $saida[0]["imagem_banner"] = ConvertBlobToBase64($saida[0]["imagem_banner"]);
        echo json_encode($saida[0]);
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
    public function GetUsuarioNivelSelect(){
        $nivelArr = EnumParaArray(NivelUsuario::class);
        $nivelIconeArr = EnumParaArray(NivelUsuarioIcone::class);
        $resultado = array();
        foreach ($nivelArr as $key => $value) {
            $cl = new stdClass;
            $cl->id = $value;
            $cl->nome = $key == "Adm"?"Administrador":$key;
            $cl->icone = $nivelIconeArr[$key];
            array_push($resultado,$cl);
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
            else if($value["nivel_usuario"] == '1')
                array_push($teste->resultados[$key]["NivelIcone"], "<i class='fas fa-user-tie' style='position:relative;left:40%;' />");
            else if($value["nivel_usuario"] == '2')
                array_push($teste->resultados[$key]["NivelIcone"], "<i class='fas fa-user-shield' style='position:relative;left:40%;' />");
            else if($value["nivel_usuario"] == '3')
                array_push($teste->resultados[$key]["NivelIcone"], "<i class='fas fa-user-astronaut' style='position:relative;left:40%;' />");
            }
        $data->data = $teste->resultados;
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }
    public function CadastraUsuario($usuario)
    {
        try {
            $usuario = json_decode($usuario, true);
            foreach ($usuario as $key => $valor) {
                if($valor == "" && $key != "id") {
                    throw new Exception("Preencha o campo ". ucwords($key)); //ucwords capitaliza as palavras
                }
            }
            if(!filter_var($usuario["email"], FILTER_VALIDATE_EMAIL)) {
                throw new Exception("Preencha o campo de email corretamente");
            }

            $usuario["cpf"] = str_replace(".", "", $usuario["cpf"]);
            $usuario["cpf"] = str_replace("-", "", $usuario["cpf"]); // Substitui os pontos e traços

            if(strlen($usuario["cpf"]) != 11) {
                throw new Exception("Preencha todo o campo de cpf");
            }

            if($usuario["senha"] !== $usuario["repetirSenha"]) {
                throw new Exception("As duas senhas devem ser iguais.");
            }

            
            $usuario['senha'] = md5($usuario['senha']);
            $usuario['nivel'] = isset($usuario['checkFuncionario']) ? 1 : 0;
            $usuario['avaliacaoMedia'] = 4; 
            $Insert = $this->usuarioDAO->CadastraUsuario($usuario);
       
            echo $Insert;
        } catch (Throwable $th) {
            $msg = new stdClass();
            $msg->error = $th->getMessage();
            echo json_encode($msg->error);
        }
    }
    public function RegistraUsuarioAdm($usuario){
        try{
            if(strlen($usuario["cpf"]) < 11)
                throw new Exception("Cpf Invalido");

            if($usuario['id'] == -1){

                $usuario['senha'] = md5(123);
                $Insert = $this->usuarioDAO->CadastraUsuario($usuario);
                echo $Insert;
            }else{
                $Up = $this->usuarioDAO->UpdateUsuario($usuario);
                echo $Up;
            }

        }
        catch (Throwable $th) {
            $msg = new stdClass();
            $msg->error = $th->getMessage();
            echo json_encode($msg->error);
        }
    }
    public function RegistraUsuarioGoogle($usuario){
        try{
            if(strlen($usuario["cpf"]) < 11)
                throw new Exception("Cpf Invalido");


                $usuario['senha'] = md5('');
                $Insert = $this->usuarioDAO->CadastraUsuario($usuario);
                $id = $this->usuarioDAO->GetUsuarioIdPorEmail($usuario['email']);
                $Insert = $this->usuarioDAO->SalvarOuAtualizarImagem(ConvertBase64ToBlob($usuario['imagem']),$id);
                
                echo $Insert;

        }
        catch (Throwable $th) {
            $msg = new stdClass();
            $msg->error = $th->getMessage();
            echo json_encode($msg->error);
        }
    }
    public function DeleteUsuario($id){
        try{
            if($id == BuscaSecaoValor(SecoesEnum::IDUSUARIOCONTEXTO))
                throw new Exception("Não é possivel Remover o Usuario atual.");
            $delete = $this->usuarioDAO->DeleteUser($id);
            echo $delete;
        }
        catch (Throwable $th) {
            $msg = new stdClass();
            $msg->error = $th->getMessage();
            echo json_encode($msg->error);
        }
    }
    public function EditaUsuario($nomeCampo, $valorCampo, $idUsuario) {

        try {
            if($valorCampo == "") {
                throw new Exception("Preencha o campo ". ucwords($nomeCampo)); //ucwords capitaliza as palavras
            }

            if($nomeCampo == "cpf") {
                if(strlen($valorCampo) != 14) {
                    throw new Exception("Preencha todo o campo de Cpf");
                }
                $valorCampo = str_replace(".", "", $valorCampo);
                $valorCampo = str_replace("-", "", $valorCampo); // Substitui os pontos e traços
            }


            $tabelaParaEditar = "usuarios";

            // IF ELSE PARA DEFINIR QUAL TABELA SERA EDITADA
            if($nomeCampo == "curriculo" || $nomeCampo == "telefone") {
                $tabelaParaEditar = "funcionario";

                if($nomeCampo == "telefone") {
                    $substituir = array("(", ")", " ", "-");
                    $valorCampo = str_replace($substituir, "", $valorCampo);
                    $nomeCampo = "numero_telefone";
                }
            } else if($nomeCampo == "avaliacaoMedia") {
                $numeroServicos = (Sql("SELECT sf.numeros_servicos FROM servicos_funcionario AS sf
                                        LEFT JOIN funcionario AS func ON func.id_usuario = $idUsuario  
                                        WHERE sf.id_funcionario = func.id"));
                $numeroServicos = isset($numeroServicos->resultados[0]) ? $numeroServicos->resultados[0]["numeros_servicos"] + 1 : 1;

                $valorCampo = BuscaSecaoValor(SecoesEnum::AVALIACAO_MEDIA) + ($valorCampo - BuscaSecaoValor(SecoesEnum::AVALIACAO_MEDIA)) / $numeroServicos;
                $tabelaParaEditar = "servicos_funcionario";
                
                if($nomeCampo == "avaliacaoMedia") {
                    $nomeCampo = "avaliacao_media";
                }
                
            }

            $Insert = $this->usuarioDAO->EditaUsuario($nomeCampo, $valorCampo, $idUsuario, $tabelaParaEditar);


            if ($nomeCampo == "nome") {
                CriaSecao(SecoesEnum::NOME, $valorCampo);
            } else if ($nomeCampo == "cpf") {
                CriaSecao(SecoesEnum::CPF, $valorCampo);
            } else if ($nomeCampo == "email") {
                CriaSecao(SecoesEnum::EMAIL, $valorCampo);
            } else if($nomeCampo == "curriculo") {
                CriaSecao(SecoesEnum::CURRICULO, $valorCampo);
            } else if($nomeCampo == "numero_telefone") {
                CriaSecao(SecoesEnum::NUMERO_TELEFONE, $valorCampo);
            } else if($nomeCampo == "avaliacao_media") {
                CriaSecao(SecoesEnum::AVALIACAO_MEDIA, $valorCampo);
            }
                
            
            echo $Insert . $numeroServicos;
        } catch (Throwable $th) {
            $msg = new stdClass();
            $msg->error = $th->getMessage();
            echo json_encode($msg->error);
        }
    }

    public function SalvaImagem($img){
        
        $idUsuario  = BuscaSecaoValor(SecoesEnum::IDUSUARIOCONTEXTO);
        try{
            $img = ConvertBase64ToBlob($img, true);
            $this->usuarioDAO->SalvarOuAtualizarImagem($img,$idUsuario);
            $img = ConvertBlobToBase64($img,true);
            CriaSecao(SecoesEnum::FOTO_USUARIO,$img);
            echo "OK";
        }
        catch (Throwable $th) {
            $msg = new stdClass();
            $msg->error = $th->getMessage();
            echo json_encode($msg->error);
        }
    }

    public function SalvaImagemBanner($img){
        
        $idUsuario = BuscaSecaoValor(SecoesEnum::IDUSUARIOCONTEXTO);
        try{
            $img = ConvertBase64ToBlob($img, true, true);
            $this->usuarioDAO->SalvarOuAtualizarImagemBanner($img,$idUsuario);
            echo "OK";
        }
        catch (Throwable $th) {
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

    public function BuscaNumeroUsuarios() {
        echo json_encode($this->usuarioDAO->BuscaNumeroUsuarios());
    }
}
