$(document).ready(async() => {
    var usrContexto = await GetSessaoPHP(SESSOESPHP.IDUSUARIOCONTEXTO);
    app.$set(dataVue, "msg", GetMensagens());

    function GetMensagens() {

        let msg = [MensagemEntidade(1, 'oi', 1, 2, '2020-06-10', '12:00:04'),
            MensagemEntidade(2, 'oi tudo bem?', 2, usrContexto, '2020-06-10', '12:05:09'),
            MensagemEntidade(3, 'Vou bem e você?', 1, 2, '2020-06-10', '12:15:04'),
            MensagemEntidade(4, 'Desculpe a demora para responder, estou bem também', 2, usrContexto, '2020-06-11', '07:00:04'),
            MensagemEntidade(5, 'Mas sobre o Projeto?', 2, usrContexto, '2020-06-11', '07:01:00'),
            MensagemEntidade(6, 'Não existe mais a nescessidade deste projeto', 1, 2, '2020-06-12', '12:00:04')
        ];
        return msg;
    }

});