{strip}
{if $gNexus->mInfo.tree}
	{form legend="Item Details"}
		{foreach from=$gNexus->mInfo.tree item=item key=key}
			{if $item.first}<a class="dropdown-toggle" data-toggle="dropdown" href="#"> {tr}{$packageMenuTitle}{/tr} <b class="caret"></b></a>
<ul class="{$packageMenuClass}">{else}</li>{/if}
			{if $item.last}</ul>{else}
				<li>
					<div class="{cycle values='even,odd'}">
						{html_checkboxes values=$item.item_id name=remove_item id="item_`$item.item_id`" style="float:right;"}
						<a href="{$smarty.const.NEXUS_PKG_URL}menu_items.php?menu_id={$item.menu_id}&amp;item_id={$item.item_id}">{booticon iname="icon-edit" ipackage="icons" iexplain="edit item" style="float:right" iforce=icon}</a>

						<label for="item_{$item.item_id}">
							<strong>{$item.title|escape}</strong>
							{if $item.hint}&nbsp;&bull;&nbsp;{$item.hint|escape}{/if}
						</label>

						<br />

						<small>
							{foreach from=$rsrcTypes key=key item=rsrc_type}
								{if $item.rsrc_type eq $key}{$rsrc_type}{/if}
							{/foreach}: {$item.rsrc|escape}<br />
							Final link: <a{if $item.rsrc_type == 'external'} class="external"{/if}{if $item.hint} title="{$item.hint}"{/if} href="{$item.display_url}">{$item.title}</a>
							{if $item.perm}&nbsp;&bull;&nbsp;{tr}Permission{/tr}: {$item.perm}{/if}
						</small>
					</div>
			{/if}
		{/foreach}

		{if $gNexus->mInfo.tree}
			<input type="hidden" name="menu_id" value="{$gNexus->mInfo.menu_id}" />
			<input type="image" src="{booticon iname="icon-trash" ipackage="icons" iexplain='remove item' url=TRUE}" title="remove selected items" style="float:right" />
		{/if}
	{/form}
{/if}
{/strip}
