<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://github.com/nicomollet
 * @since      1.0.0
 *
 * @package    Tmsm_Woocommerce_Preparation_Status
 * @subpackage Tmsm_Woocommerce_Preparation_Status/admin
 */

use WPO\WC\PDF_Invoices\Documents\Bulk_Document;

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Tmsm_Woocommerce_Preparation_Status
 * @subpackage Tmsm_Woocommerce_Preparation_Status/admin
 * @author     Nicolas Mollet <nico.mollet@gmail.com>
 */
class Tmsm_Woocommerce_Preparation_Status_Admin {

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

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/tmsm-woocommerce-preparation-status-admin.css', array('woocommerce_admin_styles'), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/tmsm-woocommerce-preparation-status-admin.js', array( 'jquery' ), $this->version, true );

	}


	/**
	 * Rename order preview actions
	 *
	 * @param array $actions
	 * @param  WC_Order $order Order object.
	 *
	 * @return mixed
	 */
	function admin_order_preview_actions($actions, $order){

		$status_actions = array();

		$status_actions = @$actions['status']['actions'];

		$status_actions['preparation']['name'] =  _x( 'In Preparation', 'Order status', 'tmsm-woocommerce-preparation-status' );

		if ( $order->has_status( array( 'processing' ) ) ) {
			$status_actions['preparation'] = array(
				'url'    => wp_nonce_url( admin_url( 'admin-ajax.php?action=woocommerce_mark_order_status&status=preparation&order_id=' . $order->get_id() ), 'woocommerce-mark-order-status' ),
				'name'   => _x( 'In Preparation', 'Order status', 'tmsm-woocommerce-preparation-status' ),
				'action' => 'preparation',
			);
		}

		if ( $status_actions ) {
			$actions['status'] = array(
				'group'   => __( 'Change status: ', 'woocommerce' ),
				'actions' => $status_actions,
			);
		}

		return $actions;
	}

	/**
	 * Rename bulk actions
	 *
	 * @param array $actions
	 *
	 * @return array
	 */
	function rename_bulk_actions(array $actions){

		$actions['mark_preparation']  = __( 'Mark in Preparation', 'tmsm-woocommerce-preparation-status' );

		return $actions;
	}

	/**
	 * WooCommerce: Rename order statuses in views filters
	 *
	 * @param $views array
	 *
	 * @return array
	 */
	public function woocommerce_rename_views_filters($views){
		foreach($views as &$view){

			$view = str_replace('In Preparation', _x( 'In Preparation', 'Order status', 'tmsm-woocommerce-preparation-status' ), $view);

		}
		return $views;
	}

	/**
	 * Rename order statuses
	 *
	 * @param $statuses
	 *
	 * @return array
	 */
	function rename_order_statuses($statuses){

		$statuses['wc-preparation'] = _x( 'Preparation', 'Order status', 'tmsm-woocommerce-preparation-status' );

		return $statuses;
	}

	/**
	 * Order actions for preparation
	 *
	 * @param array    $actions
	 * @param WC_Order $order
	 *
	 * @return mixed
	 */
	function admin_order_actions_preparation( array $actions, WC_Order $order){

		if ( $order->has_status( array( 'processing' ) ) ) {

			// Get Order ID (compatibility all WC versions)
			$order_id = method_exists( $order, 'get_id' ) ? $order->get_id() : $order->id;
			// Set the action button
			$action_preparation = array('preparation' => array(
				'url'    => wp_nonce_url( admin_url( 'admin-ajax.php?action=woocommerce_mark_order_status&status=preparation&order_id='
				                                     . $order_id ),
					'woocommerce-mark-order-status' ),
				'name'   => __( 'Mark as In Preparation', 'tmsm-woocommerce-preparation-status' ),
				'action' => "view preparation", // keep "view" class for a clean button CSS
			) );
			$actions = array_merge($action_preparation, $actions);
		}

		if ( $order->has_status( array( 'preparation' ) ) ) {
			$actions['complete'] = array(
				'url'    => wp_nonce_url( admin_url( 'admin-ajax.php?action=woocommerce_mark_order_status&status=completed&order_id=' . $order->get_id() ), 'woocommerce-mark-order-status' ),
				'name'   => __( 'Complete', 'woocommerce' ),
				'action' => 'complete',
			);
		}

		return $actions;
	}

	/**
	 * Bulk action handler for preparation
	 */
	function admin_action_mark_preparation() {

		// if an array with order IDs is not presented, exit the function
		if( !isset( $_REQUEST['post'] ) && !is_array( $_REQUEST['post'] ) )
			return;

		foreach( $_REQUEST['post'] as $order_id ) {
			$order = new WC_Order( $order_id );
			if ( $order->has_status ( 'processing' ) ) {
				$order_note = __('Status changed to In Preparation', 'tmsm-woocommerce-preparation-status');
				$order->update_status( 'preparation', $order_note, true );
			}

		}

		// of course using add_query_arg() is not required, you can build your URL inline
		$location = add_query_arg( array(
			'post_type' => 'shop_order',
			'marked_preparation' => 1, // marked_preparation=1 is just the $_GET variable for notices
			'changed' => count( $_REQUEST['post'] ), // number of changed orders
			'ids' => join( ',', $_REQUEST['post'] ),
			'post_status' => 'all'
		), 'edit.php' );

		wp_redirect( admin_url( $location ) );



		exit;

	}

	/**
	 * Action when order goes from processing to preparation
	 *
	 * @param $order_id int
	 * @param $order WC_Order
	 */
	function status_processing_to_preparation($order_id, $order){
		$order->update_status( 'preparation');
	}


	/**
	 * Get list of statuses which are consider 'paid'.
	 *
	 * @param $statuses array
	 * @return array
	 */
	function woocommerce_order_is_paid_statuses($statuses){
		$statuses[] = 'preparation';
		return $statuses;
	}

	/**
	 * WooCommerce reports with custom status preparation as paid status
	 *
	 * @param $statuses array
	 *
	 * @return array
	 */
	function woocommerce_reports_order_statuses($statuses){
		if(isset($statuses) && is_array($statuses)){
			if(in_array('preparation', $statuses) ){
				array_push( $statuses, 'preparation');
			}
		}
		return $statuses;
	}

	/**
	 * WooCommerce download is permitted? Yes
	 *
	 * @param $is_download_permitted bool
	 * @param $order WC_Order
	 *
	 * @return bool
	 */
	function woocommerce_order_is_download_permitted( $is_download_permitted, $order ) {
		if ( $order->has_status ( 'preparation' ) ) {
			return true;
		}
		return $is_download_permitted;
	}

	/**
	 * Packing Invoice Slips: automatically change status to preparation
	 *
	 * @param Bulk_Document $document
	 * @param array         $order_ids
	 */
	public function wpo_wcpdf_document_created_manually( $document, array $order_ids){

		if(class_exists('WPO_WCPDF')){
			foreach( $order_ids as $order_id ) {
				$order = wc_get_order( $order_id );
				if( ! empty($order )){
					if($order->has_status('processing')){
						$order->update_status( 'preparation' );
					}
				}
			}
		}

	}

}
