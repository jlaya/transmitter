
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
		
        $name=$this->input->post('name');
        $email=$this->input->post('email');
       	#$grand_total_price = $this->input->post('grand_total_price');
        $data_arrc=$this->input->post('data_arr');

        /*$name= "Jesus Laya";
        $email= "jesusgerard2008@gmail.com";
       	$grand_total_price = 366567059;
       	$data_arrc = '[{"price":"1892190","sku":"829L1-WW9193-T743","quantity":3,"name":"Revit 2020 Commercial New"}
			,{"price":"348561","sku":"057L1-WW3033-T744","quantity":1,"name":"AutoCAD LT 2020 Commercial New"}]';*/
        


	
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
		$this->pdf->Cell(70, 5, utf8_decode('Descripción'), 'TBLR', 0, 'C', '1');
		$this->pdf->Cell(30, 5, 'SKU', 'TBLR', 0, 'C', '1');
		$this->pdf->Cell(12, 5, 'Cant', 'TBLR', 0, 'C', '1');
		$this->pdf->Cell(30, 5, 'Precio', 'TBLR', 0, 'C', '1');
		$this->pdf->Cell(30, 5, 'Sub total', 'TBLR', 1, 'R', '1');

		$json = json_decode($data_arrc);
		$sum_price = 0.00;
		$sum_sub_total = 0.00;
		foreach ($json as $value) {

			$price = $value->price;
			$sku = $value->sku;
			$quantity = $value->quantity;
			$name_product = $value->name;
			$sub_total = (float)$price * (float)$quantity;
			$sum_price += $price;
			$sum_sub_total += $sub_total;

			$this->pdf->SetFont('Arial', '', 8);
			$this->pdf->Cell(70, 5, utf8_decode($name_product), 'TBLR', 0, 'L', '1');
			$this->pdf->Cell(30, 5, $sku, 'TBLR', 0, 'C', '1');
			$this->pdf->Cell(12, 5, $quantity, 'TBLR', 0, 'C', '1');
			$this->pdf->Cell(30, 5, $this->Format_number($price), 'TBLR', 0, 'R', '1');
			$this->pdf->Cell(30, 5, $this->Format_number($sub_total), 'TBLR', 1, 'R', '1');
		}

		$this->pdf->Cell(70, 5, "", 'TBL', 0, 'L', '1');
		$this->pdf->Cell(30, 5, "", 'TB', 0, 'C', '1');
		$this->pdf->Cell(12, 5, "", 'TB', 0, 'C', '1');
		$this->pdf->Cell(30, 5, $this->Format_number($sum_price), 'TBLR', 0, 'R', '1');
		$this->pdf->Cell(30, 5, $this->Format_number($sum_sub_total), 'TBLR', 1, 'R', '1');
		
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