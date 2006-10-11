<?php

global $gBitSystem, $gUpgradeFrom, $gUpgradeTo;

$upgrades = array(

	'BWR1' => array(
		'BWR2' => array(
// de-tikify tables
array( 'DATADICT' => array(
	array( 'DROPTABLE' => array(
		'tiki_nexus_plugins'
	)),
	array( 'RENAMETABLE' => array(
		'tiki_nexus_menus' => 'nexus_menus',
		'tiki_nexus_menu_items' => 'nexus_menu_items',
	)),
	array( 'RENAMECOLUMN' => array(
		'nexus_menus' => array(
			'`type`' => '`menu_type` C(16)'
		),
	)),
)),

		)
	),
);

if( isset( $upgrades[$gUpgradeFrom][$gUpgradeTo] ) ) {
	$gBitSystem->registerUpgrade( NEXUS_PKG_NAME, $upgrades[$gUpgradeFrom][$gUpgradeTo] );
}
?>
