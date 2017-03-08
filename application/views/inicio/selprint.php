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
    <link rel="stylesheet" href="assets/build/toastr.min.css" />
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
<h3 style="text-align:center;">Aun no hay una impresora seleccionada para imprimir el Ticket. Debe seleccionar una de la siguiente lista.</h3>

<form  class="form-signin" action="inicio/selectprint" method="post" id="selprntform">
    <hr />
    <div class="form-group">
      <label for="selprint">Seleccione la Impresora</label>
            <select name="selprint" id="selprint" class = "form-control">
                <option value="-1">-- Seleccione --</option>
                <?php 
                foreach ($printers as $prnt)
                {?>
                    <option value="<?=$prnt->id_impresora?>"><?=$prnt->label_impresora?></option>
                <?php 
                }?>
            </select>
    </div>
    <div class="form-group">
            <input type="submit" name="guardar" class="btn btn-primary" value="Ingresar" />
    </div>
</form>
</div> <!-- /container -->
<script type="text/javascript" src="assets/Scripts/jquery-1.10.2.min.js"></script>
<script type="text/javascript" src="assets/build/toastr.min.js"></script>
<script type="text/javascript">
    $(document).ready(function () {
       $('#selprntform').on('submit', function(e){
            e.preventDefault();
            if($('#selprint').val()!= '-1')
            {
                $.ajax({
                        url: "<?php echo base_url(); ?>Inicio/guardaprint",
                        type: "POST",
                        data: 'guardar=Ingresar&selprint=' + $('#selprint').val(),
                        success: function (data) {
                            console.log(data)
                            window.parent.location.href = "<?php echo base_url(); ?>Inicio"
                        }
                    });
            }
            else
            {
                toastr.error('Aun no ha seleccionado la impresora!', 'Error');
            }
        });
    });
  </script>
</body>
</html>
