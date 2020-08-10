var dataVue = {
    UsuarioContexto: {
        Email: null,
        Foto: null,
        NIVEL_USUARIO: 0,
        Nome: null,
        id: null,
        id_funcionario: null
    }
};
var computedVue = {};

var testeComp = Vue.component('teste-vue-a', {
    props: {
        text: String
    },
    data: function () {
        return {
            texto: this.text
        }
    },
    template: `
    <div>
        <p>{{text}}</p>
        <input v-model="texto"/>
        <p>{{texto}}</p>
    </div>
    `,
    mounted: function () {
        console.log(this.$refs.testevue);
    }
});

var ItemServico = Vue.component('item-servico', {
    props: {
        Servico: Object
    },
    data: function () {
        return {}
    },
    template: `
    <article class="card card-product-list">
    <div class="row no-gutters">
      <aside class="col-md-3">
        <a  class="img-wrap">
          <span v-show="Servico.novo" class="badge badge-danger"> NOVO </span>
          <img style="heigth:250px;width:250px;border-radius:100%;" src="src/img/login/prestadores_de_servico.jpg">
        </a>
      </aside>
      <div class="col-md-6">
        <div class="info-main">
          <a href="#" class="h5 title"> {{Servico.nome}}</a>
          <div class="rating-wrap mb-3">
            <ul class="rating-stars">
              <li v-bind:style="{ width:Servico.rating+'%' }" class="stars-active">
                <i class="fa fa-star"></i> <i class="fa fa-star"></i>
                <i class="fa fa-star"></i> <i class="fa fa-star"></i>
                <i class="fa fa-star"></i>
              </li>
              <li>
                <i class="fa fa-star"></i> <i class="fa fa-star"></i>
                <i class="fa fa-star"></i> <i class="fa fa-star"></i>
                <i class="fa fa-star"></i>
              </li>
            </ul>
            <div class="label-rating">{{Math.floor((Servico.rating/100)*10)}}/10</div>
          </div>

          <p>{{Servico.descricao}}</p>
        </div>
      </div>
      <aside class="col-sm-3">
        <div class="info-aside">
          <div class="price-wrap">
            <span class="price h5">$140</span>
          </div>
          <br>
          <p>
            <a href="#" class="btn btn-primary btn-block"> Contratar </a>
            <a href="#" class="btn btn-light btn-block"><i class="fa fa-heart"></i>
              <span class="text">Detalhes</span>
            </a>
          </p>
        </div>
      </aside>
    </div>
  </article>
    `,
    mounted: function () {
        // console.log(this.$refs.testevue);
    }
});

