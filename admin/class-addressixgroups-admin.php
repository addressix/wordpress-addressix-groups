<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       http://www.meworla.com
 * @since      1.0.0
 *
 * @package    Addressixgroups
 * @subpackage Addressixgroups/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Addressixgroups
 * @subpackage Addressixgroups/admin
 * @author     Meworla GmbH <info@meworla.com>
 */
class Addressixgroups_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

		$this->setup_hooks();
	}

	function init() {
	  $this->api = AddressixAPI::init();
	}

	function plugin_menu()
	{
	  add_menu_page('Köpfe', 'Köpfe', 'edit_others_posts', 'addressixgroups_lists', array($this, 'draw_plugin_admin'), 20);
	  add_options_page(
	    'Addressix Group Options',
	    'Addressix Groups', 
	    'manage_options', 
	    'addressixgroup_admin', 
	    array($this, 'draw_option_page')
	    );
	}

	function groupid_form()
	{
	  printf('<input type="text" id="groupid" name="addressixgroup[groupid]" value="%s">',
		 isset($this->options['groupid']) ? esc_attr($this->options['groupid']) : '');
	}

	function draw_option_page() {
	?>
	  <div class="wrap">
   <h2>Addressix Groups</h2>
   <form method="post" action="options.php">
   <?php 
   settings_fields('addressixgroup_group');
	  do_settings_sections('addressixgroup_admin');
	  submit_button();
?>
</form>
</div><?php
    }

	function register_settings() {
	  register_setting('addressixgroup_group', 'addressixgroup');

	  add_settings_section(
	    'addressixgroup_main',
	    'Main Settings', 
	    function() { 
	      echo '<p>Main Group ID </p>';
	    }, 
	    'addressixgroup_admin');

	  add_settings_field(
	    'groupid',
	    'Group ID', 
	    array($this, 'groupid_form'),
	    'addressixgroup_admin', 
	    'addressixgroup_main'
	    );
/*
	  add_settings_field(
	    'secret',
	    'Secret Key', 
	    array($this, 'secret_form'), 
	    'addressixoauth_admin', 
	    'addressixoauth_main'
	    );  
*/
	  $this->options = get_option('addressixgroup');
	}

	function draw_plugin_admin() {

	  if (isset($_GET['list_id']) && ((int)$_GET['list_id'])) {
	    
	    $url = '/directory/v1/lists/' . (int)$_GET['list_id'];
	    $response = $this->api->fetch($url);
	    if ($response->code==200) {
	      $this->event = $response->body;
	      require(plugin_dir_path( dirname( __FILE__ ) ) . 'admin/partials/edit-group.php');
	    }
	    else {
	      error_log('could not open event ' . $_GET['list_id'] . ' Code(' . $response->code .')');
	    }
	    $this->event = $response->body;
	  } else     
	    
	    if (isset($_GET['listmembers_id']) && ((int)$_GET['listmembers_id'])) {
	    
	    $url = '/directory/v1/lists/' . (int)$_GET['listmembers_id'];
	    $response = $this->api->fetch($url);
	    if ($response->code==200) {
	      $this->list = $response->body;

	      if (!isset($_GET['member_id'])) {
		$url = '/directory/v1/lists/' . (int)$_GET['listmembers_id'] . '/members';
		$response = $this->api->fetch($url);
		if ($response->code==200) {
		  $this->members = $response->body;
		  require(plugin_dir_path( dirname( __FILE__ ) ) . 'admin/partials/edit-members.php');
		}
		else {
		  error_log('could not open list members ' . $_GET['list_id'] . ' Code(' . $response->code .')');
		}
	      } else {
		$url = '/directory/v1/lists/' . (int)$_GET['listmembers_id'] . '/members/' . $_GET['member_id'];
		$response = $this->api->fetch($url);
		if ($response->code==200) {
		  $this->member = $response->body;
		  require(plugin_dir_path( dirname( __FILE__ ) ) . 'admin/partials/edit-member.php');
		}
		else {
		  error_log('could not open list member ' . $_GET['memberid_id'] . ' Code(' . $response->code .')');
		}
	      }
	    }
	    else {
	      error_log('could not open list ' . $_GET['list_id'] . ' Code(' . $response->code .')');
	    }
	    $this->event = $response->body;	    
	    
	  } else if (isset($_GET['new'])) {
	    require(plugin_dir_path( dirname( __FILE__ ) ) . 'admin/partials/new-event.php');
	  }
	  else {

	    // nothing other matched - draw  events overview
	    $groupid = $this->options['groupid'];
	    $url = '/directory/v1/lists?owner=' . $groupid;
	    $response = $this->api->fetch($url);
	    if ($response->code==200) {
	      //      return json_decode(json_encode($response->body), true);
	      $this->lists = $response->body;
	      require(plugin_dir_path( dirname( __FILE__ ) ) . 'admin/partials/lists.php');
	    }
	    else {
	      error_log('could not open lists for group ' . $groupid . ' Code(' . $response->code .')');
	    }
	  }

	}

	function setup_hooks()
	{
	  add_action('init', array($this, 'init'));
	  add_action('admin_init', array($this, 'register_settings'));
	  add_action('admin_menu', array($this, 'plugin_menu'));
	  add_action('admin_post_addressixgroup_edit', array($this, 'process_edit'));
	  add_action('admin_post_addressixgroup_new', array($this, 'process_new'));
	  add_action('admin_post_addressixgroup_member_edit', array($this, 'process_member_edit'));
	  add_action('admin_post_addressixgroup_member_new', array($this, 'process_member_new'));
	}

	function process_member_edit()
	{
	  // Check nonce field
	  check_admin_referer('listmember_verify');
	  
	  $m=0;

	  $shownaddresses = array();

	  foreach(array_keys($_POST) as $key) {
	    $k = explode('_', $key);
	    if ($k[0] == 'showaddress') {
	      $shownaddresses[] = (int)$k[1];
	    }
	  }

	  $url = '/directory/v1/lists/' . (int)$_POST['listmembers_id'] . '/members/' . (int)$_POST['member_id'] . '/addresses';
	  $params = array('shownaddresses' => implode(',',$shownaddresses));
	  $response = $this->api->fetch($url, $params, 'PUT');
	  if ($response->code==200) {
	    $m=1;
	  }
	  else {
	    $m = $response->code;
	    error_log('could not put list member ' . $_POST['listmembers_id'] . ' Code(' . $response->code .')');
	  }
	
	  wp_redirect(admin_url('admin.php?page=addressixgroups_lists&listmembers_id='.(int)$_POST['listmembers_id'].'&member_id=' . (int)$_POST['member_id'] . '&m='.$m));
	  exit;
	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Addressixgroups_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Addressixgroups_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/addressixgroups-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Addressixgroups_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Addressixgroups_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/addressixgroups-admin.js', array( 'jquery' ), $this->version, false );

	}

}
