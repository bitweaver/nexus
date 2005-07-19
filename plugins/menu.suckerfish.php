<?php
/**
 * Plugin for Nexus creating a hierarchial set of items using &lt;ul&gt; and &lt;li&gt; items.
 * using the appropriate css settings, this can be transformed into a dropdown menu
 *
 * @abstract creates a simple &lt;ul&gt; and &lt;li&gt; based list of items
 * @author   xing@synapse.plus.com
 * @version  $Revision: 1.1.1.1.2.4 $
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
	'write_cache_function' => 'writeSuckerfishCache',
	'description' => 'Sophisticated and flexible CSS driven dropdown menus',
	'web_link' => '<a class="external" href="http://www.htmldog.com/articles/suckerfish/">Sons of Suckerfish Menus</a>',
	'browser_requirements' => 'Many modern browsers support suckerfish menus inherently using CSS. MSIE requires javascript to be ON for them to work.',
	'edit_label' => 'CSS based menus',
	'menu_types' => array(
		'nor' => array( 'label' => 'Normal', 'note' => 'Nested list of menu items using "ul" and "li" HTML tags.' ),
		'ver' => array( 'label' => 'Vertical', 'note' => 'Vertical dropdown menu that usually resides in one of the side modules.' ),
		'hor' => array( 'label' => 'Horizontal', 'note' => 'Horizontal menu with dropdowns similar to the top bar menu. If you choose this, the created menu will appear to any user who isn\'t an administrator.' ),
	),
	'plugin_type' => NEXUS_HTML_PLUGIN,
	'include_js_in_head' => FALSE,
);

$gNexusSystem->registerPlugin( NEXUS_PLUGIN_GUID_SUCKERFISH, $pluginParams );

/**
* bloody mad function to write a custom cache file for an individual menu
* @param $pMenuHash full menu hash
* @return full menu string ready for printing (key serves as cache file path)
*/
function writeSuckerfishCache( $pMenuHash ) {
	global $smarty;
	$menu_name = preg_replace( "/ +/", "_", trim( $pMenuHash->mInfo['title'] ) );
	$menu_name = strtolower( $menu_name );
	if( $pMenuHash->mInfo['type'] != 'hor' ) {
		$menu_file = 'mod_'.$menu_name.'_'.$pMenuHash->mInfo['menu_id'].'.tpl';
		$data = '{bitmodule title="{tr}'.$pMenuHash->mInfo['title'].'{/tr}" name="'.$menu_name.'"}';
	} else {
		$data = '';
		$menu_file = 'top_bar_inc.tpl';
	}
	// if a permission has been set, we need to work out when to close the {if} clause
	$permCloseIds = array();
	$perm_close = FALSE;
	$next_cycle = FALSE;
	foreach( $pMenuHash->mInfo['tree'] as $key => $item ) {
		if( $item['first'] ) {
			if( $key == 0 ) {
				$data .= '<ul id="nexus'.$pMenuHash->mInfo['menu_id'].'" class="menu '.$pMenuHash->mInfo['type'].'">';
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
			$data .= '<li>';
			$smarty->assign( 'item', $item );
			$data .= $smarty->fetch( NEXUS_PKG_PATH.'templates/'.NEXUS_PLUGIN_GUID_SUCKERFISH.'/item.tpl' );
		}
	}
	if( $pMenuHash->mInfo['type'] == 'ver' || $pMenuHash->mInfo['type'] == 'hor' ) {
		$data .= '<div class="clear"></div>';
	}
	if( $pMenuHash->mInfo['type'] != 'hor' ) {
		$data .= '{/bitmodule}';
	}
	$ret[$menu_file] = $data;
	return $ret;
}
?>