var Select2 = Vue.component('select2', {
    props: ['options', 'value', 'tamanho'],
    template: `
  <select class="col-6" v-on:input="inputChange">
    <slot></slot>
  </select>
  `,
    mounted: function () {
        var vm = this
        $(this.$el)
            // init select2
            .select2({ data: this.options })
            .val(this.value)
            .trigger('change')
            // emit event on change.
            .on('change', function () {
                // $emit('input', this.value);

            })
    },
    watch: {
        value: function (value) {
            // update value
            $(this.$el)
                .val(value)
                .trigger('change')
        },
        options: function (options) {
            // update options
            $(this.$el).select2('destroy')
            $(this.$el).select2({ data: options })
        }
    },
    destroyed: function () {
        $(this.$el).off().select2('destroy')
    },
    methods: {
        inputChange: function (valor) { }
    }
});
var WMContainer = Vue.component('wm-container', {
    props: {
        campos: {
            type: Array,
            required: true,
            default: function () { return []; }
        },
        id: {
            type: String,
            required: true
        },
        opcoes: {
            type: Object,
            required: true,
            default: function () { return { titulo: 'Grid', visivel: false } }
        }
    },
    computed: {


    },
    watch: {
        campos() {
            let camp = this.campos;
            camp.map(x => {
                x.stringEntidade = `dataVue.${x.entidade}.${x.campo}`;
            });
            this.dataCampos = camp;
        }
    },
    data: function () {
        return {
            dataCampos: this.campos,
            dataOpcoes: this.opcoes
        }
    },
    template: `
    <div class="col-sm-12 col-md-12 container-box my-5 py-3"  v-if="opcoes.visivel" :id="id">
    <div style="margin-bottom:-20px" class="row">
      <div class="col-10">    
        <h5 class="p-1" id="title">{{opcoes.titulo}}</h5>
      </div>
      <div style="position:relative;top:3vh" class="col-2">    
        <p style="font-size:10px ;color:red;" class="" id="title">Os Campos com * são obrigatórios</p>
      </div>    
    </div>
    <hr style="border: 2px solid #343A40;border-radius: 5px;">
    <div class="row px-4 py-2">
    <div :class='campo.tamanho + " py-2"' v-for="campo in this.dataCampos">
   
          <div v-if="campo.tipo == 'wm-seletor' ">
            <wm-select
             v-bind="campo"
              />
          </div>
          <div v-else-if="campo.tipo == 'input' ">
          <wm-input v-bind="campo" />
          </div>
          <div v-else-if="campo.tipo == 'cpf' ">
          <wm-input-cpf v-bind="campo" />
          </div>
          <div v-else-if="campo.tipo == 'textarea' ">
          <wm-textarea  v-bind="campo" />
          </div>
          <div v-else-if="campo.tipo == 'checkbox' ">
          <wm-checkbox  
          v-bind="campo"
          />
          </div>
          <div v-else-if="campo.tipo == 'wm-imagem' ">
          <wm-image-upload v-bind="campo"/>
          </div>
          <div v-else>
          <p>Componente {{campo.tipo}} não Encontrado</p>
          </div>
      </div>
    </div>
  </div>
  <div v-else>
  </div>
  
  `,
    mounted: function () {
        this.opcoes;
    }
});
var WM_Input = Vue.component('wm-input', {
    props: {
        entidade: {
            type: String,
            required: true
        },
        campo: {
            type: String,
            required: true
        },
        titulo: {
            type: String,
            default: "Input"
        },
        id: {
            type: String,
            required: true
        },
        obrigatorio: {
            Boolean,
            default: false
        },
        visivel: {
            Function,
            default: () => { return () => { return true } }
        },
        disabled: {
            Function,
            default: () => { return () => { return false } }
        }
    },
    computed: {},
    data: function () {
        return {
            value: dataVue[this.entidade][this.campo],
            valueSemFormato: "",
            required: this.obrigatorio,
            classe: this.disabled(this) ? 'desativado' : '',
            dataVisivel: this.visivel(this),
        }
    },
    template: `
  <div v-if="this.visivel(this)">
    <label style="font-size:20px" v-if="this.required" :for="id">{{titulo}} *</label>
    <label style="font-size:20px"  v-else :for="id">{{titulo}}</label>
    <input type="text" 
    :value="value"  :id="id" 
    @input="input($event.target.value)" 
    v-on:change="onchange($event.target.value)" 
    :disabled="disabled(this)"
    :class=" ['form-control','wminput',this.classe]" />
 
 </div>
 <div v-else></div>
    `,
    watch: {
        disabled: async function (a) {
            this.classe = a(this) ? 'desativar' : '';
        },
        visivel: function (v) {
            this.dataVisivel = v();
        }
    },
    methods: {
        onchange: function (newVal, a) {
            WMLimpaErrorClass(this.id);
            dataVue[this.entidade][this.campo] = newVal;
            this.value = dataVue[this.entidade][this.campo];
        },
        input: function (a) {
            WMLimpaErrorClass(this.id);
            dataVue[this.entidade][this.campo] = a;
            this.value = a;
        }
    }
});
var WM_TextArea = Vue.component('wm-textarea', {
    props: {
        entidade: {
            type: String,
            required: true
        },
        campo: {
            type: String,
            required: true
        },
        titulo: {
            type: String,
            default: "Input"
        },
        id: {
            type: String,
            required: true
        },
        obrigatorio: {
            Boolean,
            default: false
        },
        visivel: {
            Function,
            default: () => { return () => { return true } }
        },
        disabled: {
            Function,
            default: () => { return () => { return false } }
        },
        row: {
            type: Number,
            default: 3
        },
        cols: {
            type: Number,
            default: 3
        },
        resize: {
            type: Boolean,
            default: false
        },
        estilo: {
            type: Object
        },
        maxlength: {
            type: Number,
            default: 200
        },
        tamanhoMinimo: {
            type: Number,
            default: 0
        }
    },
    computed: {},
    data: function () {
        return {
            value: dataVue[this.entidade][this.campo],
            valueSemFormato: "",
            required: this.obrigatorio,
            classe: this.disabled(this) ? 'desativado' : '',
            dataVisivel: this.visivel(this),
        }
    },
    template: `
  <div v-if="this.visivel(this)">
  <div class="row justify-content-start">
        <div class="col-8">
        <label style="font-size:20px" v-if="this.required" :for="id">{{titulo}} *</label>
        <label style="font-size:20px"  v-else :for="id">{{titulo}}</label>
        </div>
        <div v-if="tamanhoMinimo >0 " class="col-4 mt-2  mb-0">
            <p class="text-secondary mb-0" style="font-size:12px">Minimo de Caracteres {{value.length}}/{{tamanhoMinimo}}</p>
            <wm-percent id="barraDescricao" :heigth="4" cor="#218838" :porcentagem="(value.length > tamanhoMinimo?tamanhoMinimo:value.length/tamanhoMinimo)*100" :Legenda="false" cordefundo="#21883833" />
        </div> 
  </div>
    <textarea  type="text" 
    :style="[estilo]"
    :value="value"  :id="id" 
    @input="input($event.target.value)" 
    
    :cols="this.cols"
    :maxlength="this.maxlength"
    :resize="this.resize"
    v-on:change="onchange($event.target.value)" 
    :disabled="disabled(this)"
    :rows="this.row"
    :class=" ['form-control','wminput',this.classe]" />
 
 </div>
 <div v-else></div>
    `,
    watch: {
        disabled: async function (a) {
            this.classe = a(this) ? 'desativar' : '';
        },
        visivel: function (v) {
            this.dataVisivel = v();
        }
    },
    methods: {
        onchange: function (newVal, a) {
            WMLimpaErrorClass(this.id);
            dataVue[this.entidade][this.campo] = newVal;
            this.value = dataVue[this.entidade][this.campo];
        },
        input: function (a) {
            WMLimpaErrorClass(this.id);
            dataVue[this.entidade][this.campo] = a;
            this.value = a;
        }
    }
});
var WM_CheckBox = Vue.component('wm-checkbox', {
    props: {
        entidade: {
            type: String,
            required: true
        },
        campo: {
            type: String,
            required: true
        },
        titulo: {
            type: String,
            default: "Input"
        },
        id: {
            type: String,
            required: true
        },
        visivel: {
            Function,
            default: () => { return () => { return true } }
        },
        disabled: {
            Function,
            default: () => { return () => { return false } }
        }
    },
    data: function () {
        return {
            value: dataVue[this.entidade][this.campo],
            classe: this.disabled(this) ? 'desativado' : '',
            dataVisivel: this.visivel(this),
        }
    },
    template: `
  <div class="text-center" v-if="this.visivel(this)">
    <label style="font-size:20px"  :for="id">{{titulo}}</label>
    <br>
    <input type="checkbox" 
      :checked="this.value"
     :id="id"  v-on:change="onchange" :disabled="disabled(this)"
     :class=" 'form-control ' + this.classe" />
 
 </div>
 <div v-else></div>
    `,
    watch: {
        value: function (v) { },
        disabled: async function (a) {
            this.classe = a(this) ? 'desativar' : '';
        },
        visivel: function (v) {
            this.dataVisivel = v();
        }
    },
    methods: {
        onchange: function (newVal) {
            dataVue[this.entidade][this.campo] = $(newVal.target).is(':checked');
            this.value = dataVue[this.entidade][this.campo];
        },
        input: function (a) { }
    }
});
var WM_Select = Vue.component('wm-select', {
    props: {
        id: {
            String,
            required: true
        },

        campo: {
            String,
            required: true
        },
        entidade: {
            String,
            required: true
        },
        titulo: {
            String,
            default: function () { return 'Seletor' }
        },
        visivel: {
            Function,
            default: () => { return true }
        },
        disabled: {
            Function,
            default: () => { return false }
        },
        limpavel: {
            type: Boolean,
            default: true
        },
        ajax: {
            Function,
            required: true
        },
        icone: {
            Boolean
        },
        obrigatorio: {
            Boolean,
            default: false
        }
    },
    data: function () {
        return {
            campoSeletor: 'Select' + this.campo,
            selecionado: dataVue[this.entidade][this.campoSeletor] || null,
            options: [],
            classe: this.disabled(this) ? 'desativado' : '',
            dataVisivel: this.visivel(),
            datalimpavel: this.limpavel
        }
    },
    template: ` 
    <div v-if="dataVisivel">
    <label v-if="obrigatorio" class="selectLabel" style="font-size:20px"  :for="id">{{titulo}} *</label>
    <label v-else style="font-size:20px" class="selectLabel"  :for="id">{{titulo}}</label>
  <v-select 
                :options="options" 
                :id="id"
                :reducer="x=> x.id"
                :class="classe"
                class="seletor"
                label="nome"
                :ref="id"
                :clearable="limpavel"
                :value="selecionado" 
                :disabled="disabled(this)"
                @search="onSearch"
                @search:focus="onSearch"
                @input="input">
                <template slot="no-options">
                  Nada Encontrado.
              </template>
              <template v-if="icone" slot="option" slot-scope="option">
                <div class="d-center optionColor">
                <i :class=" 'fas '+ option.icone"></i>
                    {{ option.nome }}
                    </div>
              </template>
              <template v-if="icone" slot="selected-option" slot-scope="option">
              <div class=" d-center optionColor">
              <i :class=" 'fas '+ option.icone"></i>
                {{ option.nome }}
              </div>
            </template>
          
        </template>
                </v-select>
    </div>
    `,
    beforeMount: function () {
        if (dataVue[this.entidade][this.campoSeletor] == undefined)
            app.$set(dataVue[this.entidade], this.campoSeletor, null);
        else
            this.selecionado = dataVue[this.entidade][this.campoSeletor];

    },
    mounted: async function (vm) {
        // this.selecionado =  this.$root.dataVue[this.entidade][this.campo];
    },
    watch: {
        disabled: async function (a) {
            this.classe = a(this) ? 'desativar' : '';
        },
        visivel: function (v) {
            this.dataVisivel = v();
        }
    },
    methods: {
        async onSearch(search, loading) {
            search = search || this.$refs[this.id].search;
            loading = loading || this.$refs[this.id].toggleLoading;
            loading(true);
            await this.search(loading, search, this);
        },
        search: async (loading, search, vm) => {
            let data = await vm.ajax(search, vm.selecionado);
            vm.options = data;
            loading(false);
        },
        input(select) {
            this.selecionado = select;
            if (select != null)
                dataVue[this.entidade][this.campo] = select.id;
            else
                dataVue[this.entidade][this.campo] = null;
            dataVue[this.entidade][this.campoSeletor] = select;
        },
        async desativar(d) {
            if (this.disabled())
                this.classe = 'desativado';
            else
                this.classe = '';
        }
    }
});
var VSELECT = Vue.component('v-select', VueSelect.VueSelect);



var WMUSERIMG = Vue.component('wm-user-img', {
    props: {
        img: String,
        width: {
            default: 224,
            type: Number
        },
        height: {
            default: 224,
            type: Number
        },
        margem_imagem: {
            default: "my-4",
            type: String
        },
        class_icone: String,
        class_imagem: String,
        id: String
    },
    data: function () {
        return {
            imgData: null,
        }
    },
    template: `
    <div>
        <div v-show="this.imgData == null || this.imgData == '' " >
            <div>
                <i 
                    style="
                        color: #343a40; 
                        background: #fff; 
                        border-radius:100%; 
                        border: solid #fff 4px; 
                        font-size: 15em;"
                    :class="[
                        margem_imagem,
                        'fas fa-user-circle',
                        class_icone ? class_icone : ''
                    ]"
                    aria-hidden
                    
                ></i>
            </div>
        </div> 
        <div v-show="this.imgData != null && this.imgData != '' ">
            <div>
                <img style="border-radius: 112px;"
                    :style="[{width:this.width + 'px', height: this.height + 'px','background-color':'white','object-fit': 'cover'}]"
                    :class="['mx-2', class_imagem ? class_imagem : '', margem_imagem]" 
                    :src="this.imgData"/>
            </div>
        </div>
    </div>
    `,
    mounted: function () {
        // console.log("hey")
    },
    watch: {
        img: {
            immediate: true,
            handler(v) {
                if (v != null && v != "")
                    this.imgData = 'data:image/jpeg;base64,' + v;
                else
                    this.imgData = null;
            }
        }
    }
});


