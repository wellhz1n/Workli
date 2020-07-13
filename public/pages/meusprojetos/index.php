<link rel="stylesheet" href="pages/meusprojetos/style.css">
<div v-if="dataVue.carregando === undefined|| dataVue.carregando">
    <wm-loading />
</div>
<div v-else class="CONTAINER_M " style="height: 140vh;">
    <div class=" containerBox ">
        <!-- <wm-chart /> -->
   
        <div style="display: flex;align-items: start;    z-index: 1;">
            <div :style="{'font-size': dataVue.UsuarioContexto.Nome.length > 30 ? '30px' :'40px'}" class="textoHeaderModal">{{dataVue.UsuarioContexto.Nome}}</div>
        </div>
        <div class="row" style="justify-self: flex-end;">
            <div class="col-md-6">
                <label style="font-size: 20px; color:white" for="#pesq">Procurar</label>
                <input style="height: 33px;margin-top: 2px;" placeholder="Pequise um Projeto" id="pesq" type="text" class="form-control" @input="" />
            </div>
            <wm-select class="col-md-3" v-bind="dataVue.seletorcategoria"></wm-select>
            <wm-select class="col-md-3" v-bind="dataVue.seletorsituacao"></wm-select>

        </div>
    </div>

    <div class=" feed ">
        <!-- <wm-chart /> -->
        <wm-projeto-item :mostra_botao="false" titulo="Teste" publicado="3" :propostas="0" categoria="Teste" identidade="0" id="Teste" :tamanhodoprojeto="0" :nivelprofissional="2" descricao="Mussum sdad sadsada ds addsa sadsadsad sadd asdadsa sadasd sadsadsa sdadasd dad sdadad sdadas adsda dsadad sdadad sdada sdadasdas Ipsum, cacilds vidis litro abertis. Praesent vel viverra nisi. Mauris aliquet nunc non turpis scelerisque, eget. Não sou faixa preta cumpadi, sou preto inteiris, inteiris. Quem num gosta di mim que vai caçá sua turmis! Mauris nec dolor in eros commodo tempor. Aenean aliquam molestie leo, vitae iaculis nisl." :nome="dataVue.UsuarioContexto.Nome" :img="dataVue.UsuarioContexto.Foto" :valor="2" :id_usuario="dataVue.UsuarioContexto.id"></wm-projeto-item>

    </div>

</div>
<script src="pages/meusprojetos/script.js"></script>