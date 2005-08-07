{strip}
{foreach from=$gNexus->mInfo.tree item=item key=key}
	{if $item.first}<ul{if $key eq 0} class="toc"{/if}>{else}</li>{/if}
	{if $item.last}</ul>{else}
		<li>
			<div class="{cycle values='even,odd'}">
				<a href="{$smarty.const.NEXUS_PKG_URL}menu_items.php?sort_menu={$menuId}&amp;menu_id={$item.menu_id}&amp;item_id={$item.item_id}&amp;tab=edit">{biticon iforce=icon ipackage=liberty iname="edit" iexplain="edit item" style="float:right"}</a>
				{biticon iforce=icon ipackage=liberty iname="spacer" iexplain="" style="float:right"}

				<a href="{$smarty.const.NEXUS_PKG_URL}menu_sort.php?sort_menu={$menuId}&amp;menu_id={$item.menu_id}&amp;item_id={$item.item_id}&amp;move_item=e&amp;tab=organise">{biticon iforce=icon ipackage=liberty iname="nav_next" iexplain="move right" style="float:right"}</a>
				<a href="{$smarty.const.NEXUS_PKG_URL}menu_sort.php?sort_menu={$menuId}&amp;menu_id={$item.menu_id}&amp;item_id={$item.item_id}&amp;move_item=s&amp;tab=organise">{biticon iforce=icon ipackage=liberty iname="nav_down" iexplain="move down" style="float:right"}</a>
				<a href="{$smarty.const.NEXUS_PKG_URL}menu_sort.php?sort_menu={$menuId}&amp;menu_id={$item.menu_id}&amp;item_id={$item.item_id}&amp;move_item=n&amp;tab=organise">{biticon iforce=icon ipackage=liberty iname="nav_up" iexplain="move up" style="float:right"}</a>
				<a href="{$smarty.const.NEXUS_PKG_URL}menu_sort.php?sort_menu={$menuId}&amp;menu_id={$item.menu_id}&amp;item_id={$item.item_id}&amp;move_item=w&amp;tab=organise">{biticon iforce=icon ipackage=liberty iname="nav_prev" iexplain="move left" style="float:right"}</a>

				{$item.title}
				{biticon iforce=icon ipackage=liberty iname="spacer" iexplain=""}
			</div>
	{/if}
{/foreach}
{/strip}
