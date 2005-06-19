{strip}
{if $gBitUser->hasPermission( 'bit_p_insert_nexus_item' ) }
	{if !$inNexusMenu}
		{foreach from=$nexusList item=menu}
			{if $menu.editable}
				{assign var=nexusAssign value=TRUE}

				<div class="row">
					{formlabel label="Menu"}
					{forminput}
						{$menu.title}
					{/forminput}
				</div>

				<div class="row">
					{formlabel label="Menu description"}
					{forminput}
						{$menu.description}
					{/forminput}
				</div>

				<div class="row">
					{formlabel label="Insert in this menu" for="nexus`$menu.menu_id`"}
					{forminput}
						<input type="radio" value="{$menu.menu_id}" name="nexus[menu_id]" id="nexus{$menu.menu_id}" />
					{/forminput}
				</div>

				<div class="row">
					{formlabel label="Insert after" for="after_ref_id`$menu.menu_id`"}
					{forminput}
						<select name="nexus[after_ref_id]" id="after_ref_id{$menu.menu_id}">
							{foreach from=$menu.tree item=item}
								{if !$item.last}<option {if $item.head}style="font-weight:bold;" {/if}value="{$item.item_id}">{$item.title}</option>{/if}
							{foreachelse}
								<option value="">{tr}no items found{/tr}</option>
							{/foreach}
						</select>
					{/forminput}
				</div>

				<hr class="clear" />
			{/if}
		{/foreach}
	{else}
		<div class="row">
			{formfeedback note="This `$gContent->mType.content_description` is already part of the menu <strong>`$inNexusMenu.title`</strong>."}
			{formlabel label="Remove" for="nexusRemove"}
			{forminput}
				<input type="checkbox" name="nexus[remove_item]" id="nexusRemove" value="{$inNexusMenuItem.item_id}" />
				{formhelp note="Check thib box if you wish to remove this page from the menu. This will also allow you to insert the menu item in a different menu when editing the menu next time."}
			{/forminput}
		</div>
	{/if}

	{if $nexusList and $nexusAssign}
		<div class="row">
			{formlabel label="Don't insert into any menu" for="nexus-no-insert"}
			{forminput}
				<input type="radio" value="" name="nexus[menu_id]" id="nexus-no-insert" checked="checked" />
			{/forminput}
		</div>
	{elseif !$inNexusMenu}
		<div class="row">
			{formfeedback note="Currently there is no menu available where you can insert this `$gContent->mType.content_description`."}
		</div>
	{/if}
{/if}
{/strip}
