
        <hr />
        <footer>
            <p>&copy; <?=date('Y')?> - Cooperativa Hospital Champa Limitada</p>
        </footer>
    </div>
    <script type="text/javascript" src="assets/Scripts/jquery-1.10.2.min.js"></script>
    <script type="text/javascript" src="assets/Scripts/jquery.Rut.min.js"></script>
    <script type="text/javascript" src="assets/Scripts/jquery-ui.js"></script>
    <script type="text/javascript" src="assets/Scripts/jquery.fancybox.js?v=2.1.5"></script>    
    <script type="text/javascript" src="assets/build/toastr.min.js"></script>
    <script type="text/javascript">

        function validaGenero() {
            if ((document.getElementById('masculino').checked == false) && (document.getElementById('femenino').checked == false)) {
                toastr.error('Debes seleccionar el genero del Socio / Representante!', 'Error');
                return false;
            }
        }

        $(document).ready(function () {
        <?php if($isprint){?>
        $.fancybox({
            closeClick  : false, // prevents closing when clicking INSIDE fancybox 
            href: "<?php echo base_url().'inicio/selectprint';?>", 
            type: "iframe",
            'showCloseButton':false,
            helpers   : { 
                overlay : {closeClick: false} // prevents closing when clicking OUTSIDE fancybox 
            }
        });
        <?php } ?>

        setInterval(function () {
            $.ajax({
                type: "POST",
                url: "<?php echo base_url(); ?>/inicio/mantiene",
                data: "mantener=manten",
                success: function (data) {
                    console.log(data);
                }
            });
            // refresh list
        }, 60000);
        });

        $(document).ready(function () {

            function cargaRut(rut)
            {
                if(rut.length > 0)
                {
                    rutfinal = rut.replace(".", "");
                    rt = rutfinal.replace(".", "");
                    rt = rt.substring(0, rt.length - 1);
                    var dataID = 'rut=' + rt;
                    var datossocio = '';
                    var datosfinales = '';
                    var datosrep = '';
                    var tabla = '';
                    $.ajax({
                        type: "GET",
                        url: "<?php echo base_url(); ?>Inicio/getByRut",
                        data: dataID,
                        success: function (data) {
                            if (data.nfilas > 0) {
                                $('#nombre').val('');
                                datossocio = '<form id="frmcnfrm" method="POST" action="/Home/ImprimeGuarda" onSubmit="return validaGenero();"><table class="table table-bordered table-striped"><tr><td colspan="2"><strong>Datos personales del Socio</strong></td></tr><tr><td width="42%">Rut</td><td width="58%">' + data.rut + '<input type="hidden" name="rut" value="' + data.rut + '"/></td></tr><tr><td width="42%">Nombre completo</td><td width="58%">' + data.nombre + '<input type="hidden" name="nombre" value="' + data.nombre + '"/></td></tr>';
                                datosfinales = '<tr><td width="42%">Genero del socio / Representante</td><td width="58%"></td></tr><tr><td><input type="hidden" name="iduser" value="' + data.id_ai_soc + '"/><input type="radio" name="sex" id="masculino" value="1"><label for="masculino">Masculino</label><input type="radio" name="sex" id="femenino" style="margin-left: 40px;" value="2"><label for="femenino">Femenino</label></td><td><input type="Submit" id="confrm" name="confrm" class="btna btn btn-primary" style="margin-top: -7px;margin-left: 18px;position: absolute;" value="Confirmar asistencia"/></td></tr></table></form>';
                                if (data.asis_soc == 0) {
                                    tabla = datossocio + datosfinales;
                                }
                                else if (data.asis_soc == 1) {
                                    tabla = '<div class="alert alert-info"><strong>Importante!.</strong> El socio ' + data.nombre + ', Ha justificado su ausencia a esta reunion y por tanto no se puede registrar su asistencia.</div>';
                                }
                                else if (data.asis_soc == 2) {
                                    datosrep = '<td colspan="2"><strong>Datos personales del Representante</strong></td></tr><tr><td width="42%">Rut</td><td width="58%">' + data.rtrep + '-' + data.dvrtrep + '</td></tr><tr><td width="42%">Nombre completo</td><td width="58%">' + data.nrep + '</td></tr>';
                                    tabla = datossocio + datosrep + datosfinales;
                                }
                                else {
                                    tabla = '<div class="alert alert-info"><strong>Error!.</strong> Se ha producido un error al buscar.</div>';
                                }
                                $('#tabla').fadeIn(1000).html(tabla);
                            }
                            else {
                                $('#tabla').fadeIn(1000).html('<div class="alert alert-info"><strong>No se ha encontrado coincidencia.</strong> Tal vez digitaste un rut que no pertenece a un socio de la cooperativa.</div>');
                            }
                        }
                    });
                }
                else
                {                    
                    $('#rut').focus();
                }
            }
            
            /*$('#btnrut').on('click', function () {
                var rut = $('#rut').val().replace("-", "");
                cargaRut(rut);
            });*/
            

            $('#rut').Rut({
                on_error: function () {
                    toastr.error('Debe ingresar un Rut Válido!', 'Error');
                    $('#rut').val('');
                    $('#rut').focus();
                }
                //format_on: 'keyup' 
            });

            $('#form1').on('submit', function (e) {
                e.preventDefault();
                var rut = $('#rut').val().replace("-", "");
                cargaRut(rut);// enter pressed
            });
        });
        $(function () {
            $("#nombre").autocomplete({
                source: function (request, response) {
                    $.ajax({
                        url: "<?php echo base_url(); ?>Inicio/autocomplete",
                        type: "GET",
                        data: request,
                        success: function (data) {
                            response($.map(data, function (el) {
                                return {
                                    label: el.label,
                                    value: el.value
                                };
                            }));
                        }
                    });
                },
                select: function (event, ui) {
                    event.preventDefault();
                    $("#nombre").val(ui.item.label);
                    var dataID = 'id=' + ui.item.value;
                    var datossocio = '';
                    var datosfinales = '';
                    var datosrep = '';
                    var tabla = '';
                    $.ajax({
                        type: "GET",
                        url: "<?php echo base_url(); ?>Inicio/getByName",
                        data: dataID,
                        success: function (data) {
                            if (data.nfilas > 0) {
                                $('#nombre').val('');
                                datossocio = '<form id="frmcnfrm" method="POST" action="/Home/ImprimeGuarda" onSubmit="return validaGenero();"><table class="table table-bordered table-striped"><tr><td colspan="2"><strong>Datos personales del Socio</strong></td></tr><tr><td width="42%">Rut</td><td width="58%">' + data.rut + '<input type="hidden" name="rut" value="' + data.rut + '"/></td></tr><tr><td width="42%">Nombre completo</td><td width="58%">' + data.nombre + '<input type="hidden" name="nombre" value="' + data.nombre + '"/></td></tr>';
                                datosfinales = '<tr><td width="42%">Genero del socio / Representante</td><td width="58%"></td></tr><tr><td><input type="hidden" name="iduser" value="' + data.id_ai_soc + '"/><input type="radio" name="sex" id="masculino" value="1"><label for="masculino">Masculino</label><input type="radio" name="sex" id="femenino" style="margin-left: 40px;" value="2"><label for="femenino">Femenino</label></td><td><input type="Submit" id="confrm" name="confrm" class="btna btn btn-primary" style="margin-top: -7px;margin-left: 18px;position: absolute;" value="Confirmar asistencia"/></td></tr></table></form>';
                                if (data.asis_soc == 0) {
                                    tabla = datossocio + datosfinales;
                                }
                                else if (data.asis_soc == 1) {
                                    tabla = '<div class="alert alert-info"><strong>Importante!.</strong> El socio ' + data.nombre + ', Ha justificado su ausencia a esta reunion y por tanto no se puede registrar su asistencia.</div>';
                                }
                                else if (data.asis_soc == 2) {
                                    datosrep = '<td colspan="2"><strong>Datos personales del Representante</strong></td></tr><tr><td width="42%">Rut</td><td width="58%">' + data.rtrep + '-' + data.dvrtrep + '</td></tr><tr><td width="42%">Nombre completo</td><td width="58%">' + data.nrep + '</td></tr>';
                                    tabla = datossocio + datosrep + datosfinales;
                                }
                                else {
                                    tabla = '<div class="alert alert-info"><strong>Error!.</strong> Se ha producido un error al buscar.</div>';
                                }
                                $('#tabla').fadeIn(1000).html(tabla);
                            }
                            else {
                                $('#tabla').fadeIn(1000).html('<div class="alert alert-info"><strong>No se ha encontrado coincidencia.</strong> Tal vez digitaste un rut que no pertenece a un socio de la cooperativa.</div>');
                            }
                        }
                    });
                },
                focus: function (event, ui) {
                    event.preventDefault();
                    $("#nombre").val(ui.item.label);
                }
            });

        });
    </script>
</body>
</html>
