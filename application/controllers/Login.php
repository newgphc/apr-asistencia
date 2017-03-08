<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends CI_Controller {

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
        if($this->session->userdata('id_user') === null)
		{
            $data = [];
            $data['errorlog']=false;
            $boton = (null !== $this->input->post('loged')) ? $this->input->post('loged') : '';
            $nick = (null !== $this->input->post('idusnick')) ? $this->input->post('idusnick') : '';
            $pass = (null !== $this->input->post('pwd')) ? $this->input->post('pwd') : '';
            if($boton=="logged")
            {
                $result = $this->db->query("CALL pa_login_user('".$nick."', '".hash('sha256', $pass)."')")->result_object();
                if(sizeof($result) > 0) 
                {
                    $newdata = array(
                            'id_user' => $result[0]->id_user,
                            'username'  => $result[0]->Nombre,
                            'logged_in' => TRUE,
                            'admin' => ($result[0]->Admin == 1) ? true : false
                    );

                    $this->session->set_userdata($newdata);

                    if($result[0]->Admin == 1)
                    {
                        redirect('config');
                    }
                    else
                    {
                        redirect('carta');
                    }
                }
                else
                {
                    $data['errorlog']=true;
                }
            }

            $this->load->view('login/index', $data);
        }
		else
		{
			redirect('login');
		}
    }

    public function logout()
    {
        if($this->session->userdata('id_user') !== null)
		{
            $this->session->sess_destroy();
        }
		redirect('login');
    }
}