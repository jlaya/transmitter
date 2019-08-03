<?php
//USE CONTROLLER METHODS TO USE THE MODEL
$controller_instance = $controller_get_cart_content -> test();

global $woocommerce;


$userID =  get_current_user_id();

echo json_encode($userID);
/*function get_current_user_id() {
	global $woocommerce;
    if ( ! function_exists( 'wp_get_current_user' ) ) {
        return 0;
    }
    $user = wp_get_current_user();
    return ( isset( $user->ID ) ? (int) $user->ID : 0 );
}/*
/*
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
 * */
 
?>