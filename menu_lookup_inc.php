<?php
global $gNexus;

if ( !empty( $_REQUEST['menu_id'] ) && is_numeric( $_REQUEST['menu_id'] ) ) {
	$menuId = $_REQUEST['menu_id'];
	$gNexus = new Nexus( $menuId );
} else {
	$gNexus = new Nexus();
	$menuId = NULL;
}

$smarty->assign_by_ref( 'gNexus', $gNexus );
$smarty->assign_by_ref( 'menuId', $menuId );
?>
