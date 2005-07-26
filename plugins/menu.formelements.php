<?php
/**
 * Plugin for Nexus for handling menus that are based on form elements
 * such as drop-down menu using select
 *
 * @abstract implements javascript menu using form elements
 * @author   william@elan.net
 * copied   copied from menu.suckerfish.php originally by xing
 * @version  $Revision: 1.1.1.1.2.4 $
 * @package  nexus
 * @subpackage plugins
 */
global $gNexusSystem;

/**
* definitions
*/
define( 'NEXUS_PLUGIN_GUID_FORMELEMENTSMENU', 'formelements' );

$pluginParams = array(
	'write_cache_function' => 'writeFormMenuCache',
	'description' => 'Menus using form elements',
	'web_link' => '',
	'browser_requirements' => 'This menu should work in all browsers that support javascript',
	'edit_label' => 'Menus using form elements',
	'menu_types' => array(
		'sdd' => array( 'label' => 'Standard DropDown', 'note' => 'drop-down menu using select with menu name on top' ),
		'qdd' => array( 'label' => 'Quick DropDown', 'note' => 'drop-down menu using select with menu name in drop-down select box'),
		's3' => array( 'label' => '3-Line Box', 'note' => 'select menu with 3 lines showing' ),
		's5' => array( 'label' => '5-Line Box', 'note' => 'select menu with 5 lines showing' ),
		'sal' => array( 'label' => 'Full Text Box', 'note' => 'select menu with all menu items showing' ),
	),
	'plugin_type' => NEXUS_HTML_PLUGIN,
	'include_js_in_head' => '/nexus/plugins/menu.formelements.js',
);

$gNexusSystem->registerPlugin( NEXUS_PLUGIN_GUID_FORMELEMENTSMENU, $pluginParams );

/**
* bloody mad function to write a custom cache file for an individual menu
* @param $pMenuId menu id of the menu for which we want to create a cache file
* @return number of errors encountered
* @public
*/
function writeFormMenuCache( $pMenuHash ) {
	global $gBitSmarty;
	$menu_name = preg_replace( "/ +/", "_", trim( $pMenuHash->mInfo['title'] ) );
	$menu_name = strtolower( $menu_name );
	$menu_file = 'mod_'.$menu_name.'_'.$pMenuHash->mInfo['menu_id'].'.tpl';
	if ( $pMenuHash->mInfo['type'] != 'qdd' ) {
		$data = '{bitmodule title="{tr}'.$pMenuHash->mInfo['title'].'{/tr}" name="'.$menu_name.'"}';
	}
	else {
		$data = '{bitmodule name="'.$menu_name.'"}';
	}
	// if a permission has been set, we need to work out when to close the {if} clause
	$permCloseIds = array();
	$perm_close = FALSE;
	$next_cycle = FALSE;
	foreach( $pMenuHash->mInfo['tree'] as $key => $item ) {
		if( !empty( $item['perm'] ) ) {
			$perm_open = '{if $gBitUser->hasPermission("'.$item['perm'].'")}';
		}
		if( $item['first'] ) {
			$data .= '<form id="menu_nexus'.$pMenuHash->mInfo['menu_id'].'" action="">';
			if ( $pMenuHash->mInfo['type'] == 'qdd' ) {
				$data .= $gBitSmarty->fetch( NEXUS_PKG_PATH.'templates/formelements/start_center.tpl' );
			}
			$data .= '<select ';
			if ( $pMenuHash->mInfo['type'] == 's3' ) {
				$data .= 'size="3" ';
			}
			if ( $pMenuHash->mInfo['type'] == 's5' ) {
				$data .= 'size="5" ';
			}
			if ( $pMenuHash->mInfo['type'] == 'sal' ) {
				$data .= 'size="$pMenuHash->sizeof()" ';
			}
			if( $key == 0 ) {
				$data .= ' onchange="go(this.form.elements[0]);" ';
				$data .= ' name="menu_nexus'.$pMenuHash->mInfo['menu_id'].'" >';
				if ( $pMenuHash->mInfo['type'] == 'sdd' ) {
					$data .= '<option value=""></option>';
				}
				if ( $pMenuHash->mInfo['type'] == 'qdd' ) {
					$data .= '<option value="">'.$pMenuHash->mInfo['title'].'</option>' ;
				} 
			} else {
				$data .= '>';
			}
		} else {
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
			$data .= '</select>' ;
			if ( $pMenuHash->mInfo['type'] == 'qdd' ) {
				$data .= $gBitSmarty->fetch( NEXUS_PKG_PATH.'templates/formelements/finish_center.tpl' );
			}
			$data .= '</form>' ;
		} else {
			if( !empty( $item['perm'] ) ) {
				// open permission if clause
				$data .= $perm_open;
				if( !$item['head'] ) {
					$perm_close = TRUE;
				} else {
					$permCloseIds[] = $item['item_id'];
				}
			}
			$gBitSmarty->assign( 'item', $item );
			$data .= $gBitSmarty->fetch( NEXUS_PKG_PATH.'templates/formelements/item.tpl' );
		}
	}
	$data .= '{/bitmodule}';
	$ret[$menu_file] = $data;
	return $ret;
}
?>
