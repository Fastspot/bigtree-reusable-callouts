<?php
	/**
	 * @global BigTreeAdmin $admin
	 * @global array $bigtree
	 */

	$bigtree["access_level"] = $admin->getAccessLevel($bigtree["module"]);
?>
<form method="post" enctype="multipart/form-data" action="<?=MODULE_ROOT?>process/">
	<div class="container">
		<?php include "_form.php" ?>
		<footer>
			<input type="submit" class="button<?php if ($bigtree["access_level"] != "p") { ?> blue<?php } ?>" tabindex="<?=$bigtree["tabindex"]?>" value="Create" name="save" />
			<input type="submit" class="button blue" tabindex="<?=($bigtree["tabindex"] + 1)?>" value="Create &amp; Publish" name="save_and_publish" <?php if ($bigtree["access_level"] != "p") { ?>style="display: none;" <?php } ?>/>
		</footer>
	</div>
</form>

<script>
	(function() {
		var Select = $("#callout_select");
		var Fields = $("#callout_field_area");

		Select.change(function(event,data) {
			// TinyMCE tooltips and menus sometimes get stuck
			$(".mce-tooltip, .mce-menu").remove();

			Fields.load("<?=ADMIN_ROOT?>ajax/callout/", {
				type: $(this).val(),
				count: 0,
				key: "data",
				tab_index: 3,
				btx_reusable_callouts_editor: true
			}, function() {
				BigTree.formHooks("#callout_field_area");
				BigTreeCustomControls("#callout_field_area");
			});
		});
	})();
</script>