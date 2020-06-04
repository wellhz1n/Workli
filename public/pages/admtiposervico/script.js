var Row = null;
var TpS = {};

$(document).ready(async() => {
    var tabela;
    let acao = null;
    tabela = await Tabela("dtTipoServico", "GetTipoServicoTable", "TipoServicoBO");
    $('th')[2].click();
    CliqueTabela("dtTipoServico", tabela, (saida) => {
        acao = "EDITAR";
        Row = saida;
    }, editar, () => {
        Row = null;
    });


    $('#btnSalvar').on('click', function() {
        salvar()
    });
    $('#btnVisualizar').on('click', () => {
        visualizar();
    });
    $('#btnNovo').on('click', function() {
        acao = "NOVO";

        dataVue.tipoServicoEntidade = tipoServicoEntidade();

        $('#Listagem').prop('hidden', 'hidden');
        $('#btnNovo').prop('hidden', 'hidden');
        $('#btnEditar').prop('hidden', 'hidden');
        $('#btnRemover').attr('hidden', 'hidden');
        $('#btnVisualizar').attr('hidden', 'hidden');
        $('#btnSalvar').removeAttr('hidden');
        $('#btnCancelar').removeAttr('hidden');
        dataVue.gridTipoServico.titulo = "Novo Tipo de Serviço";
        dataVue.gridTipoServico.visivel = true;
    });

    $('#btnCancelar').on('click', function() {
        cancelar();
    });
    $('#btnRemover').on('click', async function() {
        if (Row) {
            await WMExecutaAjax("TipoServicoBO", "RemoverTipoServico", { ID: Row.id }).then(result => {
                if (result != "OK") {
                    toastr.warning("Tipo de Serviço com dependências.", "Falha ao Remover");
                    console.error(`ERRO::${result}`);
                } else {
                    tabela.ajax.reload();
                    toastr.success("Registro Removido", "Tipo de Serviço");
                }
            });
        } else {
            toastr.info('Selecione um Registro para Remover', 'Ops')
        }

    });
    $('#btnEditar').on('click', function() {
        editar();
    });

    function cancelar() {
        $('#Listagem').removeAttr('hidden');
        $('#btnNovo').removeAttr('hidden');
        $('#btnEditar').removeAttr('hidden');
        $('#btnRemover').removeAttr('hidden');
        $('#btnVisualizar').removeAttr('hidden');
        $('#btnSalvar').attr('hidden', 'hidden');
        $('#btnCancelar').attr('hidden', 'hidden');
        acao = null;
        dataVue.gridTipoServico.visivel = false;
    }



    // FUNCOES CRUD
    async function editar() {
        if (Row) {
            await WMExecutaAjax("TipoServicoBO", "GetImagemTipoServico", { ID: Row['id'] }).then(resultado => {
                resultado = resultado == "" ? [] : resultado;
                dataVue.tipoServicoEntidade["imagem"] = resultado;
            });
            dataVue.tipoServicoEntidade["id_tipoServico"] = Row["id"];
            dataVue.tipoServicoEntidade["nome"] = Row["nome"];
            dataVue.tipoServicoEntidade["descricao"] = Row["descricao"];
            dataVue.tipoServicoEntidade["ativo"] = Row["Ativo"] == "0" ? 0 : 1;


            $('#Listagem').attr('hidden', 'hidden');
            $('#btnNovo').attr('hidden', 'hidden');
            $('#btnVisualizar').attr('hidden', 'hidden');
            $('#btnRemover').attr('hidden', 'hidden');
            $('#btnEditar').attr('hidden', 'hidden');
            $('#btnSalvar').removeAttr('hidden');
            $('#btnCancelar').removeAttr('hidden');
            dataVue.gridTipoServico.titulo = "Editar Tipo de Serviço";
            dataVue.gridTipoServico.visivel = true;
        } else {
            toastr.info('Selecione um campo para editar', 'Ops')
        }
    };
    async function visualizar() {
        if (Row) {
            await WMExecutaAjax("TipoServicoBO", "GetImagemTipoServico", { ID: Row['id'] }).then(resultado => {
                resultado = resultado == "" ? [] : resultado;
                dataVue.tipoServicoEntidade["imagem"] = resultado;
            });
            acao = "VIZUALIZAR";
            dataVue.tipoServicoEntidade["id_tipoServico"] = Row["id"];
            dataVue.tipoServicoEntidade["nome"] = Row["nome"];
            dataVue.tipoServicoEntidade["descricao"] = Row["descricao"];
            dataVue.tipoServicoEntidade["ativo"] = Row["Ativo"] == "0" ? 0 : 1;


            $('#Listagem').attr('hidden', 'hidden');
            $('#btnNovo').attr('hidden', 'hidden');
            $('#btnVisualizar').attr('hidden', 'hidden');
            $('#btnRemover').attr('hidden', 'hidden');
            $('#btnEditar').attr('hidden', 'hidden');
            $('#btnCancelar').removeAttr('hidden');
            dataVue.gridTipoServico.titulo = "Visualizar Tipo de Serviço";
            dataVue.gridTipoServico.visivel = true;
        } else {
            toastr.info('Selecione um campo para Visualizar', 'Ops')
        }
    };

    function associaCampos() {
        if (acao == 'EDITAR')
            TpS.id = Row["id"];
        else
            TpS.id = -1;

        TpS.nome = dataVue.tipoServicoEntidade["nome"];
        TpS.descricao = dataVue.tipoServicoEntidade["descricao"];
        TpS.Ativo = dataVue.tipoServicoEntidade["ativo"] ? 1 : 0;
        TpS.imagem = dataVue.tipoServicoEntidade["imagem"].filter(x => !x.deletado).length > 0 ? dataVue.tipoServicoEntidade["imagem"].filter(x => !x.deletado)[0].img : null;
    }

    async function salvar() {
        if (!WMVerificaForm())
            return false;
        BloquearTela();
        associaCampos();
        await $.ajax({
            url: '../app/BO/TipoServicoBO.php',
            data: { metodo: acao == 'NOVO' ? "InsertTipoServico" : "UpdateTipoServico", tipoServico: TpS },
            type: 'post',
        }).then(async(output) => {
            if (output == "OK") {
                cancelar()
                toastr.success('Registro Atualizado', 'Tipo Serviço');
                tabela.ajax.reload();
            } else if (output.split('ERROR|').length == 2) {
                output = JSON.parse(output.split('ERROR|')[1]);
                toastr.warning(output.error, 'Tipo Serviço');
            } else {
                console.error(`ERROR:${output}`);
                toastr.error('Corra para as Colinas! Algo Deu Errado.', 'Tipo Serviço');
            }
        });
        DesbloquearTela();
    }
    // FIM

    let tipoServicoEntidade = () => {
        return {
            id_tipoServico: -1,
            nome: '',
            descricao: '',
            ativo: 1,
            imagem: []
        }
    }

    let GetCamposTipoServico = () => {
        return [{
                tipo: 'input',
                id: 'inputNome',
                titulo: 'Nome',
                tamanho: 'col-4',
                disabled: () => {
                    if (acao == "VIZUALIZAR")
                        return true;
                    return false
                },
                obrigatorio: true,
                entidade: grid.entidade,
                campo: 'nome'
            },
            {
                tipo: 'checkbox',
                id: 'CKAtivo',
                titulo: 'Ativo',
                disabled: () => {
                    if (acao == "VIZUALIZAR")
                        return true;
                    return false
                },
                tamanho: 'col-1',
                entidade: grid.entidade,
                campo: 'ativo'
            },
            {
                tipo: 'textarea',
                id: 'inputDescricao',
                titulo: 'Descriçao',
                maxlength: 500,
                estilo: { height: '34vh' },
                obrigatorio: true,
                tamanho: 'col-8',
                disabled: () => {
                    if (acao == "VIZUALIZAR")
                        return true;
                    return false
                },
                entidade: grid.entidade,
                campo: 'descricao'
            },
            {
                tipo: 'wm-imagem',
                id: 'tpdImagem',
                titulo: 'Imagem',
                disabled: () => {
                    if (acao == "VIZUALIZAR")
                        return true;
                    return false
                },
                obrigatorio: true,
                tamanho: 'col-sm-4 col-4',
                limite: 1,
                entidade: grid.entidade,
                campo: 'imagem'
            }




        ]
    };
    let GridInicializer = () => {

        var grid = GridEntidade();
        grid.entidade = "tipoServicoEntidade";
        grid.titulo = "Tipo Servico";
        grid.visivel = false;
        return grid;
    };

    // VARIAVEIS

    let grid = GridInicializer();


    app.$set(dataVue, 'tipoServicoEntidade', tipoServicoEntidade());
    app.$set(dataVue, 'gridCamposTipoServico', GetCamposTipoServico());
    app.$set(dataVue, 'gridTipoServico', grid);

    // FIM









});