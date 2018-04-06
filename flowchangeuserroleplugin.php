<?php ob_start();
/*
Plugin Name:  Flow Change Userrole Plugin

 */

include 'settings.php';

//make member if non-member
function flow_change_user($debug = null){
	// NOTE: Of course change 3 to the appropriate user ID
		$NONMEMBERNAME = get_option('nonmemberrole');
	$CUSTOMER = customer;
	$MEMBERNAME = get_option('memberrole');
	
	$u = wp_get_current_user();
	
	$debug = get_option('debug') == 'on' ?  true : false;
	if($debug){
	$testmode = true;
	echo "Debug = " . ($testmode ? 'Enabled' : 'Disabled lol dit kan helemaal niet');
	echo "<br/>";
	echo "Current role = " . $u->roles[0];
	echo "<br/>";
	echo "Non-member role = " . $NONMEMBERNAME;	
	echo "<br/><br/>";
		
	// Stuk hieronder werkt niet. Misschien ff in checkout proberen.	
		
	$gateways = WC()->payment_gateways->get_available_payment_gateways();
	//$gateways = WC_Payment_Gateways::get_available_payment_gateways();
		$enabled_gateways = [];
	$arraybla = ['hoi','ik', 'ben'];
	print_r($arraybla);
	print_r($gateways);

	if( $gateways ) {
		foreach( $gateways as $gateway ) {

			if( $gateway->enabled == 'yes' ) {

				$enabled_gateways[] = $gateway;

			}
		}
}

	print_r( $enabled_gateways ); // Should return an array of enabled gateways
	
	
	}

	if (in_array($NONMEMBERNAME, $u->roles )){
		// Remove role
		$u->remove_role( $NONMEMBERNAME );
		// Add role
		$u->add_role( $MEMBERNAME );
		if($debug){
?>
	<p>
	    User is non-member! Making member...
	</p>
<?php
		}
		header("Refresh:0");

	}elseif (in_array($CUSTOMER, $u->roles )){
		// Remove role
		$u->remove_role( $CUSTOMER );
		// Add role
		$u->add_role( $MEMBERNAME );
		if($debug){
?>
	<p>
	    User is non-member! Making member...
	</p>

<?php
		}
		header("Refresh:0");
		
	} elseif (in_array($MEMBERNAME, $u->roles ) && $testmode == true){
		
		
		// Remove role
		$u->remove_role( $MEMBERNAME );
		// Add role
		$u->add_role( $NONMEMBERNAME );
		if($debug){
?>
	<p>
	    DEBUG ONLY: User is member! Making non-member...
	</p>
<?php
		}
	}	
	else {
		if($debug){

?>
		<p>
			User is neither member nor non-member. 
		</p>
<?php
		}
	}

	


}
//Check if should be made member upon loading shop
function user_logged_in_product_already_bought() {
		if ( is_user_logged_in() ) {
			
			//$productid = 129; //Dit is de toegangsproduct dinges
			$productid = get_option('productID');
			$current_user = wp_get_current_user();
			if ( wc_customer_bought_product( $current_user->user_email, $current_user->ID, $productid ) ){
				flow_change_user(true);
			}
		}
	}


add_shortcode('flowtestuser', 'flow_change_user');
add_action( 'woocommerce_before_shop_loop', 'user_logged_in_product_already_bought');

//Make digital membership item only buyable with a coupon by removing payment methods. 
add_filter( 'woocommerce_available_payment_gateways', 'flow_unset_gateway_by_category' );
 
function flow_unset_gateway_by_category( $available_gateways ) {
	$disablePayment = get_option('disablePayment') == 'on' ?  true : false;
	
	if($disablePayment){

	global $woocommerce;
	$unset = false;
	$category_ids = array( get_option('TaxID') ); //https://www.wpwhitesecurity.com/wordpress-tutorial/find-wordpress-category-id/
	foreach ( $woocommerce->cart->cart_contents as $key => $values ) {
		$terms = get_the_terms( $values['product_id'], 'product_cat' );    
		foreach ( $terms as $term ) {        
			if ( in_array( $term->term_id, $category_ids ) ) {
				$unset = true;
				break;
			}
		}
	}
		if ( $unset == true ) {$available_gateways = [] ;}
		return $available_gateways;	
	}
	
	return $available_gateways;	
	
}


?>