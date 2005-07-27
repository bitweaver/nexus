<?php
/**
 * Plugin for Nexus creating a tikiwiki style menu with the difference of multiple levels being available
 *
 * @abstract creates a javascript expandable menu
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
define( 'NEXUS_PLUGIN_GUID_TIKIWIKI', 'tikiwiki' );

$pluginParams = array(
	'write_cache_function' => 'writeTikiWikiCache',
	'description' => 'expandable menu reminiscent of the tikiwiki menu',
	'web_link' => '<a class="external" href="http://www.tikiwiki.org">TikiWiki</a>',
	'browser_requirements' => 'Most browsers that support javascript should support these menus.',
	'edit_label' => 'TikiWiki menus',
	'menu_types' => array(
		'heo' => array( 'label' => 'head expands - open', 'note' => 'Head item serves merely as container and clicking on it will expand the underlying items (initial setting is open).' ),
		'iho' => array( 'label' => 'head expands (with icon) - open', 'note' => 'Head item serves merely as container and clicking on it will expand the underlying items (initial setting is open). Displays an icon along with it.' ),
		'hec' => array( 'label' => 'head expands - closed', 'note' => 'Initial setting is closed.' ),
		'ihc' => array( 'label' => 'head expands (with icon) - closed', 'note' => 'Initial setting is closed. Displays an icon along with it.' ),
		'ieo' => array( 'label' => 'icon expands - open', 'note' => 'Menu head item serves as link and there is an icon to expand the menu (initial setting is open).' ),
		'iec' => array( 'label' => 'icon expands - closed', 'note' => 'Initial setting is closed.' ),
	),
	'plugin_type' => NEXUS_HTML_PLUGIN,
	'include_js_in_head' => FALSE,
);

$gNexusSystem->registerPlugin( NEXUS_PLUGIN_GUID_TIKIWIKI, $pluginParams );

/**
* exports tikiwiki style menu
* @param $pMenuHash full menu hash
* @return full menu string ready for printing (key serves as cache file path)
*/
function writeTikiWikiCache( $pMenuHash ) {
	global $gBitSmarty;
	$menu_name = preg_replace( "/ +/", "_", trim( $pMenuHash->mInfo['title'] ) );
	$menu_name = strtolower( $menu_name );
	$menu_file = 'mod_'.$menu_name.'_'.$pMenuHash->mInfo['menu_id'].'.tpl';
	$data = '{bitmodule title="{tr}'.$pMenuHash->mInfo['title'].'{/tr}" name="'.$menu_name.'"}';
	$data .= '<div class="tikiwiki menu">';
	// if a permission has been set, we need to work out when to close the {if} clause
	$permCloseIds = array();
	$perm_close = FALSE;
	$perm_cycle = FALSE;
	$type = $pMenuHash->mInfo['type'];
	foreach( $pMenuHash->mInfo['tree'] as $key => $item ) {
		if( $item['first'] ) {
			$togid = 'togid'.$item['item_id'];
			$data .= '<div id="'.$togid.'" ';
			$data .= 'style="display:{if $smarty.cookies.'.$togid.' eq \'c\'}none{elseif $smarty.cookies.'.$togid.' eq \'o\'}block{else}';
			if( $key != 0 && preg_match( "/c$/", $type ) ) {
				$data .= 'none';
			} else {
				$data .= 'block';
			}
			$data .= '{/if};">';
		} else {
			// close permission clauses
			if( $perm_cycle ) {
				$data .= '{/if}';
				$perm_cycle = FALSE;
			}
			if( in_array( $item['item_id'], $permCloseIds ) ) {
				$perm_cycle = TRUE;
			}
			if( $perm_close ) {
				$data .= '{/if}';
				$perm_close = FALSE;
			}
		}
		if( $item['last'] ) {
			$data .= '</div>';
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
			if( $item['head'] ) {
				$tog_next = 'togid'.$pMenuHash->mInfo['tree'][$key+1]['item_id'];
				if( $type == 'heo' || $type == 'hec' ) {
					$item['display_url'] = "javascript:toggle('".$tog_next."');";
				} else {
					$item['expand_url'] = "javascript:icntoggle('".$tog_next."');";
				}
				$gBitSmarty->assign( 'tog_next', $tog_next );
			}
			$gBitSmarty->assign( 'item', $item );
			$gBitSmarty->assign( 'type', $type );
			$data .= $gBitSmarty->fetch( NEXUS_PKG_PATH.'templates/'.NEXUS_PLUGIN_GUID_TIKIWIKI.'/item.tpl' );
		}
	}
	$data .= '</div><!-- end .menu -->';
	$data .= '{/bitmodule}';
	$ret[$menu_file] = $data;
	return $ret;
}
?>
