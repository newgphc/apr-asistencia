<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class InicioModel extends CI_Model
{

    //Lista todos los clientes
    public function getCliente($term)
	{
        $this->db->reconnect();
        return $this->db->query("CALL pa_socio_lista_nombre('".$term."');");
    }

    //Busca un Cliente especifico
    public function saveCliente($clienteData)
    {
        $this->db->reconnect();
        $this->db->query('call pa_socio_ingresa(\''.$clienteData['COD'].'\',\''.$clienteData['RUT'].'\',\''.$clienteData['DIG'].'\',\''.$clienteData['NOMBRE'].'\',\''.$clienteData['APELLIDO'].'\',\''.$clienteData['DIR'].'\',\'0\',\'0\')');
    }


    public function TraeDatos($dato='', $tipo=0)
    {
        $this->db->reconnect();
        $var = 'CALL ';
        $var .= ($tipo == 0) ? 'busca_por_rut_final' : 'busca_por_nombre_final';
        $var .= '(\''.$dato.'\')';
        //echo $var;
        $query = $this->db->query($var)->result_object();
        $result = new StdClass();
        $result->nfilas = sizeof($query);
        foreach ($query as $row)
        {
            $result->id_ai_soc = $row->id_ai_soc;
            $result->rut = number_format($row->rut,0,',', '.').'-'.$row->dv;
            $result->nombre = $row->nombre;
            $result->asis_soc = $row->asis_soc;
            $result->nrep = $row->nrep;
            $result->rtrep = $row->rtrep !== null ? number_format($row->rtrep,0,',', '.') : '';
            $result->dvrtrep = $row->dvrtrep;
            $result->asist_final = $row->asist_final;
        }
        return $result;
    }

    public function insertaAsistencia($iduser, $id_genero=0)
    {
      $this->db->reconnect();
      $this->db->query("call registra_asistencia('".$iduser."','".$id_genero."');");
    }
}