var WM_InputCpf = Vue.component('wm-input-cpf', {
    props: {
        entidade: {
            type: String,
            required: true
        },
        campo: {
            type: String,
            required: true
        },
        titulo: {
            type: String,
            default: "Input"
        },
        id: {
            type: String,
            required: true
        },
        obrigatorio: {
            Boolean,
            default: false
        },
        visivel: {
            Function,
            default: () => { return () => { return true } }
        },
        disabled: {
            Function,
            default: () => { return () => { return false } }
        }
    },
    data: function () {
        return {
            value: dataVue[this.entidade][this.campo],
            valueSemFormato: "",
            required: this.obrigatorio,
            classe: this.disabled(this) ? 'desativado' : '',
            dataVisivel: this.visivel(this),
        }
    },
    template: `
  <div v-if="this.visivel(this)">
    <label style="font-size:20px"  v-if="this.required" :for="id">{{titulo}} *</label>
    <label style="font-size:20px"  v-else :for="id">{{titulo}}</label>
    <input type="text" 
          :value="value" 
          :id="id" 
          @input="input" 
          v-on:change="onchange($event.target.value)" 
          :disabled="disabled(this)"
          :class=" 'form-control ' + this.classe" />
 
 </div>
 <div v-else ></div>
    `,
    watch: {
        disabled: async function (a) {
            this.classe = a(this) ? 'desativar' : '';
        },
        visivel: function (v) {
            this.dataVisivel = v();
        }
    },
    mounted() {
        $(this.$el.children[1]).mask('000.000.000-00');
    },
    methods: {
        onchange: function (newVal, a) {
            WMLimpaErrorClass(this.id);
            dataVue[this.entidade][this.campo] = $(this.$el.children[1]).cleanVal();
            this.value = $(this.$el.children[1]).masked(dataVue[this.entidade][this.campo]);
        },
        input: function (a) {

        }
    }
});
var WM_ImageUpload = Vue.component('wm-image-upload', {
    props: {
        entidade: {
            type: String,
            required: true
        },
        campo: {
            type: String,
            required: true
        },
        tamanho: {
            type: String,
            default: "col-3"
        },
        titulo: {
            type: String,
            default: "Input"
        },
        limite: {
            type: Number,
            default: 5
        },
        id: {
            type: String,
            required: true
        },
        obrigatorio: {
            Boolean,
            default: false
        },
        visivel: {
            Function,
            default: () => { return () => { return true } }
        },
        disabled: {
            Function,
            default: () => { return () => { return false } }
        }
    },
    data: (a) => {
        let dt = {
            valueSemFormato: "",
            listaImagem: dataVue[a.entidade][a.campo].map(x => {
                if (x.selecionado == undefined)
                    x.selecionado = false;
                if (x.deletado == undefined)
                    x.deletado == false;
                if (x.id == undefined)
                    x.id == -1;
                return x;
            }),
            required: a.obrigatorio,
            classe: a.disabled(a) ? 'desativado' : '',
            dataVisivel: a.visivel(a),
            imagemSelecionada: null
        }
        return dt;
    },
    watch: {},
    template: `
  <div v-if="this.visivel(this)" :id="id">
  <p style="font-size: 20px; margin-bottom:0.2rem !important">{{obrigatorio?titulo + ' *':titulo}}</p>
  <p style="font-size: 12px">Imagens adicionadas {{this.listaImagem.filter(x=> !x.deletado).length}} de {{this.limite}}</p>
  <div class="imgcomponent-container">
      <button v-if="!disabled(this)" @click="adicionar(listaImagem,entidade,campo,limite,id)" class="btn btn-success" style="background:#218838 !important"><i class="fas fa-plus" style="color:#ffffffff"></i></button>
      <div v-for="(item, index) in this.listaImagem.filter(x=> !x.deletado)">
      <div :class="item.selecionado ? 'imgitem  selecionado' : 'imgitem' " v-on:click="itemClick(item,index)">
        <div>
          <div id="itemimg">
            <img :src="'data:image/jpeg;base64,' +item.img" alt=""/>
            </div>
            <div id="itemimgCrown" v-if="item.principal">
            <div id="itemimgCrown">
            <i class="fas fa-crown "  style="color: white"></i>
            </div>
            </div>
        </div>
      </div>
    </div>
  </div>
  <div v-if="this.imagemSelecionada != null">
      <div  class="previewImagem">
          <div id="contImg">
              <div class="wmoverlay" id="imgPreview">
              <img :src="'data:image/jpeg;base64,' + this.imagemSelecionada.img"   alt="">
              </div>
              <div v-if="this.imagemSelecionada.principal && !disabled(this)" v-on:click="SetPrincipal"  id="CoroaPreviewImg">
                <a style="cursor: pointer;" >
                   <i class="fas fa-crown selecaoAmarela"></i>
                </a>
              </div>
             <div v-else-if=" this.imagemSelecionada.principal == false && !disabled(this)" id="CoroaPreviewImg" v-on:click="SetPrincipal">
                <a style="cursor: pointer;"  >
                    <i class="fas fa-crown "></i>
                </a>
              </div>
             <div v-if="!disabled(this)" id="lixeiraPreviewImg" v-on:click="ApagarImagem">
              <a style="cursor: pointer;"><i class="fas fa-trash" style="color:white"></i></a>
              </div>
          </div>
      </div>
    </div>
</div>
 <div v-else ></div>
    `,
    watch: {
        disabled: async function (a) {
            this.classe = a(this) ? 'desativar' : '';
        },
        visivel: function (v) {
            this.dataVisivel = v();
        }
    },
    beforeMount() {
        // if(this.listaImagem == undefined){  
        //   this.$root.$set(dataVue,this.entidade,{});
        //   this.$root.$set(dataVue[this.entidade],this.campo,[]);
        // }
        // this.listaImagem = dataVue[this.entidade][this.campo].map(x=>{
        //   if(x.selecionado == undefined)
        //        x.selecionado = false;
        //  return x;
        // })
    },
    mounted() { },
    methods: {
        onchange: function (newVal, a) {

        },
        adicionar: async (lista, entidade, campo, limite, id) => {
            if (lista.filter(x => !x.deletado).length == limite) {
                toastr.info(`Limite de Imagens Atingido`, "Limite De Imgens");
                return false;
            }
            var input = $(document.createElement("input"));
            input.attr("type", "file");
            input.attr("accept", "image/x-png,image/gif,image/jpeg");
            // add onchange handler if you wish to get the file :)
            input.trigger("click"); // opening dialog
            await $(input).on('change', async () => {
                let imgBase = await LerImagem($(input)[0]);
                lista.push({ id: -1, img: imgBase, principal: limite == 1 || lista.length < 1 ? true : false, selecionado: false, deletado: false });
                dataVue[entidade][campo] = lista;
                WMLimpaErrorClass(id);
                return true;
            });
            return false; // avoiding navigation
        },
        itemClick(item, indice) {
            if (item.selecionado) {
                this.reseta();
                return false;
            }
            this.listaImagem.forEach((i, v) => {
                if (i.selecionado)
                    i.selecionado = false;
            });
            this.listaImagem[indice].selecionado = true;
            dataVue[this.entidade][this.campo] = this.listaImagem;
            this.imagemSelecionada = this.listaImagem[indice];
            this.imagemSelecionada.indice = indice;
        },
        ApagarImagem() {
            if (this.listaImagem[this.imagemSelecionada.indice].id != -1)
                this.listaImagem[this.imagemSelecionada.indice].deletado = true;
            else
                this.listaImagem.splice(this.imagemSelecionada.indice, 1);
            dataVue[this.entidade][this.campo] = this.listaImagem
            this.imagemSelecionada = null;
        },
        SetPrincipal() {
            WMLimpaErrorClass(this.id);
            this.listaImagem.forEach((i, v) => {
                if (i.principal && v != this.imagemSelecionada.indice)
                    i.principal = false;
            });
            this.imagemSelecionada.principal = !this.imagemSelecionada.principal;
            dataVue[this.entidade][this.campo] = this.listaImagem;
            // this.listaImagem[this.imagemSelecionada.indice].principal = !this.imagemSelecionada.principal;
        },
        reseta() {
            this.listaImagem.forEach((i, v) => {
                if (i.selecionado)
                    i.selecionado = false;
            });
            this.imagemSelecionada = null;
            dataVue[this.entidade][this.campo] = this.listaImagem;
        },
        input: function (a) {

        }
    }
});


var WMTIPOSERVICOITEM = Vue.component('tiposervicoItem', {
    props: {
        img: String,
        titulo: String,
        servicos: {
            type: Number,
            default: 0
        },
        id: String
    },
    data: function () {
        return {
            imgData: 'data:image/jpeg;base64,' + this.img,
            dataId: ""
        }
    },
    template: `
  <div class="itemlista" >
    <div @click="redirecionar(dataId)" >
    
    <div class="itemlista-img">
    <img :src="this.imgData">
        <div class="itemlista-texto">
        <h3 >{{titulo}}</h3>
        <p style="font-size: 12px">Serviços: {{servicos}}</p>
        </div>
    </div>
    </div>
</div>
    `,
    mounted: function () {
        // console.log(this.$refs.testevue);
    },
    watch: {
        img(v) {
            if (v != null)
                this.imgData = 'data:image/jpeg;base64,' + v;
            else
                this.imgData = v;
        },
        id: {
            immediate: true,
            deep: true,
            handler(v) {
                this.dataId = v;
            }
        }
    },
    methods: {
        redirecionar: (id) => {
            RediredionarComParametros("buscaservicos", [{ chave: "C", valor: id }]);
        }
    }
});

