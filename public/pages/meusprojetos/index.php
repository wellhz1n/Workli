<link rel="stylesheet" href="pages/meusprojetos/style.css">
<div v-if="dataVue.carregando === undefined|| dataVue.carregando">
    <wm-loading />
</div>
<div v-else class="CONTAINER_M " style="height: 140vh;">
    <div class=" containerBox ">
        <!-- <wm-chart /> -->
       <div style="display: flex;align-items: start;">
        <div :style="{'font-size': dataVue.UsuarioContexto.Nome.length > 30 ? '30px' :'40px'}" class="textoHeaderModal">{{dataVue.UsuarioContexto.Nome}}</div>
       </div>

    </div>

    <div class=" feed ">
        <!-- <wm-chart /> -->
    </div>

</div>
<script src="pages/meusprojetos/script.js"></script>