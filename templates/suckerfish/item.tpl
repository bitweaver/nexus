<{if $item.display_url}a{else}span{/if} class="{if $item.head}head{else}item{/if}{if $item.rsrc_type eq 'content_id'}{ldelim}if $gContent->mContentId == {$item.rsrc}{rdelim} selected{ldelim}/if{rdelim}{/if}{if $item.rsrc_type eq 'ext'} external{/if}" title="{$item.hint}"{if $item.display_url} href="{$item.display_url|escape:html}"{/if}>{$item.title|escape:html}</{if $item.display_url}a{else}span{/if}>
