<?php $this->load->view('partial/header');?>
<div class="btn-group" style="float: right; margin-top: 20px;">
    <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
      <?=$nombre?>
      <span class="caret"></span>
    </button>
    <ul class="dropdown-menu">
	  <li><a href="<?php echo base_url();?>login/logout">Salir</a></li>
    </ul>
  </div>
<div class="row">
    <div class="col-md-4">
        <img src="assets/Content/logo.png" />
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="col-md-4">
            <form name="form1" id="form1" action="#">
                <div class="form-group col-lg-10">
                    <label for="rut">Rut</label>
                    <div class="input-group">
                        <input type="text" name="rut" autocomplete="off" id="rut" class="form-control">
                        <span class="input-group-btn">
                            <button class="btn btn-default" name="btnrut" id="btnrut" type="submit"><span class="glyphicon glyphicon-search"></span></button>
                        </span>
                    </div>
                </div>
            </form>
        </div>
        <div class="col-md-2">

        </div>
        <div class="col-md-6">
            <div class="form-group col-lg-6">
                <label for="nombre">Nombre</label>
                <div class="input-group">
                    <input type="text" name="nombre" autocomplete="off" id="nombre" class="form-control">
                    <span class="input-group-btn">
                        <button class="btn btn-default" type="button" onclick="location.href='<?php echo base_url();?>Carta/Index'"><span class="glyphicon glyphicon-remove"></span></button>
                    </span>
                </div>
                <div id="suggestions"></div>
            </div>
        </div>
    </div>
</div>
<row>
    <div class="col-lg-12">
        <div id="tabla"></div>
    </div>
</row>
<?php $this->load->view('partial/footer-carta');?>