<?php
	/**
	 * @global BigTreeAdmin $admin
	 * @global array $bigtree
	 */
	
	$pending_entry = BigTreeAutoModule::getPendingItem("btx_reusable_callouts", $bigtree["commands"][0]);
	$original_item = BigTreeAutoModule::getItem("btx_reusable_callouts", $bigtree["commands"][0]);
		
	if (!$pending_entry) {
?>
<div class="container">
	<section>
		<h3>Error</h3>
		<p>The item you are trying to edit no longer exists.</p>
	</section>
</div>
<?php
	} else {
		$bigtree["entry"] = $item = $pending_entry["item"];

		// Check access levels
		$bigtree["access_level"] = $admin->getAccessLevel($bigtree["module"], $item, "btx_reusable_callouts");
		
		if ($bigtree["access_level"] != "n") {
			$original_permission_level = $admin->getAccessLevel($bigtree["module"], $original_item["item"], "btx_reusable_callouts");
			
			if ($original_permission_level != "p") {
				$bigtree["access_level"] = $original_permission_level;
			}
		}

		$callout_list = $admin->getCalloutsAllowed("name ASC");
		$bigtree["callout_key"] = "data";
		
		// Pretend we loaded over AJAX
		$_POST["data"] = $bigtree["entry"]["data"][0];
?>
<form method="post" enctype="multipart/form-data" action="<?=MODULE_ROOT?>process/">
	<input type="hidden" name="id" value="<?=htmlspecialchars($bigtree["commands"][0])?>" />
	<div class="container">
		<?php
			include "_form.php";
		?>
		<footer>
			<input type="submit" class="button<?php if ($bigtree["access_level"] != "p") { ?> blue<?php } ?>" tabindex="<?=$bigtree["tabindex"]?>" value="Save" name="save" />
			<input type="submit" class="button blue" tabindex="<?=($bigtree["tabindex"] + 1)?>" value="Save &amp; Publish" name="save_and_publish" <?php if ($bigtree["access_level"] != "p") { ?>style="display: none;" <?php } ?>/>
		</footer>
	</div>
</form>

<script>
	(function() {
		var Select = $("#callout_select");
		var Fields = $("#callout_field_area");

		Select.change(function(event) {
			// TinyMCE tooltips and menus sometimes get stuck
			$(".mce-tooltip, .mce-menu").remove();

			Fields.load("<?=ADMIN_ROOT?>ajax/callout/", {
				type: $(this).val(),
				count: 0,
				key: "data",
				data: <?=json_encode($bigtree["entry"]["data"][0])?>,
				tab_index: 3,
				btx_reusable_callouts_editor: true
			}, function() {
				BigTree.formHooks("#callout_field_area");
				BigTreeCustomControls("#callout_field_area");
			});
		});
	})();
</script>
<?php
	}
