<?php
/**
 * @version      $Header$
 *
 * @author       xing  <xing@synapse.plus.com>
 * @version      $Revision$
 * created      Monday Jul 03, 2006   11:06:47 CEST
 * @package      nexus
 * @copyright    2003-2006 bitweaver
 * @license      LGPL {@link http://www.gnu.org/licenses/lgpl.html}
 **/

/**
 * Setup
 */
require_once( LIBERTY_PKG_CLASS_PATH.'LibertySystem.php' );
define( 'NEXUS_DEFAULT_MENU', 'suckerfish' );

/**
 *   NexusSystem 
 * 
 * @package nexus
 * @uses LibertySystem
 */
class NexusSystem extends LibertySystem {
	// Contains plugin information
	var $mPlugins;

	/**
	 * Initiate class
	 * 
	 * @access public
	 * @return void
	 */
	function NexusSystem() {
		// Set the package using LibertySystem
		$this->mSystem     = NEXUS_PKG_NAME;
		$this->mPluginPath = NEXUS_PKG_PATH."plugins/";

		parent::__construct( FALSE );
	}
}
?>
