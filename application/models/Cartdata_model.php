<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class Cartdata_model extends CI_Model {

  public function __construct()
  {
     parent::__construct();
     $this->infomas = $this->load->database('infomas', TRUE);
  }
  
  
  public function insert_contact($to_insert){
     $this->db->insert('ppal_sonda_meta', $to_insert);
	 $insert_id = $this->db->insert_id();
	 return $insert_id;
  }
  
  public function check_autodesk($codigo){
  	 $query = $this->infomas->get_where('productos', array('codigo' => $codigo));
    if ($query->num_rows() > 0) {
        return TRUE;
    } else {
        return FALSE;
    }
  }
  
  public function get_sign_data($order){
  	$query = $this->db->query('SELECT meta_value FROM ppal_postmeta WHERE meta_key = "comgrap_relational_field" AND post_id="'.$order.'" LIMIT 1');
    if ($query->num_rows() > 0) {
    	$query_user = $query->result_object();
    	//GET STORED DATA
        $user_data_query  = $this->db->query('SELECT * FROM ppal_sonda_meta WHERE ref_sonda = "'.$query_user[0]->meta_value.'"');
        if($user_data_query->num_rows() > 0){
        	return $user_data_query->result_object();
		}
    } else {
        return FALSE;
    }
  }  
  
   public function get_sku_data($sku){
  	 $query = $this->infomas->get_where('productos', array('codigo' => $sku));
    if ($query->num_rows() > 0) {
        return $query->result_object();
    } else {
        return FALSE;
    }
  } 
   
    public function order_request($data){
	  $base_url = $this->config->item('base_api_url');
	  $token = $this->config->item('api_token');
      $curl = curl_init($base_url . "autodesk/Nueva?token=$token");
    
    curl_setopt_array($curl, [
      CURLOPT_POST => true,
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_HTTPHEADER => ['Content-Type: application/json'],
      CURLOPT_POSTFIELDS => json_encode($data)
    ]);

    $result['response'] = curl_exec($curl);
    $result['status'] = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    curl_close($curl);
    return $result;
  }   
  



}