var WMList = Vue.component('wm-lista', {
    props: {
        id: String,
        item: {
            type: String,
            required: true
        },
        Arquivo: {
            type: String,
            required: true
        },
        Metodo: {
            type: String,
            required: true
        }
    },
    data: function () {
        return {
            ListData: [],
            Order: true
        }
    },
    computed: {
        icone: {
            get: function () {

                if (this.Order == true || this.Order == undefined)
                    return "fas fa-sort-alpha-up-alt";
                else
                    return "fas fa-sort-alpha-down-alt";
            },
            set: (val) => {

            }

        }
    },
    methods: {
        busca: async function (order) {
            let Lista = [];
            BloquearTela();
            Lista = await WMExecutaAjax(this.Arquivo, this.Metodo, { 'order': order })
            DesbloquearTela();
            return Lista
        },
        Ordenar: async function () {
            this.Order = !this.Order;
            this.ListData = this.Order ? this.ListData.sort((a, b) => a.nome.localeCompare(b.nome)) :
                this.ListData.sort((a, b) => b.nome.localeCompare(a.nome));

        },

    },
    async beforeMount() {
        this.ListData = await this.busca(true);
    },
    template: `
  <div class="col-12 p-0 m-0" style="overflow-x:hidden; " :id="id">
  <div  class="col-12  d-flex flex-row justify-content-end">
    <button class="btn btn-success" @click="Ordenar()">
      <span v-show="this.Order"><i  class="fas fa-sort-alpha-up" /></span>
      <span v-show="!this.Order"><i  class="fas fa-sort-alpha-down" /></span>
    </button>
  </div>


  <div :class="['row', 'd-flex', ListData.length > 0?'justify-content-start pl-4 ml-3':'justify-content-center']">
    <h1  v-if="ListData.length == 0">Nada Encontrado</h1>

    <tiposervicoItem v-else-if="item == 'tiposervico'" :key="item.id" v-for="item in ListData" 
    :img="item.imagem"
    :titulo="item.nome" 
    :servicos="item.servicos"
    :id="item.id" />
</div>
</div>

    `,
    mounted: function () {
        // console.log(this.$refs.testevue);

    },
    watch: {
        Order() {
            this.icone = this.icone;
        }
    }
});

var WMPercent = Vue.component('wm-percent', {
    props: {
        id: {
            type: String,
            required: true
        },
        porcentagem: Number,
        cor: {
            type: String,
            default: 'blue'
        },
        icone: String,
        Legenda: {
            type: Boolean,
            default: true
        },
        heigth: {
            type: Number,
            default: () => { return 10 }
        },
        cordefundo: {
            type: String,
            default: '#E9ECEF'
        }
    },
    data: (a) => {
        return {
            dataP: JSON.stringify(a.porcentagem) + '%',
            color: a.cor,
            legend: a.Legenda,
            dataHeight: JSON.stringify(a.heigth) + 'px',
            datacorFundo: a.cordefundo
        }
    },
    computed: {
        width: {
            get: (a) => {
                return { 'width': a.dataP };
            }
        },
        bg: {
            get: (a) => {
                return { 'background-color': a.color };
            }
        },
        tamanhoECor: {
            get: (a) => {
                return { 'background-color': a.datacorFundo, 'height': a.dataHeight };
            }
        }
    },
    template: `
  <div :id="id">
   <div class="progress"  :style="[tamanhoECor]">
    <div class="progress-bar bg-success" 
      :style="[width,bg]"
    role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">
    {{this.legend?this.dataP:'' }}
    </div>
   </div>

</div>

    `,
    watch: {
        porcentagem: {
            immediate: true,
            deep: true,
            handler(valor, old) {
                this.dataP = JSON.stringify(valor) + '%';
            }
        },
        cor: {
            immediate: true,
            deep: true,
            handler(valor, old) {
                if (valor != undefined)
                    this.color = valor + ' !important';
            }
        },
        Legenda: {
            immediate: true,
            deep: true,
            handler(valor, old) {
                if (valor != undefined)
                    this.legend = valor;
            }
        },
        height: {
            immediate: true,
            deep: true,
            handler(valor, old) {
                if (valor != undefined)
                    this.dataHeight = JSON.stringify(valor) + 'px';
            }
        },
        cordefundo: {
            immediate: true,
            deep: true,
            handler(valor, old) {
                if (valor != undefined)
                    this.datacorFundo = valor;
            }
        }
    }

});

