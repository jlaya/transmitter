<?php
//USE CONTROLLER METHODS TO USE THE MODEL
$controller_instance = $controller_get_cart_content -> test();

function recursive_cast_to_array($o) {
    $a = (array)$o;
    foreach ($a as &$value) {
        if (is_object($value)) {
            $value = recursive_cast_to_array($value);
        }
    }

    return $a;
}

global $woocommerce;
$items = $woocommerce -> cart -> get_cart();

if (empty($items)) {
	$response = FALSE;
} else {

	//RECOPILE OBJECT IN ARRAY
	$is_license = array();
	foreach ($items as $item => $values) {
		$product_info = $values['data'] -> post;

		//ID PRODUCTO PADRE
		$parent = $product_info -> post_parent;
		$parent_category = check_category($parent);

		//ID PRODUCTO O VARIACIÓN
		$self = $product_info -> ID;
		$self_category = check_category($self);

		if ($parent_category == "Licencias" OR $self_category == "Licencias") {
			array_push($is_license, $self);
		}

	}

	if (empty($is_license)) {
		$response = FALSE;
	} else {
		$response = TRUE;
	}
}
echo json_encode($response);

function check_category($postid) {
	global $woocommerce;
	$terms = get_the_terms($postid, 'product_cat');
	if ($terms) {
		$product_cat_id = $terms[0] -> term_id;
		$ancestors = get_ancestors($product_cat_id, 'product_cat');
		// Get a list of ancestors
		$ancestors = array_reverse($ancestors);
		//Reverse the array to put the top level ancestor first
		$ancestors[0] ? $top_term_id = $ancestors[0] : $top_term_id = $product_cat_id;
		//Check if there is an ancestor, else use id of current term
		$term = get_term($top_term_id, 'product_cat');
		//Get the term
		return $term -> name;
	} else {
		return false;
	}
}
?>