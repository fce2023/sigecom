


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Sigecom</title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="<?php echo $URL; ?>temple/AdminLTE-3.2.0/plugins/fontawesome-free/css/all.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
  <!-- Tempusdominus Bootstrap 4 -->
  <link rel="stylesheet" href="<?php echo $URL; ?>temple/AdminLTE-3.2.0/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css">
  <!-- iCheck -->
  <link rel="stylesheet" href="<?php echo $URL; ?>temple/AdminLTE-3.2.0/plugins/icheck-bootstrap/icheck-bootstrap.min.css">
  <!-- JQVMap -->
  <link rel="stylesheet" href="<?php echo $URL; ?>temple/AdminLTE-3.2.0/plugins/jqvmap/jqvmap.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="<?php echo $URL; ?>temple/AdminLTE-3.2.0/dist/css/adminlte.min.css">
  <!-- overlayScrollbars -->
  <link rel="stylesheet" href="<?php echo $URL; ?>temple/AdminLTE-3.2.0/plugins/overlayScrollbars/css/OverlayScrollbars.min.css">
  <!-- Daterange picker -->
  <link rel="stylesheet" href="<?php echo $URL; ?>temple/AdminLTE-3.2.0/plugins/daterangepicker/daterangepicker.css">
  <!-- summernote -->
  <link rel="stylesheet" href="<?php echo $URL; ?>temple/AdminLTE-3.2.0/plugins/summernote/summernote-bs4.min.css">
</head>

