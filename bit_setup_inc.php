<?php
/**
 * @author   xing <xing@synapse.plus.com>
 * @version  $Revision: 1.6 $
 * @package  Nexus
 * @subpackage functions
 */
global $gBitSystem, $gBitUser;
$gBitSystem->registerPackage( 'nexus', dirname( __FILE__).'/' );

if( $gBitSystem->isPackageActive( 'nexus' ) ) {
	$gLibertySystem->registerService( LIBERTY_SERVICE_MENU, NEXUS_PKG_NAME, array(
		//'content_display_function' => 'display_pigeonholes',
		//'content_preview_function' => 'pigeonholes_preview_content',
		//'content_edit_function' => 'pigeonholes_input_content',
		//'content_store_function' => 'pigeonholes_store_content',
		//'content_edit_tpl' => 'bitpackage:pigeonholes/pigeonholes_input_inc.tpl',
		//'content_view_tpl' => 'bitpackage:pigeonholes/display_members.tpl',
		//'content_nav_tpl' => 'bitpackage:pigeonholes/display_paths.tpl',

		'content_store_function' => 'nexus_store_content',
		'content_edit_function' => 'nexus_input_content',
		'content_preview_function' => 'nexus_preview_content',
		'content_edit_tpl' => 'bitpackage:nexus/insert_menu_item_inc.tpl',
	) );

	// include service functions
	require_once( NEXUS_PKG_PATH.'servicefunctions_inc.php' );

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
