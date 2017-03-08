<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Config extends CI_Controller {

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
		if($this->session->userdata('admin'))
		{
			$data['exito']=false;
			$data['borraex']=false;
			$data['nombre'] = $this->ConfigModel->getNombreUser($this->session->userdata('id_user'));
			$boton = ($this->input->post('save') !== null) ? $this->input->post('save') : '';
			$dir=($this->input->post('dir') !== null) ? $this->input->post('dir') : '';
			$fecha=($this->input->post('fec') !== null) ? $this->input->post('fec') : '';
			$hora=($this->input->post('hora') !== null) ? $this->input->post('hora') : '';
			$empresa=($this->input->post('empresa') !== null) ? $this->input->post('empresa') : '';
			$ticket_title = ($this->input->post('ticket_title') !== null) ? $this->input->post('ticket_title') : '';
			$ticket_footer_text = ($this->input->post('ticket_footer_text') !== null) ? $this->input->post('ticket_footer_text') : '';

			if($boton=='Guardar')
			{
				if($this->db->query("CALL pa_ingresa_datos_evento('".$dir."', '".$fecha."', '".$hora."', '".$empresa."', '".$ticket_title."', '".$ticket_footer_text."')") == true)
				{
					$data['exito']=true;
				}
			}

			$btnAddPrint = ($this->input->post('guarda_impresora') !== null) ? $this->input->post('guarda_impresora') : '';

			if($btnAddPrint == "Agregar")
			{
				$dataPrint['value_impresora']=($this->input->post('ip_print') !== null) ? $this->input->post('ip_print') : '';
				$dataPrint['label_impresora']=($this->input->post('nombre_print') !== null) ? $this->input->post('nombre_print') : '';
				$this->ConfigModel->addPrint($dataPrint);
				redirect('Config#impresoras');
			}

			$resetsys=($this->input->post('resetsys') !== null) ? $this->input->post('resetsys') : '';
			if($resetsys=='Reestablecer')
			{
				if($this->db->query("CALL pa_reset_system()") == true)
				{
					$this->getSocios();
					$data['borraex']=true;
				}
			}

			$data['parametros'] = $this->CartaModel->loadParams();
			$data['listaImpr'] = $this->ConfigModel->loadListaImpresoras();
			$this->load->View('config/index', $data);
		}
		else
		{
			redirect('carta');
		}
	}

	public function deleteprint()
	{
		if((null !== $this->session->set_userdata()) && $this->session->set_userdata('admin'))
		{
			$idprint = (null !== $this->input->get('idprint') && is_numeric($this->input->get('idprint')) ) ? $this->input->get('idprint') : '';
			$this->ConfigModel->deletePrint($idprint);
			redirect('Config#impresoras');
		}
		else
		{
			show404();
		}
	}


	private function getSocios()
	{
		$listaClientes = $this->ConfigModel->getSocios();
		foreach($listaClientes as $cliente)
		{
			$this->db->reconnect();
			$this->ConfigModel->saveCliente($cliente);
		}
		$this->ConfigModel->getSetUsuarios();
	}
}
