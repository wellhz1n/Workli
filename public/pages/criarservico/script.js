window.onload = async() => {
    let p = Porcentagem;
    $("#Titulo").text("Novo Serviço");
    app.$set(dataVue, 'Porcentagem', Porcentagem());
    app.$set(dataVue, "Projeto", { entidade: 'Projeto', nome: 'Teste', textarea: 'Text Area' });
    // let cores;
    // await fetch("http://www.colr.org/json/colors/random/14", { method: 'GET', headers: new Headers(), mode: 'cors' }).then(async result => {
    //     let a = await result.json();
    //     cores = a.colors.map(x => { return '#' + x.hex });
    // })
    // let time = 500;
    // setInterval(() => {
    //     if (dataVue.Porcentagem.porcentagem < 100) {
    //         dataVue.Porcentagem.cor = cores[Math.floor(Math.random() * cores.length - 1)]
    //         dataVue.Porcentagem.porcentagem += 1
    //         time -= 10
    //     }
    //     dataVue.Porcentagem.cor = cores[Math.floor(Math.random() * cores.length - 1)]
    //     return false;
    // }, time);

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