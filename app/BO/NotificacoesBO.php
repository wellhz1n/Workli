<?php

@require_once("../Classes/Notificacao.php");
@require_once("../Enums/SecoesEnum.php");
@require_once("../DAO/NotificacoesDAO.php");
@require_once("../functions/Conexao.php");
@require_once("../functions/Session.php");

try {

    if (isset($_POST['metodo']) && !empty($_POST['metodo'])) {
        $metodo = $_POST['metodo'];
        $NotificacaoBO = new NotificacoesBO();
        if ($metodo == "GetNumeroNotificacoes") {
            echo json_encode($NotificacaoBO->GetNumeroNotificacoes());
        }
        if ($metodo == "BuscaNotificacoes") {
            echo json_encode($NotificacaoBO->BuscaNotificacoes());
        }
        if ($metodo == "BuscaNotificacoesFormatado") {
            echo json_encode($NotificacaoBO->BuscaNotificacoesFormatado());
        }
    }
} catch (Throwable $ex) {
    $msg = new stdClass();
    $msg->error = $ex->getMessage();
    echo json_encode($msg);
}

class NotificacoesBO
{
    private $NotificacoesDAO;
    private $Notificacao;
    function __construct()
    {
        $this->Notificacao = new Notificacao();
        $this->NotificacoesDAO = new NotificacoesDAO();
    }
    public function GetNumeroNotificacoes()
    {
        $idusuario = BuscaSecaoValor(SecoesEnum::IDUSUARIOCONTEXTO);
        $valorBanco = $this->NotificacoesDAO->NumeroNotificacoesNaoVistas($idusuario);
        $valorSecao = BuscaSecaoValor(SecoesEnum::NUMNOTIFICACOES);
        if ($valorSecao != null &&  $valorSecao == $valorBanco) {
            return $valorSecao;
        }
        CriaSecao(SecoesEnum::NUMNOTIFICACOES, $valorBanco);
        return $valorBanco;
    }
    public function BuscaNotificacoes()
    {
        $idusuario = BuscaSecaoValor(SecoesEnum::IDUSUARIOCONTEXTO);
        return $this->NotificacoesDAO->BuscaNotificacoes($idusuario);
    }
    public function BuscaNotificacoesFormatado()
    {
        $idusuario = BuscaSecaoValor(SecoesEnum::IDUSUARIOCONTEXTO);
        $resultado =  $this->NotificacoesDAO->BuscaNotificacoes($idusuario);
        $novoArr = [];
        array_splice($resultado, 10);
        date_default_timezone_set('America/Sao_Paulo');
        foreach ($resultado as $key => $value) {
            $hora =   new DateTime($resultado[$key]['data_hora']);

            $resultado[$key]['hora'] = $hora->format('H:i');
            // $hora = $hora->;
            if (date('Y-m-d') != $hora->format('Y-m-d')) {
                $separador = new Notificacao();
                $separador->data_hora = $hora->format('Y-m-d H:i:s');
                $separador->tipo = -1;
                $separador->id = -1;
                $ontem = new DateTime('yesterday');
                $separador->titulo = $ontem->format('d/m') ==  $hora->format('d/m') ? 'Ontem' : $hora->format('d/m');
                $teste = array_filter($novoArr, function ($v) {
                    if (is_array($v))
                        return $v['tipo'] == -1;
                    else
                        return $v->tipo == -1;
                });
                $teste = array_column($teste, 'titulo');
                if (!in_array($separador->titulo, $teste))
                    array_push($novoArr, $separador, $resultado[$key]);
                else
                    array_push($novoArr, $resultado[$key]);
            } else
                array_push($novoArr, $resultado[$key]);
        }
        return $novoArr;
    }
}
