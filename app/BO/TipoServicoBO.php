<?php
include("../Classes/TipoServico.php");
include("../DAO/TipoServicoDAO.php");
include("../functions/ImageUtils.php");

if (isset($_POST['metodo']) && !empty($_POST['metodo'])) {
    $metodo = $_POST['metodo'];
    $TipoServicoBO =  new TipoServicoBO();
    if ($metodo == "GetTipoServicoCategoria")
        $TipoServicoBO->GetTipoServicoCategoria();
    if ($metodo == "GetTipoServicoTable")
        $TipoServicoBO->GetTipoServicoTable();
    if ($metodo == "GetMaisUtilizados")
        $TipoServicoBO->GetMaisUtilizados();
    if ($metodo == "GetTipoServicoLista") {
        $ord = $_POST['order'];
        $TipoServicoBO->GetTipoServicosCompletoAtivo($ord);
    }
    if ($metodo == "GetImagemTipoServico") {
        $id = $_POST["ID"];
        $TipoServicoBO->GetImagemTipoServico($id);
    }
    if ($metodo == "RemoverTipoServico") {
        $id = $_POST["ID"];
        $TipoServicoBO->RemoverTipoServico($id);
    }
    if ($metodo == "InsertTipoServico") {
        $tpServico = $_POST['tipoServico'];
        $TipoServicoBO->InsertTipoServico($tpServico);
    }
    if ($metodo == "UpdateTipoServico") {
        $tpServico = $_POST['tipoServico'];
        $TipoServicoBO->UpdateTipoServico($tpServico);
    }
}
class TipoServicoBO
{
    public $tipoServicoDAO = null;
    function __construct()
    {
        $this->tipoServicoDAO =  new TipoServicoDAO;
    }
    public function GetTipoServicoCategoria()
    {

        $saida =  $this->tipoServicoDAO->GetTipoServicoCategoria();
        echo json_encode($saida->resultados);
    }
    public function GetTipoServicoTable()
    {
        $teste = $this->tipoServicoDAO->GetTipoServicos();
        $data = new StdClass();
        foreach ($teste->resultados as $key => $value) {
            $teste->resultados[$key]["nomeFormated"] = "<div style='display:flex;
                                                     height:100px!important;
                                                     position:relative;
                                                     top:5vh;
                                                     justify-content:center;
                                                     align-itens:center;'><p>" . $teste->resultados[$key]["nome"] . "</p></div>";
            $teste->resultados[$key]["AtivoIcone"] = array();
            if ($teste->resultados[$key]["imagem"] == null)
                $teste->resultados[$key]["imagem"] = "<i class='fas fa-camera' style='position:relative;left:40%;color:gray;'></i>";
            else
                $teste->resultados[$key]["imagem"] = "<img src='data:image/jpeg;base64," . ConvertBlobToBase64($teste->resultados[$key]["imagem"]) . "' 
                        style='width:80px;height:80px!important;border-radius:100%;
                        position: relative;
                        left: 20%;
                        top:10px;
                        border: solid;
                    }' />";

            if ($value["Ativo"] == '0')
                array_push($teste->resultados[$key]["AtivoIcone"], "<i class='fas fa-check' style='position:relative;top:5vh;left:40%;color:green;' />");
            else
                array_push($teste->resultados[$key]["AtivoIcone"], "<i class='fas fa-times' style='position:relative;top:5vh;left:40%;color:red;' />");
        }
        $data->data = $teste->resultados;
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }
    public function GetImagemTipoServico($id)
    {
        try {

            $img = $this->tipoServicoDAO->GetImagemTipoServico($id);
            $a  = $img->campos[0];
            if ($img->resultados[0][$img->campos[0]] == null)
                echo null;
            else {
                $img = ConvertBlobToBase64($img->resultados[0][$img->campos[0]]);
                $item = new stdClass();
                $item->img = $img;
                $item->principal = true;
                $arr = array();
                array_push($arr, $item);
                echo json_encode($arr);
            }
        } catch (Throwable $th) {
            $msg = new stdClass();
            $msg->error = $th->getMessage();
            echo json_encode($msg->error);
        }
    }
    public function InsertTipoServico($TipoServico)
    {
        try {
            if ($TipoServico['nome'] == "")
                throw new Exception("Preencha o Campo Nome");
            if ($TipoServico['imagem'] != null)
                $TipoServico['imagem'] = ConvertBase64ToBlob($TipoServico['imagem']);
            $this->tipoServicoDAO->InsertTipoServico($TipoServico);
            echo "OK";
        } catch (Throwable $th) {
            $msg = new stdClass();
            $msg->error = $th->getMessage();
            echo "ERROR|" . json_encode($msg);
        }
    }
    public function RemoverTipoServico($Id)
    {
        try {
            $this->tipoServicoDAO->RemoverTipoServico($Id);
            echo "OK";
        } catch (Throwable $th) {
            $msg = new stdClass();
            $msg->error = $th->getMessage();
            echo json_encode($msg->error);
        }
    }
    public function UpdateTipoServico($TipoServico)
    {
        try {
            if ($TipoServico['nome'] == "")
                throw new Exception("Preencha o Campo Nome");
            if ($TipoServico['imagem'] != null)
                $TipoServico['imagem'] = ConvertBase64ToBlob($TipoServico['imagem']);
            $this->tipoServicoDAO->UpdateTipoServico($TipoServico);
            echo "OK";
        } catch (Throwable $th) {
            $msg = new stdClass();
            $msg->error = $th->getMessage();
            echo "ERROR|" . json_encode($msg);
        }
    }
    public function GetTipoServicosCompletoAtivo($ord)
    {
        try {
            $TPServico = $this->tipoServicoDAO->GetTipoServicosCompletosAtivos($ord);
            foreach ($TPServico->resultados as $key => $value)
                $TPServico->resultados[$key]["imagem"] = ConvertBlobToBase64($TPServico->resultados[$key]["imagem"]);
            echo json_encode($TPServico->resultados);
        } catch (Throwable $th) {
            $msg = new stdClass();
            $msg->error = $th->getMessage();
            echo json_encode($msg);
        }
    }
    public function GetMaisUtilizados()
    {
        try {
            $TPServico = $this->tipoServicoDAO->GetMaisUtilizados();
            foreach ($TPServico as $key => $value)
                $TPServico[$key]["imagem"] = ConvertBlobToBase64($value["imagem"]);
            echo json_encode($TPServico);
        } catch (Throwable $th) {
            $msg = new stdClass();
            $msg->error = $th->getMessage();
            echo json_encode($msg);
        }
    }
}
