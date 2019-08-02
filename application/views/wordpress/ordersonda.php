<?php
//USE CONTROLLER METHODS TO USE THE MODEL
$controller_instance = $controller_get_cart_content -> test();

global $woocommerce;

$order = wc_get_order($order_id);
$sku_container = array();
// Iterating through each WC_Order_Item_Product objects
foreach ($order->get_items() as $item_key => $item) :

	$product = $item -> get_product();
	// Get the WC_Product object
	$product_sku = $product -> get_sku();

	array_push($sku_container, $product_sku);
endforeach;

$exist_on_autodesk = array();
if (count($sku_container) > 0) {
	foreach ($sku_container as $sku) {
		$controller_instance = $controller_get_cart_content -> check_autodesk($sku);
		if ($controller_instance) {
			array_push($exist_on_autodesk, $product_sku);
		}
	}
}

if (count($exist_on_autodesk) > 0) {
	//GET SERVICE FROM ORDER
	$user_license_data = $controller_get_cart_content -> get_usr_license_data($order_id);
	if ($user_license_data) {
		//CHECK IF ORDER IS PROCESSED || PENDING

		$queue_licences = array();
		foreach ($exist_on_autodesk as $key => $sku) {
			//GET DATA OF SKU -> AND GROUP OBJECT OF AUTODESK
			$prepare_data = $controller_get_cart_content -> get_sku_data($sku);
			if ($prepare_data) {
				$queue_licences[$key] = array("product_data" => $prepare_data);
			} else {
				die("An error was ocurred - err_code SKU");
			}
		}

		if (count($queue_licences) > 0) {

			foreach ($queue_licences as $key => $datos) {

			   $field['token']= $this->config->item('api_token');
				$field['cliente']['rut'] = $user_license_data[0] -> RUT;
				$field['cliente']['nombre'] = $user_license_data[0] -> nombre;
				$field['cliente']['id_sector'] = $user_license_data[0] -> sector;
				$field['cliente']['direccion'] = $user_license_data[0] -> direccion;
				$field['cliente']['codigo_postal'] = $user_license_data[0] -> codigo_postal;
				$field['cliente']['id_pais'] = $user_license_data[0] -> pais;
				$field['cliente']['id_comuna'] = $user_license_data[0] -> comuna;
				$field['cliente']['id_ciudad'] = $user_license_data[0] -> ciudad;
				$field['cliente']['telefono'] = $user_license_data[0] -> telefono;
				if (!empty($user_license_data[0] -> contacto)) {
					$field['cliente']['contacto']['id'] = $user_license_data[0] -> contacto;
				}
				$field['cliente']['contacto']['nombre'] = $user_license_data[0] -> contacto_nombres;
				$field['cliente']['contacto']['telefono'] = $user_license_data[0] -> contacto_telefono;
				$field['cliente']['contacto']['email'] = $user_license_data[0] -> contacto_email;
				$field['suscripcion']['id_vigencia'] = $datos["product_data"][0] -> id_vigencia;			
				$field['suscripcion']['numero_oc'] = $order_id;
				$field['suscripcion']['productos'][0]['codigo'] = $datos["product_data"][0] -> codigo;
				$field['suscripcion']['productos'][0]['id_vigencia'] = $datos["product_data"][0] -> id_vigencia;
				$field['suscripcion']['productos'][0]['cantidad'] = 1;
				$field['suscripcion']['productos'][0]['precio_neto'] = $datos["product_data"][0] -> precio_neto;
				$field['suscripcion']['productos'][0]['descuento_reseller'] = $datos["product_data"][0] -> descuento_reseller;
				$field['suscripcion']['productos'][0]['precio_neto_reseller'] = $datos["product_data"][0] -> precio_neto_reseller;
				$field['suscripcion']['productos'][0]['descuento_cliente'] = "0.00000";
				$field['suscripcion']['productos'][0]['precio_neto_cliente'] = $datos["product_data"][0] -> precio_neto;

			    /*
                $field['token']= $this->config->item('api_token');
				$field['cliente']['rut'] = $user_license_data[0] -> RUT;
				$field['cliente']['nombre'] = $user_license_data[0] -> nombre;
				$field['cliente']['id_sector'] = $user_license_data[0] -> sector;
				$field['cliente']['direccion'] = $user_license_data[0] -> direccion;
				$field['cliente']['codigo_postal'] = $user_license_data[0] -> codigo_postal;
				$field['cliente']['id_pais'] = $user_license_data[0] -> pais;
				$field['cliente']['id_comuna'] = $user_license_data[0] -> comuna;
				$field['cliente']['id_ciudad'] = $user_license_data[0] -> ciudad;
				$field['cliente']['telefono'] = $user_license_data[0] -> telefono;
				if (!empty($user_license_data[0] -> contacto)) {
					$field['cliente']['contacto']['id'] = $user_license_data[0] -> contacto;
				}
				$field['cliente']['contacto']['nombre'] = $user_license_data[0] -> contacto_nombres;
				$field['cliente']['contacto']['telefono'] = $user_license_data[0] -> contacto_telefono;
				$field['cliente']['contacto']['email'] = $user_license_data[0] -> contacto_email;
				$field['suscripcion']['id_vigencia'] = $datos["product_data"][0] -> id_vigencia;			
				$field['suscripcion']['numero_oc'] = $order_id;
				$field['suscripcion']['productos'][0]['codigo'] = "001I1-009704-T385";
				$field['suscripcion']['productos'][0]['id_vigencia'] = 1;
				$field['suscripcion']['productos'][0]['cantidad'] = 1;
				$field['suscripcion']['productos'][0]['precio_neto'] = "2083.200000";
				$field['suscripcion']['productos'][0]['descuento_reseller'] = "0.10000";
				$field['suscripcion']['productos'][0]['precio_neto_reseller'] = "1874.88000";
				$field['suscripcion']['productos'][0]['descuento_cliente'] = "0.00000";
				$field['suscripcion']['productos'][0]['precio_neto_cliente'] = "2083.200000";	    
			    */
			    
				$request_response = array();
				//SHOT THE REQUEST
				$issue_element = $controller_get_cart_content -> sendrequest_comgrap($field);
				if($issue_element){
					//$request_response[$key] = json_decode($issue_element); 
					$request_response[$key] = json_decode($issue_element["response"] , true);
				}else{
					$request_response[$key] = FALSE;
				}
				
				$set_response_format = $request_response;
			}
        //SET RESPONSE
         echo json_encode($set_response_format, TRUE);

		}

	} else {
		die("An error was ocurred - err_code REL USR");
	}

} else {
	$contruct_response = array("true" => FALSE, "content" => "");
	echo json_encode($reponse);
}
?>
