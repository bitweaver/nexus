<?php
/**
 * @author   xing <xing@synapse.plus.com>
 * @version  $Revision: 1.1.1.1.2.4 $
 * @package  nexus
 * @subpackage functions
 */
global $gNexus;

if( @BitBase::verifyId( $_REQUEST['menu_id'] ) ) {
	$menuId = $_REQUEST['menu_id'];
	$gNexus = new Nexus( $menuId );
} else {
	$gNexus = new Nexus();
	$menuId = NULL;
}
$gNexus -> load();

$gBitSmarty->assign_by_ref( 'gNexus', $gNexus );
$gBitSmarty->assign_by_ref( 'menuId', $menuId );
?>
