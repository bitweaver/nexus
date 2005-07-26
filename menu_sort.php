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

if( empty( $_REQUEST['menu_id'] ) ) {
	header( 'Location:'.NEXUS_PKG_URL.'index.php' );
}

// if someone wants to move and item, move it.
if( isset( $_REQUEST['move_item'] ) && isset( $_REQUEST['item_id'] ) ) {
	if( $_REQUEST['move_item'] == 'w' ) {
		$gNexus->moveItemWest( $_REQUEST['item_id'] );
	} elseif( $_REQUEST['move_item'] == 'n' ) {
		$gNexus->moveItemNorth( $_REQUEST['item_id'] );
	} elseif( $_REQUEST['move_item'] == 's' ) {
		$gNexus->moveItemSouth( $_REQUEST['item_id'] );
	} elseif( $_REQUEST['move_item'] == 'e' ) {
		$gNexus->moveItemEast( $_REQUEST['item_id'] );
	}
	header( 'Location: '.NEXUS_PKG_URL.'menu_sort.php?menu_id='.$_REQUEST['sort_menu'].'&tab='.$_REQUEST['tab'] );
	die;
}

if( isset( $_REQUEST['tab'] ) ) {
	$gBitSmarty->assign( $_REQUEST['tab'].'TabSelect', 'tdefault' );
}

// this is the module filename
$gBitSmarty->assign( 'nexus_file', strtolower( 'mod_'.preg_replace( "/ /", "_", $gNexus->mInfo['title'] ).'_'.$gNexus->mInfo['menu_id'].'.tpl' ) );

$gBitSystem->setBrowserTitle( 'Nexus Menus' );
$gBitSystem->display( 'bitpackage:nexus/menu_sort.tpl' );
?>
