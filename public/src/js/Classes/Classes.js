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
    AVALIACAO_MEDIA: "AVALIACAOMEDIA",
    //FIM USUARIO SECOES
    SERVICOS: "SERVICOS",

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