{strip}
{legend legend="Menu settings"}
	<div class="row">
		{formlabel label="Title"}
		{forminput}
			{$gNexus->mInfo.title|escape}
		{/forminput}
	</div>

	<div class="row">
		{formlabel label="Description"}
		{forminput}
			{$gNexus->mInfo.description|escape}
		{/forminput}
	</div>

	<div class="row">
		{formlabel label="Editable"}
		{forminput}
			{if $gNexus->mInfo.editable}
				{biticon ipackage=liberty iname=active iexplain="Editable"}
			{else}
				{biticon ipackage=liberty iname=inactive iexplain="Not editable"}
			{/if}
		{/forminput}
	</div>

	<div class="row">
		{formlabel label="Menu Type"}
		{forminput}
			{$gNexus->mInfo.plugin_guid}
		{/forminput}
	</div>

	<div class="row">
		{formlabel label="Menu subtype"}
		{forminput}
			{assign var=plugin_guid value=$gNexus->mInfo.plugin_guid}
			{foreach from=$gNexusSystem->mPlugins.$plugin_guid.menu_types item=menu_type key=m_type}
				{if $m_type eq $gNexus->mInfo.menu_type}{$menu_type.label}{/if}
			{/foreach}
		{/forminput}
	</div>
{/legend}
{/strip}
