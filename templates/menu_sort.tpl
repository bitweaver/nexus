{strip}
{if $editItem.menu_id}
	<div class="floaticon"><a href="{$smarty.const.PKG_NEXUS_URL}menu_items.php?tab=edit&amp;menu_id={$editItem.menu_id}">{booticon iname="icon-file" ipackage="icons" iexplain="create new menu"}</a></div>
{/if}

<div class="display nexus">
	<div class="header">
		<h1>{tr}Organise Menu Items{/tr}</h1>
	</div>

	<div class="body">
		{include file="bitpackage:nexus/menu_items_organise_inc.tpl"}

		{*jstabs}
			{jstab title="Organise Items"}
				{include file="bitpackage:nexus/menu_items_organise_inc.tpl"}
			{/jstab}

			{jstab title="Preview Menu"}
				{include file="bitpackage:nexus/menu_items_preview_inc.tpl"}
			{/jstab}
		{/jstabs*}
	</div><!-- end .body -->
</div><!-- end .nexus -->
{/strip}
