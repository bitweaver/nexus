<?php
global $gBitSystem, $gBitUser;
$gBitSystem->registerPackage( 'nexus', dirname( __FILE__).'/' );

if( $gBitSystem->isPackageActive( 'nexus' ) ) {
	if( $gBitUser->isAdmin() ) {
			$gBitSystem->registerAppMenu( 'nexus', 'Nexus', NEXUS_PKG_URL.'index.php', 'bitpackage:nexus/menu_nexus.tpl', 'Nexus menus');
	}
}
?>
