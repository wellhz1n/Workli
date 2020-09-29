<link rel="stylesheet" type="text/css" href="pages/buscausuarios/estilo.css" />
<script src="pages/buscausuarios/script.js"></script>

<div id="bodyBuscaUsuarios" class=>
    <div class="cardQuadrado col-3" id="filtroWrapper">
        <span id="tituloFiltro">Filtragem</span>
        <div class="separadorFiltro"></div>

        <div class="wrapperFiltro">
            <div class="tituloInputFiltro">Filtro de Usuários</div>
            
            <div class="d-contents" v-if="dataVue.usuarioFiltro != undefined">
                <vue-slider
                    class="sliderFiltroUsuarios"
                    ref="slider"
                    v-model="dataVue.usuarioFiltro.tipo_usuario"
                    v-bind="dataVue.opcoesSlider"
                ></vue-slider>
            </div>
        </div>
       
        <div class="wrapperFiltro">
            <div class="tituloInputFiltro">Profissão</div>
            
            <autocomplete 
                id="inputProfissao" 
                :search.lazy="(v) => {return dataVue.buscaProfissoes ? dataVue.buscaProfissoes(v) : []}"
                placeholder="Procurar por profissão"
                aria-label="Procurar por profissão"
                auto-select
                @submit="(v) => {dataVue.executaFunc(v)}"
            ></autocomplete>
        </div>
        <div class="wrapperFiltro">            
            <div class="inputPerfil" id="tagsInput">
                <label>Competências</label>
                <div class="d-contents" v-if="dataVue.usuarioFiltro != undefined">
                    <tags-input v-model="dataVue.usuarioFiltro.tags" label-style="success" delete-key="['46', '8']" placeholder="Inserir Tags">
                        <div class="tags-input"
                            slot-scope="{tag, removeTag, inputEventHandlers, inputBindings }"
                        >
                            <span 
                                v-for="tag in dataVue.tags"
                                class="tags-input-tag"
                            >
                                <span>{{ tag }}</span>
                                <button type="button" class="tags-input-remove"
                                    v-on:click="removeTag(tag)"
                                >&times;
                                </button>
                            </span>
                            <input
                                class="tags-input-text"  
                                placeholder="Adicionar Tag..."
                                v-on="inputEventHandlers"
                                v-bind="inputBindings"
                            >
                        </div>
                    </tags-input>
                </div>
            </div>
        </div>

        <div class="wrapperFiltro avaliacaoFiltro">
            <span class="tituloInputFiltro avaliacaoTexto">Avaliação mínima</span>
            
            <div class="d-contents" v-if="dataVue.usuarioFiltro != undefined">
                <star-rating 
                        v-model='dataVue.usuarioFiltro.avaliacao'
                        :glow="4"
                        glow-color="#ff000000aa"
                        :clearable="true"
                        :increment='0.5'
                        :star-size='25'
                        :fixed-points='1'
                        text-class='textoEstrelas'
                        :round-start-rating='true'
                        :padding='5'
                ></star-rating>
            </div>
        </div>

    </div>


    <div class="col-9" id="usuariosWrapper">
    
        <div id="buscaUsuariosWrapper">
            <div class="buscaUsuarios">
                <div class="iconSearch">
                    <i class="fa fa-search" aria-hidden="true"></i>
                </div>
                <input 
                    id="inputBuscaUsuario" 
                    type="text" 
                    placeholder="Procurar por usuários" 
                    @input="dataVue.usuarioFiltro.queryBusca = $event.target.value" 
                />
            </div>
            <div class="paginacaoUsuarios">
                <wm-paginacao :totaldepaginas="dataVue.usuarios != undefined? parseInt(dataVue.usuarios.pagina) : 1" :paginaatual="parseInt(dataVue.paginaAtual)? parseInt(dataVue.paginaAtual) : 1" @changepagina="(e) => {dataVue.trocarPagina(e);}" />
            </div>
        </div>
        <div id="usuariosListaWrapper">
            <wm-loading v-if="dataVue.Carregando" style="margin-top: 15%;"></wm-loading>
            <div v-else class="d-contents"> 
                <div v-if="dataVue.usuarios != undefined && dataVue.usuarios.lista.length < 1">
                    <wm-error mensagem="Nenhum usuário encontrado." /> 
                </div> 
                <div v-else-if="dataVue.usuarios != undefined"> 
                    <wm-card-usuario :dados_usuario="item" v-for="item in dataVue.usuarios.lista"> </wm-card-usuario>
                </div>
                
            </div>

            
            

        </div>
    </div>
</div>

