<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Brand</title>
    <link rel="stylesheet" href="./loginRegistro/assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Montserrat:400,400i,700,700i,600,600i">
    <link rel="stylesheet" href="./loginRegistro/assets/fonts/ionicons.min.css">
    <link rel="stylesheet" href="./loginRegistro/assets/css/styles.min.css">
</head>

<?php

//MANAGEMENT OF DATABASE

	$servername = "localhost:3309";
	$username = "admin";
	$password = "admin";
	$dbname = "mydb";

	// Create connection
	$conn = new mysqli($servername, $username, $password, $dbname);
	// Check connection
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	} 
	
	//GET PROVINCES FROM DATABASE
	function getProvinces(){
		global $conn;
		$sql = "SELECT * FROM Provincia";
		$result = $conn->query($sql);

		if ($result->num_rows > 0) {
			// output data of each row
			/*while($row = $result->fetch_assoc()) {
				echo "id: " . $row["nombre"]. "<br>";
			}*/
			return $result;
		} else {
			echo "0 results";
		}
		$conn->close();
	}
	
	//REGIST DIRECTION
	function registerDirection($dir, $prov){
		global $conn;
		$call = $conn->prepare('CALL registerDirection(?, ?)');
		$call->bind_param('ss', $dir, $prov);
		$call->execute();	
	}
	
	//GET ID OF LAST INSERTED DIRECTION
	function getLastDirection(){
		global $conn;
		$sql = "CALL getLastDirection()";
		//$result = $conn->query($sql);
		//$id = $result->fetch_assoc();
		//return $id['idDireccion'];
		//$conn->close();
		$stmt = $conn->prepare($sql); 
		$stmt->execute();
		$stmt->store_result();
		$stmt->bind_result($output_value);
		$stmt->fetch();
		$stmt->close();
		//echo $output_value;
		return $output_value;
	}
	
	//ADDS NEW USER TO DATABASE
	function addUser($username, $mail, $pass, $usernick, $direction, $province, $birthday){
		registerDirection($direction, $province);
	    $address = getLastDirection();
		
		global $conn;
		$call = $conn->prepare('CALL registerUser(?, ?, ?, ?, ?, ?)');
		//echo 'Error: ' . $conn->error . "<BR />\n"; 
		$call->bind_param('ssssis', $username, $mail, $pass, $usernick, $address, $birthday);
		$call->execute();
		
	}
		

?>


<?php

//VALIDATE USER INPUT DATA

	// define variables and set to empty values
    $nameErr = $emailErr = $pwdErr = $nicknameErr = $provinceErr = $directionErr = $birthErr = "";
    $name = $email = $password = $nickname = $province = $direction = $birth = "";
	$withoutErr = true;

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
      if (empty($_POST["name"])) {
        $nameErr = "* Nombre requerido";
		$withoutErr = false;
      } else {
        $name = test_input($_POST["name"]);
        // check if name only contains letters and whitespace
        if (!preg_match("/^\pL+(?>[- ']\pL+)*$/u",$name)) {
          $nameErr = "* Solo se aceptan letras y espacios";
		  $withoutErr = false;
        }
      }
      
      if (empty($_POST["email"])) {
        $emailErr = "* Correo requerido";
		$withoutErr = false;
      } else {
        $email = test_input($_POST["email"]);
        // check if e-mail address is well-formed
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
          $emailErr = "* Formato de correo inválido";
          $withoutErr = false;		  
        }
      }
	  
	  if (empty($_POST["password"])) {
        $pwdErr = "* Contraseñá requerida";
		$withoutErr = false;
      } else {
        $password = test_input($_POST["password"]);
      }
	  
	  if (empty($_POST["nickname"])) {
        $nicknameErr = "* Nickname requerido";
		$withoutErr = false;
      } else {
        $nickname = test_input($_POST["nickname"]);
      }
	  
	  if (empty($_POST["province"])) {
        $provinceErr = "* Provincia no selecionada";
		$withoutErr = false;
      } else {
        $province = test_input($_POST["province"]);
      }
	  
	  if (empty($_POST["direction"])) {
        $directionErr = "* Dirección requerida";
		$withoutErr = false;
      } else {
        $direction = test_input($_POST["direction"]);
      }
	  
	  if (empty($_POST["birth"])) {
        $birthErr = "* Fecha de nacimiento es requerido";
		$withoutErr = false;
      } else {
        $birth = test_input($_POST["birth"]);
      }
	  
	  if ($withoutErr === true){
		addUser($name, $email, $password, $nickname, $direction, $province, $birth); 
	  }
      
    }


    function test_input($data) {
      $data = trim($data);
      $data = stripslashes($data);
      $data = htmlspecialchars($data);
      return $data;
    }
	
?>
	
