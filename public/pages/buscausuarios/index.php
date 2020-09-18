<div id="bodyBuscaUsuarios" class=>
    <div class="cardQuadrado col-3" id="filtroWrapper">
        <span id="tituloFiltro">Filtragem</span>
        <div class="separadorFiltro"></div>
        <div class="wrapperFiltro">
            <div class="tituloInputFiltro">Profissão</div>
            
            <autocomplete class="inputProfissao"></autocomplete>
        </div>
        <div class="wrapperFiltro">            
            <div class="inputPerfil" id="tagsInput">
                <label>Competências</label>
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

        <div class="wrapperFiltro avaliacaoFiltro">
            <span class="tituloInputFiltro avaliacaoTexto">Avaliação mínima</span>
            
            <star-rating 
                    v-model='dataVue.usuarioFiltro.avaliacao'
                    :increment='0.5'
                    :star-size='25'
                    :fixed-points='1'
                    text-class='textoEstrelas'
                    :round-start-rating='true'
                    :padding='5'
            ></star-rating>
        </div>

    </div>


    <div class="col-9" id="usuariosWrapper">
    
        <div id="buscaUsuariosWrapper">
            <div class="buscaUsuarios">
                <div class="iconSearch">
                    <i class="fa fa-search" aria-hidden="true"></i>
                </div>
                <input id="inputBuscaUsuario" type="text" placeholder="Procurar por usuários"/>
            </div>
            <div class="paginacaoUsuarios">
                <wm-paginacao :totaldepaginas="3" :paginaatual="1" />
            </div>
        </div>
        <div id="usuariosListaWrapper">
            <wm-loading v-if="dataVue.Carregando" style="margin-top: 15%;"></wm-loading>
            <div v-else class="d-contents"> 
                <div v-if="dataVue.usuarios.lista.length < 1">
                    <wm-error mensagem="Nenhum usuário encontrado." /> 
                </div> 
                <div v-else> 
                    <wm-card-usuario :dados_usuario="item" v-for="item in dataVue.usuarios.lista"> </wm-card-usuario>
                </div>
                
            </div>

            
            

        </div>
    </div>
</div>

<link rel="stylesheet" type="text/css" href="pages/buscausuarios/estilo.css" />
<script src="pages/buscausuarios/script.js"></script>