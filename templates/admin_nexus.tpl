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

{form}
	<input type="hidden" name="page" value="{$page}" />

	<table class="panel">
		<caption>{tr}Nexus Plugins{/tr}</caption>
		<tr>
			<th style="width:70%;">{tr}Plugin{/tr}</th>
			<th style="width:20%;">{tr}GUID{/tr}</th>
			<th style="width:10%;">{tr}Active{/tr}</th>
		</tr>

		{foreach from=$gNexusSystem->mPlugins item=plugin key=guid}
			<tr class="{cycle values="odd,even"}">
				<td>
					<h3>{$plugin.title|escape}</h3>
					<label for="{$guid}">
						{$plugin.description|escape}
					</label>
				</td>
				<td>{$guid}</td>
				<td align="center">
					{if $plugin.is_active == 'x'}
						{tr}Missing{/tr}
					{else}
						{html_checkboxes name="plugins[`$guid`]" values="y" checked=`$plugin.is_active` labels=false id=$guid}
					{/if}
				</td>
			</tr>
		{/foreach}
	</table>

	<div class="row submit">
		<input type="submit" name="pluginsave" value="{tr}Save Plugin Settings{/tr}" />
	</div>
{/form}
