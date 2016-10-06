<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       http://www.meworla.com
 * @since      1.0.0
 *
 * @package    Addressixgroups
 * @subpackage Addressixgroups/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Addressixgroups
 * @subpackage Addressixgroups/public
 * @author     Meworla GmbH <info@meworla.com>
 */
class Addressixgroups_Public {

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
	 * @param      string    $plugin_name       The name of the plugin.
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

	function shortcode_members($atts)
	{
	  $a = shortcode_atts(array('groupid' => 0), $atts);

	  // get the events for the requested agenda
	  if ($a['groupid']) {
	    $url = '/groups/v1/groups/' . $a['groupid'] . '/members?view=zhweb';
	    $response = $this->api->fetch($url);
	    if ($response->code==200) {
	      //      return json_decode(json_encode($response->body), true);
	      $this->members = $response->body;
	      require(plugin_dir_path( dirname( __FILE__ ) ) . 'public/partials/members.php');
	    }
	    else {
	      error_log('could not open events for group ' . $id . ' Code(' . $response->code .')');
	    }	    
	  }
	  else {
	    return '';
	  }
	}

	function shortcode_groupmenu($atts)
	{
	  $a = shortcode_atts(array('name' => ''), $atts);
	  if ($a['name']!='') {
	    $menu_items = wp_get_nav_menu_items($a['name']);
	    if ($menu_items == false) {
	      echo 'Menu ' . $a['name'] . ' not found';
	    }
	    echo '<div class="grouplist">';
	    foreach((array)$menu_items as $key => $menu_item) {
	      $mid = $menu_item->object_id;
	      $thumb = '';
	      if (has_post_thumbnail($mid)) {
		$thumb = get_the_post_thumbnail($mid, 'medium');
	      }
	      echo '<div class="groupentry"><a href="' . $menu_item->url . '">';
	      if ($thumb) {
		echo '<div class="groupimg">' . $thumb . '</div>';
	      }
	      echo '<h1>' . $menu_item->title . '</h1>';
	      echo '</a></div>';
	    }
	    echo '</div>';

	  }
	}

	function setup_hooks()
	{
	   add_action('init', array($this, 'init'));
	   add_shortcode('addressix_members', array($this, 'shortcode_members'));	
	   add_shortcode('addressix_groupmenu', array($this, 'shortcode_groupmenu'));	
	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
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

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/addressixgroups-public.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
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

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/addressixgroups-public.js', array( 'jquery' ), $this->version, false );

	}

}
