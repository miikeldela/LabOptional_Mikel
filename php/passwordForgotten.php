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
        //include '../php/IncreaseGlobalCounter.php' ;
        
        if(isset($_GET['email'])){
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
                } catch (PDOException $e){
                  echo $e->getMessage();
                }

                $email=$_GET['email'];
                $email_berria= str_replace('@', '%40', $email);
               // $contraseña=$_GET['contraseña'];
                $sql="SELECT * FROM usuarios";
                $stmt = $dbh->prepare($sql); 
                $stmt->setFetchMode(PDO::FETCH_ASSOC);
                // Ejecutamos
                
                // Mostramos los resultados
                $resultado= $stmt->execute();
                if( $resultado ){
                  if( $resultado->fetchColumn()>0){
                    $encontrado=0;
                    while($fila = $stmt->fetch() ){
                      if($email== $fila["email"]){
                        $encontrado=1;
                        $usuario = $fila;
                        $titulo    = 'Recuperar contraseña';
                        $mensaje   = random_int(100000,999999);
                        if(mail($email,$titulo,$mensaje))
                            $_SESSION["emailRecuperar"]=$fila["email"]; 
                            $_SESSION["code"]=$mensaje; 
                        }else{
                            echo 'Error, el mail no se ha podido enviar';
                        }   
                  } 
                }
                }else{
                  die ('Error en el query database');
                } 
                //$connection->close();
                $dbh = null;
              }
            ?>
    <center><form id="fforgot" name="fforgot" action="" method="get" enctype="multipart/form-data"> 
        <label for="email">Email:(*)</label>
        <input type="text" id="email" name="email" ><br><br>
        <button type="reset" id="reset" style="width:100px; height:25px" >Reset</button>
        <input id="submit" type="submit" value="Submit" style="width:100px; height:25px" >
        <span style="color:red" > <?php echo($errores) ?> </span>
      </form></center>
    

    </div>
  </section>
  <?php include '../html/Footer.html' ?>
</body>
</html>