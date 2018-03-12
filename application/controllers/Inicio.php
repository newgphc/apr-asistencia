<?php
		use Mike42\Escpos\PrintConnectors\NetworkPrintConnector;
		use Mike42\Escpos\CapabilityProfile;
		//use Mike42\Escpos\EscposImage;
		use Mike42\Escpos\Printer;
defined('BASEPATH') OR exit('No direct script access allowed');

class Inicio extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/user_guide/general/urls.html
	 */

	public function __construct() {
       parent::__construct();
	   $this->load->library('session');
    }

	public function index()
	{
		//var_dump($this->session->userdata());
		$data['isprint'] = (null !== $this->session->userdata('id_print')) ? false : true;
		$this->load->view('inicio/index', $data);
	}

	public function mantiene()
	{
		$mantener = (null !== $this->input->post('mantener')) ? $this->input->post('mantener') : '';
		header("Content-Type:text/plain");
		echo ($mantener == 'manten') ? 'Manteniendo la session...' : 'error';
	}

	public function print()
	{
			$printer_id = $this->session->userdata('id_print');
			$printer_ip = $this->ConfigModel->getPrinterById($printer_id);

			if(null !== $printer_ip)
			{
					$id_socio = $this->input->post('iduser');
					$genero = $this->input->post('sex');
					$asis = $this->input->post('tasis');
					$rut = $this->input->post('rut');
					$nombre = $this->input->post('nombre');
					$rutrep = $this->input->post('rutrep');
					$nombrep = $this->input->post('nrep');

					$printer_ip = $printer_ip->value_impresora;

					$connector = new NetworkPrintConnector($printer_ip, 9100);
					$profile = CapabilityProfile::load("simple");
					$printer = new Printer($connector,$profile);

					try
					{
							$printer->text("************************************************\n\n");
							$printer->text("              Ticket de asistencia\n");
							$printer->text("           Junta Anual de Socios " . date('Y') . "\n\n");
							$printer->text("************************************************\n\n");
							$printer->text("   Fecha:  " .date("d-m-Y"). "\n");
							$printer->text("   Hora:  " . date("H:i:s"). "\n");
							//$printer->text("   Numero de cliente:   " .$numero. "\n");
							$printer->text("   Rut socio:   " . $rut."\n");
							$printer->text("   Nombre: " . chunk_split($nombre, 34, "\n   ")."\n");
							//$printer->text("   Direccion: " . chunk_split(utf8_decode($direccion), 31, "\n   ")."\n");

							if($asis == 2)
				      {
									$printer->text("   Rut representante:  " . $rutrep . "\n");
								 	$printer->text("   Nombre representante: " . chunk_split($nombrep, 20, "\n   ") . "\n\n");
				      }

							$printer->text("************************************************\n\n");
							$printer->text("      ***     Valido para el sorteo     ***     \n\n");
							$printer->text("************************************************\n");

							$printer->feed(3);
							$printer->cut(Printer::CUT_PARTIAL);
							$this->InicioModel->insertaAsistencia($id_socio, $genero);
					}
					finally {
							$printer->close();
					}
			}
			//header("Content-Type:application/json");
			//echo json_encode($response);
			redirect('inicio');
	}

	public function test()
	{
			$connector = new NetworkPrintConnector('192.168.1.110', 9100);
			$profile = CapabilityProfile::load("simple");
			$printer = new Printer($connector,$profile);
			$printer -> text("Hello World!\n");
			$printer -> cut();
			$printer -> close();
	}

	public function selectprint()
	{
		$data['printers']=$this->ConfigModel->loadListaImpresoras();
		$this->load->view('inicio/selprint', $data);
	}

	public function guardaprint()
	{
		$guardar = ($this->input->post('guardar')) ? $this->input->post('guardar'): '';

		if($guardar == 'Ingresar')
		{
			$selectprint = (null != $this->input->post('selprint') && $this->input->post('selprint') != '-1') ? $this->input->post('selprint') : null ;
			$this->session->set_userdata('id_print', $selectprint);
			var_dump('selectprint'.$selectprint);
		}
	}

	public function cambiaprint()
	{
			$this->session->unset_userdata('id_print');
			redirect('inicio');
	}

	public function autocomplete()
	{
		$myArray = array();
		$result = $this->InicioModel->getCliente($this->input->get('term'))->result_object();

        foreach($result as $row)
		{
            $tempArray = array(
								'label'=>$row->nombre_completo,
								'value'=>$row->id_ai_soc
								);
                			array_push($myArray, $tempArray);
        }

		header('Content-type: application/json');
        echo json_encode($myArray);
	}

	public function getByRut()
	{
		$value = ($this->input->get('rut') !== null) ? $this->input->get('rut') : '';
		header('Content-type: application/json');
        echo json_encode($this->InicioModel->TraeDatos($value, 0));
	}

	public function getByName()
	{
		$value = ($this->input->get('id') !== null) ? $this->input->get('id') : '';
		header('Content-type: application/json');
        echo json_encode($this->InicioModel->TraeDatos($value, 1));
	}
}
