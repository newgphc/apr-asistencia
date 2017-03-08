<html lang="en">
<head>
    <base href="<?php echo base_url();?>" />
    <meta charset="utf-8">
    <title>Ingreso | Sistema de gestion de cartas</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">

    <!-- Le styles -->
    <link href="assets/Content/bootstrap.css" rel="stylesheet">
    <style type="text/css">
      body {
        display: table;
		height:100%;
		width: 100%;
        background-color: #f5f5f5;
      }
	  .container {
		display: table-cell;
		vertical-align: middle;
	  }
      .form-signin {
        max-width: 320px;
        padding: 19px 29px 29px;
        margin: 0 auto 20px;
        background-color: #fff;
        border: 1px solid #e5e5e5;
        -webkit-border-radius: 5px;
           -moz-border-radius: 5px;
                border-radius: 5px;
        -webkit-box-shadow: 0 1px 2px rgba(0,0,0,.05);
           -moz-box-shadow: 0 1px 2px rgba(0,0,0,.05);
                box-shadow: 0 1px 2px rgba(0,0,0,.05);
      }
      .form-signin .form-signin-heading,
      .form-signin .checkbox {
        margin-bottom: 10px;
      }
      .form-signin input[type="text"],
      .form-signin input[type="password"] {
        font-size: 16px;
        height: auto;
        margin-bottom: 15px;
        padding: 7px 9px;
      }

    </style>
    <!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
      <script src="../assets/js/html5shiv.js"></script>
    <![endif]-->
</head>

  <body cz-shortcut-listen="true">

    <div class="container">
      <form class="form-signin" method="POST">
	  <img src="assets/Content/logo_login.png"/>
        <h3>Ingresar al sistema</h3>
        <input type="text" class="form-control input-sm" name="idusnick" placeholder="Nombre de usuario">
        <input type="password" class="form-control input-sm" name="pwd" placeholder="Contraseña">
        <label class="checkbox">
          <input type="checkbox" value="remember-me"> No cerrar sesion
        </label>
		<?php 
		if($errorlog == true)
		{				
			echo '<div class="alert alert-danger"><strong>Nombre de usuario o contrase&ntilde;a incorrectos.</strong></div>';
		}
		?>
		<input name="loged" value="logged" type="hidden"/>
        <button class="btn btn-large btn-primary" type="submit">Ingresar</button>
        <p style="text-align: center; margin-top: 5px;">Ó</p>
      <a href="inicio" class="btn btn-success">Ir a Toma de Asistencia</a>
      </form>
    </div> <!-- /container -->
</body></html>