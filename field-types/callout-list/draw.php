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
