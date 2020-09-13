<div id='error404'>
    <div id='error404content'>
        <h3 class='' id='t-error' onload='aleta()'>
            A pagina <label class='' id="pagina"></label>
            Não existe
        </h3>
        <p>
            Por favor volte para a segurança! Caso tenha alguma duvida entre em contato conosco.
        </p>
    </div>
    <div id='error404img'></div>
</div>
<script>
    $('#Titulo').text('404');
    $("#pagina").text( $.urlParam('page'));
</script>