let ResultadoSql = {
    campos: [],
    resultados: [],
    error: []
}

let Usuario = {
    id: 0,
    nome: '',
    email: '',
    senha: ''
}
const TipoServico = {
    id: -1,
    nome: '',
    descricao: '',
    Ativo: 1
}

function Chartclass() {
    return {
        Labels: null,
        Values: null,
        Tipo: TipoGrafico.BAR
    }
};
const GridEntidade = () => {
    return {
        titulo: 'Grid',
        visivel: false,
        entidade: ''
    }
}
//ENUMS
const Meses = {
    0: 'Janeiro',
    1: 'Fevereiro',
    2: 'Março',
    3: 'Abril',
    4: 'Maio',
    5: 'Junho',
    6: 'Julho',
    7: 'Agosto',
    8: 'Setembro',
    9: 'Outubro',
    10: 'Novembro',
    11: 'Dezembro'
};
const TipoGrafico = {
    BAR: 'bar',
    LINE: 'line',
    PIE: 'pie'
}
const SESSOESPHP = {

    LOGIN: "LOGIN",
    NOME: "NOME",
    EMAIL: "EMAIL",
    NIVEL_USUARIO: "NIVELUSUARIO",
    FOTO_USUARIO: "FOTOUSUARIO",
    IDUSUARIOCONTEXTO: "IDUSUARIOCONTEXTO",
    IDFUNCIONARIOCONTEXTO: "IDFUNCIONARIOCONTEXTO",
    AVALIACAO_MEDIA: "AVALIACAOMEDIA",
    //FIM USUARIO SECOES
    SERVICOS: "SERVICOS",
    NUMNOTIFICACOES: "NUMNOTIFICACOES",
    NOTIFICACOES: "NOTIFICACOES"

}
const Projeto = () => {
    return {
        id: 0,
        Categoria: null,
        Nome: '',
        Descricao: '',
        imagens: [],
        NivelDoProjeto: null,
        NivelDoProfissional: null,
        Valor: null
    }
}
const NivelFuncionario = {
    0: "$ Iniciante",
    1: "$$ Intermediário",
    2: "$$$ Avançado"
}
const NivelProjeto = {
    0: "Apenas a Ideia",
    1: "Pequeno",
    2: "Médio",
    3: "Grande"
}
const Valores = {
    0: "R$20 - R$100",
    1: "R$100 - R$300",
    2: "R$300 - R$600",
    3: "R$600 - R$1000",
    4: "R$1000 - R$1500",
    5: "R$1500 - R$2000",
    6: "R$2000 - R$5000"

}
const MensagemEntidade = (id_chat_mensagen = -1, id_chat = -1, msg = '', id_usuario_destinatario = -1, id_usuario_remetente = -1, date = GetDataAtual(), time = new Date().toLocaleTimeString(), _visualizado = 0) => {
    return {
        id_chat_mensagen: id_chat_mensagen,
        id_chat: id_chat,
        msg: msg,
        tipo: TipoMensagem.MSG,
        id_usuario_remetente: id_usuario_remetente,
        id_usuario_destinatario: id_usuario_destinatario,
        date: date,
        time: time,
        visualizado: _visualizado
    }
}
const SeparadorMensagemEntidade = (id = -1, msg = 'Hoje', date = new Date().toISOString().split('T')[0]) => {
    return {
        id: id,
        msg: msg,
        tipo: TipoMensagem.Separador,
        date: date
    }
}
const TipoMensagem = {
    MSG: 'msg',
    Separador: 'separador'
}

toastr.options = {
    "closeButton": false,
    "debug": false,
    "newestOnTop": false,
    "progressBar": true,
    "positionClass": "toast-bottom-right",
    "preventDuplicates": true,
    "onclick": null,
    "showDuration": "300",
    "hideDuration": "1000",
    "timeOut": "5000",
    "extendedTimeOut": "1000",
    "showEasing": "swing",
    "hideEasing": "linear",
    "showMethod": "fadeIn",
    "hideMethod": "fadeOut"
}

const ToastType = {
    SUCCESS: 'success',
    ERROR: 'error',
    WARN: 'warn',
    INFO: 'info'
}


//#region Notificacoes
const TipoNotificacao = {
    DEFAULT: 0,
    PROPOSTA: 1,
    CHAT: 2,
    ALERT: 3,
    ERROR: 4,
    SUCCESS: 5
}
const NotificacaoEntidade =
    function (_id = -1, _descricao = "", _titulo = "", _id_projeto = null, _id_chat = null,
        _id_usuario = null, _id_usuario_criacao = null, _date = GetDataAtual(),
        _time = new Date().toLocaleTimeString(), _visto = 0, _tipo = TipoNotificacao.DEFAULT, _automatica = 0) {
        return {
            id: _id,
            descricao: _descricao,
            titulo: _titulo,
            id_projeto: _id_projeto,
            id_chat: _id_chat,
            id_usuario: _id_usuario,
            id_usuario_criacao: _id_usuario_criacao,
            data: _date,
            tipe: _time,
            visto: _visto,
            tipo: _tipo,
            automatica: _automatica
        }
    }

//#endregion
const Situacao = {
    Pendente: 0,
    Andamento: 2,
    Rejeitada: 3,
    Aprovada: 1,
    Concluido: 4

}