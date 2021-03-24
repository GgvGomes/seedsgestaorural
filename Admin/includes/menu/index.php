<?php
include '../../includes/conexoes/index.php';
?>
<aside id="left-panel" class="left-panel">
    <nav class="navbar navbar-expand-sm navbar-default">

        <div class="navbar-header" style="padding: 15% ;">
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#main-menu" aria-controls="main-menu" aria-expanded="false" aria-label="Toggle navigation">
                <i class="fa fa-bars"></i>
            </button>
            <a class="navbar-brand" href="./"><img src="../../images/logo.png" alt="Logo"></a>
            <a class="navbar-brand hidden" href="./"><img src="../../images/logo2.png" alt="Logo"></a>
        </div>

        <div id="main-menu" class="main-menu collapse navbar-collapse">
            <ul class="nav navbar-nav">
                <li class="active">
                    <a class="Dashboard"> <i class="menu-icon fa fa-dashboard"></i>Visão Geral</a>
                </li>
                <h3 class="menu-title">Fazendas</h3><!-- /.menu-title -->
                <li class="active">
                    <a class="Fazenda"> <i class="menu-icon fa fa-dashboard"></i>Fazendas</a>
                </li>
                <li class="active">
                    <a class="Zona"> <i class="menu-icon fa fa-dashboard"></i>Zona</a>
                </li>
                <li class="active">
                    <a class="Talhoes"> <i class="menu-icon fa fa-dashboard"></i>Talhoes</a>
                </li>
                <li class="active">
                    <a class="Frotas"> <i class="menu-icon fa fa-dashboard"></i>Frotas</a>
                </li>
                <li class="active">
                    <a class="Estoque"> <i class="menu-icon fa fa-dashboard"></i>Estoque</a>
                </li>
                <li class="active">
                    <a class="Producao"> <i class="menu-icon fa fa-dashboard"></i>Produção</a>
                </li>
                <li class="active">
                    <a class="ResetCiclo">
                    <i class="menu-icon fa fa-dashboard"></i>Reset Ciclo</a>
                </li>
                <h3 class="menu-title">Financeiro</h3><!-- /.menu-title -->
                <li class="active">
                    <a class="Relatorios"> <i class="menu-icon fa fa-dashboard"></i>Relatórios</a>
                </li>
                <li class="active">
                    <a class="EntradaProdutos"> <i class="menu-icon fa fa-dashboard"></i>Entrada de Produtos</a>
                </li>
                <li class="active">
                    <a class="EntradaProdutos"> <i class="menu-icon fa fa-dashboard"></i>Serviços</a>
                </li> 
                <h3 class="menu-title">Administrativo</h3><!-- /.menu-title -->

                <li class="active">
                    <a class="Usuarios"> <i class="menu-icon fa fa-dashboard"></i>Usuários</a>
                </li>
                <?php if($Permissao_Seeds=="999"){?>
                <li class="active">
                    <a class="Licenca"> <i class="menu-icon fa fa-dashboard"></i>Licenças</a>
                </li>
                <?php
                }
                ?>

            </ul>
    </nav>
</aside>

<script src="<?php echo $urlPrincipal; ?>includes/menu/javaScript.js"></script>