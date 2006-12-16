{strip}
{if $editItem.menu_id}
	<div class="floaticon"><a href="{$smarty.const.NEXUS_PKG_URL}menu_items.php?tab=edit&amp;menu_id={$editItem.menu_id}">{biticon ipackage="icons" iname="document-new" iexplain="create new menu"}</a></div>
{/if}

<div class="display nexus">
	<div class="header">
		<h1>{tr}Create / Edit Menu Items{/tr}</h1>
	</div>

	<div class="body">
		{formfeedback hash=$formfeedback}
		{if $delList}
			<ul>
				{foreach from=$delList item=delItem}
					<li>{$delItem}</li>
				{/foreach}
			</ul>
		{/if}

		{jstabs}
			{jstab title="Edit Menu Items"}
				{include file="bitpackage:nexus/menu_items_edit_inc.tpl"}
			{/jstab}

			{jstab title="Menu Details"}
				{include file="bitpackage:nexus/menu_details_inc.tpl"}
			{/jstab}
		{/jstabs}

		{include file="bitpackage:nexus/menu_items_details_inc.tpl"}
	</div><!-- end .body -->
</div><!-- end .nexus -->
{/strip}
