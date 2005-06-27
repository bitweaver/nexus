<?php
/**
 * @author   xing <xing@synapse.plus.com>
 * @version  $Revision: 1.1.1.1.2.2 $
 * @package  nexus
 * @subpackage functions
 */

/**
* required setup
*/
require_once( NEXUS_PKG_PATH.'Nexus.php');
include_once( NEXUS_PKG_PATH.'menu_lookup_inc.php' );

$nexusList = $gNexus->getMenuList();
$smarty->assign( 'nexusList', $nexusList );

// nexusHash['title'] must already be set before calling this file
if( isset( $nexusHash ) && !empty( $_REQUEST['nexus']['menu_id'] ) ) {
	$nexusHash['menu_id'] = $_REQUEST['nexus']['menu_id'];
	$nexusHash['after_ref_id'] = $_REQUEST['nexus']['after_ref_id'];
	$nexusHash['rsrc'] = $gContent->mContentId;
	$nexusHash['rsrc_type'] = 'content_id';
	if( !$gNexus->storeItem( $nexusHash ) ) {
		vd( $gNexus->mErrors );
	}
	$gNexus->load();
} elseif( isset( $nexusHash ) && !empty( $_REQUEST['nexus']['remove_item'] ) ) {
	$gNexus->expungeItem( $_REQUEST['nexus']['remove_item'] );
} else {
	// if the page is already present in a menu, don't allow users to add it again
	foreach( $nexusList as $menu ) {
		foreach( $menu['items'] as $item ) {
			if( !empty( $item['rsrc'] ) && $item['rsrc'] == $gContent->mContentId && $item['rsrc_type'] == 'content_id' ) {
				$smarty->assign( 'inNexusMenu', $menu );
				$smarty->assign( 'inNexusMenuItem', $item['item_id'] );
			}
		}
	}
}
?>
