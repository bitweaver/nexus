{strip}
{capture name=class}
	{if $item.head}head{else}item{/if}{if $item.rsrc_type eq 'ext'} external{/if}{if $item.rsrc_type eq 'content_id'}{ldelim}if $gContent->mContentId == {$item.rsrc}{rdelim} selected{ldelim}/if{rdelim}{/if}
{/capture}

{if $type eq 'ieo' or $type eq 'iec'}
	<span style="display:block;">
		{if $item.expand_url}
			<a style="float:left;padding:0 3px;" href="{$item.expand_url}">
				{ldelim}if $smarty.cookies.{$tog_next} == 'c' or (!$smarty.cookies.{$tog_next} and '{$type}' == 'iec'){rdelim}
					{biticon ipackage="icons" iname="list-add" id="`$tog_next`img" iexplain="expand menu"}
				{ldelim}else{rdelim}
					{biticon ipackage="icons" iname="list-remove" id="`$tog_next`img" iexplain="expand menu"}
				{ldelim}/if{rdelim}
			</a>
		{/if}

		<a class="{$smarty.capture.class}" title="{$item.hint}" {if $item.display_url}href="{$item.display_url}{/if}">
			{$item.title|escape}
		</a>
	</span>
{elseif $type eq 'iho' or $type eq 'ihc'}
	<span style="display:block;">
		{if $item.expand_url}
			<a style="float:left;padding:0 3px;" href="{$item.expand_url}">
				{ldelim}if $smarty.cookies.{$tog_next} == 'c' or (!$smarty.cookies.{$tog_next} and '{$type}' == 'ihc'){rdelim}
					{biticon ipackage="icons" iname="list-add" id="`$tog_next`img" iexplain="expand menu"}
				{ldelim}else{rdelim}
					{biticon ipackage="icons" iname="list-remove" id="`$tog_next`img" iexplain="expand menu"}
				{ldelim}/if{rdelim}
				&nbsp;&nbsp;{$item.title|escape}
			</a>
		{else}
			<a class="{$smarty.capture.class}" title="{$item.hint}" {if $item.display_url}href="{$item.display_url}{/if}">
				{$item.title|escape}
			</a>
		{/if}
	</span>
{else}
	<a class="{$smarty.capture.class}" title="{$item.hint}" {if $item.display_url}href="{$item.display_url}{/if}">
		{$item.title|escape}
	</a>
{/if}
{/strip}