WM_NovoProjeto = Vue.component('wm-projeto', {
    prop: {
        entidade: {
            type: String,
            required: true
        },
    },
    data: () => {
        return {
            page: 0,
            totalPage: 2,
            categorias: [],
            nivelusuario: [],
            nivelprojeto: [],
            faixaDePreco: [],
            titulos: ["Selecione uma Categoria para seu Projeto", "Informações do seu Projeto", "Definindo um Orçamento"],
            projeto: Projeto(),
            carregando: false,
            buscandoDados: false,
            concluiu: false
        }
    },
    async beforeMount() {
        this.buscandoDados = true;
        this.$root.$set(dataVue, this.$attrs.entidade, this.projeto);
        this.BuscaArrays();
        await this.BuscaCategorias(this);
        this.buscandoDados = false;
    },
    methods: {
        BuscaArrays() {
            let nivel = Object.values(NivelFuncionario);
            nivel.map((n, index) => {
                this.nivelusuario.push({ id: index, valor: n });
            });
            let nivelp = Object.values(NivelProjeto);
            nivelp.map((n, index) => {
                this.nivelprojeto.push({ id: index, valor: n });
            });
            let valores = Object.values(Valores);
            valores.map((n, index) => {
                this.faixaDePreco.push({ id: index, valor: n });
            });
        },
        BuscaCategorias: async (context) => {
            let Lista = [];
            Lista = await WMExecutaAjax("TipoServicoBO", "GetTipoServicoCategoria");
            if (Lista.length > 0)
                context.categorias = Lista;
        },
        async ProximaPage() {
            if (this.page == 2) {
                await this.SalvaProjeto(this);
                return;
            }
            if (this.projeto.imagens.filter(x => !x.deletado).length > 0 &&
                this.projeto.imagens.filter(x => x.principal && !x.deletado).length < 1) {
                toastr.info("Selecione uma Imagem Como Principal.", "Algo Errado");
                return;
            }
            if (this.page < this.totalPage)
                this.page++;
        },
        AnteriorPage() {
            if (this.page > 0)
                this.page--;
        },
        CategoriaChange(id) {
            this.projeto.Categoria = id;
            dataVue[this.$attrs.entidade]["Categoria"] = id;
        },
        NivelChange(id) {
            this.projeto.NivelDoProfissional = id;
            dataVue[this.$attrs.entidade]["NivelDoProfissional"] = id;
        },
        NivelProjetoChange(id) {
            this.projeto.NivelDoProjeto = id;
            dataVue[this.$attrs.entidade]["NivelDoProjeto"] = id;
        },
        ValorChange(id) {
            this.projeto.Valor = id;
            dataVue[this.$attrs.entidade]["Valor"] = id;
        },
        async SalvaProjeto(contexto) {
            contexto.carregando = true;
            BloquearTelaSemLoader();
            let result = await WMExecutaAjax("ProjetoBO", "SalvarProjeto", { Projeto: contexto.projeto }, false);
            if (result.error == undefined) {
                if (result.split('|')[0] == "OK") {
                    dataVue[contexto.$attrs.entidade]["id"] = result.split('|')[1];
                    DesbloquearTelaSemLoader();
                    contexto.carregando = false;
                    contexto.concluiu = true;
                } else {
                    toastr.error("Corram para as montanhas.", "Ops");
                    DesbloquearTelaSemLoader();
                    contexto.carregando = false;
                    return
                }
            } else {
                toastr.error(result.error, "Ops");
                DesbloquearTelaSemLoader();
                contexto.carregando = false;
                return
            }

        }


    },
    computed: {
        BloqueiaBotao: {
            get: (contexto) => {
                if (contexto.page == 0)
                    if (contexto.projeto.Categoria == null)
                        return true;
                if (contexto.page == 1)
                    if ((dataVue[contexto.$attrs.entidade]["Nome"] == null || dataVue[contexto.$attrs.entidade]["Nome"] == "") ||
                        (dataVue[contexto.$attrs.entidade]["Descricao"] == null || dataVue[contexto.$attrs.entidade]["Descricao"] == "" ||
                            contexto.projeto.Descricao.length < 50))
                        return true;
                if (contexto.page == 2)
                    if (contexto.projeto.NivelDoProfissional == null || contexto.projeto.NivelDoProjeto == null ||
                        contexto.projeto.Valor == null)
                        return true;


                return false;
            }
        }
    },

    watch: {},
    template: `
    <div :class="['col-10', 'my-5' ,concluiu?'sucesso':'bg-ligth' ,'boxServico']">
    <div  v-if="!concluiu">
    <div class="row">
        <div :class="['col-8','p-3','list', this.buscandoDados? 'op-0':'op-1']">
            <h4 class="text-secondary">{{titulos[page]}}</h4>
            <div id="servicoqc" style="height: 400px;overflow-y: auto">

                <div v-if="page == 0" >
                    <div class="control-group p-2 ml-1 mt-1 col-4">

                        <div  :class="[]">
                            <div v-for="item in this.categorias">
                            <label class="control control-radio">
                            {{item.nome}}
                            <input type="radio":checked="item.id == projeto.Categoria" v-on:change=" CategoriaChange(item.id)" :value="item.id" name="radio" />
                            <div class="control_indicator"></div>
                            </label>
                            </div>
                        </div>
                        

                    </div>
                   
                </div>

                <div v-if="page == 1" >
                        <div class="col-11">
                        <wm-input id="inputNome" :entidade="this.$attrs.entidade" campo="Nome" titulo="Titulo Do Projeto" />
                      </div>
                     <div class="col-11">
                        <wm-textarea id="txArea" :tamanhoMinimo="50" :estilo="{height:'150px'}" :maxlength="500"  :entidade="this.$attrs.entidade" campo="Descricao" titulo="Descrição Do Projeto" />
                        
                        </div>
                        <div class="col-8 mt-3">
                        <wm-image-upload id="imgUp" titulo="Adicione Imagens" :entidade="this.$attrs.entidade" campo="imagens" /> 
                        </div>
                </div>
                <div v-if="page == 2">
                <p class="mb-0" style="font-size:20px">Tamanho do Projeto</p>
                        <div class="mt-0 row pl-3" style="width:100%">
                            <div class="radio-toolbar" v-for="item in nivelprojeto">
                                <input type="radio" :disabled="carregando" :class="projeto.NivelDoProjeto == item.id?'radio-checado':'' "  :id="'radio'+item.valor" :checked="projeto.NivelDoProjeto == item.id" v-on:change="NivelProjetoChange(item.id)" name="radioFruit" :value="item.id" >
                                <label :for="'radio'+item.valor">{{item.valor}}</label>
                            </div>
                        </div>
                    <p class="mb-0" style="font-size:20px">Nivel de Profissional Desejado</p>
                 <div class="mt-0 row pl-3" style="width:100%">
                        <div class="radio-toolbar" v-for="item in nivelusuario">
                            <input :disabled="carregando" :class="projeto.NivelDoProfissional == item.id?'radio-checado':'' " type="radio" :id="'radio'+item.id" :checked="projeto.NivelDoProfissional == item.id" v-on:change="NivelChange(item.id)" name="radioFruit" :value="item.id" >
                            <label :for="'radio'+item.id">{{item.valor}}</label>
                        </div>
                  </div>

                 <p class="mb-0" style="font-size:20px">Faixa de Preço</p>
                <div class="mt-0 row pl-3" style="width:100%">
                       <div class="radio-toolbar" v-for="item in faixaDePreco">
                           <input :disabled="carregando" :class="projeto.Valor == item.id?'radio-checado':'' " type="radio" :id="'radio'+item.valor" :checked="projeto.Valor == item.id" v-on:change="ValorChange(item.id)" name="radioFruit" :value="item.id" >
                           <label :for="'radio'+item.valor">{{item.valor}}</label>
                       </div>
                 </div>
                </div>

            </div>
            <div class="row d-flex align-items-center">
                <div class="col-1 pr-0" v-show="page!=0">
                    <button class="p-0 mt-1 pr-0 ml-3 mr-0"@click="AnteriorPage" style="background:transparent;border:none" :disabled="carregando"  ><i style="font-size: 24px;cursor: pointer" class="fas fa-arrow-left"></i></button>
                </div>
                <div class="col">
                    <wm-percent id="teste" cor="#218838" :porcentagem="50*page" :Legenda="false" cordefundo="#21883833" />
                </div>
                <div class="col-2 p-0">
                <div v-if="carregando">
                    <button  :disabled="true" class="btn w-75 d-flex text-center justify-content-center btn-success text-light" style="cursor: pointer"><div class="activity_in"></div> </button>
                </div>
                <div v-else>
                 <button  class="btn btn-success text-light" :disabled="BloqueiaBotao" @click="ProximaPage" style="cursor: pointer"> {{ page < 2?"Proximo":"Concluir"}}</button>
                </div>
    
                </div>
            </div>

        </div>
        <div class="col-4 p-0 d-flex flex-column justify-content-center  ladoEsquerdo" style="height: 500px;word-break: break-word;">
            <h3 class="mt-2 p-1 pl-4" style="width: 100%;font-size:26px">{{page == 0 ? "Descrição do Tipo Serviço":"Resumo do Projeto"}}</h3>
            <div class="descContainer align-self-start p-3 pl-4" style="height: 280px;overflow-y: auto; width: 100%">
            <div v-if="page == 0">
            <p >{{
                projeto.Categoria != null ?
                categorias.filter(x=> x.id ==  projeto.Categoria)[0].descricao:"" 
            }}</p>
            </div>
            <div v-else>
                <p class="mb-0">
                Titulo: <span style="font-size:14px" class="text-secondary">{{ projeto.Nome }}</span>
                </p>
                <p class="mb-0">
                Categoria: <span style="font-size:14px" class="text-secondary">{{ categorias.filter(x => x.id == projeto.Categoria)[0]['nome'] }}</span>
                </p>
                <p class="mb-0">
                    Tamanho do Projeto: <span style="font-size:14px" class="text-secondary">{{projeto.NivelDoProjeto != null?nivelprojeto.filter(x => x.id == projeto.NivelDoProjeto)[0]['valor']:'' }}</span>
                </p>
                    <p class="mb-0">
                    Nivel de Profissional: <span style="font-size:14px" class="text-secondary">{{projeto.NivelDoProfissional != null?nivelusuario.filter(x => x.id == projeto.NivelDoProfissional)[0]['valor']:'' }}</span>
                    </p>
                    <p class="mb-0">
                    Faixa de Preço: <span style="font-size:14px" class="text-secondary">{{projeto.Valor != null?faixaDePreco.filter(x => x.id == projeto.Valor)[0]['valor']:'' }}</span>
                    </p>
            </div>
            </div>
            <img style=" height: 40%;width: 60%;margin-right: 80px" class="mb-4 align-self-end p-2" src="src/img/projeto/imgbase.png" />
           </div>
        </div>
    </div>
    <div  v-else >
            <div class="row">
                <div  style="height: 500px" class="col-12 d-flex  justify-content-center align-items-center">
                    <div class="col-12 text-center ">
                        <h4 class="" style="color:#218838;font-weight: bold; font-family: Poppins-Medium">Seu Projeto foi criado com Sucesso!!!</h4>
                        <div class="mb-4" style="width: 60% ;margin-left: 19%;background: #218838aa;height: 2px"></div>
                        <button @click="$root.Redirect('home')" class="botaoGenerico">Pagina Inicial</button>
                    <button  class="botaoGenerico">Ver Projeto</button>
                    </div>
                </div>
        </div>
    </div>
    </div>
`

});
HomeItem = Vue.component('wm-home-item', {
    props: {
        titulo: {
            type: String,
            required: true
        },
        imagem: {
            type: String,
            required: true
        }
    },
    data: () => {
        return {
            datatitulo: this.titulo,
            dataimagem: this.imagem
        }
    },
    watch: {
        titulo: {
            immediate: true,
            deep: true,
            handler(newval) {
                this.datatitulo = newval;
            }
        },
        imagem: {
            immediate: true,
            deep: true,
            handler(newval) {
                this.dataimagem = newval;
            }
        }
    },
    template: `
        <div class='itemPreferido'>
            <span class="tituloPreferido">{{datatitulo}}</span>
            <div class="linhaSeparadora"></div>
            <div class="imgWrapper">
                <img class="imgItemPreferido" :src=" 'data:image/png;base64,'+dataimagem " />
            </div>
        </div>
    `

});
WmProjetoItem = Vue.component('wm-projeto-item', {
    props: {
        id: {
            type: String,
            required: true
        },
        identidade: {
            type: String
        },
        titulo: {
            type: String
        },
        publicado: {
            type: String
        },
        propostas: {
            type: Number
        },
        descricao: {
            type: String
        },
        categoria: {
            type: String
        },
        tamanhodoprojeto: {
            type: String
        },
        nivelprofissional: {
            type: String
        },
        img: {
            type: String
        },
        nome: {
            type: String
        },
        valor: {
            type: String
        },
        id_usuario: {
            type: String
        },
        mostra_botao: {
            type: Boolean,
            default: true
        },
        texto_botao: {
            type: String,
            default: 'Fazer Proposta'
        }

    },
    data: () => {
        return {
            dataidentidade: null,
            datatitulo: '',
            datapublicado: '',
            datapropostas: 0,
            datadescricao: "",
            datacategoria: '',
            datatamanhodoprojeto: '',
            datanivelprofissional: '',
            dataimg: '',
            dataimgsemformato: '',
            datanome: '',
            dataValor: '',
            dataid_ususario: -1,
            mostrarmais: false,
            datatextoBotao: 'Fazer Proposta'

        }
    },
    watch: {
        titulo: {
            immediate: true,
            deep: true,
            handler(newval) {
                this.datatitulo = newval;
            }
        },
        identidade: {
            immediate: true,
            deep: true,
            handler(newval) {
                this.dataidentidade = newval;
            }
        },
        publicado: {
            immediate: true,
            deep: true,
            handler(newval) {
                this.datapublicado = newval;
            }
        },
        propostas: {
            immediate: true,
            deep: true,
            handler(newval) {
                this.datapropostas = newval;
            }
        },
        descricao: {
            immediate: true,
            handler(newval) {

                this.datadescricao = newval;
            }
        },
        categoria: {
            immediate: true,
            deep: true,
            handler(newval) {
                this.datacategoria = newval;
            }
        },
        tamanhodoprojeto: {
            immediate: true,
            deep: true,
            handler(newval) {
                this.datatamanhodoprojeto = NivelProjeto[newval];
            }
        },
        valor: {
            immediate: true,
            deep: true,
            handler(newval) {
                this.dataValor = Valores[newval];
            }
        },
        id_usuario: {
            immediate: true,
            deep: true,
            handler(newval) {
                this.dataid_ususario = newval;
            }
        },
        nivelprofissional: {
            immediate: true,
            deep: true,
            handler(newval) {
                this.datanivelprofissional = NivelFuncionario[newval];
            }
        },

        img: {
            immediate: true,
            deep: true,
            handler(newval) {
                if (newval == null || newval == "") {
                    this.dataimg = null;
                    this.dataimgsemformato = null;
                } else {
                    this.dataimg = 'data:image/jpg;base64,' + newval;
                    this.dataimgsemformato = newval;
                }
            }
        },
        nome: {
            immediate: true,
            deep: true,
            handler(newval) {
                this.datanome = newval;
            }
        },
        texto_botao: {
            immediate: true,
            deep: true,
            handler(newval) {
                this.datatextoBotao = newval;
            }
        }
    },
    mounted() { },
    methods: {
        mostrar() {
            this.mostrarmais = !this.mostrarmais;

        },
        abrirModal() {
            this.$emit("aberto-modal", {
                id: this.dataidentidade,
                nome: this.datanome,
                imagem: this.dataimgsemformato,
                titulo: this.datatitulo,
                publicado: this.datapublicado,
                proposta: this.datapropostas,
                descricao: this.datadescricao,
                categoria: this.datacategoria,
                tamanho: this.datatamanhodoprojeto,
                valor: this.dataValor,
                id_usuario: this.dataid_ususario
            });

        }
    },
    template: `
    <div class="projetoItemContainer">
    <div class="projetoHeader ">
        <div class=" p-2">
            <h3 class="font_Poopins_B">{{this.datatitulo}}</h3>
        </div>
        <div  v-if="mostra_botao"  class="p-2justify-content-center align-items-center">
            <button 
                class="btn btn-secondary m-0 font_Poopins_SB"
                @click="abrirModal"
            >
            {{datatextoBotao}}
                
            </button>
        </div>
    </div>
    <div class="projetoHeader2 ">
        <div class="d-flex flex-row justify-content-space-between">
            <div class="mx-2">
                <p class="font_Poopins_SB"><strong>Publicado</strong>: {{this.datapublicado}}</p>
            </div>
            <div class="mx-2">
                <p class="font_Poopins_SB"><strong>Propostas</strong>:{{this.datapropostas}}</p>
            </div>
        </div>
        <div class="mx-2 mr-4 projetoValor">
            <p class="font_Poopins_B">{{this.dataValor}}</p>
        </div>
    </div>
    <div class="ItemContainerDesc">
        <div >
            <p  v-html="this.mostrarmais ? datadescricao : datadescricao.substr(0,400)" class="font_Poopins m-0">
            </p>
            <p class="m-0">
            <span v-show="datadescricao.length > 400" @click="mostrar" class="mostrarmais">{{ !this.mostrarmais ?'Mostrar mais':'Mostrar menos'}}</span>
            </p>
            </div>
        <div >
            <p class="m-0 font_Poopins_B"><strong>Categoria</strong>: {{this.datacategoria}}</p>
            <p class="m-0 font_Poopins_B"><strong>Tamanho do Projeto</strong>: {{this.datatamanhodoprojeto}}</p>
            <p class="m-0 font_Poopins_B"><strong>Nível de Profissional Desejado</strong>: {{this.datanivelprofissional}}</p>
        </div>
    </div>
    <div class="projetoFoter">
        <div class=" d-flex flex-row justify-content-center  align-items-center p-2">
            <img v-if="this.dataimg != null" class="fotoperfilprojeto" :src="this.dataimg"/>
            <i v-else style="color: #343a40; background: #fff; border-radius:100%; border: solid #fff 4px; font-size: 45px !important;" class=" fas fa-user-circle"></i>
            <p class="mx-2 mt-3 font_Poopins_M">{{this.datanome}}</p>
        </div>
    </div>
</div>
`
});




