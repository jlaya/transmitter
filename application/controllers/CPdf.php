
<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class CPdf extends CI_Controller {


    function __construct()
    {
        parent::__construct();
        $this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0');
        $this->output->set_header('Pragma: no-cache');

    }

    public function send_email($from, $to, $cc, $subject, $title, $content, $upload_pdf){
    
	    $date=date("Y-m-d");
	    $ip_server = $_SERVER['SERVER_ADDR'];
	    
	    $this->load->library('email');
	    $this->load->library('parser');
	    $config['protocol']    = 'smtp';
	    $config['smtp_host']    = 'ssl://mail.comgrap.store';
	    $config['smtp_port']    = '465';
	    $config['smtp_timeout'] = '7';
	    $config['smtp_user']    = 'compras@comgrap.store';
	    $config['smtp_pass']    = 'Postven2019.';
	    $config['charset']    = 'utf-8';
	    $config['newline']    = "\r\n";
	    $config['mailtype'] = 'html'; // or html
	    $config['validation'] = TRUE; // bool whether to validate email or not
	    $this->email->initialize($config);
	    $this->email->from("$from", "$title");
	    $this->email->to("$to");
	    $this->email->cc("$cc");
	    $this->email->subject("$subject");
	    $this->email->message($content);
        $this->email->attach("upload/$upload_pdf");

        if($this->email->send()){
	    	echo 'Email send.';
         } else {
         	echo $this->email->print_debugger();
        }
	}
	

	function Format_number($decimal)
    {
        $result = str_replace('', '', number_format($decimal, 2, ",", "."));
        return $result;
    }


    function cart_pdf(){



		
       /*$grand_total_price = $this->input->post('grand_total_price');
        $name=$this->input->post('name');
        $email=$this->input->post('email');
        $data_arrc=$this->input->post('data_arr');
        foreach ($data_arrc as $data_arr) {
            $valor=str_replace('\"','"',"$data_arr");
            $json[]=json_decode($valor);
		}*/


		$grand_total_price = 363625030;
		$name = "Jesus Laya";
		$email= "fserrano100@gmail.com";


		$data_arrc = array(

			array(
				'description' => "Revit 2020 Commercial New Single-user ELD Annual Subscription",
				'sku' => "829L1-WW2859-T981",
				'price' => "1892190",
			),
			array(
				'description' => "Civil 3D 2020 Commercial New Single-user ELD Annual Subscription",
				'sku' => "237L1-WW8695-T548",
				'price' => "1732840",
			)

		);




		

		require_once(APPPATH.'third_party/fpdf/fpdf.php');

		 // estas son las configuraciones para la generacion del PDF
		 // los detalles los encuentras en la pagina oficial
		 $this->pdf   = new FPDF($orientation = 'P', $unit        = 'mm', $format      = 'A4');
		// Agregamos una página
		$this->pdf->AddPage();
		// Define el alias para el número de página que se imprimirá en el pie
		$this->pdf->AliasNbPages();

		/* Se define el titulo, márgenes izquierdo, derecho y
		* el color de relleno predeterminado
		*/
		$this->pdf->SetTitle(utf8_decode("Cotización"));
		$this->pdf->SetLeftMargin(15);
		$this->pdf->SetRightMargin(15);
		$this->pdf->SetFillColor(139, 28, 28);
		// Se define el formato de fuente: Arial, negritas, tamaño 9
		$this->pdf->SetFont('Arial', 'B', 9);
		/*
		* TITULOS DE COLUMNAS
		*
		* $this->pdf->Cell(Ancho, Alto,texto,borde,posición,alineación,relleno);
		*/
		// El encabezado del PDF
		#$this->Image('imagenes/logo.png',10,8,22);
		$this->pdf->SetFont('Arial', 'B', 13);
		$this->pdf->Cell(30);
		$this->pdf->Cell(120, 10, utf8_decode('Cotización'), 0, 0, 'C');
		$this->pdf->SetFont('Arial', '', 13);
		$this->pdf->Ln(15);
		$this->pdf->Cell(120, 10, utf8_decode("Nombre: $name"), 0, 0, 'L');
		
		$this->pdf->SetFont('Arial', 'B', 8);
		$this->pdf->SetTextColor(0, 0, 0);  # COLOR DEL TEXTO
		$this->pdf->SetFillColor(255, 255, 255);


		$this->pdf->Ln(15);
		$this->pdf->SetFont('Arial', '', 8);
		$this->pdf->Cell(100, 5, utf8_decode('Descripción'), 'TBLR', 0, 'C', '1');
		$this->pdf->Cell(36, 5, 'SKU', 'TBLR', 0, 'C', '1');
		$this->pdf->Cell(36, 5, 'Precio', 'TBLR', 1, 'C', '1');

		foreach ($data_arrc as $value) {
			$this->pdf->SetFont('Arial', '', 8);
			$this->pdf->Cell(100, 5, utf8_decode($value['description']), 'TBLR', 0, 'C', '1');
			$this->pdf->Cell(36, 5, $value['sku'], 'TBLR', 0, 'C', '1');
			$this->pdf->Cell(36, 5, $this->Format_number($value['price']), 'TBLR', 1, 'C', '1');
		}

		$this->pdf->Cell(100, 5, "", 'TBLR', 0, 'C', '1');
		$this->pdf->Cell(36, 5, "", 'TBLR', 0, 'C', '1');
		$this->pdf->Cell(36, 5, $this->Format_number($grand_total_price), 'TBLR', 1, 'C', '1');
		$this->pdf->Ln(15);

		$date_time = date('Y-m-d H:i:s');

		$this->pdf->Output("upload/Cotizacion$date_time.pdf",'F');

		$upload_pdf = "Cotizacion$date_time.pdf";


		// Envio del servicio Email
		$from = "compras@comgrap.store";
        $to = "comgrap@comgrap.cl";
        $cc = $email;
		$subject = "Cotización";
		$title = "Cotización para $name";
		$content = "Se ha generado una nueva Cotización para $name";
		// Salida de email
		$this->send_email($from, $to, $cc, $subject, $title, $content, $upload_pdf);





  	}


}