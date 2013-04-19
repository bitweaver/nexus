{strip}
{if $packageMenuTitle}<a href="#"> {tr}{$packageMenuTitle|capitalize}{/tr}</a>{/if}
<ul class="{$packageMenuClass}">
	<li><a class="item" href="{$smarty.const.KERNEL_PKG_URL}admin/index.php?page=nexus">{tr}Nexus{/tr}</a></li>
	<li><a class="item" href="{$smarty.const.NEXUS_PKG_URL}">{tr}Nexus Menus{/tr}</a></li>
</ul>
{/strip}
