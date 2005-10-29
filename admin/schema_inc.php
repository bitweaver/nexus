<?php

$tables = array(
'tiki_nexus_plugins' => "
	plugin_guid C(16) PRIMARY,
	plugin_type C(16) NOTNULL,
	is_active C(1) NOTNULL DEFAULT 'y',
	plugin_description C(250),
	maintainer_url C(250)
",

'tiki_nexus_menus' => "
	menu_id I4 AUTO PRIMARY,
	plugin_guid C(16) NOTNULL,
	title C(128),
	description C(255),
	type C(16),
	editable I4 DEFAULT 0
",

'tiki_nexus_menu_items' => "
	item_id I4 AUTO PRIMARY,
	menu_id I4 DEFAULT 0,
	parent_id I4,
	ext_menu I4,
	pos I4,
	title C(128),
	hint C(255),
	rsrc C(255),
	rsrc_type C(16),
	perm C(128)
",
);

global $gBitInstaller;

foreach( array_keys( $tables ) AS $tableName ) {
	$gBitInstaller->registerSchemaTable( NEXUS_PKG_NAME, $tableName, $tables[$tableName] );
}

$gBitInstaller->registerPackageInfo( NEXUS_PKG_NAME, array(
	'description' => 'Nexus allows you to create multi level menus using a simple and intuitive interface. Menus can be of a dropdown style using css.',
	'license' => '<a href="http://www.gnu.org/licenses/licenses.html#LGPL">LGPL</a>',
	'version' => '0.1',
	'state' => 'experimental',
	'dependencies' => '',
) );

// ### Default UserPermissions
$gBitInstaller->registerUserPermissions( NEXUS_PKG_NAME, array(
	array('bit_p_insert_nexus_item', 'Can insert menu item in a menu while editing a page', 'editors', NEXUS_PKG_NAME),
	array('bit_p_create_nexus_menus', 'Can create new menus using Nexus', 'editors', NEXUS_PKG_NAME),
) );

// ### Default Preferences
$gBitInstaller->registerPreferences( NEXUS_PKG_NAME, array(
//	array(NEXUS_PKG_NAME, 'pref','value'),
) );

?>
