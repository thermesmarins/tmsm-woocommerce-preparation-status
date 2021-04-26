<?php

/**
 * Define the preparation status
 *
 * @link       https://github.com/thermesmarins/
 * @since      1.0.0
 */

class Tmsm_Woocommerce_Preparation_Status_Poststatus {


	/**
	 * Register post status: processed
	 */
	public function register_post_status_preparation() {
		register_post_status( 'wc-preparation', array(
			'label'                     => __('In Preparation', 'tmsm-woocommerce-preparation-status'),
			'public'                    => true,
			'show_in_admin_status_list' => true,
			'show_in_admin_all_list'    => true,
			'exclude_from_search'       => false,
			'label_count'               => _n_noop( 'In Preparation <span class="count">(%s)</span>',
				'In Preparation <span class="count">(%s)</span>', 'tmsm-woocommerce-preparation-status' ),
		) );
	}


}
