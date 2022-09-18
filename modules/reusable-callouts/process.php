<?php
	if (method_exists($admin, "verifyCSRFToken")) {
		$admin->verifyCSRFToken();
	}
	
	// Stick the callout type back into the callout
	$_POST["data"][0]["type"] = $_POST["type"];
	
	// Let's pretend to be a form
	$bigtree["form"] = [
		"table" => "btx_reusable_callouts",
		"hooks" => ["publish" => "BTXReusableCallouts::publishHook"],
		"title" => "Reusable Callout",
		"fields" => [
			[
				"column" => "type",
				"type" => "text",
				"title" => "Callout Type"
			],
			[
				"column" => "title",
				"type" => "text",
				"title" => "Callout Title"
			],
			[
				"column" => "data",
				"type" => "callouts",
				"title" => "Callout Data"
			]
		]
	];
	$bigtree["form_root"] = MODULE_ROOT;
	
	include SERVER_ROOT."core/admin/auto-modules/forms/process.php";
