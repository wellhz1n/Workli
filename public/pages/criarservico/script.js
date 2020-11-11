$("#Titulo").text("Criar Projeto | Conserta");
window.onload = async() => {
    let p = Porcentagem;
    app.$set(dataVue, 'Porcentagem', Porcentagem());
    app.$set(dataVue, "Projeto", { entidade: 'Projeto', nome: 'Teste', textarea: 'Text Area' });

    function Porcentagem() {
        return {
            id: 'teste',
            entidade: 'Porcentagem',
            porcentagem: 15,
            cor: '#218838',
            cordefundo: '#21883833',
            Legenda: false
        }
    }

}