<?php
/**
* Nexus system class for handling the menu plugins
*
* @abstract
* @author   xing <xing@synapse.plus.com>
* copied   copied from LibertySystem.php
* @version  $Revision: 1.1.1.1.2.5 $
* @package  nexus
*/

// for menus that use regular HTML as output
/**
* definitions
*/
define( 'NEXUS_HTML_PLUGIN', 'nexushtml' );
define( 'NEXUS_DEFAULT_GUID', 'suckerfish' );

/**
* required setup
*/
require_once( KERNEL_PKG_PATH.'BitBase.php' );

/**
* @package  nexus
* @subpackage  NexusSystem
*/
class NexusSystem extends BitBase {

	var $mPlugins;

	function NexusSystem() {
		BitBase::BitBase();
		$this->loadPlugins();
	}

	function loadPlugins( $pCacheTime=BIT_QUERY_CACHE_TIME ) {
		$rs = $this->mDb->query( "SELECT * FROM `".BIT_DB_PREFIX."tiki_nexus_plugins`", NULL, BIT_QUERY_DEFAULT, BIT_QUERY_DEFAULT );
		while( $rs && !$rs->EOF ) {
			$this->mPlugins[$rs->fields['plugin_guid']] = $rs->fields;
			$rs->MoveNext();
		}
	}

	function scanPlugins() {
		$pluginsPath = NEXUS_PKG_PATH.'plugins/';
		if( $pluginDir = opendir( $pluginsPath ) ) {
			// Scan the plugins directory for plugins
			while( FALSE !== ( $plugin = readdir( $pluginDir ) ) ) {
				if( preg_match( '/\.php$/', $plugin ) ) {
					include_once( $pluginsPath.$plugin );
				}
			}
		}

		// match up storage_type_id to plugin_guids. this _id varies from install to install, but guids are the same
		foreach( array_keys( $this->mPlugins ) as $guid ) {
			$handler = &$this->mPlugins[$guid]; //shorthand var alias
			if( !isset( $handler['verified'] ) && $handler['is_active'] =='y' ) {
				// We are missing a plugin!
				$sql = "UPDATE `".BIT_DB_PREFIX."tiki_nexus_plugins` SET `is_active`='x' WHERE `plugin_guid`=?";
				$this->mDb->query( $sql, array( $guid ) );
				$handler['is_active'] = 'n';
			} elseif( !empty( $handler['verified'] ) && $handler['is_active'] =='x' ) {
				//We found a formally missing plugin - re-enable it
				$sql = "UPDATE `".BIT_DB_PREFIX."tiki_nexus_plugins` SET `is_active`='y' WHERE `plugin_guid`=?";
				$this->mDb->query( $sql, array( $guid ) );
				$handler['is_active'] = 'y';
			} elseif( empty( $handler['verified'] ) && !isset( $handler['is_active'] ) ) {
				//We found a missing plugin - insert it
				$sql = "INSERT INTO `".BIT_DB_PREFIX."tiki_nexus_plugins` ( `plugin_guid`, `plugin_type`, `plugin_description`, `is_active` ) VALUES ( ?, ?, ?, 'y' )";
				$this->mDb->query( $sql, array( $guid, $handler['plugin_type'], $handler['description'] ) );
				$handler['is_active'] = 'y';
			}
		}
		if( !empty( $sql ) ) {
			// we just ran some SQL - let's flush the loadPlugins query cache
			$this->loadPlugins( 0 );
		}
		asort( $this->mPlugins );
	}

	function registerPlugin( $pGuid, $pPluginParams ) {
		if( isset( $this->mPlugins[$pGuid] ) ) {
			$this->mPlugins[$pGuid]['verified'] = TRUE;
		} else {
			$this->mPlugins[$pGuid]['verified'] = FALSE;
		}
		$this->mPlugins[$pGuid] = array_merge( $this->mPlugins[$pGuid], $pPluginParams );
	}

	// @parameter pPluginGuids an array of all the plugin guids that are active. Any left out are *inactive*!
	function setActivePlugins( $pPluginGuids ) {
		if( is_array( $pPluginGuids ) ) {
			$sql = "UPDATE `".BIT_DB_PREFIX."tiki_nexus_plugins` SET `is_active`='n' WHERE `is_active`!='x'";
			$this->mDb->query( $sql );
			foreach( array_keys( $this->mPlugins ) as $guid ) {
				$this->mPlugins[$guid]['is_active'] = 'n';
			}

			foreach( array_keys( $pPluginGuids ) as $guid ) {
				$sql = "UPDATE `".BIT_DB_PREFIX."tiki_nexus_plugins` SET `is_active`='y' WHERE `plugin_guid`=?";
				$this->mDb->query( $sql, array( $guid ) );
				$this->mPlugins[$guid]['is_active'] = 'y';
			}
			// we just ran some SQL - let's flush the loadPlugins query cache
			$this->loadPlugins( 0 );
		}
	}

	function getPluginFunction( $pGuid, $pFunctionName ) {
		$ret = NULL;
		if( !empty( $pGuid ) 
			&& !empty( $this->mPlugins[$pGuid] ) 
			&& !empty( $this->mPlugins[$pGuid][$pFunctionName] ) 
			&& function_exists( $this->mPlugins[$pGuid][$pFunctionName] ) 
		) {
			$ret = $this->mPlugins[$pGuid][$pFunctionName];
		}
		return $ret;
	}

	/**
	 * fucntion to store pluging settings
	 * $param $pParamHash contains settings for any guid that require updating
	 * return TRUE
	 */
	function storePluginSettings( $pParamHash ) {
		// first get all values from tiki_nexus_plugin_settings to see which ones need updating and which ones are added for the first time
		$rs = $this->mDb->query( "SELECT * FROM `".BIT_DB_PREFIX."tiki_nexus_plugin_settings`", NULL );
		while( $rs && !$rs->EOF ) {
			$settings[] = $rs->fields;
			$rs->MoveNext();
		}
		vd($settings);
	}
}

global $gNexusSystem;
$gNexusSystem = new NexusSystem();
$gNexusSystem->scanPlugins();
$gBitSmarty->assign_by_ref( 'gNexusSystem', $gNexusSystem );
?>
