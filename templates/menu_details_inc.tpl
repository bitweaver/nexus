{strip}
{legend legend="Menu settings"}
	<div class="row">
		{formlabel label="Title"}
		{forminput}
			{formfeedback note=$gNexus->mInfo.title}
		{/forminput}
	</div>

	<div class="row">
		{formlabel label="Description"}
		{forminput}
			{formfeedback note=$gNexus->mInfo.description}
		{/forminput}
	</div>

	<div class="row">
		{formlabel label="Editable"}
		{forminput}
			{if $gNexus->mInfo.editable}
				{formfeedback note='yes'}
			{else}
				{formfeedback note='no'}
			{/if}
		{/forminput}
	</div>

	<div class="row">
		{formlabel label="Menu Type"}
		{forminput}
			{formfeedback note=$gNexus->mInfo.plugin_guid}
		{/forminput}
	</div>

	<div class="row">
		{formlabel label="Menu subtype"}
		{forminput}
			{assign var=plugin_guid value=$gNexus->mInfo.plugin_guid}
			{foreach from=$gNexusSystem->mPlugins.$plugin_guid.menu_types item=menu_type key=m_type}
				{if $m_type eq $gNexus->mInfo.type}{$menu_type.label}{/if}
			{/foreach}
		{/forminput}
	</div>
{/legend}
{/strip}
