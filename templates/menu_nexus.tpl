{strip}
{if $packageMenuTitle}<a class="dropdown-toggle" data-toggle="dropdown" href="#"> {tr}{$packageMenuTitle}{/tr} <b class="caret"></b></a>{/if}
<ul class="{$packageMenuClass}">
	<li><a class="head" href="{$smarty.const.NEXUS_PKG_URL}menus.php">{booticon ipackage="icons" iname="icon-sitemap" iexplain="Menus" ilocation=menu}</a></li>
	{if $gNexus->mInfo.menu_id}
		<li><a class="item" href="{$smarty.const.NEXUS_PKG_URL}menu_items.php?menu_id={$gNexus->mInfo.menu_id}">{booticon iname="icon-edit" ipackage="icons" iexplain="Edit Items" ilocation=menu}</a></li>
		<li><a class="item" href="{$smarty.const.NEXUS_PKG_URL}menu_sort.php?menu_id={$gNexus->mInfo.menu_id}">{booticon iname="icon-recycle"  ipackage="icons"  iexplain="Organise Items" ilocation=menu}</a></li>
	{/if}
</ul>
{/strip}
