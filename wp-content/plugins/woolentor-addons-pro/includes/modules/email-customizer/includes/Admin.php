<?php
/**
 * Admin.
 */

namespace Woolentor_Email_Customizer;

/**
 * Admin class.
 */
class Admin {

	/**
     * Admin constructor.
     */
    public function __construct() {
        new Admin\QM();
        new Admin\Fields();
    }

}