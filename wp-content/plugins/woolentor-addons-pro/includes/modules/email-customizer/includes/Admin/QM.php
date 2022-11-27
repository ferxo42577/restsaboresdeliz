<?php
/**
 * QM.
 */

namespace Woolentor_Email_Customizer\Admin;

/**
 * QM class.
 */
class QM {

	/**
     * QM constructor.
     */
    public function __construct() {
        add_action( 'wp_footer', function () {
            $this->disable_query_monitor();
        }, 999 );
    }

    /**
     * Disable query monitor.
     */
    public function disable_query_monitor() {
        if ( woolentor_is_email_customizer_template() ) {
            add_filter( 'qm/dispatch/html', '__return_false', 999, 2 );
        }
    }

}