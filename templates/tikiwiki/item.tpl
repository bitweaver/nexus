 {strip}
{if $item.head and ( $type eq 'ieo' or $type eq 'iec' )}
	<span style="display:block;">
{/if}
{if $item.expand_url}
	<a style="float:left;padding:0 3px;" href="{$item.expand_url}">
		{biticon ipackage=liberty iname=folder id="`$tog_next`img" iexplain="expand menu"}
	</a>
{/if}
<a class="{if $item.head}head{else}item{/if}{if $item.rsrc_type eq 'ext'} external{/if}" title="{$item.hint}" {if $item.display_url}href="{$item.display_url}{/if}">
	{$item.title}
</a>
{if $item.head and ( $type eq 'ieo' or $type eq 'iec' )}
	</span>
{/if}
{if $item.head and ( $type eq 'ieo' or $type eq 'iec' )}
	<script type="text/javascript">
		setfoldericonstate('{$tog_next}');
	</script>
{/if}
{/strip}
