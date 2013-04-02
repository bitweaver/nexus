{strip}
{foreach from=$gNexus->mInfo.tree item=item key=key}
	{if $item.first}<ul{if $key eq 0} class="toc"{/if}>{else}</li>{/if}
	{if $item.last}</ul>{else}
		<li>
			<div class="{cycle values='even,odd'} clear">
				<a href="{$smarty.const.NEXUS_PKG_URL}menu_items.php?sort_menu={$menuId}&amp;menu_id={$item.menu_id}&amp;item_id={$item.item_id}&amp;tab=edit">{booticon iname="icon-edit"  iforce=icon ipackage="icons"  iexplain="edit item" style="float:right"}</a>
				{biticon iforce=icon ipackage=liberty iname="spacer" iexplain="" style="float:right"}

				<a href="{$smarty.const.NEXUS_PKG_URL}menu_sort.php?sort_menu={$menuId}&amp;menu_id={$item.menu_id}&amp;item_id={$item.item_id}&amp;move_item=e&amp;tab=organise">{biticon iforce=icon ipackage="icons" iname="go-next" iexplain="move right" style="float:right"}</a>
				<a href="{$smarty.const.NEXUS_PKG_URL}menu_sort.php?sort_menu={$menuId}&amp;menu_id={$item.menu_id}&amp;item_id={$item.item_id}&amp;move_item=s&amp;tab=organise">{booticon iname="icon-cloud-download"  iforce=icon ipackage="icons"  iexplain="move down" style="float:right"}</a>
				<a href="{$smarty.const.NEXUS_PKG_URL}menu_sort.php?sort_menu={$menuId}&amp;menu_id={$item.menu_id}&amp;item_id={$item.item_id}&amp;move_item=n&amp;tab=organise">{booticon iname="icon-cloud-upload"  iforce=icon ipackage="icons"  iexplain="move up" style="float:right"}</a>
				<a href="{$smarty.const.NEXUS_PKG_URL}menu_sort.php?sort_menu={$menuId}&amp;menu_id={$item.menu_id}&amp;item_id={$item.item_id}&amp;move_item=w&amp;tab=organise">{booticon iname="icon-arrow-left"  iforce=icon ipackage="icons"  iexplain="move left" style="float:right"}</a>

				{$item.title|escape}
				{biticon iforce=icon ipackage=liberty iname="spacer" iexplain=""}
			</div>
	{/if}
{/foreach}
{/strip}
