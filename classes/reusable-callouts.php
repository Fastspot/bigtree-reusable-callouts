<?php
	/*
		Class: BTXReusableCallouts
			Facilitates reusable callouts for the module and field type.
	*/

	class BTXReusableCallouts extends BigTreeModule {

		var $Table = "btx_reusable_callouts";

		/*
			Function: check
				Checks to see if a callout is set to reference a reusable callout, if so it transforms it.
				Callout is passed by reference, e.g. just "BTXReusableCallouts::check($callout)"

			Parameters:
				callout - A callout array (by reference)
		*/

		static function check(&$callout) {
			// Not using a reusable callout
			if (empty($callout["__reusable_callout_id"])) {
				return $callout;
			}

			// Use an anonymous module to allow this to be called statically
			$mod = new BigTreeModule("btx_reusable_callouts");
			$reused = $mod->get($callout["__reusable_callout_id"]);

			// Using a deleted one, just hope there's some other data I guess
			if (empty($reused)) {
				return $callout;
			}

			// Data is actually an array containing one callout - done this way for ease of code re-use
			$callout = $reused["data"][0];
			$callout["type"] = $reused["type"];

			return $callout;
		}

		static function publishHook($table, $id, $changes, $many_to_many, $tags) {
			// Figure out if the callout type has the field type yet
			$callout = BigTreeAdmin::getCallout($changes["type"]);
			$secret_field_exists = false;

			foreach ($callout["resources"] as $resource) {
				if ($resource["type"] == "com.fastspot.reusable-callouts*callout-list" && $resource["id"] == "__reusable_callout_id") {
					$secret_field_exists = true;
				}
			}

			// Field doesn't exist, update the callout
			if (!$secret_field_exists) {
				array_unshift($callout["resources"], array(
					"id" => "__reusable_callout_id",
					"type" => "com.fastspot.reusable-callouts*callout-list",
					"title" => "Existing Callout",
					"subtitle" => "(leave empty to create a custom callout)"
				));

				sqlquery("UPDATE bigtree_callouts SET resources = '".BigTree::json($callout["resources"], true)."' WHERE id = '".sqlescape($callout["id"])."'");
			}
		}

	}