<body>


    <!-- Main content -->
  <br> 
        <!-- Small boxes (Stat box) -->
        <div class="row">
          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-info">
              <div class="inner">
                <?php
                $stmt = $pdo->query("SELECT SUM(cantidad) FROM detalle_tecnico_producto");
                $cantidadTotal = $stmt->fetchColumn();
                ?>
                <h3><?php echo $cantidadTotal; ?></h3>

                <p>Salidas</p>
              </div>
              <div class="icon">
                <i class="ion ion-bag"></i>
              </div>
              <?php if ($rol_sesion != 'tecnico') { ?>
              <a href="<?php echo $URL; ?>/inventario/movimientos/salida/" class="small-box-footer">Mas informacion <i class="fas fa-arrow-circle-right"></i></a>
              <?php } ?>

              <?php if ($rol_sesion == 'tecnico') { ?>
              <a href="<?php echo $URL; ?>/inventario/tecnico/salida/lista.php" class="small-box-footer">Mas informacion <i class="fas fa-arrow-circle-right"></i></a>
              <?php } ?>
            </div>
          </div>
          <!-- ./col -->
          <?php if ($rol_sesion != 'tecnico') { ?>
          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-success">
              <div class="inner">
                <?php
                $stmt = $pdo->query("SELECT SUM(cantidad) FROM detalle_producto_proveedor");
                $cantidadTotal = $stmt->fetchColumn();
                ?>
                <h3><?php echo $cantidadTotal; ?></h3>

                <p>Entradas</p>
              </div>
              <div class="icon">
                <i class="ion ion-stats-bars"></i>
              </div>
              <a href="<?php echo $URL; ?>/inventario/movimientos/entrada/" class="small-box-footer">Mas informacion <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>
          <?php } ?>
          <!-- ./col -->
          <?php if ($rol_sesion != 'tecnico') { ?>
          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-warning">
              <div class="inner">
                <?php
                $stmt = $pdo->query("SELECT COUNT(*) FROM cliente");
                $cantidadClientes = $stmt->fetchColumn();
                ?>
                <h3><?php echo $cantidadClientes; ?></h3>

                <p>Clietes Registrados</p>
              </div>
              <div class="icon">
                <i class="ion ion-person-add"></i>
              </div>
              <a href="<?php echo $URL; ?>/atencion_cliente/clientes/lista_clientes/" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>
          <?php } ?>
          <!-- ./col -->
          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-danger">
              <div class="inner">
                <?php
                $stmt = $pdo->query("SELECT COUNT(*) FROM historial_atencion_cliente");
                $cantidadHistorial = $stmt->fetchColumn();
                ?>
                <h3><?php echo $cantidadHistorial; ?></h3>

                <p>Historial</p>
              </div>
              <div class="icon">
                <i class="ion ion-pie-graph"></i>
              </div>
              <a href="<?php echo $URL; ?>/atencion_cliente/historial.php/" class="small-box-footer">Mas informacion <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>
          <!-- ./col -->
        </div>
        <!-- /.row -->
        <!-- Main row -->
        <div class="row">
          <!-- Left col -->
          <section class="col-lg-7 connectedSortable">
            <!-- Custom tabs (Charts with tabs)-->
           
      <!-- /.card-header -->
      <div class="card-body">
                <div class="row">
                  <div class="col-md-8">
                    <p class="text-center">
                      <strong><?php echo date('d M, Y'); ?> - <?php echo date('d M, Y', strtotime('+30 days')); ?></strong>
                    </p>

                    <div class="chart">
                      <!-- Sales Chart Canvas -->
                      <canvas id="salesChart" height="180" style="height: 180px;"></canvas>
                    </div>
                    <!-- /.chart-responsive -->
                  </div>
                  <!-- /.col -->
                  <div class="col-md-4">
                    <p class="text-center">
                      <strong>Goal Completion</strong>
                    </p>

                    <div class="progress-group">
                      Pedidos
                      <?php
                      $query = "SELECT COUNT(*) as activos FROM atencion_cliente WHERE estado = 1";
                      $stmt = $pdo->query($query);
                      $fila = $stmt->fetch(PDO::FETCH_ASSOC);
                      $activos = $fila['activos'];
                      $query = "SELECT COUNT(*) as total FROM atencion_cliente";
                      $stmt = $pdo->query($query);
                      $fila = $stmt->fetch(PDO::FETCH_ASSOC);
                      $total = $fila['total'];
                      ?>
                      <span class="float-right"><b><?php echo $activos; ?></b>/<?php echo $total; ?></span>
                      <div class="progress progress-sm">
                        <div class="progress-bar bg-primary" style="width: 80%"></div>
                      </div>
                    </div>
                    <!-- /.progress-group -->

                    <?php
                    $query = "SELECT SUM(cantidad) as total FROM detalle_tecnico_producto";
                    $stmt = $pdo->query($query);
                    $fila = $stmt->fetch(PDO::FETCH_ASSOC);
                    $total_salida = $fila['total'];
                    $query = "SELECT SUM(cantidad) as total FROM detalle_producto_proveedor";
                    $stmt = $pdo->query($query);
                    $fila = $stmt->fetch(PDO::FETCH_ASSOC);
                    $total_entrada = $fila['total'];
                    ?>
                    <div class="progress-group">
                      Salidas / Entradas 
                      <span class="float-right"><b><?php echo $total_salida; ?></b>/<?php echo $total_entrada; ?></span>
                      <div class="progress progress-sm">
                        <div class="progress-bar bg-secondary" style="width: <?php echo ($total_salida / $total_entrada) * 100; ?>%"></div>
                      </div>
                    </div>

                    <!-- /.progress-group -->
                    <div class="progress-group">
                      <?php
                      $query = "SELECT COUNT(*) as total FROM historial_atencion_cliente";
                      $stmt = $pdo->query($query);
                      $fila = $stmt->fetch(PDO::FETCH_ASSOC);
                      $total_historial = $fila['total'];
                      ?>
                      <span class="progress-text">Historial</span>
                      <span class="float-right"><b><?php echo $total_historial; ?></b></span>
                      <div class="progress progress-sm">
                        <div class="progress-bar bg-success" style="width: <?php echo ($total_historial / 800) * 100; ?>%"></div>
                      </div>
                    </div>

                    <!-- /.progress-group -->
                   <!--  <div class="progress-group">
                      Send Inquiries
                      <span class="float-right"><b>250</b>/500</span>
                      <div class="progress progress-sm">
                        <div class="progress-bar bg-warning" style="width: 50%"></div>
                      </div>
                    </div>
 -->
                    
                  <!-- /.col -->
                <!-- </div> -->
                <!-- /.row -->
              </div>
              <!-- ./card-body -->
              <!-- <div class="card-footer">
                <div class="row">
                  <div class="col-sm-3 col-6">
                    <div class="description-block border-right">
                      <span class="description-percentage text-success"><i class="fas fa-caret-up"></i> 17%</span>
                      <h5 class="description-header">$35,210.43</h5>
                      <span class="description-text">TOTAL REVENUE</span>
                    </div> -->
                    <!-- /.description-block -->
                  </div>
                  <!-- /.col -->
                  <div class="col-sm-3 col-6">
                    <div class="description-block border-right">
                      <?php
                      $query = "SELECT SUM(precio) as total FROM productos";
                      $stmt = $pdo->query($query);
                      $fila = $stmt->fetch(PDO::FETCH_ASSOC);
                      ?>
                      <span class="description-percentage text-warning"><i class="fas fa-caret-left"></i> 0%</span>
                      <h5 class="description-header"><?php echo number_format($fila['total'], 2); ?></h5>
                      <span class="description-text">TOTAL COST</span>
                    </div>
                    <!-- /.description-block -->
                  </div>
                  <!-- /.col -->
                  <!-- <div class="col-sm-3 col-6">
                    <div class="description-block border-right">
                      <span class="description-percentage text-success"><i class="fas fa-caret-up"></i> 20%</span>
                      <h5 class="description-header">$24,813.53</h5>
                      <span class="description-text">TOTAL PROFIT</span>
                    </div> -->
                    <!-- /.description-block -->
                 <!--  </div> -->
                  <!-- /.col -->
                  <!-- <div class="col-sm-3 col-6">
                    <div class="description-block">
                      <span class="description-percentage text-danger"><i class="fas fa-caret-down"></i> 18%</span>
                      <h5 class="description-header">1200</h5>
                      <span class="description-text">GOAL COMPLETIONS</span>
                    </div> -->
                    <!-- /.description-block -->
                 <!--  </div>
                </div> -->
                <!-- /.row -->
              </div>
              <!-- /.card-footer -->
            </div>
            <!-- /.card -->
          </div>
          <!-- /.col -->
        </div>
        <!-- /.row -->

      
            

                   
