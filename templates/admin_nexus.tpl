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
