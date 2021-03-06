<?php
/**
 * Plugin for Nexus creating a hierarchial set of items using &lt;ul&gt; and &lt;li&gt; items.
 * using the appropriate css settings, this can be transformed into a dropdown menu
 *
 * @abstract creates a simple &lt;ul&gt; and &lt;li&gt; based list of items
 * @author   xing@synapse.plus.com
 * @version  $Revision$
 * @package  nexus
 * @subpackage plugins
 */
global $gNexusSystem;

/**
* definitions
*/
// GUID should be a maximum of 16 chars
define( 'NEXUS_PLUGIN_GUID_SUCKERFISH', 'suckerfish' );

$pluginParams = array(
	'auto_activate'        => TRUE,
	'write_cache_function' => 'write_suckerfish_cache',
	'title'                => 'Suckerfish Menus',
	'description'          => 'Sophisticated and flexible CSS driven dropdown menus',
	'web_link'             => '<a class="external" href = "http://www.htmldog.com/articles/suckerfish/">Sons of Suckerfish Menus</a>',
	'browser_requirements' => 'Many modern browsers support suckerfish menus inherently using CSS.',
	'edit_label'           => 'CSS based menus',
	'plugin_type'          => 'nexus_plugin',
	'menu_types'           => array(
		'nor' => array( 'label' => 'Normal',     'note' => 'Nested list of menu items using "ul" and "li" HTML tags.' ),
		'ver' => array( 'label' => 'Vertical',   'note' => 'Vertical dropdown menu that usually resides in one of the side modules.' ),
		'hor' => array( 'label' => 'Horizontal', 'note' => 'Horizontal menu which you can use to insert in or replace the top menu bar.' ),
	),
);

$gNexusSystem->registerPlugin( NEXUS_PLUGIN_GUID_SUCKERFISH, $pluginParams );

/**
* bloody mad function to write a custom cache file for an individual menu
* @param $pMenuHash full menu hash
* @return full menu string ready for printing (key serves as cache file path)
*/
function write_suckerfish_cache( $pMenuHash ) {
	global $gBitSmarty;
	$menu_name = preg_replace( "/ +/", "_", trim( $pMenuHash->mInfo['title'] ) );
	$menu_name = strtolower( $menu_name );

	$menu_file = $pMenuHash->mInfo['cache']['file'];
	$data = '{bitmodule title="{tr}'.$pMenuHash->mInfo['title'].'{/tr}" name="'.$menu_name.'" classplus="nexus-menu"}';
	$data .= '<div class="suckerfish">';

	// if a permission has been set, we need to work out when to close the {if} clause
	$permCloseIds = array();
	$perm_close = FALSE;
	$next_cycle = FALSE;
	$menu_id = 'nexus'.$pMenuHash->mInfo['menu_id'].'-{$moduleParams.layout_area}{$moduleParams.pos}';

	foreach( $pMenuHash->mInfo['tree'] as $key => $item ) {
		if( $item['first'] ) {
			if( $key == 0 ) {
				$data .= '<ul id="'.$menu_id.'" class="menu '.$pMenuHash->mInfo['menu_type'].'">';
			} else {
				$data .= '<ul>';
			}
		} else {
			$data .= '</li>';
			// close permission clauses
			if( $next_cycle ) {
				$data .= '{/if}';
				$next_cycle = FALSE;
			}
			if( in_array( $item['item_id'], $permCloseIds ) ) {
				$next_cycle = TRUE;
			}
			if( $perm_close ) {
				$data .= '{/if}';
				$perm_close = FALSE;
			}
		}

		if( $item['last'] ) {
			$data .= '</ul>';
		} else {
			if( !empty( $item['perm'] ) ) {
				// open permission if clause
				$data .= '{if $gBitUser->hasPermission("'.$item['perm'].'")}';
				if( !$item['head'] ) {
					$perm_close = TRUE;
				} else {
					$permCloseIds[] = $item['item_id'];
				}
			}
			if( $item['rsrc_type'] == 'content_id' ) {
				$data .= '<li{if $gContent->mContentId == '.$item['rsrc'].'} class="selected"{/if}>';
			} else {
				$data .= '<li>';
			}
			$gBitSmarty->assign( 'item', $item );
			$data .= $gBitSmarty->fetch( NEXUS_PKG_PATH.'templates/'.NEXUS_PLUGIN_GUID_SUCKERFISH.'/item.tpl' );
		}
	}

	$data .= '<!--[if lt IE 8]><script type="text/javascript">BitBase.fixIEDropMenu("'.$menu_id.'");</script><![endif]-->';
	$data .= '<div class="clear"></div>';
	$data .= '</div>';
	$data .= '{/bitmodule}';

	$ret[$menu_file] = $data;

	return $ret;
}
?>
