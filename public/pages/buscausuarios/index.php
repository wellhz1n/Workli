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
            <div class="cardQuadrado cardUsuario row">
                <div class="parteDadosUsuario">
                    <div class="parteSuperiorCU">
                        <div class="parteDadosPrincipais">
                            <wm-user-img :img="dataVue.imagemUsuario" class="iconeCardUsuario" ></wm-user-img>
                            <div class="informacoesPrincipais">
                                <div class="nomeUWrapper">
                                    <div class="nomeUsuario">MATEUS ANDRE NUNES ARRUDA</div>
                                    <img class="iconeUsuario" src="src/img/icons/perfil/planoMaster.svg"></img>
                                </div>
                                <div class="profAvaliacaoWrapper">
                                    <div class="profissaoTexto">PEDREIRO</div>
                                    <span class="profBolinha">•</span>
                                    <div class="avaliacaoUsuario">
                                        <star-rating 
                                            v-model='dataVue.usuarioFiltro.avaliacao'
                                            :increment='0.5'
                                            :star-size='18'
                                            :fixed-points='1'
                                            text-class='textoEstrelasUsuario'
                                            :round-start-rating='true'
                                            :padding='5'
                                            :read-only='true'
                                        ></star-rating>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="botaoCUWrapper">
                            <button class="btn btn-success">Contratar funcionário</button>
                        </div>
                    </div>
                    <div class="parteInferiorCU">
                        <div class="descricaoUsuario">
                            Mussum Ipsum, cacilds vidis litro abertis. Interessantiss quisso pudia ce receita de bolis, mais bolis eu num gostis. Quem num gosta di mim que vai caçá sua turmis! Cevadis im ampola pa arma uma pindureta. Em pé sem cair, deitado sem dormir, sentado sem cochilar e fazendo pose.
                        </div>
                        <div class="tagsCUWrapper">
                            <div class='tagCU'>Marcenaria</div>
                            <div class='tagCU'>Cimento</div>
                            <div class='tagCU'>Pintura</div>
                            <div class='tagCU'>Tijolo</div>
                            <div class='tagCU'>Comer batata</div>
                            <div class='tagCU'>a a a a a a a a a a a teste espaco</div>
                            <div class='tagCU'>CSS nas horas vagas</div>
                            <div class='tagCU'>${tag}</div>
                            <div class='tagCU'>${tag}</div><div class='tagCU'>${tag}</div><div class='tagCU'>${tag}</div><div class='tagCU'>${tag}</div><div class='tagCU'>${tag}</div>

                        </div>
                    </div>
                </div>
            </div>
            <div class="cardQuadrado cardUsuario row">
                <div class="parteDadosUsuario">
                    <div class="parteSuperiorCU">
                        <div class="parteDadosPrincipais">
                            <wm-user-img :img="dataVue.imagemUsuario" class="iconeCardUsuario" ></wm-user-img>
                            <div class="informacoesPrincipais">
                                <div class="nomeUWrapper">
                                    <div class="nomeUsuario">MATEUS ANDRE NUNES ARRUDA</div>
                                    <img class="iconeUsuario" src="src/img/icons/perfil/planoMaster.svg"></img>
                                </div>
                                <div class="profAvaliacaoWrapper">
                                    <div class="profissaoTexto">PEDREIRO</div>
                                    <span class="profBolinha">•</span>
                                    <div class="avaliacaoUsuario">
                                        <star-rating 
                                            v-model='dataVue.usuarioFiltro.avaliacao'
                                            :increment='0.5'
                                            :star-size='18'
                                            :fixed-points='1'
                                            text-class='textoEstrelasUsuario'
                                            :round-start-rating='true'
                                            :padding='5'
                                            :read-only='true'
                                        ></star-rating>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="botaoCUWrapper">
                            <button class="btn btn-success">Contratar funcionário</button>
                        </div>
                    </div>
                    <div class="parteInferiorCU">
                        <div class="descricaoUsuario">
                            Mussum Ipsum, cacilds vidis litro abertis. Interessantiss quisso pudia ce receita de bolis, mais bolis eu num gostis. Quem num gosta di mim que vai caçá sua turmis! Cevadis im ampola pa arma uma pindureta. Em pé sem cair, deitado sem dormir, sentado sem cochilar e fazendo pose.
                        </div>
                        <div class="tagsCUWrapper">
                            <div class='tagCU'>Marcenaria</div>
                            <div class='tagCU'>Cimento</div>
                            <div class='tagCU'>Pintura</div>
                            <div class='tagCU'>Tijolo</div>
                            <div class='tagCU'>Comer batata</div>
                            <div class='tagCU'>a a a a a a a a a a a teste espaco</div>
                            <div class='tagCU'>CSS nas horas vagas</div>
                            <div class='tagCU'>${tag}</div>
                            <div class='tagCU'>${tag}</div><div class='tagCU'>${tag}</div><div class='tagCU'>${tag}</div><div class='tagCU'>${tag}</div><div class='tagCU'>${tag}</div>

                        </div>
                    </div>
                </div>
            </div>
            <div class="cardQuadrado cardUsuario row">
                <div class="parteDadosUsuario">
                    <div class="parteSuperiorCU">
                        <div class="parteDadosPrincipais">
                            <wm-user-img :img="dataVue.imagemUsuario" class="iconeCardUsuario" ></wm-user-img>
                            <div class="informacoesPrincipais">
                                <div class="nomeUWrapper">
                                    <div class="nomeUsuario">MATEUS ANDRE NUNES ARRUDA</div>
                                    <img class="iconeUsuario" src="src/img/icons/perfil/planoMaster.svg"></img>
                                </div>
                                <div class="profAvaliacaoWrapper">
                                    <div class="profissaoTexto">PEDREIRO</div>
                                    <span class="profBolinha">•</span>
                                    <div class="avaliacaoUsuario">
                                        <star-rating 
                                            v-model='dataVue.usuarioFiltro.avaliacao'
                                            :increment='0.5'
                                            :star-size='18'
                                            :fixed-points='1'
                                            text-class='textoEstrelasUsuario'
                                            :round-start-rating='true'
                                            :padding='5'
                                            :read-only='true'
                                        ></star-rating>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="botaoCUWrapper">
                            <button class="btn btn-success">Contratar funcionário</button>
                        </div>
                    </div>
                    <div class="parteInferiorCU">
                        <div class="descricaoUsuario">
                            Mussum Ipsum, cacilds vidis litro abertis. Interessantiss quisso pudia ce receita de bolis, mais bolis eu num gostis. Quem num gosta di mim que vai caçá sua turmis! Cevadis im ampola pa arma uma pindureta. Em pé sem cair, deitado sem dormir, sentado sem cochilar e fazendo pose.
                        </div>
                        <div class="tagsCUWrapper">
                            <div class='tagCU'>Marcenaria</div>
                            <div class='tagCU'>Cimento</div>
                            <div class='tagCU'>Pintura</div>
                            <div class='tagCU'>Tijolo</div>
                            <div class='tagCU'>Comer batata</div>
                            <div class='tagCU'>a a a a a a a a a a a teste espaco</div>
                            <div class='tagCU'>CSS nas horas vagas</div>
                            <div class='tagCU'>${tag}</div>
                            <div class='tagCU'>${tag}</div><div class='tagCU'>${tag}</div><div class='tagCU'>${tag}</div><div class='tagCU'>${tag}</div><div class='tagCU'>${tag}</div>

                        </div>
                    </div>
                </div>
            </div>

            
            

        </div>
    </div>
</div>

<link rel="stylesheet" type="text/css" href="pages/buscausuarios/estilo.css" />
<script src="pages/buscausuarios/script.js"></script>