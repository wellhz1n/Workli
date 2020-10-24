$(document).ready(async () => {
    let Row = null;
    let tabela;
    let acao = null;
    let TpS = TipoServico;
// INICIALIZACAO DA TABELA
    tabela = await Tabela("dtUsuario", "GetUsuarioAdmTable", "UsuarioBO");
    CliqueTabela("dtUsuario", tabela, (saida) => {
        acao = "EDITAR";
        Row = saida;
    }, editar, () => {
        Row = null;
    });
// FIM
// BTNS
$('#btnSalvar').on('click',async function(){
    if(!WMVerificaForm())
        return false;
    BloquearTela();
   let result = await WMExecutaAjax("UsuarioBO","RegistraUsuarioAdm",{Usuario:dataVue.UsuarioEntidade});
   if(result != true){
    DesbloquearTela();
    
        toastr.info(result,'Ops');
        return false
    }
    else{
        await tabela.ajax.reload();
        $('#Listagem').removeAttr('hidden');
        $('#btnNovo').removeAttr('hidden');
        $('#btnEditar').removeAttr('hidden');
        $('#btnRemover').removeAttr('hidden');
        $('#btnVisualizar').removeAttr('hidden');
        $('#btnSalvar').attr('hidden','hidden');
        $('#btnCancelar').attr('hidden','hidden');
        dataVue.gridUsuario.visivel = false;
        dataVue.UsuarioEntidade = usuarioEntidade();
        DesbloquearTela(); 
        toastr.success("Usuario Salvo com Sucesso.",'Sucesso');
        return true;
    }
});
$('#btnNovo').on('click',function(){
    $('#Listagem').prop('hidden','hidden');
    $('#btnNovo').prop('hidden','hidden');
    $('#btnEditar').prop('hidden','hidden');
    $('#btnVisualizar').prop('hidden','hidden');
    $('#btnRemover').prop('hidden','hidden');
    $('#btnSalvar').removeAttr('hidden');
    $('#btnCancelar').removeAttr('hidden');
    dataVue.gridUsuario.titulo = "Novo Usuario";
    dataVue.gridUsuario.visivel = true;
});
$('#btnCancelar').on('click',function(){
    $('#Listagem').removeAttr('hidden');
    $('#btnNovo').removeAttr('hidden');
    $('#btnEditar').removeAttr('hidden');
    $('#btnVisualizar').removeAttr('hidden');
    $('#btnRemover').removeAttr('hidden');
    $('#btnSalvar').attr('hidden','hidden');
    $('#btnCancelar').attr('hidden','hidden');
    dataVue.gridUsuario.visivel = false;
    acao = null;
    dataVue.UsuarioEntidade = usuarioEntidade();
});
$('#btnEditar').on('click',()=>{
    editar();
});
$('#btnVisualizar').on('click',()=>{
    visualizar();
});
$('#btnRemover').on('click',async ()=>{
    if(Row != null){
        let resultado = await WMExecutaAjax("UsuarioBO","DeleteUsuario",{ID:Row.id});
        if(resultado != true){
                console.warn(`ERROR---:${resultado}`);
                toastr.info("Impossivel Remover Usuario",'Ops');
                return false
            }
            else{
                tabela.ajax.reload();
                toastr.success("Usuario removido com sucesso.",'Sucesso');
                return true;
            }
    }
    else
    toastr.info('Selecione Um Registro Para Remover','Ops');

});
// FIM

// FUNCOES CRUD
    async function editar(){
        if(Row != null){
            dataVue.UsuarioEntidade.id = Row.id;
            dataVue.UsuarioEntidade.nome = Row.nome;
            dataVue.UsuarioEntidade.email = Row.email;
            dataVue.UsuarioEntidade.cpf = Row.cpf;
            dataVue.UsuarioEntidade.nivel = Row.nivel_usuario;
            dataVue.UsuarioEntidade.Selectnivel = await WMExecutaAjax("UsuarioBO","GetUsuarioNivelSelect")
                                                .then(result =>{
                                                        result = result.filter(x=> x.id == Row.nivel_usuario)[0];
                                                        return result;
                                                });
        $('#Listagem').prop('hidden','hidden');
        $('#btnNovo').prop('hidden','hidden');
        $('#btnEditar').prop('hidden','hidden');
        $('#btnVisualizar').prop('hidden','hidden');
        $('#btnRemover').prop('hidden','hidden');
        $('#btnSalvar').removeAttr('hidden');
        $('#btnCancelar').removeAttr('hidden');
        dataVue.gridUsuario.titulo = "Editar Usuario";
        dataVue.gridUsuario.visivel = true;
        }  
        else
            toastr.info('Selecione Um Registro Para Editar','Ops');
    };
    async function visualizar(){
        if(Row != null){
            acao = "VIZUALIZAR"
            dataVue.UsuarioEntidade.id = Row.id;
            dataVue.UsuarioEntidade.nome = Row.nome;
            dataVue.UsuarioEntidade.email = Row.email;
            dataVue.UsuarioEntidade.cpf = Row.cpf;
            dataVue.UsuarioEntidade.nivel = Row.nivel_usuario;
            dataVue.UsuarioEntidade.Selectnivel = await WMExecutaAjax("UsuarioBO","GetUsuarioNivelSelect")
                                                .then(result =>{
                                                        result = result.filter(x=> x.id == Row.nivel_usuario)[0];
                                                        return result;
                                                });
        $('#Listagem').prop('hidden','hidden');
        $('#btnNovo').prop('hidden','hidden');
        $('#btnEditar').prop('hidden','hidden');
        $('#btnVisualizar').prop('hidden','hidden');
        $('#btnRemover').prop('hidden','hidden');
        $('#btnCancelar').removeAttr('hidden');
        dataVue.gridUsuario.titulo = "Visualizar Usuario";
        dataVue.gridUsuario.visivel = true;
        }  
        else
            toastr.info('Selecione Um Registro Para Visualizar','Ops');
    };
// FIM
let usuarioEntidade = () =>{
    return{
        id:-1,
        nome:'',
        email:'',
        cpf:'',
        Selectnivel:{id:0,nome:'Cliente',icone:'fa-user'},
        nivel:0
    }
}

let GetCamposUsuario = ()=>{
    return[
        {
            tipo:'input',
            id:'inputNome',
            titulo:'Nome',
            tamanho:'col-sm-12 col-md-4',
            entidade:grid.entidade,
            visivel:()=>{return true},
            disabled:()=> {
                if(acao == "VIZUALIZAR")
                    return true;
                return false},
            campo:'nome'
        },
        {
            tipo:'input',
            id:'inputEmail',
            titulo:'E-mail',
            disabled:()=> {
                if(acao == "VIZUALIZAR")
                    return true;
                return false},
            tamanho:'col-sm-12 col-md-4',
            entidade:grid.entidade,
            obrigatorio:true,
            campo:'email'
        },
        {
            tipo:'cpf',
            id:'inputCPF',
            titulo:'CPF',
            tamanho:'col-sm-12 col-md-4',
            disabled:()=>{
                if(acao == "VIZUALIZAR")
                    return true;
                if(dataVue.UsuarioEntidade.id != -1)
                    return true;
                return false;
            },
            entidade:grid.entidade,
            obrigatorio:true,
            campo:'cpf'
        },
        {
            tipo:'wm-seletor',
            id:'Cachumba',
            visivel:()=>{return true},
            titulo:'Nivel De Usuário',
            disabled:()=>{
                if(acao == "VIZUALIZAR")
                    return true;
                return false},
            tamanho:'col-sm-12 col-md-3',
            entidade:grid.entidade,
            campo:"nivel",
            limpavel:false,
            icone:true,
            obrigatorio:true,
            ajax:async(q,selecionado)=>{
                let resultado = await WMExecutaAjax("UsuarioBO","GetUsuarioNivelSelect");
                resultado = resultado.filter(x=> x.id != selecionado.id);
                return resultado;
            }
        }
    ]
};
let GridInicializer = () =>{
    
  var grid =  GridEntidade();
    grid.entidade = "UsuarioEntidade";
    grid.titulo = "Usuario";
    grid.visivel = false;
    return grid;
};

// VARIAVEIS
    
let grid = GridInicializer();


app.$set(dataVue,'UsuarioEntidade',usuarioEntidade());
app.$set(dataVue,'gridCamposUsuario',GetCamposUsuario());
app.$set(dataVue,'gridUsuario',grid);


// FIM

});
