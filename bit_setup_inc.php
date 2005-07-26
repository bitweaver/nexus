<?php
/**
 * @author   xing <xing@synapse.plus.com>
 * @version  $Revision: 1.1.1.1.2.4 $
 * @package  Nexus
 * @subpackage functions
 */
global $gBitSystem, $gBitUser;
$gBitSystem->registerPackage( 'nexus', dirname( __FILE__).'/' );

if( $gBitSystem->isPackageActive( 'nexus' ) ) {
	if( $gBitUser->isAdmin() ) {
			$gBitSystem->registerAppMenu( 'nexus', 'Nexus', NEXUS_PKG_URL.'index.php', 'bitpackage:nexus/menu_nexus.tpl', 'Nexus menus');
	}

	// check if there is a js file for MSIE
	if( is_dir( TEMP_PKG_PATH.'nexus/modules/' ) ) {
		if( is_file( TEMP_PKG_PATH.'nexus/modules/hoverfix_array.js' ) ) {
			$gBitSmarty->assign( 'hoverfix', TEMP_PKG_PATH.'nexus/modules/hoverfix_array.js' );
		}

		if( is_file( TEMP_PKG_PATH.'nexus/modules/top_bar_inc.tpl' ) ) {
			$gBitSmarty->assign( 'use_custom_top_bar', TRUE );
		}
	}
}
?>
