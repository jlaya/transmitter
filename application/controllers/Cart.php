<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cart extends CI_Controller {

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
	public function index(){
     header("HTTP/1.1 404 Not Found");
     header("Location: /404.php" );
	}
	
	public function test(){
		$testvar = "Hello Word";
		return $testvar;
	}	

    //WE NEED TO EXECUTE MULTIPLE SERVICES IN A VIEW
	public function get_cart_content(){
		//WE NEED TO INSTANCE CI OBJECT TO EXECUTE CONTROLLER IN VIEW
         $object['controller_get_cart_content']=$this; 
         $this->load->view('wordpress/listcart',$object);

	}
	
    //WE NEED TO EXECUTE MULTIPLE SERVICES IN A VIEW
	public function check_licenses_presence(){
		//WE NEED TO INSTANCE CI OBJECT TO EXECUTE CONTROLLER IN VIEW
         $object['controller_get_cart_content']=$this; 
         $this->load->view('wordpress/listcart',$object);
	}	

    //WE NEED TO EXECUTE MULTIPLE SERVICES IN A VIEW
	public function cart_session_data(){
		//WE NEED TO INSTANCE CI OBJECT TO EXECUTE CONTROLLER IN VIEW
         $object['controller_get_cart_content']=$this; 
         $this->load->view('wordpress/sessioncart',$object);
	}	

	public function load_assoc_data(){
		$data = $this->input->post("datos_sonda");

		if($data){
			$to_insert = array(
			"rut"=>$data["rut"],
			"nombre"=>$data["nombre"],
			"direccion"=>$data["direccion"],
			"codigo_postal"=>$data["codigo_postal"],
			"pais"=>$data["pais"],
			"sector"=>$data["sector"],
			"ciudad"=>$data["ciudad"],
			"comuna"=>$data["comuna"],
			"telefono"=>$data["telefono"],
			"contacto"=>$data["contacto"],
			"contacto_nombres"=>$data["contacto_nombres"],
			"contacto_telefono"=>$data["contacto_telefono"],
			"contacto_email"=>$data["contacto_email"]
		);

		$this->load->model("Cartdata_model");
		$checkInsert = $this->Cartdata_model->insert_contact($to_insert);
		if($checkInsert){
			echo $checkInsert;
		}else{
			return FALSE;		
		}
		}
		
		
		
	}
	

	public function check_order_sonda(){
		$data = $this->input->post("order");

		//WE NEED TO INSTANCE CI OBJECT TO EXECUTE CONTROLLER IN VIEW
         $object['controller_get_cart_content']=$this; 
		 $object['order_id']=$data;
         $this->load->view('wordpress/ordersonda',$object);		
	}
	
	public function check_autodesk($sku){
		//SERVICE
		$this->load->model("Cartdata_model");
		$isautodesk = $this->Cartdata_model->check_autodesk($sku);
		if($isautodesk){
			return TRUE;
		}else{
			return FALSE;
		}
		
	}
	
	public function get_sku_data($sku){
		//SERVICE
		$this->load->model("Cartdata_model");
		$signdata = $this->Cartdata_model->get_sku_data($sku);
		if($signdata){
			return $signdata;
		}else{
			return FALSE;
		}	    
	}	
	
	public function get_usr_license_data($order){
		//SERVICE
		$this->load->model("Cartdata_model");
		$signdata = $this->Cartdata_model->get_sign_data($order);
		if($signdata){
			return $signdata;
		}else{
			return FALSE;
		}
			
	}	
	
	public function sendrequest_comgrap($data){
		//SERVICE
		$this->load->model("Cartdata_model");
		$status_request = $this->Cartdata_model->order_request($data);		
	    if($status_request){
	    	return $status_request;
	    }else{
	    	return FALSE;
	    }
	}


    public function get_cart(){
        $data['grand_total_qty'] = $this->input->post('grand_total_qty');
        $data['grand_total_price'] = $this->input->post('grand_total_price');
        $data['data_arr'] = $this->input->post('data_arr');
        $this->db->insert('orders', $data);



    }


    /*
    //ORDER CHECK EXISTENCE
    private function exist_sku	*/
}