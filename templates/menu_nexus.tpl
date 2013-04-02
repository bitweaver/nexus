{strip}
<ul>
	<li><a class="head" href="{$smarty.const.NEXUS_PKG_URL}menus.php">{biticon ipackage="icons" iname="folder-remote" iexplain="Menus" ilocation=menu}</a></li>
	{if $gNexus->mInfo.menu_id}
		<li><a class="item" href="{$smarty.const.NEXUS_PKG_URL}menu_items.php?menu_id={$gNexus->mInfo.menu_id}">{booticon iname="icon-edit" ipackage="icons" iexplain="Edit Items" ilocation=menu}</a></li>
		<li><a class="item" href="{$smarty.const.NEXUS_PKG_URL}menu_sort.php?menu_id={$gNexus->mInfo.menu_id}">{biticon ipackage="icons" iname="view-refresh" iexplain="Organise Items" ilocation=menu}</a></li>
	{/if}
</ul>
{/strip}
