<?php
require_once( '../../bit_setup_inc.php' );
global $gBitSystem;
require_once( NEXUS_PKG_PATH.'Nexus.php');

if( isset( $_REQUEST['store_plugins'] ) ) {
	$gNexusSystem->setActivePlugins( $_REQUEST['PLUGINS'] );
}

$gBitSystem->setBrowserTitle( 'Nexus Menus' );
$gBitSystem->display( 'bitpackage:nexus/nexus_plugins.tpl' );
?>
