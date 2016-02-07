<?php
/**
 * @author   xing <xing@synapse.plus.com>
 * @version  $Revision$
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

$gBitSmarty->assignByRef( 'gNexus', $gNexus );
$gBitSmarty->assignByRef( 'menuId', $menuId );
?>
