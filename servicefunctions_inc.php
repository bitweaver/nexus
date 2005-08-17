<?php
/**
 * $Header: /cvsroot/bitweaver/_bit_nexus/servicefunctions_inc.php,v 1.1.2.1 2005/08/17 23:42:54 squareing Exp $
 *
 * Copyright ( c ) 2004 bitweaver.org
 * All Rights Reserved. See copyright.txt for details and a complete list of authors.
 * Licensed under the GNU LESSER GENERAL PUBLIC LICENSE. See license.txt for details
 *
 * @package nexus
 * @subpackage functions
 */

/**
 * Nexus edit template service
 */
function nexus_input_content( $pObject=NULL ) {
	global $gBitSmarty;
	require_once( NEXUS_PKG_PATH.'Nexus.php');
	$nexus = new Nexus();

	$nexusList = $nexus->getMenuList();
	$gBitSmarty->assign( 'nexusList', $nexusList );

	foreach( $nexusList as $menu ) {
		foreach( $menu['items'] as $item ) {
			if( !empty( $item['rsrc'] ) && $item['rsrc'] == $pObject->mContentId && $item['rsrc_type'] == 'content_id' ) {
				$gBitSmarty->assign( 'inNexusMenu', $menu );
				$gBitSmarty->assign( 'inNexusMenuItem', $item['item_id'] );
			}
		}
	}
}

/**
 * Nexus preview service
 * when we hit preview, we make the selections persistent
 */
function nexus_preview_content( $pObject=NULL ) {
}

/**
 * Nexus store service
 * store the content as part of an existing menu
 */
function nexus_store_content( $pObject, $pParamHash ) {
	global $gBitSystem, $gBitUser, $gBitSmarty;
	require_once( NEXUS_PKG_PATH.'Nexus.php');
	$nexus = new Nexus();

	if( !empty( $pParamHash['content_id'] ) && !empty( $pParamHash['nexus']['menu_id'] ) ) {
		$nexusHash['title'] = $pParamHash['content_store']['title'];
		$nexusHash['hint'] = ( !empty( $pParamHash['description'] ) ? $pParamHash['description'] : NULL );
		$nexusHash['menu_id'] = $pParamHash['nexus']['menu_id'];
		$nexusHash['after_ref_id'] = $pParamHash['nexus']['after_ref_id'];
		$nexusHash['rsrc'] = $pParamHash['content_id'];
		$nexusHash['rsrc_type'] = 'content_id';
		if( !$nexus->storeItem( $nexusHash ) ) {
			vd( $nexus->mErrors );
		}
		$nexus->load();
	} elseif( !empty( $pParamHash['nexus']['remove_item'] ) ) {
		$nexus->expungeItem( $pParamHash['nexus']['remove_item'] );
	}
}
?>
