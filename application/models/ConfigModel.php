<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class ConfigModel extends CI_Model
{

    //Lista todos los clientes
    public function getSocios()
	{
        $params = $this->CartaModel->loadParams();

        $sqlserverhos = $this->load->database('hospital', TRUE);
        $sqlservercha = $this->load->database('champa', TRUE);
        $lista = [];
        $lista = $sqlserverhos->query("select Codigo as 'COD', Rut as 'RUT', Dig as 'DIG', Nombre as 'NOMBRE', Paterno + ' ' + Materno as 'APELLIDO', PropCalle + ' ' + PropNumero as 'DIR' from Clientes where Situacion <> 3 and Situacion <> 5")->result_array();
        return array_merge($lista, $sqlservercha->query("select Codigo as 'COD', Rut as 'RUT', Dig as 'DIG', Nombre as 'NOMBRE', Paterno + ' ' + Materno as 'APELLIDO', PropCalle + ' ' + PropNumero as 'DIR' from Clientes where Situacion <> 3 and Situacion <> 5")->result_array());
    }

    public function getSetUsuarios()
	{
        //Borramos la tabla Usuarios para ingresarlos actualizados
        $this->db->reconnect();
        $this->db->empty_table('usuarios');
        $sqlserverhos = $this->load->database('hospital', TRUE);
        $Usuarios = $sqlserverhos->query("SELECT Usuario, Nombre, Pass, Email FROM Usuarios;")->result_object();

        $usuario['Usuario'] = 'Admin';
        $usuario['Nombre'] = 'Administrador';
        $usuario['Pass'] = 'e3d49eb7d5bd443589ed96f77b2e9a1c2504b359d5c200c1401a2266065a8856';
        $usuario['Email'] = 'jose.scream17@gmail.com';
        $usuario['Admin'] = 1;
        //Ingresamos a traves del Array $usuario los datos del usuario actual a la tabla usuarios
        $this->db->reconnect();
        $this->db->insert('usuarios', $usuario);
        //Ingresamos los usuarios uno por uno
        foreach($Usuarios as $Usuario)
        {
            $usuario['Usuario'] = $Usuario->Usuario;
            $usuario['Nombre'] = utf8_encode($Usuario->Nombre);
            //Hasheamos la Pass par guardarla encriptada en la base de datos
            $usuario['Pass'] = hash('sha256', trim($Usuario->Pass));
            $usuario['Email'] = $Usuario->Email;
            $usuario['Admin'] = 0;
            //Ingresamos a traves del Array $usuario los datos del usuario actual a la tabla usuarios
            $this->db->reconnect();
            $this->db->insert('usuarios', $usuario);
        }
    }

    //Busca un Cliente especifico
    public function saveCliente($clienteData)
    {
        $this->db->reconnect();
        $this->db->query('call pa_socio_ingresa(\''.$clienteData['COD'].'\',\''.$clienteData['RUT'].'\',\''.$clienteData['DIG'].'\',\''.$clienteData['NOMBRE'].'\',\''.$clienteData['APELLIDO'].'\',\''.$clienteData['DIR'].'\',\'0\',\'0\')');
    }

    public function loadListaImpresoras()
    {
        $this->db->reconnect();
        $result = $this->db->query("CALL lista_impresoras()")->result_object();
        return $result;
    }

    public function getPrinterById($id=0)
    {
        $this->db->reconnect();
        $result = $this->db->query("SELECT value_impresora FROM impresoras WHERE id_impresora = ".$id)->first_row();
        return $result;
    }

    public function deletePrint($idprint=0)
    {
        $this->db->reconnect();
        $result = $this->db->query("delete from impresoras where id_impresora = '".$idprint."'");
        return $result;
    }

    public function addPrint($data)
    {
        $this->db->reconnect();
        $this->db->insert('impresoras', $data);
    }

    public function getNombreUser($id)
    {
        $this->db->reconnect();
        $this->db->from('usuarios');
        $this->db->where('id_user', $id);
        return $this->db->get()->result_object()[0]->Nombre;
    }
}
