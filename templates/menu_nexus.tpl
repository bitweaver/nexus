{strip}
<ul>
	{if $gNexus->mInfo.menu_id}
		<li><a class="head" href="{$smarty.const.NEXUS_PKG_URL}menus.php">{biticon ipackage="icons" iname="folder-remote" iexplain="menus"} Menus</a>
		<li><a class="item" href="{$smarty.const.NEXUS_PKG_URL}menu_items.php?menu_id={$gNexus->mInfo.menu_id}">{biticon ipackage="icons" iname="accessories-text-editor" iexplain="edit items" iforce=icon} {tr}Add/Edit items{/tr}</a></li>
		<li><a class="item" href="{$smarty.const.NEXUS_PKG_URL}menu_sort.php?menu_id={$gNexus->mInfo.menu_id}">{biticon ipackage="icons" iname="view-refresh" iexplain="organise items" iforce=icon} {tr}Organise items{/tr}</a></li>
	{else}
		<li><a class="item" href="{$smarty.const.NEXUS_PKG_URL}menus.php">{biticon ipackage="icons" iname="folder-remote" iexplain="menus" iforce=icon} Menus</a></li>
	{/if}
</ul>
{/strip}
