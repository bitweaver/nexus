<?php
/**
 * @author   xing <xing@synapse.plus.com>
 * @version  $Revision: 1.1.1.1.2.3 $
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
		header( 'Location: '.NEXUS_PKG_URL.'menu_items.php?menu_id='.$menu_id.'&tab=edit' );
		die;
	}
	$gNexus->load();
	$formfeedback['success'] = 'The menu \''.$gNexus->mInfo['title'].'\' was updated successfully.';
}

$gBitSmarty->assign( 'menuList', $gNexus->getMenuList() );
if( isset( $formfeedback ) ) {
	$gBitSmarty->assign( 'formfeedback', $formfeedback );
}

$gBitSystem->setBrowserTitle( 'Nexus Menus' );
$gBitSystem->display( 'bitpackage:nexus/menus.tpl' );
?>