<body style="background-color:rgb(rgb(224,224,224));">

    <nav class="navbar navbar-light navbar-expand-lg fixed-top clean-navbar" style="background-color:#165f40;">
        <div class="container-fluid"><a class="navbar-brand logo" href="#" style="color:#ffffff;">Foresta<img class="rounded-circle img-fluid float-left" src="./images/logo.jpg" width="15%" height="100%"></a><button class="navbar-toggler" data-toggle="collapse" data-target="#navcol-1"><span class="sr-only">Toggle navigation</span><span class="navbar-toggler-icon"></span></button>
            <div
                class="collapse navbar-collapse" id="navcol-1">
                <ul class="nav navbar-nav ml-auto" style="color:rgb(255,255,255);">
                    <li class="nav-item" role="presentation"><a class="nav-link" href="index.html" style="color:#fcfbfb;">Home</a></li>
                    <li class="nav-item" role="presentation"><a class="nav-link" href="tutorial.html" style="color:#ffffff;">Tutoriales</a></li>
                    <li class="nav-item" role="presentation"><a class="nav-link" href="ubicacionEmpresas.html" style="color:#ffffff;">Empresas asociadas</a></li>
                    <li class="nav-item" role="presentation"><a class="nav-link" href="ubicacionPlantar.html" style="color:#ffffff;">Puntos de plantación</a></li>
                    <li class="nav-item" role="presentation"><a class="nav-link" href="eventosRelacionados.html" style="color:#ffffff;">Eventos relacionados</a></li>
                    <li class="nav-item" role="presentation"><a class="nav-link" href="canjearPuntos.html" style="color:#ffffff;">Canjear Puntos</a></li>
                    <li class="dropdown" style="color:rgb(255,255,255);"><a data-toggle="dropdown" aria-expanded="false" href="#" class="dropdown-toggle nav-link dropdown-toggle" style="color:#ffffff;">Registro</a>
                        <div role="menu" class="dropdown-menu dropdown-menu-left">
                            <a role="presentation" href="registro.html" class="dropdown-item">Cliente</a>
                            <a role="presentation" href="registroEmpresas.html" class="dropdown-item">Empresa</a>
                        </div>
                    </li>
                </ul>
        </div>
        </div>
    </nav>
	
    <main class="page registration-page" style="background-color:#040404;color:rgb(58,104,150);">
        <section class="clean-block clean-form dark" style="background-color:rgb(94,83,66);background-image:url(&quot;./loginRegistro/assets/img/cover-trees.jpg&quot;);">
            <div class="container" style="background-size:cover;">
                <div class="block-heading">
                    <h2 class="text-info text-light" style="color:rgb(241,241,241);text-shadow:-2px 0 black, 0 2px black, 2px 0 black, 0 -2px black;font-size:50px;">Registro</h2>
                </div>
				
				<form style="background-color:rgb(224,224,224);" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
                    <div class="form-group"><label for="name" style="color:rgb(0,0,0);font-size:18px;">Nombre</label>
					<input class="form-control item" name="name" type="text"></div> <!--value="Jae Park"></div>-->
                    <span class="error" style="color:#FF0000;"> <?php echo $nameErr;?></span>
					
					<div class="form-group"><label for="email" style="color:rgb(0,0,0);font-size:18px;">Correo</label>
					<input class="form-control item" name="email" type="email"></div> <!--value="jaesix@gmail.com"></div>-->
                    <span class="error" style="color:#FF0000;"> <?php echo $emailErr;?></span>
                    
					<div class="form-group"><label for="password" style="color:rgb(0,0,0);font-size:18px;">Contraseña</label>
					<input class="form-control item" name="password" type="password"></div> <!--value="imdrum"></div>-->
                    <span class="error" style="color:#FF0000;"> <?php echo $pwdErr;?></span>
                    
					<div class="form-group"><label for="nickname" style="color:rgb(0,0,0);font-size:18px;">Nickname</label>
					<input class="form-control item" name="nickname" type="text"></div> <!--value="jaesix"></div>-->         
                    <span class="error" style="color:#FF0000;"> <?php echo $nicknameErr;?></span>
					
					<div class="form-group"><label for="province" style="color:rgb(0,0,0);font-size:18px;">Provincia</label>
					<select class="form-control" name='province' style="color:rgb(0,0,0);font-size:18px;" id="provincia">
						<?php
						$data = getProvinces();
						
						while($row = $data->fetch_assoc()) {
							echo "<option value=" . $row["idProvincia"] . ">" . (string)$row["nombre"] . "</option>";
						}
						?>
						<!-- <option value="2" name="province" selected="selected">ALAJUELA</option> -->
					</select>
                    <span class="error" style="color:#FF0000;"> <?php echo $provinceErr;?></span>
					
					</div>			
					
					<div class="form-group"><label for="direction" style="color:rgb(0,0,0);font-size:18px;">Dirección</label>
					<input class="form-control item" type="text" name="direction"></div> <!--value="Cerritos"></div>-->
                    <span class="error" style="color:#FF0000;"> <?php echo $directionErr;?></span>
                    
					<div class="form-group"><label for="birth" style="color:rgb(0,0,0);font-size:18px;">Fecha de nacimiento</label></div>
					<input class="form-control" type="date" name="birth" value="1992-09-15"><br>
                    <span class="error" style="color:#FF0000;"> <?php echo $birthErr;?></span>
					
					<button class="btn btn-primary btn-block" type="submit" style="background-color:rgb(98,152,103);font-size:18px;" value="Sing Up">Sing Up</button>
					<br>
				</form>
            </div>
        </section>
    </main>
	</form>
	
<?php
//TEST
	//registerDirection($direction, $province);
	//echo getLastDirection();
	//getProvinces();
	
	echo "<h1>RESULTADO</h1>";
	echo "Nombre: " . $name;
	echo "<br>";
	echo "Email: " . $email;
	echo "<br>";
	echo "Contraseña: " . $password;
	echo "<br>";
	echo "Nickname: " . $nickname;
	echo "<br>";
	echo "Provincia: " . $province;
	echo "<br>";
	echo "Direccion: " . $direction;
	echo "<br>";
	echo "Fecha de nacimiento: " . $birth;
	
?>
	
    <div class="footer-basic" style="background-color:#c6b9a6;">
        <footer>
            <div class="social"><a href="#"><i class="icon ion-social-instagram"></i></a><a href="#"><i class="icon ion-social-twitter"></i></a><a href="#"><i class="icon ion-social-facebook"></i></a></div>
        </footer>
    </div>
    <script src="./loginRegistro/assets/js/jquery.min.js"></script>
    <script src="./loginRegistro/assets/bootstrap/js/bootstrap.min.js"></script>
    <script src="./loginRegistro/assets/js/script.min.js"></script>
</body>

</html>