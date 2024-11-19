
<h1><i class="zmdi zmdi-male-alt zmdi-hc-fw"></i> Atención al Clientes</h1>

<div class="container-fluid">
    <ul class="breadcrumb breadcrumb-tabs">
        <li class="dropdown">
            <a href="#" class="btn btn-primary dropdown-toggle" data-toggle="dropdown">
                <i class="zmdi zmdi-account-add"></i> &nbsp; Agregar Tecnico <span class="caret"></span>
            </a>
            <ul class="dropdown-menu">
                <li>
                    <a href="<?php echo $URL;?>tecnico/crear_tecnico.php">
                        <i class="zmdi zmdi-account-add"></i> &nbsp; Agregar Tecnico
                    </a>
                </li>

                <li>
                    <a href="<?php echo $URL;?>tecnico/lista_tecnicos.php">
                        <i class="zmdi zmdi-eye"></i> &nbsp; Lista de Tecnicos
                    </a>
                </li>

            </ul>
        </li>
        <li class="dropdown">
            <a href="#" class="btn btn-primary dropdown-toggle" data-toggle="dropdown">
                <i class="zmdi zmdi-eye"></i> &nbsp; Asiganar Tecnico <span class="caret"></span>
            </a>
            <ul class="dropdown-menu">
              
                <li>
                    <a href="<?php echo $URL;?>tecnico/designar_tecnico.php">
                        <i class="zmdi zmdi-eye"></i> &nbsp; Designar Tecnico
                    </a>
                </li>
                <li>
                    <a href="<?php echo $URL;?>tecnico/lista_tecnicos_asignados.php">
                        <i class="zmdi zmdi-eye"></i> &nbsp; Tecnicos asignados a clientes
                    </a>
                </li>
            </ul>
        </li>
    </ul>
</div>

