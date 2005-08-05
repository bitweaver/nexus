{strip}
<ul>
	{if $gNexus->mInfo.menu_id}
		<li><a class="head" href="{$smarty.const.NEXUS_PKG_URL}menus.php">{biticon ipackage="nexus" iname="menu" iexplain="menus"} Menus</a>
			<ul>
				<li><a class="item" href="{$smarty.const.NEXUS_PKG_URL}menu_items.php?menu_id={$gNexus->mInfo.menu_id}">{biticon ipackage="liberty" iname="edit" iexplain="edit items" iforce=icon} {tr}Add/Edit items{/tr}</a></li>
				<li><a class="item" href="{$smarty.const.NEXUS_PKG_URL}menu_sort.php?menu_id={$gNexus->mInfo.menu_id}">{biticon ipackage="nexus" iname="organise" iexplain="organise items" iforce=icon} {tr}Organise items{/tr}</a></li>
			</ul>
		</li>
	{else}
		<li><a class="item" href="{$smarty.const.NEXUS_PKG_URL}menus.php">{biticon ipackage="nexus" iname="menu" iexplain="menus" iforce=icon} Menus</a></li>
	{/if}
	{if $gBitUser->hasPermission('bit_p_admin')}
		<li><a class="item" href="{$smarty.const.NEXUS_PKG_URL}admin/nexus_plugins.php">{biticon ipackage="liberty" iname="plugin" iexplain="menus" iforce=icon} Nexus Plugins</a></li>
	{/if}
</ul>
{/strip}
