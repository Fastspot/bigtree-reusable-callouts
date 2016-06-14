<?php
	// We don't want to show this field when we're modifying the reusable callouts in the module.
	// $_POST is something we have control over both inline and when calling the AJAX resources
	if (!isset($_POST["btx_reusable_callouts_editor"])) {
		$module = new BTXReusableCallouts;
		$available = $module->getMatching("type", $bigtree["callout"]["id"]);
	
		if (count($available)) {
			$field["options"]["list"] = array();
	
			foreach ($available as $item) {
				$field["options"]["list"][] = array("value" => $item["id"], "description" => $item["title"]);
			}
?>
<fieldset>
	<label<?=$label_validation_class?>><?=$field["title"]?><? if ($field["subtitle"]) { ?> <small><?=$field["subtitle"]?></small><? } ?></label>
	<?php include BigTree::path("admin/form-field-types/draw/list.php") ?>
</fieldset>
<hr>
<?php
		}
	}
?>
<script>
	(function() {

		var Form;
		var Fieldset;
		var LastValue = "<?=$field["value"]?>";
		var OtherFieldsets;
		var RequiredFields;

		// Add a ready hook to populate our values
		BigTree.ReadyHooks.push(function() {
			Form = $("#<?=$field["id"]?>").parents("form");
			Fieldset = $("#<?=$field["id"]?>").parents("fieldset");
			OtherFieldsets = Form.find("fieldset").not(Fieldset);
			RequiredFields = OtherFieldsets.find("input.required, select.required, textarea.required, input.numeric, input.email, input.link");
		});

		<?php if ($field["value"]) { ?>
		// Add a ready hook to disable other form elements if this is enabled
		BigTree.ReadyHooks.push(saveInputStates);
		<?php } ?>

		function saveInputStates() {
			RequiredFields.each(function() {
				$(this).attr("data-reusable-callouts-saved-class", $(this).attr("class"));
				$(this).attr("class", "");
			});
		}

		function resetInputStates() {
			RequiredFields.each(function() {
				$(this).attr("class", $(this).attr("data-reusable-callouts-saved-class"));
			});
		}

		$("#<?=$field["id"]?>").change(function() {
			var value = $(this).val();

			// We're going to remove the required classes and disable everything
			if (value && !LastValue) {
				saveInputStates();
			// We're going to return the classes and re-enable
			} else if (!value && LastValue) {
				resetInputStates();
			}

			LastValue = value;
		});

	})();
</script>