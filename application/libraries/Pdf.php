<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
    // Incluimos el archivo fpdf
    require_once APPPATH."/third_party/fpdf/fpdf.php";
 
    //Extendemos la clase Pdf de la clase fpdf para que herede todas sus variables y funciones
    class Pdf extends FPDF 
    {
        public $numdoc;

        public function __construct() 
        {
            parent::__construct();
        }

        public function setNumDoc($numdoc)
        {
            $this->numdoc = $numdoc;
        }

        // Cabecera de página
        function Header()
        { 
            // Logo
            $this->Image(base_url().'assets/Content/logo.png',10,8,80);
            // Arial bold 15
            $this->SetFont('Arial','B',15);
            // Movernos a la derecha
            $this->Cell(80);
            // Posición: a 1,5 cm del final
            $this->SetY(30);
            // Arial italic 8
            $this->SetFont('Arial','I',9);
            // Número de página
            $this->Cell(0,0,'Cooperativa de AguaPotableHospital- Champa',0,0,'L');
            $this->Ln(5);
            $this->Cell(0,0,'Rut '.utf8_decode('N°').' 70.025.350-3',0,0,'L');
            $this->Ln(5);
            $this->Cell(0,0,'Pasaje La Copa '.utf8_decode('N°').' 7 Hospital',0,0,'L');
            $this->Ln(5);
            $this->Cell(0,0,'Paine',0,0,'L');
            $this->SetY(30);
            $this->Cell(0,0,'Solicitud '.utf8_decode('N°').': '.$this->numdoc,0,0,'R');
            $this->Ln(30);
        }

        // Pie de página
        function Footer()
        {
            // Posición: a 1,5 cm del final
            $this->SetY(-15);
            // Arial italic 8
            $this->SetFont('Arial','I',8);
            // Número de página
            $this->Cell(0,10,'Pagina '.$this->PageNo().'/{nb}',0,0,'C');
        }
    }
?>