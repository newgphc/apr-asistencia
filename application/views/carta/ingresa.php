
<!DOCTYPE html>
<html>
<head>
    <base href="<?php echo base_url();?>" />
    <title></title>
    <link rel="stylesheet" type="text/css" href="assets/Content/bootstrap-select.css">
	<link rel="stylesheet" type="text/css"  href="assets/Content/bootstrap.css" />
	<style>
	html {
		height: 500px;
	}
	body {
		height: 500px;
		background: #f2f0f0;
	}
	</style>
</head>
<body>
<?php
$id_socio=0;
if(sizeof($datos) > 0) 
{
	$row_services = $datos[0];
    $id_socio = $row_services->id_ai_soc;
	if($row_services->asis_soc == 1)
	{
		$asis = "Asistira";
	}else{
		$asis = "No asistira";
	}
      echo '<table class="table table-bordered table-striped" id="datarep">';
      echo '<tr>';
        echo '<td colspan="2"><strong>Datos personales del socio</strong></td>';
      echo '</tr>';
       echo '<tr>';
        echo '<td width="42%">Rut</td>';
        echo '<td width="58%">'.$row_services->rut.'</td>';
      echo '</tr>';
      echo '<tr>';
        echo '<td width="42%">Nombre completo</td>';
        echo '<td width="58%">'.$row_services->nombre.'</td>';
      echo '</tr>';
      echo '<tr>';
        echo '<td>Direccion</td>';
        echo '<td><select name="direccion" id="direccion" class="selectpicker" style="width: 370px;">';
		foreach ($suscripciones as $row)
		{
			echo '<option value="'.$row->id_ai_susc.'">'.$row->direccion_susc.'</option>';
		}
		echo '</select></td>';
      echo '</tr>';
	echo '<tr>';
         echo '<td>Motivo de inasistencia</td>';
         echo '<td><div class="control-group input-append"><input name="motivo" type="text" id="motivo" value="" style="width:270px" placeholder="Motivo" class="form-control input-sm" data-required="true"/></div><input type="checkbox" name="rep" id="rep" value="rep"/> Representante<br></td>';
       echo '</tr>';
	   echo '<tr style="display:none;" id="f1">';
	   echo '<td colspan="2"><strong>Datos de representante</strong></td></tr>';
	   echo '<tr style="display:none;" id="f2"><td>Rut representante</td><td><div class="control-group input-append"><input name="rutrep" type="text" id="rutrep" value="" style="width:370px" placeholder="Ej: 11.111.111-1" class="form-control input-sm" data-required="true"/></div></td></tr>';
	   echo '<tr style="display:none;" id="f3"><td>Nombre representante</td><td><div class="control-group input-append"><input name="nombrerep" type="text" id="nombrerep" value="" style="width:370px" placeholder="Nombre completo" class="form-control input-sm" data-required="true"/></div></td></tr>';
	   echo '<tr>';
        echo '<td colspan="2" style="height: 47px;padding-top: 13px;" id="lasttd"><input type="button" id="savedata" class="btna btn btn-primary fancybox fancybox.iframe" style="margin-left: 10px;position: absolute;" value="Guardar datos"/> <div id="loadingDiv" style="margin-left: 140px; display:none;">Guardando...<img src="assets/Content/fancybox_loading.gif" /></div></td>';
	  echo '</tr>';
	echo '</table>'; 
}
else
{
	echo '<div class="alert alert-danger"><strong>Error</strong> No se ha podido cargar la informacion. Por favor vuelva a intentarlo.</div>';
}
?>
<script type="text/javascript" src="assets/Scripts/jquery-1.10.2.min.js"></script>
<script type="text/javascript" src="assets/Scripts/bootstrap-select.min.js"></script>
<script type="text/javascript" src="assets/Scripts/bootstrap.min.js"></script>
<script type="text/javascript" src="assets/Scripts/jquery.Rut.min.js"></script>
    <script type="text/javascript">
        $(window).on('load', function () {
            $('.selectpicker').selectpicker({
                'selectedText': 'cat'
            });
			
            // $('.selectpicker').selectpicker('hide');
			$('#savedata').on('click', function () {
			var motivo = $('#motivo').val();
			var rutrep = $('#rutrep').val();
			var nombrerep = $('#nombrerep').val();
			var direccion = $('#direccion').val();
			if($('#rep').is(":checked")) {
				if((motivo != '') && (rutrep != '') && (nombrerep != '') && (direccion != '')){
				$(this).closest('div').removeClass('error');
				$('#loadingDiv').show();  // show Loading Div
					var dataCadena = 'id_soc=<?=$id_socio?>&tcarta=2&rut_soc=' + rutrep + '&nomb_soc=' + nombrerep + '&motivo=' + motivo + '&direccion=' + direccion;
						$.ajax({
							type: "POST",
							url: "<?php echo base_url();?>Carta/insertrep",
							data: dataCadena,
							success: function(data) {
								if(data=='success')
								{
									$('#lasttd').append('<span style="margin-left: 140px;">Datos guardados con e&acute;xito.<span/><a href="<?php echo base_url();?>Carta/generadoc?id_user=<?=$id_socio?>" target="_Blank" style="margin-left: 10px;" name="pdf" type="button" class="btna btn btn-primary" id="pdf">Imprimir Comprobante<a/>');
									$('#savedata').attr("disabled","disabled");
									$('#savedata').removeClass('btn-primary');
									$('#savedata').addClass('disabled');
									$('#motivo').attr("disabled","disabled");
									$('#rutrep').attr("disabled","disabled");
									$('#rep').attr("disabled","disabled");
									$('#nombrerep').attr("disabled","disabled");
									$('#direccion').attr("disabled","disabled");
								}
							}
						});
					$('#loadingDiv').hide(); // hide loading div
				}
				else
				{
					$(this).closest('div').removeClass('success').addClass('error');
					alert('Todos los campos son obligatorios.');
				}
			}else{
				if((motivo != '') && (direccion != '')){
				$(this).closest('div').removeClass('error');
				$('#loadingDiv').show();  // show Loading Div
					var dataCadena = 'id_soc=<?=$id_socio?>&tcarta=1&rut_soc=' + rutrep + '&nomb_soc=' + nombrerep + '&motivo=' + motivo + '&direccion=' + direccion;
						$.ajax({
							type: "POST",
							url: "<?php echo base_url();?>Carta/insertrep",
							data: dataCadena,
							success: function(data) {
								if(data=='success')
								{
									$('#lasttd').append('<span style="margin-left: 140px;">Datos guardados con e&acute;xito.<span/><a href="<?php echo base_url();?>Carta/generadoc?id_user=<?=$id_socio?>" target="_Blank" style="margin-left: 10px;" name="pdf" type="button" class="btna btn btn-primary" id="pdf">Imprimir Comprobante<a/>');
									$('#savedata').attr("disabled","disabled");
									$('#savedata').removeClass('btn-primary');
									$('#savedata').addClass('disabled');
									$('#rep').attr("disabled","disabled");
									$('#motivo').attr("disabled","disabled");
									$('#direccion').attr("disabled","disabled");
								}
							}
						});
					$('#loadingDiv').hide(); // hide loading div
				}
				else
				{
					$(this).closest('div').removeClass('success').addClass('error');
					alert('Todos los campos son obligatorios.');
				}
			}			
			});
			$("#rep").click(function(event){
			 if($(this).is(":checked")) {
				$("#f1").show();
				$("#f2").show();
				$("#f3").show();
			 }else{
				$("#f1").hide();
				$("#f2").hide();
				$("#f3").hide();
			 }
		   });
		   $('#rutrep').Rut({
				on_error: function(){
					alert('Rut incorrecto');
					$('#rutrep').val('');
					$('#rutrep').focus();
				}
			});	
        });
    </script>
</body>
</html>