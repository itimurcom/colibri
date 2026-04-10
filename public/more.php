<?php
include ("engine/kernel.php");

function more_handle_named_operation($operation=NULL)
	{
	switch ($operation)
		{
		case 'as_main' :
			return as_main_arr();
		case 'as_item' :
			return as_item_arr();
		case 'user' :
			return as_user_arr();
		case 'contents' :
			return as_contents_arr();
		default :
			return NULL;
		}
	}

function more_decode_feed_payload($payload=NULL)
	{
	$data = skel80_decode_encrypted_array($payload, []);
	return is_array($data) ? $data : [];
	}

function more_render_feed_payload($payload=[])
	{
	if (!is_array($payload) || empty($payload))
		{
		return ['result' => '0', 'value' => NULL, 'error' => 'Invalid feed payload'];
		}

	$o_feed = new itFeed($payload);
	$o_feed->compile();
	$body = $o_feed->code();
	unset($o_feed);

	return ['result' => '1', 'value' => $body];
	}

if (isset($_REQUEST['op']))
	{
	$result = more_handle_named_operation($_REQUEST['op']);
	if (!is_null($result))
		{
		return skel80_json_response($result);
		}
	}

return skel80_json_response(
	more_render_feed_payload(
		more_decode_feed_payload(ready_val($_REQUEST['data']))
	)
);
?>
