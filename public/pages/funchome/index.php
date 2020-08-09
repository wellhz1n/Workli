<div class="col-12">
<div class="row">

    <h1>Bem Vindo Funcionario: <?php echo BuscaSecaoValor(SecoesEnum::NOME) ?>
    <div class="col-2">

        <a onclick="Rediredionar('chat')" class="btn btn-success" style="cursor:pointer;color: #ffff;">
        <i class="fas fa-comments "></i> Chat
        </a>
    </div>
    
</div>

</div>
<script type="application/javascript">
    $("#Titulo").text("Home-Funcionário");
</script>