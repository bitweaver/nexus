{formfeedback hash=$feedback}

{legend legend="Menu Cache"}
	<div class="row">
		{formlabel label="Menu Cache" for=""}
		{forminput}
		{smartlink ititle="Rewrite Menu Cache" rewrite_cache=1 page=$page}
			{formhelp note="This will remove any old files in the nexus menu cache directory and rewrite any exiting menus. Useful when you have renamed menus."}
		{/forminput}
	</div>
{/legend}

{form legend="Nexus Menu Plugins"}
	<input type="hidden" name="page" value="{$page}" />

	<ul class="data">
		{foreach from=$gNexusSystem->mPlugins item=plugin}
			<li class="item {cycle values='even,odd'}">
				<div class="floaticon">
					<input type="checkbox" name="plugins[{$plugin.plugin_guid}]" id="{$plugin.plugin_guid}" value="y" {if $plugin.is_active eq 'y'}checked="checked"{/if} />
				</div>
				<h3><label for="{$plugin.plugin_guid}">{$plugin.plugin_guid}</label></h3>
				{$plugin.plugin_description}<br />
				{tr}Menu subtypes{/tr}
				<ul class="small">
					{foreach from=$plugin.menu_types item=menu_type}
						<li>{$menu_type.note}</li>
					{/foreach}
				</ul>
				<small>
				{if $plugin.web_link}<strong>{tr}Online Resource{/tr}:</strong> {$plugin.web_link}<br />{/if}
					<strong>{tr}Browser Requirements{/tr}:</strong> {$plugin.browser_requirements}
				</small>
			</li>
		{/foreach}
	</ul>

	<div class="row submit">
		<input type="submit" name="pluginsave" value="{tr}Save Plugin Settings{/tr}" />
	</div>
{/form}
