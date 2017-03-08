<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class CartaModel extends CI_Model 
{
    public function loadDatos($dato='', $tipo=0)
    {        	
        $this->db->reconnect();
        $var = 'CALL ';
        $var .= ($tipo == 0) ? 'pa_socio_busca' : 'pa_socio_busca_id';
        $var .= '(\''.$dato.'\')';
        //echo $var;
        $query = $this->db->query($var)->result_object();
        return $query;
    }

    public function loadSuscripciones($idSocio='')
    {
        $this->db->reconnect();
        return $query = $this->db->query("CALL pa_socio_busca_susc_id('".$idSocio."')");
    }

    public function loadParams()
    {	
        $this->db->reconnect();
        $result = $this->db->query("CALL pa_param_evento()")->result_object()[0];
        return $result;
    }

    public function loadDatosCarta($id)
    {
        $this->db->reconnect();
        $query = $this->db->query("CALL pa_busca_datos_carta('" . $id . "')")->result_object()[0];
        return $query;
    }
}
