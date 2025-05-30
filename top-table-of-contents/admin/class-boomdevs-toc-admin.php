<?php
require_once BOOMDEVS_TOC_PATH . '/includes/class-boomdevs-toc-utils.php';
/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://wpmessiah.com/
 * @since      1.0.0
 *
 * @package    Boomdevs_Toc
 * @subpackage Boomdevs_Toc/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Boomdevs_Toc
 * @subpackage Boomdevs_Toc/admin
 * @author     BoomDevs <admin@boomdevs.com>
 */
class Boomdevs_Toc_Admin {

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
        $this->version     = $version;
        add_action('wp_ajax_Boomdevs_Toc_custom_plugin_install', [$this, 'Boomdevs_Toc_custom_plugin_install']);
        add_action( 'wp_ajax_nopriv_Boomdevs_Toc_custom_plugin_install', [$this, 'Boomdevs_Toc_custom_plugin_install'] );

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
         * defined in Boomdevs_Toc_Loader as all of the hooks are defined
         * in that particular class.
         *
         * The Boomdevs_Toc_Loader will then create the relationship
         * between the defined hooks and the functions defined in this
         * class.
         */

		 wp_enqueue_style( 'fa5', 'https://use.fontawesome.com/releases/v5.13.0/css/all.css', array(), '5.13.0', 'all' );
		wp_enqueue_style( 'fa5-v4-shims', 'https://use.fontawesome.com/releases/v5.13.0/css/v4-shims.css', array(), '5.13.0', 'all' );

        wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/boomdevs-toc-admin.css', array(), $this->version, 'all' );


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
         * defined in Boomdevs_Toc_Loader as all of the hooks are defined
         * in that particular class.
         *
         * The Boomdevs_Toc_Loader will then create the relationship
         * between the defined hooks and the functions defined in this
         * class.
         */

        wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/boomdevs-toc-admin.js', array( 'jquery' ), $this->version, true );

        // Localize script
        wp_localize_script( $this->plugin_name, 'boomdevs_toc_messages', array(
            'skin_change_confirmation_alert' => __( 'This is an irreversible action, Do you really want to import this skin?', 'boomdevs-toc' ),
            'skin_change_alert' => __( 'You have successfully imported an skin.', 'boomdevs-toc' ),
        ) );

        wp_localize_script( $this->plugin_name, 'bd_toc_content', array(
                'ajaxurl' => admin_url( 'admin-ajax.php' ),
                'action' => 'get_premade_layout',
                'nonce' => wp_create_nonce( "layout_content" ),
                'skin_change_alert' => __( 'You have successfully imported an skin.', 'boomdevs-toc' ),
            )
        );

        wp_localize_script($this->plugin_name, 'Boomdevs_Toc_custom_plugin_install_obj', array(
            'ajax_url'  => admin_url('admin-ajax.php'),
            'security' => wp_create_nonce('Boomdevs_Toc_custom_plugin_install_nonce')
            )
        );
    }

    /**
     * Custom links for pro buttons
     *
     * @param $actions
     * @return array
     */
    function bd_toc_add_action_plugin( $actions ) {

        if (Boomdevs_Toc_Utils::isProActivated()) {
            $settinglinks = array(
                '<a class="bd_toc_setting_button" href="'.esc_url(admin_url('/customize.php')).'">' . __( 'Settings', 'boomdevs-toc' ) . '</a>',
            );

        }else{
            $settinglinks = array(
                '<a class="bd_toc_setting_button" href="'.esc_url(admin_url('/admin.php?page=boomdevs-toc-settings#tab=auto-insert')).'">' . __( 'Settings', 'boomdevs-toc' ) . '</a>',
            );
            $pro_link = array(
                '<a class="bd_toc_pro_button" target="_blank" href="'.esc_url('https://wpmessiah.com/products/wordpress-table-of-contents/#price').'">' . __( 'Go Pro', 'boomdevs-toc' ) . '</a>',
            );
        }

        if (Boomdevs_Toc_Utils::isProActivated()) {
            $actions = array_merge( $actions, $settinglinks); 
        }else{
            $actions = array_merge( $actions, $settinglinks, $pro_link );
        }

        return $actions;
    }

    public function Boomdevs_Toc_custom_plugin_install() {

        check_ajax_referer('Boomdevs_Toc_custom_plugin_install_nonce', 'security');
    
        $plugin_slug = 'ai-image-alt-text-generator-for-wp';
        $plugin_file = 'ai-image-alt-text-generator-for-wp/boomdevs-ai-image-alt-text-generator.php';
    
        // Include necessary WordPress files for plugin installation and activation
        require_once ABSPATH . 'wp-admin/includes/plugin.php';
        require_once ABSPATH . 'wp-admin/includes/class-wp-upgrader.php';
    
        // Install the plugin
        $upgrader = new Plugin_Upgrader();
        $installed = $upgrader->install("https://downloads.wordpress.org/plugin/{$plugin_slug}.latest-stable.zip");
    
        if (is_wp_error($installed)) {
            wp_send_json_error(array('message' => $installed->get_error_message()));
        }
    
        // Activate the plugin
        $activated = activate_plugin($plugin_file);
    
        if (is_wp_error($activated)) {
            wp_send_json_error(array('message' => $activated->get_error_message()));
        }
    
        wp_send_json_success(array('message' => 'Plugin installed and activated successfully.'));
    }



}
