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
        if ($metodo == "BuscaNotificacoesFormatadoComParametros") {
            echo json_encode($NotificacaoBO->BuscaNotificacoesFormatadoComParametros(isset($_POST["PARAMETROS"]) ? $_POST["PARAMETROS"] : [], $_POST["PAGINA"]));
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
        $resultado =  $this->NotificacoesDAO->BuscaNotificacoes($idusuario, false);
        $novoArr = [];
        $arrayVisualizar = [];
        array_splice($resultado, 10);
        date_default_timezone_set('America/Sao_Paulo');
        foreach ($resultado as $key => $value) {
            $hora =   new DateTime($resultado[$key]['data_hora']);

            $resultado[$key]['hora'] = $hora->format('H:i');
            if ($resultado[$key]['id'] != -1 && $resultado[$key]['visto'] == 0)
                array_push($arrayVisualizar, $resultado[$key]['id']);
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
        $this->NotificacoesDAO->UpdateVistoVariasNotificacoes($arrayVisualizar);
        CriaSecao(SecoesEnum::NOTIFICACOES, json_encode($novoArr));
        return $novoArr;
    }
    public function BuscaNotificacoesFormatadoComParametros($parametros = [], $pagina)
    {
        $pagina = json_decode($pagina);
        $idusuario = BuscaSecaoValor(SecoesEnum::IDUSUARIOCONTEXTO);
        $resultado =  $this->NotificacoesDAO->BuscaNotificacoesComPaginacao($idusuario, $parametros, $pagina);
        $novoArr = [];
        $arrayVisualizar = [];
        date_default_timezone_set('America/Sao_Paulo');
        foreach ($resultado[1] as $key => $value) {
            $hora =   new DateTime($resultado[1][$key]['data_hora']);

            $resultado[1][$key]['hora'] = $hora->format('H:i');
            if ($resultado[1][$key]['id'] != -1 && $resultado[1][$key]['visto'] == 0)
                array_push($arrayVisualizar, $resultado[1][$key]['id']);
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
                    array_push($novoArr, $separador, $resultado[1][$key]);
                else
                    array_push($novoArr, $resultado[1][$key]);
            } else
                array_push($novoArr, $resultado[1][$key]);
        }
        $this->NotificacoesDAO->UpdateVistoVariasNotificacoes($arrayVisualizar);

        $obj = new stdClass();
        $obj->paginas = $resultado[0];
        $obj->lista = $novoArr;
        return $obj;
    }
    public function NovaNotificacao($titulo, $descricao, $idUsuario, $idUsuarioCriacao, $tipo, $idProjeto = null, $idChat = null)
    {
        $Notificacao = new Notificacao(-1, $descricao, $titulo, $idProjeto, $idChat, $idUsuario, $idUsuarioCriacao, null, $tipo);
        return $this->NotificacoesDAO->SalvarAtualizarNotificacao($Notificacao);
    }
}
