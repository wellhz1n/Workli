<style>
    * {
        transition: all .2s cubic-bezier(0.445, 0.05, 0.55, 0.95);
    }
</style>

<link rel="stylesheet" href="templates/css/template.css">



<div class="row" id="body-row">
    <div id="sidebar-container" class="sidebar-collapsed d-none d-md-block">
        <ul class="list-group" id="menu">
            <a href="#submenu2" id="admcadastros" data-toggle="collapse" aria-expanded="false" class="bg-dark list-group-item list-group-item-action flex-column align-items-start">
                <div class="d-flex w-100 justify-content-start align-items-center">
                    <span class="fas fa-file-invoice fa-fw mr-3"></span>
                    <span class="menu-collapsed" style="font-size: 14px">Cadastros</span>
                    <span class="fas fa-angle-down ml-auto"></span>
                </div>
            </a>
            <div id='submenu2' class="collapse sidebar-submenu">
                <a href="?page=admtiposervico" id="admtiposervico" class="list-group-item list-group-item-action  bg-secondary text-white">
                    <span class="fas fa-cubes fa-fw mr-3"></span>
                    <span class="menu-collapsed">Tipo Serviço</span>
                </a>
                <a href="?page=admusuario" id="admusuario" class="list-group-item list-group-item-action  bg-secondary text-white">
                    <span class="fas fa-users fa-fw mr-3"></span>
                    <span class="menu-collapsed">Usuário</span>
                </a>
            </div>
      
            <a href="#submenu4" data-toggle="collapse" id="" aria-expanded="false" class="bg-dark list-group-item list-group-item-action flex-column align-items-start">
                <div class="d-flex w-100 justify-content-start align-items-center">
                    <span class="fa fa-question fa-search mr-3"></span>
                    <span class="menu-collapsed" style="font-size: 14px">Buscas</span>
                    <span class="fas fa-angle-down ml-auto"></span>
                </div>
            </a>
            <div id='submenu4' class="collapse sidebar-submenu">
                <a href="?page=buscaservicos" id="buscaservicos" class="list-group-item list-group-item-action  bg-secondary text-white">
                    <span class="fas fa-cubes fa-fw mr-3"></span>
                    <span class="menu-collapsed">Buscar Projeto</span>
                </a>
                <a href="?page=buscausuarios" id="buscausuarios" class="list-group-item list-group-item-action  bg-secondary text-white">
                    <span class="fas fa-users fa-fw mr-3"></span>
                    <span class="menu-collapsed">Buscar Usuários</span>
                </a>

            </div>


            <a href="#submenu3" id="perfilUsuario" data-toggle="collapse" id="" aria-expanded="false" class="bg-dark list-group-item list-group-item-action flex-column align-items-start">
                <div class="d-flex w-100 justify-content-start align-items-center">
                    <span class="fa fa-question fa-cog mr-3"></span>
                    <span class="menu-collapsed" style="font-size: 14px">Configurações</span>
                    <span class="fas fa-angle-down ml-auto"></span>
                </div>
            </a>
            <div id='submenu3' class="collapse sidebar-submenu">
                <a href="?page=perfilUsuario" id="perfilUsuario" class="list-group-item list-group-item-action  bg-secondary text-white">
                    <span class="fas fa-user-cog fa-fw mr-3"></span>
                    <span class="menu-collapsed">PerfilDeUsuario</span>
                </a>

            </div>
            <a data-toggle="sidebar-colapse" style="cursor: pointer!important;" class="bg-dark list-group-item list-group-item-action d-flex align-items-center">
                <div class="d-flex w-100 justify-content-start align-items-center">
                    <span id="collapse-icon" class="fa  fa-2x mr-3 "></span>
                    <span id="collapse-text" class="menu-collapsed" style="font-size: 14px">Fechar</span>
                </div>
            </a>
        </ul>
    </div>


    <script>
        // Hide submenus
        if (sessionStorage.getItem('FECHAMENULATERAL') == undefined)
            sessionStorage.setItem('FECHAMENULATERAL', true);
        MenuLateralColapsado = true;
        Padrao();

        $('#body-row .collapse').collapse('hide');

        // Collapse/Expand icon
        $('#collapse-icon').addClass('fa-angle-left');

        // Collapse click
        this.parent.window.onload = () => {
            ClickMenuColapsed();
            if (sessionStorage.getItem('FECHAMENULATERAL') && !MenuLateralColapsado)
                $('[data-toggle="sidebar-colapse"]').click();
            $('[data-toggle="sidebar-colapse"]').click(function(e) {
                if (e.view != undefined) {

                    SidebarCollapse();
                    SetMenuAtivo();
                }

            });
        }


        SetMenuAtivo();






        function Padrao() {
            $('.menu-collapsed').addClass('d-none');
            $('.sidebar-submenu').addClass('d-none');
            $('.fa-angle-down').addClass('d-none');
            $('#collapse-icon').toggleClass('fa-angle-left fa-angle-right');

        }

        function SidebarCollapse() {
            $('.menu-collapsed').toggleClass('d-none');
            $('.sidebar-submenu').toggleClass('d-none');
            $('.fa-angle-down').toggleClass('d-none');
            $('#sidebar-container').toggleClass('sidebar-expanded sidebar-collapsed');
            if ($('#sidebar-container').hasClass('sidebar-collapsed')) {
                sessionStorage.setItem('FECHAMENULATERAL', false);
                MenuLateralColapsado = true;
            } else {
                sessionStorage.setItem('FECHAMENULATERAL', true);
                MenuLateralColapsado = false;
            }
            // Treating d-flex/d-none on separators with title
            var SeparatorTitle = $('.sidebar-separator-title');
            if (SeparatorTitle.hasClass('d-flex')) {
                SeparatorTitle.removeClass('d-flex');
            } else {
                SeparatorTitle.addClass('d-flex');
            }

            // Collapse/Expand icon
            $('#collapse-icon').toggleClass('fa-angle-left fa-angle-right');
        }

        function ClickMenuColapsed() {

            let menus = $('#menu > a').map((x, a) => a.id);
            menus = menus.filter((x, obj) => obj != "");
            menus.map((indice, obj) => {
                $(`#menu #${obj}`).on('click', () => {
                    if (MenuLateralColapsado)
                        Rediredionar(obj);

                });
            });

        }

        function SetMenuAtivo() {
            let menuTela = $(`#menu #${GetPageName()} `)[0];
            let ePai = $(menuTela.parentNode).is('#menu');
            if (ePai) {
                $(menuTela).addClass('MenuAtivo');
                MenuPai = menuTela.id;
            } else {
                let elementoPai = $(menuTela.parentNode.previousElementSibling);
                MenuPai = elementoPai.id;
                if (elementoPai.hasClass('collapsed') || MenuLateralColapsado) {
                    $(elementoPai).addClass('MenuAtivo');
                    $(menuTela).removeClass('MenuAtivo');
                } else {
                    $(elementoPai).removeClass('MenuAtivo');
                    $(menuTela).addClass('MenuAtivo');
                }

                $(menuTela).addClass('MenuAtivo');
                $(elementoPai).on('click', () => {
                    if (!elementoPai.hasClass('collapsed') || MenuLateralColapsado) {
                        $(elementoPai).addClass('MenuAtivo');
                        $(menuTela).removeClass('MenuAtivo');
                    } else {
                        $(elementoPai).removeClass('MenuAtivo');
                        $(menuTela).addClass('MenuAtivo');
                    }

                });
            }
        }
    </script>