<a class="{if $item.head}head{else}item{/if}{if $item.rsrc_type eq 'ext'} external{/if}" title="{$item.hint}"{if $item.display_url} href="{$item.display_url|escape:"html"}"{/if}>{$item.title|escape:"html"}</a>