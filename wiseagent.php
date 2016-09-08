<?php
/**
 * Plugin Name: Gravity Forms: Wise Agent
 * Plugin URI: 
 * Description: An add-on that connects to the Wise Agent API
 * Version: 1.0
 * Author: Darkspire Media
 * Author URI: http://darkspire.media
 *
 * ------------------------------------------------------------------------
 * Copyright 2016 Darkspire, Inc.
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA 02111-1307 USA
 */

define( 'GF_WiseAgent_VERSION', '1.0.0' );

add_action( 'gform_loaded', array( 'GF_WiseAgent_Bootstrap', 'load' ), 5 );

class GF_WiseAgent_Bootstrap {

    public static function load(){

        require_once( 'class-gf-wiseagent.php' );

        GFAddOn::register( 'GFWiseAgent' );
    }

}

function gf_wiseagent(){
    return GFWiseAgent::get_instance();
}