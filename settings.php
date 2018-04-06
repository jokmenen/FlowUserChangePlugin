<?php


// Settings page for Flow Change User Plugin
 
add_action('admin_menu', function() {
    add_options_page( 'Flow User Change Settings', 'Flow User Change', 'manage_options', 'flow-user-change', 'flow_user_change_settings' );
});
 
 
add_action( 'admin_init', function() {
    register_setting( 'flow-user-change-settings', 'debug' );
	register_setting( 'flow-user-change-settings', 'disablePayment' );
    register_setting( 'flow-user-change-settings', 'nonmemberrole' );
    register_setting( 'flow-user-change-settings', 'memberrole' );
    register_setting( 'flow-user-change-settings', 'productID' );
	register_setting( 'flow-user-change-settings', 'TaxID' );
});
 
 
function flow_user_change_settings() {
  ?>
<h1>Flow Change User Plugin Settings</h1>
    <div class="wrap">
      <form action="options.php" method="post">
 
        <?php
          settings_fields( 'flow-user-change-settings' );
          do_settings_sections( 'flow-user-change-settings' );
        ?>
        <table>
             <tr>
                <th>Debug?</th>
                <td>
                    <label>
                        <input type="checkbox" name="debug" <?php echo esc_attr( get_option('debug') ) == 'on' ? 'checked="checked"' : ''; ?> />
                    </label>
                </td>
            </tr>
			 <tr>
                <th>Disable payment methods for Digital Memberships?</th>
                <td>
                    <label>
                        <input type="checkbox" name="disablePayment" <?php echo esc_attr( get_option('disablePayment') ) == 'on' ? 'checked="checked"' : ''; ?> />
                    </label>
                </td>
            </tr>
			
			
			<tr>
                <th>Roles to change: </th>
                <td>
					<label>
						<select name="nonmemberrole">
							<?php wp_dropdown_roles( get_option('nonmemberrole') )?>
						</select> Non Member <br/>
					</label>
					<label>
						<select name="memberrole">
							<?php wp_dropdown_roles( get_option('memberrole') )?>
						</select> Member
					</label>
                </td>
            </tr>
            <tr>
                <th>Digital Membership Product ID</th>
                <td><input type="number" placeholder="Insert Number Here..." name="productID" value="<?php echo esc_attr( get_option('productID') ); ?>" size="50" /></td>
            </tr>
			<tr>
                <th>Digital Membership Taxonomy ID</th>
                <td><input type="number" placeholder="Insert Number Here..." name="TaxID" value="<?php echo esc_attr( get_option('TaxID') ); ?>" size="50" /></td>
            </tr>
            <tr>
                <td><?php submit_button(); ?></td>
            </tr>
 
        </table>
 
      </form>
    </div>
  <?php
}
 
// That's it, have fun !!