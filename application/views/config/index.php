<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<base href="<?php echo base_url();?>" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Sistema de Gestion De Cartas APR</title>
<link rel="stylesheet" href="assets/Content/bootstrap.css" />
<link type="text/css" href="assets/Content/bootstrap-timepicker.min.css" />
<link rel="stylesheet" href="assets/Content/datepicker3.css" />
<script type="text/javascript" src="assets/Scripts/jquery-1.10.2.min.js"></script>
<script type="text/javascript" src="assets/Scripts/bootstrap.min.js"></script>
<script type="text/javascript" src="assets/Scripts/bootstrap-timepicker.min.js"></script>
<script type="text/javascript" src="assets/Scripts/bootstrap-datepicker.js"></script>
</head>
<body>
    <div class="container body-content">
    <div class="btn-group" style="float: right; margin-top: 20px;">
    <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
      <?=$nombre?>
      <span class="caret"></span>
    </button>
    <ul class="dropdown-menu">
	  <li><a href="<?php echo base_url();?>login/logout">Salir</a></li>
    </ul>
  </div>
    <h1>Configuraciones del Sistema</h1>
    <form id="form1" name="form1" method="post" action="<?php echo base_url();?>Config" onsubmit="return validaForm();">
    <!-- Nav tabs -->
  <ul class="nav nav-tabs" role="tablist" id="myTab">
    <li role="presentation" class="active"><a href="#principal" aria-controls="principal" role="tab" data-toggle="tab">Principales</a></li>
    <li role="presentation"><a href="#impresoras" aria-controls="impresoras" role="tab" data-toggle="tab">Impresoras</a></li>
    <!--<li role="presentation"><a href="#settings" aria-controls="settings" role="tab" data-toggle="tab">Settings</a></li>-->
  </ul>

  <!-- Tab panes -->
  <div class="tab-content">
    <div role="tabpanel" class="tab-pane active" id="principal">
     <table class="table table-bordered table-striped">
        <tr>
            <td colspan="2"><strong>Datos del evento</strong></td>
        </tr>
        <tr>
            <td width="42%">Dirección Recinto:</td>
            <td width="58%"><input type="text" name="dir" id="dir" value="<?php echo  (null!== $parametros->direccion_evento) ? $parametros->direccion_evento : ''; ?>" class="form-control input-sm"/></td>
        </tr>
        <tr>
            <td width="42%">Fecha</td>
            <td width="58%"><input type="text" name="fec" id="fec" value="<?php echo (null !== $parametros->fecha_evento) ? date('d-m-Y', strtotime($parametros->fecha_evento)) : ''; ?>" class="form-control input-sm"/></td>
        </tr>	 
        <tr>
            <td width="42%">Hora</td>
            <td width="58%"><input type="text" name="hora" value="<?php echo (null !== $parametros->hora_evento) ? $parametros->hora_evento : ''; ?>" id="hora" class="form-control input-sm"/></td>
        </tr>
        <tr>
            <td width="42%">Nombre Empresa</td>
            <td width="58%"><input type="text" name="empresa" id="empresa" value="<?php echo (null !== $parametros->nombre_empresa) ? $parametros->nombre_empresa : ''; ?>" class="form-control input-sm"/></td>
        </tr>
        <tr>
            <td width="42%">Titulo Ticket</td>
            <td width="58%"><input type="text" name="ticket_title" id="ticket_title" value="<?php echo (null !== $parametros->ticket_title) ? $parametros->ticket_title : ''; ?>" class="form-control input-sm"/></td>
        </tr>
        <tr>
            <td width="42%">Texto Al Pie del Ticket</td>
            <td width="58%"><input type="text" name="ticket_footer_text" id="ticket_footer_text" value="<?php echo (null !== $parametros->ticket_footer_text) ? $parametros->ticket_footer_text : ''; ?>" class="form-control input-sm"/></td>
        </tr>
    </table>
    </div>
    <div role="tabpanel" class="tab-pane" id="impresoras">
    <h3>Lista de Impresoras disponibles</h3>
    <table>
        <tr>
            <th>Dirección Ip</th>
            <th>Nombre Impresora</th>
            <th></th>
        </tr>
        <tr>
            <td><input type="text" class="form-control" name="ip_print" id="ip_print"/></td>
            <td><input type="text" class="form-control" name="nombre_print" id="nombre_print"/></td>
            <td><input type="submit" class="btn btn-primary" name="guarda_impresora" value="Agregar" /></td>
        </tr>
    </table>
    <table class="table table-bordered table-striped">
        <tr>
            <th>#</th>
            <th>Dirección Ip</th>
            <th>Nombre</th>
            <th>Borrar</th>
        </tr>
        <?php 
        $i=1;
        foreach($listaImpr as $print)
        {?>
            <tr>
                <td><?=$i?></td>
                <td><?=$print->value_impresora?></td>
                <td><?=$print->label_impresora?></td>
                <td><a href="<?php echo base_url(). 'Config/deleteprint?idprint='. $print->id_impresora;?>" onclick="return confirm('Estas seguro/a?')" title="Borrar"><span class="glyphicon glyphicon-remove"></a></td>
            </tr>
        <?php
        $i++;
        }
        ?>
    </table>
    </div>
    <div role="tabpanel" class="tab-pane" id="settings">...</div>
    <div class="form-inline">
        <input type="submit" name="resetsys" id="resetsys" value="Reestablecer" onclick="return confirm('Estas seguro/a?')" class="btna btn btn-primary"/></td>
        <input type="submit" name="save" id="save" value="Guardar" class="btna btn btn-primary"/></td>
    </div>
     <?php
        if($exito==true){
            ?><div class="alert alert-info"><strong>Datos Guardados.</strong></div><?php
        }
        if($borraex==true){
            ?><div class="alert alert-info"><strong>Datos Borrados, El sistema está listo para una nueva asamblea.</strong></div><?php
        }
    ?>
  </div>
    </form>
    <hr />
        <footer>
            <p>&copy; <?=date('Y')?> - Cooperativa Hospital Champa Limitada</p>
        </footer>
    <script type="text/javascript">
    function validaForm()
    {
        var dir = document.getElementById('dir').value;
        var fecha = document.getElementById('fec').value;
        var hora = document.getElementById('hora').value;
        var empresa = document.getElementById('empresa').value;
        if((dir=="")||(fecha=="")||(hora=="")||(empresa==""))
        {
            alert('Debe completar todos los campos antes de guardar.');
            return false;
        }
    }
    $('#fec').datepicker({
        todayBtn: "linked",
        format: "dd-mm-yyyy",
        language: "es",
        calendarWeeks: true,
        autoclose: true,
        todayHighlight: true
    });
    $('#hora').timepicker({
        minuteStep: 1,
        template: 'modal',
        appendWidgetTo: 'body',
        showSeconds: true,
        showMeridian: false,
        defaultTime: false
    });
    
    $('#myTab a').click(function(e) {
    e.preventDefault();
    $(this).tab('show');
    });

    // store the currently selected tab in the hash value
    $("ul.nav-tabs > li > a").on("shown.bs.tab", function(e) {
    var id = $(e.target).attr("href").substr(1);
    window.location.hash = id;
    });

    // on load of the page: switch to the currently selected tab
    var hash = window.location.hash;
    $('#myTab a[href="' + hash + '"]').tab('show');
    </script>
    </div>
</body>
</html>
