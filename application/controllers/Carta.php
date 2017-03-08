<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Carta extends CI_Controller {

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
        if($this->session->userdata('logged_in'))
		{
            $this->db->reconnect();
            $data['nombre'] = $this->ConfigModel->getNombreUser($this->session->userdata('id_user'));
		    $this->load->view('carta/index', $data);
        }
		else
		{
			redirect('login');
		}
	}

	public function autocomplete()
	{
        if($this->session->userdata('logged_in'))
		{
            $myArray = array();
            $this->db->reconnect();
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
		else
		{
			show404();
		}
	}

	public function loadByRut()
	{
        if($this->session->userdata('logged_in'))
		{
            $value = ($this->input->get('rut') !== null) ? $this->input->get('rut') : '';
            $this->db->reconnect();
            $this->creaHtml($this->CartaModel->loadDatos($value, 0));
        }
		else
		{
			show404();
		}
	}

    private function creaHtml($datos)
    {
        header('Content-type: text/html');
        if(sizeof($datos) > 0) {
            $row_services = $datos[0];
            $id_cli=$row_services->id_ai_soc;
            if($row_services->asis_soc==0)
            {
                $asis = 'Asistira  <a  href="'.base_url().'Carta/ingresacarta?id='.$id_cli.'" class="btna btn btn-primary fancybox fancybox.iframe" style="margin-top: -7px;margin-left: 18px;position: absolute;">Asignar representante</a>';
            }
            else
            {
                $asis = 'No asistira <a  href="'.base_url().'Carta/generadoc?id_user='.$id_cli.'" target="_Blank" class="btna btn btn-primary" style="margin-top: -7px;margin-left: 18px;position: absolute;">Imprimir Comprobante</a> <a href="'.base_url().'Carta/reset_asoc?id_soc='.$id_cli.'" class="btna btn btn-primary" style="margin-top: -7px;margin-left: 195px;position: absolute;" onclick="return confirm(\'Estas seguro/a?\')">Reestablecer Asistencia</a>';
            }
            echo '<table class="table table-bordered table-striped">';
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
                echo '<td>Estado asistencia</td>';
                echo '<td>'.$asis.'</td>';
            echo '</tr>';
            echo '</table>'; 
        }else{
            echo '<div class="alert alert-info"><strong>No se ha encontrado coincidencia.</strong> Tal vez digitaste un rut que no pertenece a un socio de la cooperativa.</div>';
        }
    }

    public function ingresacarta()
    {
        if($this->session->userdata('logged_in'))
		{            
            $value = ($this->input->get('id') !== null) ? $this->input->get('id') : '';
            $this->db->reconnect();
            $data['datos'] = $this->CartaModel->loadDatos($value, 1);
            $this->db->reconnect();
            $data['suscripciones'] = $this->CartaModel->loadSuscripciones($value)->result_object();
            $this->load->View('carta/ingresa', $data);
        }
		else
		{
			show404();
		}
    }

    public function insertrep()
    {
        if($this->session->userdata('logged_in'))
		{
            $id_soc = ($this->input->post('id_soc') !== null) ? $this->input->post('id_soc') : '';
            $tcarta = ($this->input->post('tcarta') !== null) ? $this->input->post('tcarta') : '';
            $rut_soc = ($this->input->post('rut_soc') !== null) ? $this->input->post('rut_soc') : '';
            $nomb_soc = ($this->input->post('nomb_soc') !== null) ? $this->input->post('nomb_soc') : '';
            $motivo = ($this->input->post('motivo') !== null) ? $this->input->post('motivo') : '';
            $direccion = ($this->input->post('direccion') !== null) ? $this->input->post('direccion') : '';
            $rut_soc = str_replace ('-', '', $rut_soc);
            $rut_soc = str_replace ('.', '', $rut_soc);
            $rut = substr ($rut_soc, 0,  - 1);
            $dvrt = substr ($rut_soc,  - 1);
            //echo "CALL pa_socio_actualiza_asist('".$id_soc."', '".$tcarta."', '".$rut."', '".$dvrt."', '".$nomb_soc."', '".$motivo."', '".$direccion."')";
            $this->db->reconnect();
            if($this->db->query("CALL pa_socio_actualiza_asist('".$id_soc."', '".$tcarta."', '".$rut."', '".$dvrt."', '".$nomb_soc."', '".$motivo."', '".$direccion."')") == true)
            {
                echo 'success';
            }
            else
            {
                echo 'error';
            }
        }
		else
		{
			show404();
		}  
    }

    public function generadoc()
    {
        if($this->session->userdata('logged_in'))
		{
            $this->pdf = new Pdf();
            $this->db->reconnect();
            $parametros = $this->CartaModel->loadParams();

            $id = ($this->input->get('id_user') !== null) ? $this->input->get('id_user') : '';
            $this->db->reconnect();
            $datosCarta = $this->CartaModel->loadDatosCarta($id);

            if($datosCarta->tpcrt == 1)
            {
                $this->genAviso($datosCarta, $parametros);
            }

            if($datosCarta->tpcrt == 2)
            {
                $this->genPoder($datosCarta, $parametros);
            }        

            $this->pdf->Output(uniqid().'-'.$datosCarta->id.'.pdf', 'I');
        }
		else
		{
			show404();
		}  
    }

    private function genAviso($datosCarta, $parametros)
    {
        $this->pdf->setNumdoc($datosCarta->id);
        $this->pdf->AliasNbPages();
        $this->pdf->AddPage();
        $this->pdf->SetFont('Times','',14);

        $this->pdf->SetFont('Arial','U'); 
        $this->pdf->Cell(180,5,'C A R T A  A V I S O',0,0,'C');
        $this->pdf->Ln(15);
        $this->pdf->SetFont('Arial');       

        $this->load->helper('general');

        //hora_evento
        $this->pdf->MultiCell(190, 7,utf8_decode('Yo, '.$datosCarta->nombre.' cédula de identidad N° '.number_format($datosCarta->rut,0, ",", ".").'-'.$datosCarta->dv.' en mi calidad de socio de '.$parametros->nombre_empresa.', domiciliado en , '.$datosCarta->direccion.'. comunico al Honorable Directorio que no podré asistir a la Asamblea General Ordinaria de Socios fijada para el día '.dateToFormat(strtotime($parametros->fecha_evento)).', en '.$parametros->direccion_evento.', por motivos De '.$datosCarta->motivo.', Sin otro particular, saluda atentamente a usted,'),0,1);
        $this->pdf->Ln(10);
        $this->pdf->SetFont('Arial'); //Where "U" means underline.
        $this->pdf->Cell(20,5,utf8_decode($datosCarta->nomb_soc),0,0,'L');
        $this->pdf->SetFont('Arial','U'); //Where "U" means underline.
        $this->pdf->Cell(150,5,'                                       ',0,1,'R');
        $this->pdf->Cell(20,5,utf8_decode($datosCarta->apell_soc),0,0,'L');
    }

    private function genPoder($datosCarta, $parametros)
    {
        $this->pdf->setNumdoc($datosCarta->id);
        $this->pdf->AliasNbPages();
        $this->pdf->AddPage();
        $this->pdf->SetFont('Times','',14);

        $this->pdf->SetFont('Arial','U'); 
        $this->pdf->Cell(180,5,'C A R T A  P O D E R',0,0,'C');
        $this->pdf->Ln(15);
        $this->pdf->SetFont('Arial');     

        $this->load->helper('general');

        $this->pdf->MultiCell(190, 7, utf8_decode('Yo, '.$datosCarta->nombre.' cédula de identidad N° '.number_format($datosCarta->rut,0, ",", ".").'-'.$datosCarta->dv.' en mi calidad de socio de '.$parametros->nombre_empresa.', domiciliado en , '.$datosCarta->direccion.'. Autorizo a '.$datosCarta->nomb_rep_soc.' cédula de identidad N°'.number_format($datosCarta->rut_rep_soc,0, ",", ".").'-'.$datosCarta->dvrep.' para que asista en mi representación con derecho a voz a la Asamblea General Ordinaria de Socios fijada para el día '.dateToFormat(strtotime($parametros->fecha_evento)).', en '.$parametros->direccion_evento.', Sin otro particular, saluda atentamente a usted,'),0,1);
        $this->pdf->Ln(10);
        $this->pdf->SetFont('Arial'); //Where "U" means underline.
        $this->pdf->Cell(20,5,utf8_decode($datosCarta->nomb_soc),0,0,'L');
        $this->pdf->SetFont('Arial','U'); //Where "U" means underline.
        $this->pdf->Cell(150,5,'                                       ',0,1,'R');
        $this->pdf->Cell(20,5,utf8_decode($datosCarta->apell_soc),0,0,'L');
    }

    public function reset_asoc()
    {
        if($this->session->userdata('logged_in'))
		{
            $id_soc = ($this->input->get('id_soc') !== null) ? $this->input->get('id_soc') : '';
            $this->db->reconnect();
            if($this->db->query("CALL pa_socio_actualiza_asist_reset('".$id_soc."')") == true){
                redirect('Carta');
            }else{
                echo 'error';
            }
        }
		else
		{
			show404();
		}
    }

	public function loadByName()
	{
        if($this->session->userdata('logged_in'))
		{
            $value = ($this->input->get('id') !== null) ? $this->input->get('id') : '';
            $this->db->reconnect();
            $this->creaHtml($this->CartaModel->loadDatos($value, 1));
        }
		else
		{
			show404();
		}
	}
}
