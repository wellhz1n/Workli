<div class="col-12 p-0 m-0">
<div class="header-table">
        <h5 class="p-2" id="title">Usuario</h5>
    </div>
    <div class=" float-right mt-1 ml-3 p-1 " style="z-index:200">
        <button class="btn btn-primary" id="btnNovo"><i class="fas fa-plus mr-1"></i>Novo</button>
        <button class="btn btn-primary" id="btnEditar"><i class="fas fa-edit mr-1"></i>Editar</button>
        <button class="btn btn-primary" id="btnVisualizar" ><i class="fas fa-eye mr-1"></i>Visualizar</button>
        <button class="btn btn-danger" id="btnRemover" ><i class="fas fa-trash mr-1"></i>Remover</button>
        <button class="btn btn-primary" id="btnSalvar" hidden><i class="fas fa-save mr-1"></i>Salvar</button>
        <button class="btn btn-danger" id="btnCancelar" hidden><i class="fas fa-times mr-1"></i>Cancelar</button>
        
    </div>
    <br>
    <!-- LISTA -->
   

    <div id="Listagem" class=" col-12 my-5 py-3">
        <table id="dtUsuario" class="table table-striped  table-bordered table-sm" cellspacing="0" width="100%">
            <thead>
                <tr>

                <th class="th-sm  " scope="col" name="nome">
                        Nome
                    </th>
                    <th class="th-sm " scope="col" name="email">
                        Email
                    </th>
                    <th class="th-sm " style="width: 16%" scope="col" name="NivelIcone">
                        Nivel de Usuario
                    </th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>

    </div>
    <wm-container 
                  :campos="dataVue.gridCamposUsuario" 
                  id="gridUsuario" 
                  :opcoes="dataVue.gridUsuario">
    </wm-container>
    <script type="text/javascript" src="pages/admusuario/script.js"></script>