WmModal = Vue.component('wm-modal', {
    props: {
        visivel: { Boolean, default: false },
        callback: { Function, default: () => { } },
        id: {
            type: String,
            required: true
        },
        height: {
            type: String,
            default: "95%"
        },
        width: {
            type: String,
            default: "80%"
        }
    },
    data: () => {
        return {
            dataVisible: false,
            dataCallback: () => { },
        }
    },
    watch: {
        visivel: {
            immediate: true,
            deep: true,
            handler(newval) {
                this.dataVisible = newval;
            }
        },
        callback: {
            immediate: true,
            deep: true,
            handler(newval) {
                this.dataCallback = newval;
            }
        }

    },
    methods: {
        fecharModal(key) {
            if ((key.target.id == this.id + 'close' || key.target.id == this.id) && key.target.classList["value"].split(" ").filter(x => x == "btn-close" || x == "modalBackdrop").length > 0) {
                this.dataVisible = false;
                this.dataCallback();
            }
        }
    },
    template: `
    <transition name="modal-fade">
        <div :id="id" class="modalBackdrop" v-if="this.dataVisible" @click="fecharModal" >
            <div :style="[{'height':height+' !important'},{'width':width + ' !important'}]" :class="['modalVue',this.dataVisible?'modal-slide':'']" >
                <div class="modalHeader">
                    <slot name="header">
                        Título Header
                    </slot>
                    <button
                    :id="id + 'close'"
                        type="button"
                        class="btn-close btnCloseModal"
                        @click="fecharModal"
                    >
                        X
                    </button>
                </div>
                <div class="modalBody">
                    <slot name="body">
                    Body padrão
                    </slot>
                </div>
                <div class="modalFooter">
                <slot name="footer">
                    Footer default
                    </slot>
                </div>
            </div>
        </div>
    </transition>
    `

});
Wm_Paginacao = Vue.component('wm-paginacao', {
    props: {
        paginaatual: {
            type: Number,
            default: "1"
        },
        totaldepaginas: {
            type: Number,
            required: true,
            default: "1"
        }
    },
    data: () => {
        return {
            dataPaginaAtual: 1,
            dataTotaldePaginas: 1,
            maxpaginas: 5,
            minpaginas: 1
        }
    },
    computed: {
        paginationTriggers: {
            get() {
                const currentPage = this.dataPaginaAtual;
                const pageCount = this.GeraPaginas().length;
                const visiblePagesCount = pageCount < this.maxpaginas ? pageCount : this.maxpaginas;
                const visiblePagesThreshold = Math.ceil((visiblePagesCount - 1) / 2);
                const pagintationTriggersArray = Array(visiblePagesCount - 1).fill(0);
                if (pageCount == 1) {
                    pagintationTriggersArray[0] = 1
                    const pagintationTriggers = pagintationTriggersArray.map(
                        (paginationTrigger, index) => {
                            return pagintationTriggersArray[0] + index
                        }
                    );
                    return pagintationTriggers
                }
                if (currentPage <= visiblePagesThreshold + 1) {
                    pagintationTriggersArray[0] = 1
                    const pagintationTriggers = pagintationTriggersArray.map(
                        (paginationTrigger, index) => {
                            return pagintationTriggersArray[0] + index
                        }
                    )
                    pagintationTriggers.push(pageCount)
                    return pagintationTriggers
                }

                if (currentPage >= pageCount - visiblePagesThreshold + 1) {
                    const pagintationTriggers = pagintationTriggersArray.map(
                        (paginationTrigger, index) => {
                            return pageCount - index
                        }
                    )
                    pagintationTriggers.reverse().unshift(1)
                    return pagintationTriggers
                }
                pagintationTriggersArray[0] = currentPage - visiblePagesThreshold + 1
                const pagintationTriggers = pagintationTriggersArray.map(
                    (paginationTrigger, index) => {
                        return pagintationTriggersArray[0] + index
                    }
                )
                pagintationTriggers.unshift(1);
                pagintationTriggers[pagintationTriggers.length - 1] = pageCount
                return pagintationTriggers
            }
        }

    },
    methods: {
        GeraPaginas() {
            return Array.from(Array((this.dataTotaldePaginas + 1) - 1).keys()).map(i => 1 + i);
        },
        NextPage() {
            if (this.dataPaginaAtual + 1 <= this.dataTotaldePaginas)
                this.dataPaginaAtual++;
            this.$emit("changepagina", this.dataPaginaAtual);

        },
        PreviusPage() {
            if (this.dataPaginaAtual - 1 > 0)
                this.dataPaginaAtual--;
            this.$emit("changepagina", this.dataPaginaAtual);

        },
        ChangePage(page) {
            this.dataPaginaAtual = page;
            this.$emit("changepagina", this.dataPaginaAtual);
        }
    },
    watch: {
        paginaatual: {
            immediate: true,
            deep: true,
            handler(val) {
                this.dataPaginaAtual = JSON.parse(val);
            }
        },
        totaldepaginas: {
            immediate: true,
            deep: true,
            handler(val) {
                this.dataTotaldePaginas = JSON.parse(val);
            }
        }
    },
    template: `
    <div class="paginacao">
    <ul>
       <li > <button :disabled="this.dataPaginaAtual == 1" class="arrow" @click="this.PreviusPage"><i class="fas fa-arrow-left"></i></button></li>
        <li  v-for="item in paginationTriggers" ><a @click="ChangePage(item)":class="[item ==dataPaginaAtual?'active':'']">{{item}}</a></li>
        <li><button :disabled="this.dataPaginaAtual == this.dataTotaldePaginas" class="arrow" @click="this.NextPage" ><i class="fas fa-arrow-right"></i></button></li>
    </ul>
        </div>

`

});

