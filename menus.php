<?php
/**
 * @author   xing <xing@synapse.plus.com>
 * @version  $Revision: 1.1.1.1.2.6 $
 * @package  nexus
 * @subpackage functions
 */

/**
* required setup
*/
require_once( '../bit_setup_inc.php' );
global $gBitSystem;
require_once( NEXUS_PKG_PATH.'Nexus.php');
include_once( NEXUS_PKG_PATH.'menu_lookup_inc.php' );

$formfeedback = '';
$gBitSystem->verifyPermission( 'bit_p_create_nexus_menus' );

if( isset( $_REQUEST['action'] ) ) {
	if( $_REQUEST['action'] == 'edit' ) {
		$gBitSmarty->assign( 'editMenu', $gNexus->getMenu() );
	}

	if( $_REQUEST['action'] == 'remove' ) {
		$formHash['remove'] = TRUE;
		$formHash['menu_id'] = $menuId;
		$msgHash = array(
			'label' => 'Delete Menu',
			'confirm_item' => $gNexus->mInfo['title'],
			'warning' => 'This will remove this menu including all menu items associated with it.<br />This cannot be undone!',
		);
		$gBitSystem->confirmDialog( $formHash,$msgHash );
	}

	if( $_REQUEST['action'] == 'rewrite_cache' ) {
		if( $gNexus->rewriteMenuCache() ) {
			$formfeedback['success'] = 'The complete menu cache has been rewritten.';
		}
	}

	if( $_REQUEST['action'] == 'remove_dead' ) {
		if( $deadLinks = $gNexus->expungeDeadItems( $menuId ) ) {
			$deadHtml = '<ul>';
			foreach( $deadLinks as $dead ) {
				$deadHtml .= '<li>'.$dead.'</li>';
			}
			$deadHtml .= '</ul>';
			$formfeedback['warning'] = 'Links that were dead and removed from '.$gNexus->mInfo['title'].$deadHtml;
		} else {
			$formfeedback['success'] = 'No dead links were found for this menu.';
		}
	}

	if( $_REQUEST['action'] == 'convert_structure' ) {
		if( $gNexus->importStructure( $_REQUEST['structure_id'] ) ) {
			$formfeedback['success'] = 'The structure was successfully imported as menu.';
		} else {
			vd( $gNexus->mErrors );
		}
	}
}

if( isset( $_REQUEST['confirm'] ) ) {
	if( $gNexus->expungeMenu( $menuId ) ) {
		header ("Location: ".NEXUS_PKG_URL."menus.php");
		die;
	} else {
		vd( $gNexus->mErrors );
	}
}

if( isset( $_REQUEST['store_menu'] ) ) {
	$menu_id = $gNexus->storeMenu( $_REQUEST );
	// redirect to menu items page if this is a new menu
	if( empty( $menuId ) ) {
		header( 'Location: '.NEXUS_PKG_URL.'menu_items.php?menu_id='.$menu_id );
		die;
	}
	$gNexus->load();
	$formfeedback['success'] = 'The menu \''.$gNexus->mInfo['title'].'\' was updated successfully.';
}

$gBitSmarty->assign( 'menuList', $menuList = $gNexus->getMenuList() );
$gBitSmarty->assign( 'formfeedback', $formfeedback );

// options only available if there is a top bar menu
if( is_file( TEMP_PKG_PATH.'nexus/modules/top_bar_inc.tpl' ) ) {
	// if the top bar is set and we don't need it, remove it.
	foreach( $menuList as $menu ) {
		if( !( $menu['plugin_guid'] == NEXUS_PLUGIN_GUID_SUCKERFISH && $menu['type'] == 'hor' ) ) {
			$nuke_top_bar = TRUE;
		}
	}

	if( !empty( $nuke_top_bar ) ) {
		unlink( TEMP_PKG_PATH.'nexus/modules/top_bar_inc.tpl' );
	}

	if( !empty( $_REQUEST['store_pos'] ) ) {
		$gBitSmarty->assign( 'use_custom_top_bar', TRUE );
		$gBitSystem->storePreference( 'top_bar_position', !empty( $_REQUEST['top_bar_position'] ) ? $_REQUEST['top_bar_position'] : NULL, NEXUS_PKG_NAME );
	}
}

$gBitSystem->setBrowserTitle( 'Nexus Menus' );
$gBitSystem->display( 'bitpackage:nexus/menus.tpl' );
?>
