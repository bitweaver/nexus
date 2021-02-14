<?php
/**
 * @author   xing <xing@synapse.plus.com>
 * @version  $Revision$
 * @package  Nexus
 * @subpackage functions
 */
global $gBitSystem, $gBitUser, $gLibertySystem, $gBitThemes;

$registerHash = array(
	'package_name' => 'nexus',
	'package_path' => dirname( __FILE__ ).'/',
	'service'      => LIBERTY_SERVICE_MENU,
);
$gBitSystem->registerPackage( $registerHash );

if( $gBitSystem->isPackageActive( 'nexus' ) ) {
	// load nexus plugins
	require_once( NEXUS_PKG_PATH.'NexusSystem.php' );
	global $gNexusSystem;
	$gNexusSystem = new NexusSystem();
	if( !$gBitSystem->isFeatureActive( NEXUS_PKG_NAME.'_plugin_file_suckerfish' ) ) {
		$gNexusSystem->scanAllPlugins( NEXUS_PKG_PATH.'plugins/' );
	} else {
		$gNexusSystem->loadActivePlugins();
	}
	$gBitSmarty->assignByRef( 'gNexusSystem', $gNexusSystem );

	// include service functions
	require_once( NEXUS_PKG_INCLUDE_PATH.'servicefunctions_inc.php' );

	$gLibertySystem->registerService( LIBERTY_SERVICE_MENU, NEXUS_PKG_NAME, array(
		'content_store_function'   => 'nexus_content_store',
		'content_edit_function'    => 'nexus_content_edit',
		'content_preview_function' => 'nexus_content_preview',
		'content_edit_tab_tpl'     => 'bitpackage:nexus/insert_menu_item_inc.tpl',
	) );

	if( $gBitUser->hasPermission( 'p_nexus_create_menus' ) ) {
		$menuHash = array(
			'package_name'  => NEXUS_PKG_NAME,
			'index_url'     => NEXUS_PKG_URL.'index.php',
			'menu_template' => 'bitpackage:nexus/menu_nexus.tpl',
		);
		$gBitSystem->registerAppMenu( $menuHash );
	}

	if( is_dir( TEMP_PKG_PATH.'nexus/modules/' ) ) {
		// make sure the template knows about the custom top bar
		if( is_file( TEMP_PKG_PATH.'nexus/modules/top_bar_inc.tpl' ) ) {
			$gBitSmarty->assign( 'use_custom_top_bar', TRUE );
		}
	}

	$gBitThemes->loadCss( NEXUS_PKG_PATH.'css/nexus.css' );
}
?>
