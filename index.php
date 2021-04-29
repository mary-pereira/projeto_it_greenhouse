<?php
  session_start();

  if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    die();
  }

  // leitura das API's
  $path = 'api/files';
  // scandir($path) — Lista os arquivos e diretórios que estão no caminho especificado
  $files = array_diff(scandir($path), array('..', '.')); // array_diff - para tirar os pontos('.' e '..') do array

  // função que adiciona o simbolo 'ºC' e '%' 
  function escreveSimbolo($nome) {
    $simbolo = "";
    if ($nome == "luminosidade" || $nome == "humidade" || $nome == "humidade solo" ) {
      $simbolo = "%";
    }       
    if ($nome == "temperatura" ) {
      $simbolo = "ºC";
    }
    return $simbolo;
  }
?>

<!DOCTYPE html>
<html lang="pt">

<head>
  <?php include('head.php'); ?>
  <title>SG | Dashboard </title>
</head>

<body class="bg-light">

  <!-- Navbar -->
  <?php include('navbar.php'); ?>
  <!-- Fim da Navbar -->

  <div class="d-flex">

    <!-- Sidebar -->
    <?php include('sidenav.php'); ?>
    <!-- Fim da Sidebar -->

    <!-- Conteudo da página -->
    <div class="container-fluid content-page">
      <div class="content pt-3">

        <!-- tipo Jumbotron da Dasboard mas com uso do 'Media object'-->
        <div class="p-3 mb-3 rounded shadow-sm bg-white">
          <div class="lh-100">
            <h4 class="mb-1">Dashboard</h4>
            <h6 class="mb-1 text-success">Sistema de monitoramento</h6>
          </div>
        </div>        

        <!-- CARD'S-->
        <div class="row row-cols-1 row-cols-sm-2 row-cols-lg-2 row-cols-xl-4 row_cards">

          <?php  
          // array com os 4 nomes principais aparesenta no dashoard
          $name = array("luminosidade", "temperatura", "humidade", "porta");  
          $dif_name = array_diff($files, $name); // array com os nomes não incluidos no array $name
         
          for ($i = 0; $i < 4; $i++) { 
            // confirmação caso não haver a pasta presente no array $name
            if(file_exists($path."/".$name[$i])) { 
              //se ficheiro existe                          
              $nome = $name[$i];              
            }else{
              //se ficheiro não existe                               
              foreach($dif_name as $value){                
                $nome = $value;
              }
            }   

            $valor = file_get_contents($path . "/" . $nome . "/valor.txt");
            $hora = file_get_contents($path . "/" . $nome . "/hora.txt");   
            $img = "public/img/icon_sensor_" . $nome . ".png";   // vai buscar o caminho para a img respativa 
            $simbolo = escreveSimbolo($nome); // vai buscar o simbolo '%' ou 'ºC' dependendo do $nome
          ?>

            <!-- 'Card' + 'Media object' (formata img + texto frente a frente) -->
            <div class="col col_card">
              <div class="card border-light">
                <div class="card-body rounded shadow-sm p-3">
                  <div class="media mb-3">
                    <img class="mr-3" width="50" src="<?php echo "$img" ?>" onerror="this.src='public/img/icon_sensor_default.png'"  alt="Icon de  <?php echo "$nome" ?>">
                    <div class="media-body">
                      <h4 class="mb-1"> <b> <?php echo $valor . $simbolo ?> </b> </h4>
                      <h6 class="mb-1 text-muted"><?php echo ucfirst($nome)?></h6>
                    </div>
                  </div>
                  <!-- actualização com icon + link de historico-->
                  <div class="pt-3 border-top">
                    <span>
                      <i class="far fa-calendar-alt mr-1 mt-2 text-muted"></i>
                      <?php           
                        echo $hora;
                        
                        if ( ($_SESSION['username'] == 'admin') ) {
                          echo "<a href='historico.php?nome=" . $nome . 
                                  "'> <i class='fas fa-angle-double-right span_icon'></i>
                                  <span class='span_card'> Histórico </span>
                                </a>";
                        }
                      ?>

                    </span>
                  </div>
                </div>
              </div>
            </div>
            
          <?php } ?>

        </div>
        <!-- FIM da secção das card's-->

        <!-- Tabela dos Sensores -->
        <div class="card border-light rounded shadow-sm mt-3">
            <div class="card-header bg-success text-white header-table">Tabela de Sensores</div>
                <div class="card-body card_sensores">
                    <table class="table table-sm table-responsive-sm">
                        <thead>
                            <tr>
                            <th scope="col">Dispositivo Iot</th>
                            <th scope="col">Valor</th>
                            <th scope="col">Data de Registo</th>
                            <th scope="col">Estado</th>
                            <?php
                              if ($_SESSION['username'] == 'admin') {
                                echo "<th scope='col'>Histórico</th>";
                              }
                            ?>
                            </tr>
                        </thead>
                        <tbody>
                         <!-- código para listar todo os sensores -->
                          <?php 
                            foreach ($files as $value) {
                              $simbolo = escreveSimbolo($value); // vai buscar o simbolo '%' /'ºC' dependendo do nome do sensor
                          ?>
                          <tr>
                           <!-- o 'ucfirst' no '$value' serve para colocar a primeira letra do nome em maiúscula -->
                            <th scope="row"> <?php echo ucfirst($value) ?> </th>
                            <td  style="height: 50px"> 
                              <?php  
                                if (!file_exists($path . "/" . $value . "/valor.txt")) { //Se o ficheiro nao existir escreve NULL
                                  echo "NULL";
                                }else{
                                  print_r(file_get_contents($path . "/" . $value . "/valor.txt") . $simbolo);
                                }
                              ?> 
                            </td>

                            <td> 
                              <?php 
                                if (!file_exists($path . "/" . $value . "/hora.txt")) {
                                  echo "NULL";
                                }else{
                                  print_r(file_get_contents($path . "/" . $value . "/hora.txt"));
                                }
                                ?>
                            <td> 
                              <?php
                                if (!file_exists($path . "/" . $value . "/log.txt")) {
                                  echo "";
                                }else{

                                  if(   (file_get_contents($path . "/" . $value . "/valor.txt")) >20 ) {
                                    echo "<span class= 'badge badge-pill badge-danger' >Alto</span>";
                                  } else {

                                    if( (file_get_contents($path . "/" . $value . "/valor.txt")) == "aberta" ||
                                        (file_get_contents($path . "/" . $value . "/valor.txt")) == "abertas"
                                    ){
                                        echo "<span class='badge badge-pill badge-success'>Aberta</span>";
                                    } else{

                                      if(   ( (file_get_contents($path . "/" . $value . "/valor.txt")) >=1  ) &&
                                            ( (file_get_contents($path . "/" . $value . "/valor.txt")) <=20 )
                                        ) {
                                          
                                          echo "<span class='badge badge-pill badge-success'>Normal</span>";
                                      } else {

                                        if( (file_get_contents($path . "/" . $value . "/valor.txt")) == "fechada" ||
                                            (file_get_contents($path . "/" . $value . "/valor.txt")) == "fechadas"
                                          ) {
                                          echo "<span class='badge badge-pill badge-danger'>Fechado</span>";
                                        } else {
                                          if( (file_get_contents($path . "/" . $value . "/valor.txt")) == 0 ) {
                                            echo "<span class='badge badge-pill badge-warning'>Baixo</span>";
                                          }
                                        }
                                      }
                                    }
                                  }
                                }
                              ?>
                            </td>
                
                            <?php
                              if ( ($_SESSION['username'] == 'admin') && 
                                   (file_exists($path . "/" . $value . "/log.txt")) ) {
                                echo "<td> 
                                        <a href='historico.php?nome=" . $value. "'>
                                          <span>Histórico</span> 
                                        </a> 
                                      </td>";
                              }
                            ?>
                          </tr>

                          <?php
                            }
                          ?>

                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Fim do conteudo da página -->
      </div>
    </div>
  </div>

  <!-- JavaScript Bundle with Popper -->
  <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-Piv4xVNRyMGpqkS2by6br4gNJ7DXjqk09RmUpJ8jgGtD7zP9yug3goQfGII0yAns" crossorigin="anonymous"></script>

  <!-- JavaScript from NavBar -->
  <script type="text/javascript" src="public/js/navbar.js"></script>

</body>

</html>