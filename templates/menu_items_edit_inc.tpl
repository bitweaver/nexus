{strip}
{form legend="Add / Edit menu items"}
	<input type="hidden" name="menu_id" value="{$gNexus->mInfo.menu_id}" />
	<input type="hidden" name="item_id" value="{$editItem.item_id}" />
	<input type="hidden" name="tab" value="edit" />

	<div class="row">
		{formlabel label="Link to content" for="content_type_guid"}
		{forminput}
			{html_options options=$contentTypes name=content_type_guid id=content_type_guid selected=$smarty.request.content_type_guid}
		{/forminput}

		{forminput}
			<input type="text" name="find_objects" value="{$smarty.request.find_objects}" /> 
			<input type="submit" value="{tr}filter{/tr}" name="search_objects" />
			{formhelp note=""}
		{/forminput}

		{forminput}
			{html_options name="content" options=$contentList onchange="document.getElementById('rsrc').value=options[selectedIndex].value;document.getElementById('title').value=options[selectedIndex].label.replace(/ \[id.*?\]/,'');document.getElementById('rsrc_type').value='content_id';"}
			<noscript>
				{formhelp note="Since you don't have javascript (enabled), please insert the appropriate information from the dropdown manually. The content ID is the number associated with the item in the dropdown list."}
			</noscript>
		{/forminput}
	</div>

	{if $menuList}
		<div class="row">
			{formlabel label="Insert menu" for="menu_list"}
			{forminput}
				{html_options name="menu_list" options=$menuList onchange="document.getElementById('rsrc').value=options[selectedIndex].value;document.getElementById('title').value=options[selectedIndex].label.replace(/ \[id.*?\]/,'');document.getElementById('rsrc_type').value='menu_id';"}
				<noscript>
					{formhelp note="Since you don't have javascript (enabled), please insert the appropriate information from the dropdown manually. The content ID is the number associated with the item in the dropdown list."}
				</noscript>
			{/forminput}
		</div>
	{/if}

	<div class="row">
		<hr />
	</div>

	<div class="row">
		{formlabel label="Title" for="title"}
		{forminput}
			<input type="text" name="title" id="title" size="50" value="{$editItem.title|escape}" />
			{formhelp note="Enter a title for your menu item."}
		{/forminput}
	</div>

	<div class="row">
		{formlabel label="Hint" for="hint"}
		{forminput}
			<textarea name="hint" id="hint" cols="50" rows="2">{$editItem.hint|escape}</textarea>
			{formhelp note="A hint for this item. This hint is visible when you hover over the menu item - hint is set as 'title' attribute for link (for menu plugins that support this feature)."}
		{/forminput}
	</div>

	<div class="row">
		{formlabel label="Resource type" for="rsrc_type"}
		{forminput}
			{html_options name="rsrc_type" id="rsrc_type" options=$rsrcTypes selected=`$editItem.rsrc_type`}
			{formhelp note="Here you can pick the resource type you wish to link to."}
		{/forminput}
	</div>

	<div class="row">
		{formlabel label="Resource link" for="rsrc"}
		{forminput}
			<input type="text" name="rsrc" id="rsrc" size="50" value="{$editItem.rsrc|escape}" />
			{formhelp note="<dl><dt>External URL</dt><dd>enter full link. e.g.: <strong>http://www.example.com</strong></dd><dt>Internal URL</dt><dd>enter link beginning from your bitweaver installation directory. e.g.: <strong>wiki/rankings.php</strong></dd><dt>Content ID</dt><dd>enter the number referring to some content (e.g. the number assoctiated with each item in the content dropdown is a content ID). e.g.: <strong>3</strong></dd><dt>Structure ID</dt><dd>Enter the structure ID that you want to use.</dd></dl>"}
		{/forminput}
	</div>

	<div class="row">
		{formlabel label="Permission" for="perm"}
		{forminput}
			{html_options name="perm" id="perm" options=$perms selected=`$editItem.perm`}
			{formhelp note="Permission required to view this item and any children associated with it. If no permission is selected, the menu is visible to all users."}
		{/forminput}
	</div>

	{if !$editItem.item_id}
		<div class="row">
			{formlabel label="Insert after" for="after_ref_id"}
			{forminput}
				<select name="after_ref_id" id="after_ref_id">
					{foreach from=$gNexus->mInfo.items item=item}
						<option value="{$item.item_id}">{$item.title|escape}</option>
					{foreachelse}
						<option value="">{tr}no items found{/tr}</option>
					{/foreach}
				</select>
				{formhelp note="Pick the position after which you want to add the item."}
			{/forminput}
		</div>
	{/if}

	<div class="row submit">
		<input type="submit" name="store_item" value="{tr}Save Item{/tr}" />
	</div>
{/form}
{/strip}
