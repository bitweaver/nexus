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

// get content and pass it on
include_once( LIBERTY_PKG_PATH.'get_content_list_inc.php' );
$gBitSmarty->assign( 'contentSelect', $contentSelect );
$gBitSmarty->assign( 'contentTypes', $contentTypes );

$cList[''] = '';
foreach( $contentList['data'] as $cItem ) {
	$cList[$contentTypes[$cItem['content_type_guid']]][$cItem['content_id']] = $cItem['title'].' [id: '.$cItem['content_id'].']';
}
$gBitSmarty->assign( 'contentList', $cList );

// store item
if( isset( $_REQUEST['store_item'] ) ) {
	if( $gNexus->storeItem( $_REQUEST ) ) {
		$formfeedback['success'] = 'The menu item was saved successfully.';
	} else {
		$formfeedback = $gNexus->mErrors;
	}
	$gNexus->load();
}

if( isset( $_REQUEST['remove_item'] ) && is_array( $_REQUEST['remove_item'] ) ) {
	$delList = '<ul>';
	foreach( $_REQUEST['remove_item'] as $rem_id ) {
		if( $delItem = $gNexus->expungeItem( $rem_id, FALSE ) ) {
			$delList .= '<li>'.$delItem['title'].'</li>';
		} else {
			$formfeedback = $gNexus->mErrors;
		}
	}
	$delList .= '</ul>';
	if( isset( $delList ) ) {
		$formfeedback['success'] = 'The following items were successfully removed from the menu'.$delList;
	}
	$gNexus->load();
	$gNexus->writeModuleCache();
}

if( !empty( $_REQUEST['item_id'] ) ) {
	$gBitSmarty->assign( 'editItem', $gNexus->mInfo['items'][$_REQUEST['item_id']] );
}
// when we use the content type dropdown or the filter, we need to pass back the information to the tpl
if( isset( $_REQUEST['find_objects'] ) && !isset( $_REQUEST['store_item'] ) ) {
	$gBitSmarty->assign( 'editItem', $_REQUEST );
}
if( isset( $_REQUEST['tab'] ) ) {
	$gBitSmarty->assign( $_REQUEST['tab'].'TabSelect', 'tdefault' );
}
if( isset( $formfeedback ) ) {
	$gBitSmarty->assign( 'formfeedback', $formfeedback );
}

// get all available perms only when the admin is visiting here.
if( $gBitUser->isAdmin() ) {
	$tmpPerms = $gBitUser->getGroupPermissions();
} else {
	$tmpPerms = $gBitUser->mPerms;
}
$perms['no permission'][''] = 'none';
foreach( $tmpPerms as $perm => $info ) {
	$perms[$info['package']][$perm] = $perm;
}
$gBitSmarty->assign( 'perms', $perms );

// get a list of available resource types
$rsrcTypes = array(
	'external' => 'URL',
	'content_id' => 'Content ID',
	'structure_id' => 'Structure ID',
	'menu_id' => 'Menu ID',
//	'gallery_list' => 'Gallery List', // this is not in use yet - xing
);
$gBitSmarty->assign( 'rsrcTypes', $rsrcTypes );

// get all available menus that can be included
$menuHashList = array();
$menuList[''] = '';
$menuHashList = $gNexus->getMenuList();
foreach( $menuHashList as $menu ) {
	if( $menuId != $menu['menu_id'] ) {
		$menuList[$menu['menu_id']] = $menu['title'].' [id: '.$menu['menu_id'].']';
	}
}
if( count( $menuList ) > 1 ) {
	$gBitSmarty->assign( 'menuList', $menuList );
}

$gBitSystem->setBrowserTitle( 'Nexus Menus' );
$gBitSystem->display( 'bitpackage:nexus/menu_items.tpl' );
?>
