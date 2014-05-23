{strip}
{if $editMenu.menu_id}
	<div class="floaticon"><a href="{$smarty.const.PKG_NEXUS_URL}menus.php">{booticon iname="icon-file" ipackage="icons" iexplain="create new menu"}</a></div>
{/if}
<div class="display nexus">
	<div class="header">
		<h1>{tr}Menu Administration{/tr}</h1>
	</div>

	<div class="body">

		{formfeedback hash=$formfeedback}

		{form legend="Create / Edit Menus"}
			<input type="hidden" name="menu_id" value="{$editMenu.menu_id}" />

			<div class="control-group">
				{if $editMenu}
					{formfeedback warning="{tr}Renaming an assigned menu will cause an error because the cache file will be renamed as well. You will have to make appropriate modifications in the layout page as well.{/tr}"}
				{/if}
				{formlabel label="Title" for="title"}
				{forminput}
					<input type="text" name="title" id="title" size="50" value="{$editMenu.title|escape}" />
					{formhelp note="Enter a name for your menu."}
				{/forminput}
			</div>

			<div class="control-group">
				{formlabel label="Description" for="description"}
				{forminput}
					<textarea name="description" id="description" cols="50" rows="3">{$editMenu.description|escape}</textarea>
					{formhelp note="A description of this menu. This description is visible to users that can add items to this menu."}
				{/forminput}
			</div>

			<div class="control-group">
				{formlabel label="Editable menu" for="editable"}
				{forminput}
					{html_checkboxes name="editable" values="1" checked=$editMenu.editable labels=false id="editable"}
					{formhelp note="Checking this will allow users with the correct permission (p_nexus_insert_item) to add menu items when they are editing content such as a wiki page or a fisheye gallery."}
				{/forminput}
			</div>

			<div class="control-group">
				{formlabel label="Pick menu type"}
				{forminput}
					{foreach from=$gNexusSystem->mPlugins item=plugin name=foo}
						{if $plugin.is_active eq 'y'}
							<label>
								<input type="radio" name="plugin_guid" value="{$plugin.plugin_guid}"
									{if $editMenu.plugin_guid eq $plugin.plugin_guid or ( $editMenu.plugin_guid eq '' and $plugin.plugin_guid eq 'suckerfish' )}
										checked="checked"
									{/if}
								/> {$plugin.title}
							</label>
							<br />
							<select name="type_{$plugin.plugin_guid}" id="type_{$plugin.plugin_guid}">
								{foreach from=$plugin.menu_types key=type item=menu_type}
										<option value="{$type}"{if $type eq $editMenu.menu_type} selected="selected"{/if}>{$menu_type.label}</option>
								{/foreach}
							</select>

							<script type="text/javascript">/* <![CDATA[ */
								document.write( ' <a href="javascript:flip(\'{$plugin.plugin_guid}\')">{tr}Detailed menu help{/tr}</a>' );
							/* ]]> */</script>

							{formhelp note=$plugin.description}

							<script type="text/javascript">/* <![CDATA[ */
								document.write( '<div class="formhelp" id="{$plugin.plugin_guid}" style="display:none;">' );
							/* ]]> */</script>

							<dl>
								{foreach from=$plugin.menu_types item=menu_type}
									<dt>{$menu_type.label}</dt><dd>{$menu_type.note}</dd>
								{/foreach}
							</dl>

							<script type="text/javascript">/* <![CDATA[ */
								document.write( '</div>' );
							/* ]]> */</script>

							{if !$smarty.foreach.foo.last}
								<hr />
							{/if}
						{/if}
					{/foreach}
				{/forminput}
			</div>

			<div class="control-group submit">
				<input type="submit" class="btn btn-default" name="store_menu" value="Save Settings" />
			</div>

			{formhelp note="If you want to insert a menu in the top bar, please create the menu and then assign it to the top bar in the <em>Manage Layouts</em> screen. We recommend the <em>horizontal suckerfish</em> menu for top bar menus." link="kernel/admin/index.php?page=layout/Manage Layouts" page="NexusPackage"}
		{/form}

		<table class="table data" summary="{tr}List of menus that can be used on this site{/tr}">
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
						{$menu.description|escape}
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
						<a href="{$smarty.const.NEXUS_PKG_URL}menu_sort.php?menu_id={$menu.menu_id}">{booticon iname="icon-recycle"  ipackage="icons"  iexplain='sort menu items'}</a>
						<a href="{$smarty.const.NEXUS_PKG_URL}menu_items.php?menu_id={$menu.menu_id}">{booticon iname="icon-plus-sign"  ipackage="icons"  iexplain='create and edit menu items'}</a>
						<a href="{$smarty.const.KERNEL_PKG_URL}admin/index.php?page=layout&amp;module_name=bitpackage%3Atemp%2Fnexus%2Fmod_{$menu.title|escape|replace:' ':'_'|lower}_{$menu.menu_id}.tpl">{booticon iname="icon-paperclip"  ipackage="icons"  iexplain=assign}</a>
						<a href="{$smarty.const.NEXUS_PKG_URL}menus.php?action=remove_dead&amp;menu_id={$menu.menu_id}">{biticon ipackage="icons" iname="mail-mark-junk" iexplain='remove dead links'}</a>
						<a href="{$smarty.const.NEXUS_PKG_URL}menus.php?action=edit&amp;menu_id={$menu.menu_id}">{booticon iname="icon-edit" ipackage="icons" iexplain='configure menu'}</a>
						<a href="{$smarty.const.NEXUS_PKG_URL}menus.php?action=remove&amp;menu_id={$menu.menu_id}">{booticon iname="icon-trash" ipackage="icons" iexplain='remove menu'}</a>
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
