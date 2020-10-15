<title>Login</title>




<!-- LINKS INTERNOS -->
<link rel="stylesheet" href="pages/login/style.css">


<div class="row justify-content-center text-center m-0">
    <div class="col-sm-11 col-md-10 col-lg-9 col-xl-8 alert-dark mt-5 rounded text-center align-self-center sombraFundoLogar">
        <div class="limiter">
            <div class="container-login100 container-troca">

                <!-- Registro para Funcionário -->
                <div class="wrap-login100 p-l-50 p-r-50 p-t-30 p-b-50 funcionarioForm">
                    <form class="login100-form validate-form" id="formLogin">
                        <span class="login100-form-title p-b-title">
                            Login
                        </span>

                        
                        <div class="g-signin2 my-2 botaoGoogle" data-theme="light"  data-onsuccess="onSignIn"></div>
                        <div class="envolveOu">
                            <div class="barrinhaOu"></div>
                            <span class="textoOu">OU</span>
                            <div class="barrinhaOu"></div>
                        </div>

                        <div class="wrap-input100 validate-input" data-validate="Apelido ou Email é necessário">
                            <input class="input100" type="text" name="email" placeholder="Apelido ou Email">
                            <span class="focus-input100"></span>
                        </div>

                        <div class="wrap-input100 validate-input margin-bottom-30px" data-validate="Senha é necessária">
                            <input class="input100" type="password" name="senha" placeholder="*************">
                            <span class="focus-input100"></span>
                        </div>



                        <div class="container-login100-form-btn">
                            <div class="wrap-login100-form-btn">
                                <div class="login100-form-bgbtn"></div>
                                <button id="BTN_Login" class="login100-form-btn fundoTrocarPraAzul" type="button">
                                    Entrar
                                </button>
                            </div>

                            <a href="#" class="dis-block txt3 hov1 p-r-30 p-t-10 p-b-10 p-l-30 " onclick="Rediredionar('login&position=registro')">
                                Registrar
                            </a>
                        </div>

                    </form>
                </div>



                <!-- Imagem da animação -->
                <div class="login100-more imagemPrincipalLogin fundoTrocarPraAzulImagem" style="background-image: url('src/img/login/prestadores-de-servico.jpg');"></div>

                <!-- Registro para Usuário -->
                <div class="wrap-login100 usuarioForm">
                    <form  class="login100-form validate-form" id="formRegistrar" method="POST">
                        <span class="login100-form-title p-b-title tituloRegistrar">
                            Registrar — Cliente
                        </span>
                        
                        <div class="g-signin2 botaoGoogle" data-theme="light" data-onsuccess="onSignIn"></div>
                        <div class="envolveOu">
                            <div class="barrinhaOu"></div>
                            <span class="textoOu">OU</span>
                            <div class="barrinhaOu"></div>
                        </div>
                        <div class="wrap-input100 validate-input" data-validate="Nome é necessário">
                            <input class="input100" type="text" name="nome" placeholder="Nome">
                            <span class="focus-input100"></span>
                        </div>

                        <div class="wrap-input100 validate-input" data-validate="Email é necessário: ex@abc.xyz">
                            <input class="input100" type="email" name="email" placeholder="Endereço de e-mail">
                            <span class="focus-input100"></span>
                        </div>

                        <div class="wrap-input100 validate-input" data-validate="Cpf é necessário">
                            <input class="input100 cpf" type="text" name="cpf" placeholder="Cpf">
                            <span class="focus-input100"></span>
                        </div>

                        <div class="wrap-input100 validate-input" data-validate="Senha é necessária">
                            <input class="input100" type="password" name="senha" placeholder="*************">
                            <span class="focus-input100"></span>
                        </div>

                        <div class="wrap-input100 validate-input" data-validate="Repetir senha é necessária">
                            <input class="input100" type="password" name="repetirSenha" placeholder="*************">
                            <span class="focus-input100"></span>
                        </div>
                        <div class="d-flex justify-content-center w-100">
                            <div class="switchWrapperCard">
                                <div class="switchWrapper">
                                    <span class="switchTexto">Registrando-se como Cliente</span>
                                    <div class="switch">    
                                        <input type="checkbox" class="checkFuncionario" name="checkFuncionario">
                                        <span class="deslize round botaoDeTroca"></span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="envolveSwitch">
                            <div class="barrinhaSwitch"></div>
                        </div>

                        <div class="container-login100-form-btn">
                            <div class="wrap-login100-form-btn">
                                <div class="login100-form-bgbtn "></div>
                                <button class="login100-form-btn fundoTrocarPraAzul" id="BTN_Register" type="button">
                                    Registrar
                                </button>
                            </div>

                            <a href="#" class="dis-block txt3 hov1 p-r-30 p-t-10 p-b-10 p-l-30 " onclick="Rediredionar('login')">
                                Logar
                            </a>
                        </div>
                        
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalCriarContaGoogle" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Para Concluir o Cadastro Preencha os Campos</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body w-100 d-flex flex-column justify-content-center align-items-center">
                <img id="imagem" class="mb-2 border border-success" style="height: 100px;width: 100px;border-radius: 100%;" />
                <span style="font-size: 19px !important" id="nome" class="login100-form-title text-center">
                </span>
                <span style="font-size: 15px !important;font-weight: normal !important;display: block;width: 100%" id="email" class="  text-center ">
                </span>
                <div class="p-2 w-100">
                    <div class="dropdown-divider"></div>
                    <p>Digite seu CPF</p>
                    <div class="wrap-input100 validate-input" data-validate="Cpf é necessário">
                        <input class="input100 cpf" type="text" id="cpfModal" name="cpf" placeholder="Cpf">
                        <span class="focus-input100"></span>
                    </div>
                    <p>Escolha entre Cliente e Funcionário</p>
                    <div class="d-inline-flex align-items-center">
                    <div class="switch">
                        <input type="checkbox" class="checkFuncionario" name="checkFuncionario">
                        <span class="deslize round botaoDeTroca"></span>
                    </div>
                    <p id="legenda" class="px-1">Cliente</p>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
                <button type="button" id="mdContinuar" class="btn btn-success disabled" title="Preencha o campo Cpf para continuar" disabled >Confirmar</button>
            </div>
        </div>
    </div>
</div>

<script  src="pages/login/script.js"></script>
<script>
    $("#Titulo").text("Login");

<?php 
    if(isset($_GET["position"])) {
        if ($_GET["position"] == "registro") 
        { ?>
            alternaLadoImagem();
            $("#Titulo").text("Registro");
        <?php }
        $t = isset($_GET["t"]) ? $_GET["t"] : "c";
        if($t == "f") 
        { ?>
            $(document).ready(() => {
                ativaBotaoFuncionarioClientes()
            });
        <?php } 
    } ?>
</script>