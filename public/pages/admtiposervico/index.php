<div class="col p-0 m-0">
<div class="header-table">
        <h5 class="p-2 mx-2" id="title">Tipo de Serviço</h5>
    </div>
    <div class=" float-right mt-1 ml-3 p-1 pr-4 ">
    <button class="btn btn-green mb-1" id="btnNovo"><i class="fas fa-plus mr-1"></i>Novo</button>
        <button class="btn btn-green mb-1" id="btnEditar"><i class="fas fa-edit mr-1"></i>Editar</button>
        <button class="btn btn-green mb-1" id="btnVisualizar" ><i class="fas fa-eye mr-1"></i>Visualizar</button>   
        <button class="btn btn-danger mb-1" id="btnRemover" ><i class="fas fa-trash mr-1"></i>Remover</button>
        <button class="btn btn-green mb-1" id="btnSalvar" hidden><i class="fas fa-save mr-1"></i>Salvar</button>
        <button class="btn btn-danger mb-1" id="btnCancelar" hidden><i class="fas fa-times mr-1"></i>Cancelar</button>
    </div>
    <!-- LISTA -->


    <div id="Listagem" class=" col-12 my-5 py-3 pr-4">
        <table id="dtTipoServico" class="table table-striped  table-bordered table-sm" cellspacing="0" width="100%">
            <thead>
                <tr>
                <th class="th-sm" style="width: 15%;" name="imagem" width="20%">
                        Imagem
                    </th>
                    <th class="th-sm " style="width: 75%" name="nomeFormated" width="20%">
                        Nome
                    </th>
                    <!-- <th class="th-sm" name="descricao" width="70%">
                        Descrição
                    </th> -->
                    <th class="th-sm text-center" style="width: 15%" name="AtivoIcone" width="10%">
                        Ativo
                    </th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>

    </div>
    <wm-container 
                  :campos="dataVue.gridCamposTipoServico" 
                  id="gridTipoServico" 
                  :opcoes="dataVue.gridTipoServico">
    </wm-container>
    <script type="text/javascript" src="pages/admtiposervico/script.js"></script>