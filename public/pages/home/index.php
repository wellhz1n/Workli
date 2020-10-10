<title>Contrate Funcionários & Encontre trabalhos online | Workli</title>

<link rel="stylesheet" href="pages/home/style.css" >


<!-- Importações de componentes PHP -->
<?php
require "pages/home/itemFuncionamento/index.php";
require "pages/home/itemVantagem/index.php";
require "pages/home/itemPreferido/index.php";
?>

<div class="row tiraMargin">
    <div class="col-12" id="secaoImagensTrocas">
        <div id="carrossel" class="carousel slide carrossel carousel_fade" data-ride="carousel">
            <ol id="paginacaoPosition" class="carousel-indicators">
                <li data-target="#carrossel" class="paginacao active" data-slide-to="0"></li>
                <li data-target="#carrossel" class="paginacao"data-slide-to="1"></li>
                <li data-target="#carrossel" class="paginacao" data-slide-to="2"></li>
            </ol>
            <div class="carousel-inner" style="height: 100%;">
                <div class="carousel-item active" style="height: 100%;">
                    <img class="d-block w-100 ImagemCarrosel" src="src/img/login/imagemCarrossel1.jpg">
                </div>
                <div class="carousel-item" style="height: 100%;">
                    <img class="d-block w-100 ImagemCarrosel" src="src/img/login/imagemCarrossel2.jpg">
                </div>
                <div class="carousel-item" style="height: 100%;">
                    <img class="d-block w-100 ImagemCarrosel" src="src/img/login/imagemCarrossel3.jpg" style="transform: rotate3d(0, 1, 0, 180deg);">
                </div>
            </div>
        </div>
        <div id="textoEBotoesWrapper">
            <div id="textoEBotoes">
                <div id="textoParalax">
                    <span id="textoParalaxCima" class="textoParalaxFormat">Contrate funcionários para qualquer trabalho!</span>
                    <span id="textoParalaxBaixo" class="textoParalaxFormat">Encontre profissionais qualificados para a sua necessidade.</span>
                </div>
                <a id="botaoContratar" class="botaoTelaInicial" href="?page=login&position=registro&t=c">Quero Contratar</a>
                <a id="botaoTrabalhar" class="botaoTelaInicial" href="?page=login&position=registro&t=f">Quero Trabalhar</a>
            </div>
        </div>
        <div id="backgroundDegrade"></div>
        <div id="imagemOndaWrapper">
            <img id="imagemTransicaoFluid" src="src/img/visual/onda.svg#svgView(viewBox(0,0,1920,82)" />
        </div>
    </div>


    <div class="col-12" id="secaoFuncionamento">
        <span id="textoFuncionamento" style="font-family: Poppins-Bold">Como Funciona?</span>
        <div id="wrapperFuncionamento">
            <?php
            echo itemFuncionamento("Contrate um serviço", "É facil, simplesmente crie um trabalho que precisa ser feito e receba propostas dos nossos funcionários rapidamente.", "trabalho");
            echo itemFuncionamento("Escolha funcionários", "Sempre que precisar, irá haver um funcionário para lhe atender: desde desenvolvimento web e design gráfico até serviços de jardinagem.", "escolha-funcionario");
            echo itemFuncionamento("Pagamento seguro", "Pagamentos seguros feitos através do sistema peer-to-peer, em que você negocia diretamente com o funcionário que lhe atenderá.", "pagamento-seguro");
            ?>
        </div>
    </div>
    <div class="col-12" id="secaoVantagens">
        <span id="textoVantagem" style="font-family: Poppins-Bold">Vantagens do Conserta</span>
        <div class="wrapperVantagem wrapperVantagemCima">
            <?php
            echo itemVantagem("Procure por funcionários", "Encontre profissionais confiáveis e cheque seu perfil para ver sua avaliação e experiência em relação à trabalhos anteriores.", "procurar-portfolios");
            echo itemVantagem("Veja as propostas", "Receba propostas dos funcionários e escolha a que melhor se encaixa em seu orçamento e necessidade.", "usuario-estrela");
            echo itemVantagem("Chat", "Através do chat, é possível haver uma conversa entre o funcionário e o cliente.", "chat");
            ?>
        </div>
        <div class="wrapperVantagem wrapperVantagemBaixo">
            <?php
            echo itemVantagem("Pague por qualidade", "Pague pelo trabalho quando o mesmo estiver completo e você satisfeito.", "pagamento");
            echo itemVantagem("Cheque o progresso", "Mantenha-se atualizado sobre como o trabalho está progredindo.", "relogio");
            ?>
        </div>
    </div>

    <div id="imagemOndaWrapperBaixo">
        <img id="imagemTransicaoFluidBaixo" src="src/img/visual/onda.svg" />
    </div>

    <div class="col-12" id="secaoPreferidos">
        <div id="titulosPreferidos">
            <span id="textoPreferidos" style="font-family: Poppins-Bold">Favorito dos clientes</span>
            <span id="textoPreferidosBaixo" style="font-family: Poppins-SemiBold">Abaixo há uma lista dos nossos serviços mais populares:</span>
        </div>
        <div id="wrapperPreferidos">
                <!-- VAI SER UM ITEM VUE JS -->
                <wm-home-item :titulo="item.nome" :imagem="item.imagem" v-for="item in dataVue.ItemMaisUsado">
            </div>
        </div>
    </div>

    <!-- <div class="col-10" id="teste123">
        <button id="btnADD" type="button">Adicionar Item</button>
        
        <div style="height: 600px;overflow-y: auto;">
            <item-servico style="margin: 10px;padding: 10px;" v-for="serv in dataVue.Servico" v-bind:Servico="serv"></item-servico>
        </div>
    </div>
    <hr>
    <div id="Listagem" class=" col-12 my-5 py-3">
        <table id="dtUsuarios" class="table table-striped  table-bordered table-sm" cellspacing="0" width="100%">
            <thead>
                <tr>

                    <th class="th-sm" name="nome">
                        Nome
                    </th>
                    <th class="th-sm" name="email">
                        Email
                    </th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>

    </div> -->

</div>

<script type="application/javascript" src="pages/home/script.js" ></script>
<script >
    $("#Titulo").text("Contrate Funcionários e Encontre trabalhos online | Workli");
</script>