WM_Error = Vue.component('wm-error', {

    props: {
        mensagem: {
            type: String,
            default: "Estamos com Problemas,Por favor tente novamente"
        }
    },
    data: () => {
        return {
            msg: '',
            faces: ["surprise", "sad-cry", "dizzy", "meh", "tired", "kiss", "frown"],
        }

    },
    computed: {
        ClasseProcessada: {
            get(a) {
                let random = Math.floor((Math.random() * (a.faces.length)))
                return `far fa-${a.faces[random]}`;
            }
        }
    },
    watch: {
        mensagem: {
            immediate: true,
            deep: true,
            handler(n, o) {
                this.msg = n;
            }
        }
    },
    template: `
    <div class="d-flex justify-content-center flex-column align-items-center" style="margin-top: 10%">
        <p>{{this.msg}}</p>
         <i style="font-size: 200px;opacity: 0.4;" :class="ClasseProcessada"></i>
</div>
    `
});

LoadingComponent = Vue.component('wm-loading', {
    props: {
        cor: {
            type: String,
            default: '#28a745 '
        },
        msg: {
            type: String,
            default: 'Carregando...'
        }
    },
    data: () => {
        return {
            datamsg: 'Carregando...',
            datacor: '#28a745'
        }
    },
    template: `
    <div class="col-12 mx-2 justify-content-center">
    <div class="d-flex justify-content-center flex-column align-items-center" style="margin-top: 20%">
        <div class="spinner-border" :style="{'color': this.datacor + ' !important'}"></div>
        <p>{{this.datamsg}}</p>
    </div>  
    </div>  
    `,
    watch: {
        cor: {
            immediate: true,
            deep: true,
            handler(v) {
                this.datacor = v;
            }
        },
        msg: {
            immediate: true,
            deep: true,
            handler(v) {
                this.datamsg = v;
            }
        }
    }
});
WM_IMAGEVIEWER = Vue.component('wm-image-viewer', {
    props: {
        imgs: {
            type: Array,
            default: () => { return [] }
        }
    },
    data: () => {
        return {
            dataimgs: [],
            imgSelecionada: null,
            modalAberto: false,
        }
    },
    methods: {
        callbackModal() {
            this.modalAberto = false;
        },
        AbreModal(item) {
            this.imgSelecionada = item;
            this.modalAberto = true;
        },
        VoltarDePagina(indice) {
            if (indice - 1 >= 0) {
                indice--;
                this.imgSelecionada = this.dataimgs[indice];
            } else {
                indice = this.dataimgs.length - 1;
                this.imgSelecionada = this.dataimgs[indice];
            }
        },
        ProximaDePagina(indice) {
            if (indice + 1 <= this.dataimgs.length - 1) {
                indice++;
                this.imgSelecionada = this.dataimgs[indice];
            } else {
                indice = 0;
                this.imgSelecionada = this.dataimgs[indice];
            }
        },
        MudarDePagina(indice) {
            if (indice != this.imgSelecionada.id)
                this.imgSelecionada = this.dataimgs[indice];
        }
    },
    watch: {
        imgs: {
            immediate: true,
            deep: true,
            handler(newv, old) {
                if (newv != null) {

                    this.dataimgs = newv.map((valor, index) => {
                        return { id: index, img: 'data:image/png;base64,' + valor };
                    });
                }
            }
        }
    },
    template: `
    <div v-if="this.dataimgs.length > 0" class="p-1 m-2">
    <p class="tituloImagemViewer">Imagens</p>
    <hr class="separadorTituloViewer"/>
    <div class="row mx-2">
        <div @click="AbreModal(item)" v-for="item in this.dataimgs" class="mr-2 imgViewerContainer">
        <div class="imageViewerOverflow d-flex justify-content-center align-items-center flex-column ">
        <i style="font-size: 18px;" class="fas fa-eye mt-1"></i>
        <p class="font_Poopins" style="font-size: 17px;">Visualizar</p>
        </div>
        <img class="imgViewerImg" :src="item.img" />
        </div>
    </div>
<wm-modal height="600px" width="78%" id="modalImagem" :visivel="modalAberto" :callback="callbackModal">
    <template v-slot:header>
        <div style="height: 60px;" class="d-flex justify-content-center align-items-center">
            <p style="font-size:30px;color: #218838;" class="mx-5 font_Poopins_SB my-2">Visualizar Imagem</p>
        </div>
    </template>
    <template v-slot:body>
        <div class="imgViewerModalBody p-2">
            <div  @click="VoltarDePagina(imgSelecionada.id)" style="cursor:pointer; user-select: none;position: absolute;
            left: 1%; " class="mx-1">
                <i style="color: #218838; font-size:30px" class="fas fa-arrow-circle-left"></i>
            </div>
            <div class="imgModalContainer">
                 <transition name="fade">
                        <img class="imgViewerModalImage" :src="imgSelecionada.img" />
                 </transition>
                <div class="ImageBalls">
                    <div v-for="i in dataimgs" >
                        <div style="cursor:pointer;user-select: none;" @click="MudarDePagina(i.id)" :class="['imgball',i.id == imgSelecionada.id ? 'selected':'']"></div>
                    </div>
                </div>
            </div>
            <div @click="ProximaDePagina(imgSelecionada.id)" style="cursor:pointer;  user-select: none;position: absolute;
            right: 1%; " class="m-1">
                <i style="color: #218838; font-size:30px" class="fas fa-arrow-circle-right"></i>
            </div>

        </div>
    </template>
    <template v-slot:footer>
        <div>

        </div>
    </template>
</wm-modal>
</div>
    `
});
WMCHAT = Vue.component('wm-chat', {
    props: {
        mensagens: {
            type: Array,
            default: () => { return [] }
        },
        userpropostaimage: {
            type: String,
            default: null
        },
        idusuariodestinatario: {
            type: Number,
            default: -1
        },
        heigth: {
            type: String,
            default: ''
        }
    },
    data() {
        return {
            dataMensagens: [],
            imagemUsuario: null,
            imagemUsuarioProposta: null,
            MensagemDigitada: null,
            idUsusarioContexto: null,
            carregando: true,
            idusuariodestinatariodata: -1,
            dataHeigth: ''
        }
    },
    methods: {
        inputMensagem(msg) {
            this.MensagemDigitada = msg
        },
        async NovaMensagem() {
            if (this.MensagemDigitada != null) {
                let mensagem = MensagemEntidade(-1, -1, this.MensagemDigitada, this.idusuariodestinatariodata, this.idUsusarioContexto, GetDataAtual());
                this.dataMensagens = ChatSeparatorGenerator([...Array.from(this.dataMensagens), mensagem]);
                this.MensagemDigitada = null;
                setTimeout(() => {
                    let scro = document.getElementById('bodyChatChat')
                    scro.scrollTop = scro.scrollHeight - scro.clientHeight;

                }, 1);
                this.$emit('novamensagem', mensagem);
            }
        }
    },
    async beforeMount() {
        this.idUsusarioContexto = await GetSessaoPHP(SESSOESPHP.IDUSUARIOCONTEXTO);
        this.imagemUsuario = await GetSessaoPHP(SESSOESPHP.FOTO_USUARIO);
        this.carregando = false;
        await setTimeout(() => {
            let scro = document.getElementById('bodyChatChat')
            scro.scrollHeight + 78;
            scro.scrollTop = scro.scrollHeight;

        }, .1);
    },
    mounted() {


    },
    watch: {
        mensagens: {
            immediate: true,
            deep: true,
            handler(nv, ov) {
                if (nv != undefined || nv != null) {
                    let visualizou = false;
                    nv.map((item, index) => {
                        if (ov != undefined && ov.length == 0)
                            visualizou = true;
                        if (ov != undefined && (ov.length > 0 && ov.length == nv.length)) {

                            if (ov[index].visualizado != item.visualizado)
                                visualizou = true;
                        }
                    });

                    if (ov == undefined || visualizou || ov.length != nv.length) {

                        this.dataMensagens = ChatSeparatorGenerator(Array.from(nv));
                        setTimeout(() => {
                            if (nv.length != ov.length) {

                                let scro = document.getElementById('bodyChatChat')
                                scro.scrollHeight + 78;
                                scro.scrollTop = scro.scrollHeight;
                            }
                            return

                        }, .1);
                    }
                } else
                    this.dataMensagens = [];

            }
        },
        userpropostaimage: {
            immediate: true,
            deep: true,
            handler(nv, ov) {

                this.imagemUsuarioProposta = nv;
            }
        },
        idusuariodestinatario: {
            immediate: true,
            handler(nv) {
                if (nv != undefined || nv != null)
                    this.idusuariodestinatariodata = nv
            }
        },
        heigth: {
            immediate: true,
            handler(nv) {
                this.dataHeigth = nv;
            }
        }
    },
    template: `
    <div v-if="!carregando">
    <div id="bodyChatChat" :style="{height:dataHeigth}">
    <div v-for="item in this.dataMensagens">
    <div v-if="item.tipo == 'separador'" class="dataChatDiv"><span class="dataChatDivTexto">{{item.msg}}</span></div>
    <div v-if="item.tipo == 'msg' && item.id_usuario_remetente == idUsusarioContexto " class="textoFuncionario">
        <wm-user-img :img="imagemUsuario" class="imagemGeralBC" class_icone="BCNullIcon" class_imagem="BCImageIcon"></wm-user-img>
        <div class="textoTF" >
        <p class="m-0">{{item.msg}}</p>            
        <div class="tempoTF">
        <div class="TTICON">
        <p class="m-0 ">{{item.time.slice(0,5)}}</p> 
        <span v-show="item.visualizado == 0 " class="ml-1" style="font-size: 10px;opacity: 0.6;"><i class="fas fa-check"></i></span>
        <span v-show="item.visualizado == 1" class="ml-1" style="font-size: 10px;color:rgb(6 226 22);"><i class="fas fa-check-double"></i></span>
        </div>
        </div>
        </div>
    </div>

    <div class="textoCliente" v-else-if="item.tipo == 'msg'">
        <wm-user-img :img="imagemUsuarioProposta" class="imagemGeralBC" class_icone="BCNullIcon" class_imagem="BCImageIcon"></wm-user-img>
        <div class="textoTC">
                <p class="m-0">{{item.msg}}</p>            
            <div class="tempoTC">
            <div class="TTICON">
            <p class="m-0">{{item.time.slice(0,5)}}</p> 
            </div>
            </div>
           
        </div>
    </div>
    </div>

    <div id="ancora">

    </div>
</div>
    <div class="bodyChatEnviar">
        <div class="wrapperImagemBC">
            <wm-user-img :img="this.imagemUsuario" class_icone="BCNullIcon" class_imagem="BCImageIcon"></wm-user-img>
        </div>
        <div class="wrapperInputBC">
            <input @keyup.enter="NovaMensagem" type="text" @input="inputMensagem($event.target.value)" :value="MensagemDigitada" class="inputBC" placeholder="Faça uma pergunta..."></input>
           <div @click="NovaMensagem" >
            <i class="far fa-paper-plane iconeSetaEnviar" style="cursor:pointer"></i>
            </div>
            </div>
    </div>

</div>
</div>
<div v-else>
    <wm-loading></wm-loading>
</div>
    `
});

