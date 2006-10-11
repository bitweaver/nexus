<?php
require_once( NEXUS_PKG_PATH.'Nexus.php');

$gNexus = new Nexus();
$gNexusSystem->scanAllPlugins( NEXUS_PKG_PATH.'plugins/' );

$feedback = array();

if( !empty( $_REQUEST['rewrite_cache'] ) ) {
	if( $gNexus->rewriteMenuCache() ) {
		$feedback['success'] = tra( 'The complete menu cache has been rewritten.' );
	}
}

if( !empty( $_REQUEST['pluginsave'] ) ) {
	$gNexusSystem->setActivePlugins( $_REQUEST['plugins'] );
	$feedback['success'] = tra( 'The plugins were successfully updated' );
}
$gBitSmarty->assign( 'feedback', $feedback );
?>
