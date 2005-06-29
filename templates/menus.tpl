{strip}
{if $editMenu.menu_id}
	<div class="floaticon"><a href="{$gBitLoc.PKG_NEXUS_URL}menus.php">{biticon ipackage=liberty iname=new iexplain="create new menu"}</a></div>
{/if}
<div class="display nexus">
	<div class="header">
		<h1>{tr}Menu Administration{/tr}</h1>
	</div>

	<div class="body">

		{form legend="Create / Edit Menus"}
			<input type="hidden" name="menu_id" value="{$editMenu.menu_id}" />

			{formfeedback hash=$formfeedback}

			<div class="row">
				{formlabel label="Title" for="title"}
				{forminput}
					<input type="text" name="title" id="title" size="50" value="{$editMenu.title}" />
					{formhelp note="Enter a name for your menu."}
				{/forminput}
			</div>

			<div class="row">
				{formlabel label="Description" for="description"}
				{forminput}
					<textarea name="description" id="description" cols="80" rows="3">{$editMenu.description}</textarea>
					{formhelp note="A description of this menu. This description is visible to users that can add items to this menu."}
				{/forminput}
			</div>

			<div class="row">
				{formlabel label="Editable menu" for="editable"}
				{forminput}
					{html_checkboxes name="editable" values="1" checked=`$editMenu.editable` labels=false id="editable"}
					{formhelp note="Checking this will allow users with the correct permission (bit_p_insert_nexus_item) to add menu items when they are editing content such as a wiki page or a fisheye gallery."}
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
										<option value="{$type}"{if $type eq $editMenu.type} selected="selected"{/if}>{$menu_type.label}</option>
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

		<table class="data" summary="{tr}List of menus that can be used on this site{/tr}">
			<caption>{tr}Existing menus{/tr}</caption>
			<tr>
				<th scope="col">{tr}Title{/tr}</th>
				<th scope="col">{tr}Description{/tr}</th>
				<th scope="col">{tr}GUID{/tr}</th>
				<th scope="col">{tr}Type{/tr}</th>
				<th scope="col">{tr}# of Items{/tr}</th>
				<th scope="col">{tr}Editable{/tr}</th>
				<th scope="col">{tr}Actions{/tr}</th>
			</tr>
			{foreach from=$menuList item=menu}
				<tr class="{cycle values="even,odd"}">
					<td>{$menu.title}</td>
					<td>{$menu.description}</td>
					<td>{$menu.plugin_guid}</td>
					<td>
						{assign var=plugin_guid value=$menu.plugin_guid}
						{foreach from=$gNexusSystem->mPlugins.$plugin_guid.menu_types item=menu_type key=m_type}
							{if $m_type eq $menu.type}{$menu_type.label}{/if}
						{/foreach}
					</td>
					<td style="text-align:right;">{$menu.items|@count}</td>
					<td style="text-align:center;">
						{if $menu.editable}
							{biticon ipackage=liberty iname=active iexplain="menu is editable"}
						{else}
							{biticon ipackage=liberty iname=inactive iexplain="menu is not editable"}
						{/if}
					</td>
					<td class="actionicon">
						<a href="{$gBitLoc.PKG_NEXUS_URL}menu_sort.php?menu_id={$menu.menu_id}">{biticon ipackage=nexus iname=organise iexplain='sort menu items'}</a>
						<a href="{$gBitLoc.PKG_NEXUS_URL}menu_items.php?menu_id={$menu.menu_id}">{biticon ipackage=liberty iname=edit iexplain='create and edit menu items'}</a>
						<a href="{$gBitLoc.KERNEL_PKG_URL}admin/index.php?page=layout&amp;module_name=bitpackage%3Atemp%2Fnexus%2Fmod_{$menu.title|replace:' ':'_'|lower}_{$menu.menu_id}.tpl">{biticon ipackage=liberty iname=assign iexplain=assign}</a>
						<a href="{$gBitLoc.PKG_NEXUS_URL}menus.php?action=remove_dead&amp;menu_id={$menu.menu_id}">{biticon ipackage=nexus iname=remove_dead iexplain='remove dead links'}</a>
						<a href="{$gBitLoc.PKG_NEXUS_URL}menus.php?action=edit&amp;menu_id={$menu.menu_id}">{biticon ipackage=liberty iname=config iexplain='configure menu'}</a>
						<a href="{$gBitLoc.PKG_NEXUS_URL}menus.php?action=remove&amp;menu_id={$menu.menu_id}">{biticon ipackage=liberty iname=delete iexplain='remove menu'}</a>
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
