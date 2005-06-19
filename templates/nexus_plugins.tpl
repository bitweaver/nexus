{strip}
<div class="admin nexus">
	<div class="header">
		<h1>{tr}Nexus Menu Plugins{/tr}</h1>
	</div>

	<div class="body">
		{form legend="Nexus Menu Plugins"}
			<ul class="data">
				{foreach from=$gNexusSystem->mPlugins item=plugin}
					{if $plugin.verified}
						<li class="item {cycle values='even,odd'}">
							<div class="floaticon">
								<input type="checkbox" name="PLUGINS[{$plugin.plugin_guid}]" id="{$plugin.plugin_guid}" value="y" {if $plugin.is_active eq 'y'}checked="checked"{/if} />
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
					{/if}
				{/foreach}
			</ul>

			<div class="row submit">
				<input type="submit" name="store_plugins" value="{tr}Save Plugin Settings{/tr}" />
			</div>
		{/form}
	</div><!-- end .body -->
</div><!-- end .nexus -->
{/strip}
