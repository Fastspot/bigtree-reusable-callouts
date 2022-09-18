<?php
	/**
	 * @global BigTreeAdmin $admin
	 * @global array $bigtree
	 */
	
	// BigTree 4.5 and higher have a different callouts drawing system
	if (BIGTREE_REVISION >= 500) {
		$bigtree["css"][] = "reusable-callouts.css";
	}
	
	if (method_exists($admin, "drawCSRFToken")) {
		$admin->drawCSRFToken();
	}
	
	$callout_list = $admin->getCalloutsAllowed("name ASC");
	
	// Emulate a $_POST to load in the callout AJAX
	if (empty($bigtree["entry"])) {
		$_POST["type"] = $callout_list[0]["id"];
	} else {
		$_POST["type"] = $bigtree["entry"]["type"];
	}
	
	$_POST["count"] = 0;
	$_POST["key"] = "data";
	$_POST["tab_index"] = 3;
	
	// Set a post var to tell the "Callout List" field type to not draw itself.
	$_POST["btx_reusable_callouts_editor"] = true;
?>
<input type="hidden" name="_bigtree_post_check" value="true" />
<section>
	<?php
		if (!empty($_SESSION["bigtree_admin"]["post_max_hit"])) {
			unset($_SESSION["bigtree_admin"]["post_max_hit"]);
	?>
	<p class="warning_message">The file(s) uploaded exceeded the web server's maximum upload size. If you uploaded multiple files, try uploading one at a time.</p>
	<?php
		}
	?>
	<p class="error_message" style="display: none;">Errors found! Please fix the highlighted fields before submitting.</p>
	
	<fieldset>
		<label>Callout Type</label>
		<?php if (count($callout_list) > 0) { ?>
		<select name="type" id="callout_select">
			<?php foreach ($callout_list as $item) { ?>
			<option value="<?=$item["id"]?>" <?php if (!empty($bigtree["entry"]["type"]) && $item["id"] == $bigtree["entry"]["type"]) { ?> selected="selected"<?php } ?>><?=$item["name"]?></option>
			<?php } ?>
		</select>
		<?php } else { ?>
		<input type="text" disabled="disabled" value="No callouts available" />
		<?php } ?>
	</fieldset>

	<fieldset>
		<label>Callout Title <small>(for reference when picking a reusable callout only)</small></label>
		<input type="text" name="title" value="<?=$bigtree["entry"]["title"] ?? ""?>" />
	</fieldset>

	<hr>
	
	<div id="callout_field_area">
		<?php
			if (BIGTREE_REVISION >= 500) {
				include BigTree::path("admin/ajax/callout.php");
			} else {
				include BigTree::path("admin/ajax/callouts/resources.php");
			}
		?>
	</div>
</section>
<script>
	BigTree.formHooks("#callout_field_area");
</script>