
        <hr />
        <footer>
            <p>&copy; <?=date('Y')?> - Cooperativa Hospital Champa Limitada</p>
        </footer>
    </div>
    <script type="text/javascript" src="assets/Scripts/jquery-1.10.2.min.js"></script>
    <script type="text/javascript" src="assets/Scripts/bootstrap.min.js"></script>
    <script type="text/javascript" src="assets/Scripts/jquery.Rut.min.js"></script>
    <script type="text/javascript" src="assets/Scripts/jquery-ui.js"></script>
    <script type="text/javascript" src="assets/Scripts/jquery.fancybox.js?v=2.1.5"></script>    
    <script type="text/javascript" src="assets/build/toastr.min.js"></script>
    <script type="text/javascript">
    $(document).ready(function() {   
        $(".fancybox").fancybox({
            'beforeClose': function() { $('#tabla').fadeOut(1000) }
        }); 
        function cargaRut(rut){
            if(rut.length > 0)
            {
                rutfinal = rut.replace(".","");
                rt = rutfinal.replace(".","");
                rt = rt.substring(0, rt.length - 1);
                var dataID = 'rut='+rt;
                $.ajax({
                    type: "GET",
                    url: "<?php echo base_url();?>Carta/loadByRut",
                    data: dataID,
                    success: function(data) {
                        $('#nombre').val('');
                        //Escribimos las sugerencias que nos manda la consulta
                        $('#tabla').fadeIn(1000).html(data);
                    }
                });
            }
            else
            {                    
                $('#rut').focus();
            }
        }
        /*$('#btnrut').on('click', function(){	
            var rut = $('#rut').val().replace("-","");
            cargaRut(rut);
        });*/
        $('#rut').Rut({
            on_error: function(){
                toastr.error('Debe ingresar un Rut Válido!', 'Error');
                $('#rut').val('');
                $('#rut').focus();
            }
        });
        
        $('#form1').on('submit', function(e){
            e.preventDefault();
            var rut = $('#rut').val().replace("-","");
            cargaRut(rut);
        });

        $("#nombre").autocomplete({
            source: function (request, response) {
                $.ajax({
                    url: "<?php echo base_url();?>Inicio/autocomplete",
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
            select: function(event, ui) {
                event.preventDefault();
                $("#nombre").val(ui.item.label);
                var dataID = 'id=' + ui.item.value;
                    $.ajax({
                        type: "GET",
                        url: "<?php echo base_url();?>Carta/loadByName",
                        data: dataID,
                        success: function(data) {
                            $('#rut').val('');
                                //Escribimos las sugerencias que nos manda la consulta
                                $('#tabla').fadeIn(1000).html(data);
                            }
                        });
            },
            focus: function(event, ui) {
                event.preventDefault();
                $("#nombre").val(ui.item.label);
            }
        });
    });
</script>
</body>
</html>
