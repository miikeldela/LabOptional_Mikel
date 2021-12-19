<?php session_start();?>
<!DOCTYPE html>
<html>
<head>
  <?php include '../html/Head.html'?>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
</head>
<body>
  <script type= "text/javascript" src='../js/ValidateFieldsQuestionJS.js'></script>
  <script src="../js/ValidateFieldsQuestionJQ.js"></script>
  <?php include '../php/Menus.php' ?>
  <section class="main" id="s1">
    <div>
      <?php
        include '../php/IncreaseGlobalCounter.php' ;
        
        if(isset($_GET['email']) & isset($_GET['contraseña']) ){
                //Codigo para meter al usuario en la base de datos
                $local=0; //0 para la nube
                if ($local==1){
                    $server="localhost";
                    $user="root";
                    $pass="";
                    $basededatos="ws";
                }
                else{
                    $server="localhost";
                    $user="mdelafuente016";
                    $pass="KYTMwUGduVWdmFGblRWb";
                    $basededatos="db_mdelafuente016";
                }
                /**$connection=mysqli_connect($server,$user,$pass,$basededatos);
                if(!$connection){
                    die("Error de conexión" . mysqli_error($connection));
                }*/

                try {
                    $dsn = "mysql:host=$server;dbname=$basededatos";
                    $dbh = new PDO($dsn, $user, $pass);
                

                    $email=$_GET['email'];
                    $email_berria= str_replace('@', '%40', $email);
                    $contraseña=$_GET['contraseña'];
                    $sql="SELECT * FROM usuarios";
                    $stmt = $dbh->prepare($sql);
                    //$resultado= mysqli_query($connection,$sql);
                  // $stmt->setFetchMode(PDO::FETCH_OBJ);
                    // Ejecutamos
                    
                    $stmt->execute();
                    // Mostramos los resultados
                    $usuarios = $stmt->fetchAll(PDO::FETCH_OBJ);
                    foreach ($usuarios as $usuario)
                    {
                    //if( $stmt->execute() ){
                      //  $encontrado=0;
                        //while($fila = $stmt->fetch()){
                          if($email== $usuario->email){
                            $encontrado=1;
                            //$usuario = $fila;

                            if(!password_verify($contraseña, $usuario->contraseña)){
                              echo "<script>
                                alert('La contraseña no es correcta');
                                window.location= 'Login.php'
                              </script>";

                            }else{
                              if($usuario->estado=="inactivo"){
                                echo "<script>
                                alert('El usuario está inactivo');
                                window.location= 'Login.php'
                              </script>";
                              }else{
                                $_SESSION["autentificado"]= "SI";
                                $_SESSION["email"]=$usuario->email; 
                                $_SESSION["tipoA_P"]=$usuario->tipoA_P;
                                IncreaseGlobalCounter();
                                if ($usuario->email=="admin@ehu.es"){
                                  echo "<script>
                                  alert('La contraseña es correcta');
                                  window.location= 'HandlingAccounts.php';
                                  </script>";
                                }else{
                                  echo "<script>
                                    alert('La contraseña es correcta');
                                    window.location= 'HandlingQuizesAjax.php';
                                  </script>";
                                }
                                die();
                              }
                            } 
                        }
                        }
                        if($encontrado==0){
                          echo "<script>
                                alert('El email no está registrado');
                                window.location= 'Login.php'
                              </script>";
                        } 

              }catch (PDOException $e){
                echo $e->getMessage();
              }
                //$connection->close();
                $dbh = null;

              }
            ?>
    <center><form id="fregister" name="fregister" action="" method="get" enctype="multipart/form-data"> 
        <label for="email">Email:(*)</label>
        <input type="text" id="email" name="email" ><br><br>
        <label for="contraseña">Contraseña:(*)</label>
        <input type="password" id="contraseña" name="contraseña" ><br><br>
        <button type="reset" id="reset" style="width:100px; height:25px" >Reset</button>
        <input id="submit" type="submit" value="Submit" style="width:100px; height:25px" >
        <span style="color:red" > <?php echo($errores) ?> </span>
      </form></center>
    

    </div>
  </section>
  <?php include '../html/Footer.html' ?>
</body>
</html>