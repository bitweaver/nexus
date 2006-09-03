{strip}
{if $editMenu.menu_id}
	<div class="floaticon"><a href="{$smarty.const.PKG_NEXUS_URL}menus.php">{biticon ipackage="icons" iname="document-new" iexplain="create new menu"}</a></div>
{/if}
<div class="display nexus">
	<div class="header">
		<h1>{tr}Menu Administration{/tr}</h1>
	</div>

	<div class="body">

		{formfeedback hash=$formfeedback}

		{jstabs}
			{jstab title="Create / Edit Menus"}
				{form legend="Create / Edit Menus"}
					<input type="hidden" name="menu_id" value="{$editMenu.menu_id}" />

					<div class="row">
						{if $editMenu}
							{formfeedback warning="{tr}Renaming an assigned menu will cause an error because the cache file will be renamed as well. You will have to make appropriate modifications in the layout page as well.{/tr}"}
						{/if}
						{formlabel label="Title" for="title"}
						{forminput}
							<input type="text" name="title" id="title" size="50" value="{$editMenu.title|escape}" />
							{formhelp note="Enter a name for your menu."}
						{/forminput}
					</div>

					<div class="row">
						{formlabel label="Description" for="description"}
						{forminput}
							<textarea name="description" id="description" cols="50" rows="3">{$editMenu.description}</textarea>
							{formhelp note="A description of this menu. This description is visible to users that can add items to this menu."}
						{/forminput}
					</div>

					<div class="row">
						{formlabel label="Editable menu" for="editable"}
						{forminput}
							{html_checkboxes name="editable" values="1" checked=`$editMenu.editable` labels=false id="editable"}
							{formhelp note="Checking this will allow users with the correct permission (p_nexus_insert_item) to add menu items when they are editing content such as a wiki page or a fisheye gallery."}
						{/forminput}
					</div>

					<div class="row">
						{formlabel label="Pick menu type"}
						{forminput}
							{foreach from=$gNexusSystem->mPlugins item=plugin}
								{if $plugin.is_active eq 'y'}
									<div class="row">
										<label>
											<input type="radio" name="plugin_guid" value="{$plugin.plugin_guid}"
												{if $editMenu.plugin_guid eq $plugin.plugin_guid or ( $editMenu.plugin_guid eq '' and $plugin.plugin_guid eq 'suckerfish' )}
													checked="checked"
												{/if}
											/>
											&nbsp;{$plugin.plugin_guid}
										</label>
										<br />
										&nbsp;
										<select name="type_{$plugin.plugin_guid}" id="type_{$plugin.plugin_guid}">
											{foreach from=$plugin.menu_types key=type item=menu_type}
												{if $type eq hor and $use_custom_top_bar and !$editMenu.menu_type eq 'hor'}
													<option value="">{tr}Only one horizontal menu can exist.{/tr}</option>
												{else}
													<option value="{$type}"{if $type eq $editMenu.menu_type} selected="selected"{/if}>{$menu_type.label}</option>
												{/if}
											{/foreach}
										</select>
										{formhelp note=$plugin.plugin_description}
										<div class="formhelp">
											<dl>
												{foreach from=$plugin.menu_types item=menu_type}
													<dt>{$menu_type.label}</dt><dd>{$menu_type.note}</dd>
												{/foreach}
											</dl>
										</div>
									</div>
								{/if}
							{/foreach}
						{/forminput}
					</div>

					<div class="row submit">
						<input type="submit" name="store_menu" value="Save Settings" />
					</div>
				{/form}
			{/jstab}

			{jstab title="Advanced Settings"}
				{form legend="Top Bar Menu Position"}
					<div class="row">
						{formlabel label="Position" for="nexus_top_bar"}
						{forminput}
							<select name="nexus_top_bar" id="nexus_top_bar">
								<option value="right" {if $gBitSystem->getConfig('nexus_top_bar') eq 'right'}selected="selected"{/if}>{tr}To the right of the bitweaver menu{/tr}</option>
								<option value="left" {if $gBitSystem->getConfig('nexus_top_bar') eq 'left'}selected="selected"{/if}>{tr}To the left of the bitweaver menu{/tr}</option>
								<option value="replace" {if $gBitSystem->getConfig('nexus_top_bar') eq 'replace'}selected="selected"{/if}>{tr}Replace the bitweaver menu{/tr}</option>
							</select>
							{formhelp note="Here you can set the position of where your custom top bar menu should be. This setting is only used when you are using a horizontal suckerfish menu."}
						{/forminput}
					</div>

					<div class="row submit">
						<input type="submit" name="store_pos" value="Save Settings" />
					</div>
				{/form}

				{legend legend="Menu Cache"}
					<div class="row">
						{formlabel label="Menu Cache" for=""}
						{forminput}
							{smartlink ititle="Rewrite Menu Cache" action=rewrite_cache}
							{formhelp note="This will remove any old files in the nexus menu cache directory and rewrite any exiting menus. Useful when you have renamed menus."}
						{/forminput}
					</div>
				{/legend}
			{/jstab}
		{/jstabs}

		<table class="data" summary="{tr}List of menus that can be used on this site{/tr}">
			<caption>{tr}Existing menus{/tr}</caption>
			<tr>
				<th scope="col">{tr}Title{/tr} / {tr}Description{/tr}</th>
				<th scope="col">{tr}GUID{/tr}</th>
				<th scope="col">{tr}Type{/tr}</th>
				<th scope="col">{tr}# of Items{/tr}</th>
				<th scope="col">{tr}Editable{/tr}</th>
				<th scope="col">{tr}Actions{/tr}</th>
			</tr>
			{foreach from=$menuList item=menu}
				<tr class="{cycle values="even,odd"}">
					<td>
						<h2>{$menu.title|escape}</h2>
						{$menu.description}
					</td>
					<td>{$menu.plugin_guid}</td>
					<td>
						{assign var=plugin_guid value=$menu.plugin_guid}
						{foreach from=$gNexusSystem->mPlugins.$plugin_guid.menu_types item=menu_type key=m_type}
							{if $m_type eq $menu.menu_type}{$menu_type.label}{/if}
						{/foreach}
					</td>
					<td style="text-align:right;">{$menu.items|@count}</td>
					<td style="text-align:center;">
						{if $menu.editable}
							{biticon ipackage="icons" iname="face-smile" iexplain="menu is editable"}
						{else}
							{biticon ipackage="icons" iname="face-sad" iexplain="menu is not editable"}
						{/if}
					</td>
					<td class="actionicon">
						<a href="{$smarty.const.NEXUS_PKG_URL}menu_sort.php?menu_id={$menu.menu_id}">{biticon ipackage="icons" iname="view-refresh" iexplain='sort menu items'}</a>
						<a href="{$smarty.const.NEXUS_PKG_URL}menu_items.php?menu_id={$menu.menu_id}">{biticon ipackage="icons" iname="accessories-text-editor" iexplain='create and edit menu items'}</a>
						<a href="{$smarty.const.KERNEL_PKG_URL}admin/index.php?page=layout&amp;module_name=bitpackage%3Atemp%2Fnexus%2Fmod_{$menu.title|escape|replace:' ':'_'|lower}_{$menu.menu_id}.tpl">{biticon ipackage="icons" iname="mail-attachment" iexplain=assign}</a>
						<a href="{$smarty.const.NEXUS_PKG_URL}menus.php?action=remove_dead&amp;menu_id={$menu.menu_id}">{biticon ipackage="icons" iname="mail-mark-junk" iexplain='remove dead links'}</a>
						<a href="{$smarty.const.NEXUS_PKG_URL}menus.php?action=edit&amp;menu_id={$menu.menu_id}">{biticon ipackage="icons" iname="document-properties" iexplain='configure menu'}</a>
						<a href="{$smarty.const.NEXUS_PKG_URL}menus.php?action=remove&amp;menu_id={$menu.menu_id}">{biticon ipackage="icons" iname="edit-delete" iexplain='remove menu'}</a>
					</td>
				</tr>
			{foreachelse}
				<tr>
					<td class="norecords" colspan="7">{tr}No Records Found{/tr}</td>
				</tr>
			{/foreach}
		</table>
	</div><!-- end .body -->
</div><!-- end .nexus -->
{/strip}