WMChart = Vue.component('wm-chart', {

    template: `
    <div class="col-6">
    <canvas id="myChart" width="100%"></canvas>
    </div>
    `,
    mounted() {
        var ctx = document.getElementById('myChart').getContext('2d');
        var myChart = new Chart(ctx, {
            type: 'pie',
            data: {
                labels: ['Red', 'Blue', 'Yellow', 'Green', 'Purple', 'Orange'],
                datasets: [{
                    label: '# of Votes',
                    data: [12, 19, 3, 5, 2, 3],
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.2)',
                        'rgba(54, 162, 235, 0.2)',
                        'rgba(255, 206, 86, 0.2)',
                        'rgba(75, 192, 192, 0.2)',
                        'rgba(153, 102, 255, 0.2)',
                        'rgba(255, 159, 64, 0.2)'
                    ],
                    borderColor: [
                        'rgba(255, 99, 132, 1)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 206, 86, 1)',
                        'rgba(75, 192, 192, 1)',
                        'rgba(153, 102, 255, 1)',
                        'rgba(255, 159, 64, 1)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                legend: {
                    display: false
                },
                scales: {
                    yAxes: [{
                        ticks: {
                            display: false
                        },
                        gridLines: {
                            display: false,
                            drawBorder: false
                        },

                    }]
                }
            }
        });
    }
});
WMNotify = Vue.component('wm-notify', {
    props: {
        tipo: {
            type: Number,
            default: TipoNotificacao.DEFAULT
        },
        titulo: {
            type: String,
            default: ""
        },
        subtitulo: {
            type: Object,
            default: () => {
                return {
                    titulo: "",
                    descricao: ""
                }
            }
        },
        descricao: {
            type: String,
            default: ""
        },
        hora: {
            type: String,
            default: "00:00"
        }
    },
    data(){
        return {
            dataTipo: TipoNotificacao.DEFAULT,
            dataTitulo: "",
            dataSubtitulo: { titulo: "", descricao: "" },
            dataDescricao: "",
            dataHora: "00:00"
        }
    },
    watch: {
        tipo: {
            immediate: true,
            deep: true,
            handler(nv, ov) {

                this.dataTipo = nv;
            }
        },
        titulo: {
            immediate: true,
            deep: true,
            handler(nv, ov) {
                this.dataTitulo = nv;
            }
        },
        subtitulo: {
            immediate: true,
            deep: true,
            handler(nv, ov) {
                this.dataSubtitulo = nv;
            }
        },
        descricao: {
            immediate: true,
            deep: true,
            handler(nv, ov) {
                this.dataDescricao = nv;
            }
        },
        hora: {
            immediate: true,
            deep: true,
            handler(nv, ov) {
                this.dataHora = nv;
            }
        }

    },
    beforeMount: () => {
    },
    computed: {
        NotificacaoClasse: {
            get: (contexto) => {
                let classeBase = {
                    classe: ['itemnotificacao'],
                    icone: ['fas']
                };
                switch (contexto.dataTipo) {
                    case TipoNotificacao.ERROR:
                        classeBase.classe.push('Red1');
                        classeBase.icone.push("fa-times");
                        break;
                    case TipoNotificacao.SUCCESS:
                        classeBase.classe.push('Green');
                        classeBase.icone.push("fa-check");
                        break;
                    case TipoNotificacao.PROPOSTA:
                        classeBase.classe.push('Green');
                        classeBase.icone.push("fa-comment-dollar");
                        break;
                    case TipoNotificacao.CHAT:
                        classeBase.classe.push('Blue1');
                        classeBase.icone.push("fa-comments");
                        break;
                    case TipoNotificacao.ALERT:
                        classeBase.classe.push('Yellow1');
                        classeBase.icone.push("fa-exclamation-triangle");
                        break;
                    default:
                        classeBase.icone.push("fa-info");
                        break;
                }
                return classeBase;
            }
        }
    },
    template: `
            <div :class="NotificacaoClasse.classe">
                    <div class="ItemNotificacaoIcone">
                     <i :class="NotificacaoClasse.icone"></i>
                    </div>
                    <div class="DadosNotificacao">
                    <div class="TituloNotificacao ">
                        <p class="m-0 font_Poopins_B">{{this.dataTitulo}}</p>
                    </div>
                    <div class="DadosSubtitulo" v-if="this.dataSubtitulo.titulo != '' && this.dataSubtitulo.descricao != '' ">
                        <p class="m-0 font_Poopins_SB" >{{this.dataSubtitulo.titulo}}</p>
                        <p  class="m-0 ml-1 font_Poopins" style="font-size: 11px;">{{this.dataSubtitulo.descricao}}</p>
                    </div>
                    <div class="descricaoNotificacao ">
                        <div class="m-0 font_Poopins" v-html="this.dataDescricao"></div>
                    </div>
                    <div class="hora">
                        <p class="m-0 font_Poopins_B"><i class="far fa-clock"></i>{{this.dataHora}}</p>
                    </div>
                    </div>
            </div>
    `
});