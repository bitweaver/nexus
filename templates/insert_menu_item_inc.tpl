{strip}
{if $gBitUser->hasPermission( 'p_nexus_insert_item' ) and $nexusList}
	{jstab title="Menu"}
		{legend legend="Insert in Menu"}
			{if !$inNexusMenu}
				{foreach from=$nexusList item=menu}
					{if $menu.editable or $gBitUser->isAdmin()}
						<div class="row">
							{formlabel label="Menu"}
							{forminput}
								{$menu.title|escape} {if $menu.description}<small>( {$menu.description|escape} )</small>{/if}
							{/forminput}
						</div>

						<div class="row">
							{formlabel label="Insert here" for="nexus_menu_`$menu.menu_id`"}
							{forminput}
								<input type="radio" value="{$menu.menu_id}" name="nexus[menu_id]" id="nexus_menu_{$menu.menu_id}" />
								&nbsp; {tr}after{/tr} &nbsp;
								<select name="nexus[after_ref_id]" id="after_ref_id{$menu.menu_id}">
									{foreach from=$menu.tree item=item}
										{if !$item.last}<option {if $item.head}style="font-weight:bold;" {/if}value="{$item.item_id}">{$item.title|escape}</option>{/if}
									{foreachelse}
										<option value="">{tr}no items found{/tr}</option>
									{/foreach}
								</select>
							{/forminput}
						</div>

						<hr class="clear" />
					{/if}
				{/foreach}

				<div class="row">
					{formlabel label="Don't insert" for="nexus-no-insert"}
					{forminput}
						<input type="radio" value="" name="nexus[menu_id]" id="nexus-no-insert" checked="checked" />
					{/forminput}
				</div>
			{else}
				<div class="row">
					<p>{tr}This {$gContent->mType.content_description} is already part of the menu <strong>{$inNexusMenu.title|escape}</strong>.{/tr}</p>
					{formlabel label="Remove" for="nexusRemove"}
					{forminput}
						<input type="checkbox" name="nexus[remove_item]" id="nexusRemove" value="{$inNexusMenuItem}" />
						{formhelp note="Check the box if you wish to remove this page from the menu. This will also allow you to insert the menu item in a different menu when editing the menu next time."}
					{/forminput}
				</div>
			{/if}
		{/legend}
	{/jstab}
{/if}
{/strip}