</div>
</body>
</html>


<script src="plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap -->
<script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- overlayScrollbars -->
<script src="plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>
<!-- AdminLTE App -->
<script src="dist/js/adminlte.js"></script>

<!-- PAGE PLUGINS -->
<!-- jQuery Mapael -->
<script src="<?php echo $URL; ?>/temple/AdminLTE-3.2.0/plugins/jquery-mousewheel/jquery.mousewheel.js"></script>
<script src="<?php echo $URL; ?>/temple/AdminLTE-3.2.0/plugins/raphael/raphael.min.js"></script>
<script src="<?php echo $URL; ?>/temple/AdminLTE-3.2.0/plugins/jquery-mapael/jquery.mapael.min.js"></script>
<script src="<?php echo $URL; ?>/temple/AdminLTE-3.2.0/plugins/jquery-mapael/maps/usa_states.min.js"></script>
<!-- ChartJS -->
<script src="<?php echo $URL; ?>/temple/AdminLTE-3.2.0/plugins/chart.js/Chart.min.js"></script>

<!-- AdminLTE for demo purposes -->
<!-- <script src="<?php echo $URL; ?>/temple/AdminLTE-3.2.0/dist/js/demo.js"></script> -->
<!-- AdminLTE dashboard demo (This is only for demo purposes) -->
<script src="<?php echo $URL; ?>/temple/AdminLTE-3.2.0/dist/js/pages/dashboard2.js"></script>
</body>
</html>
