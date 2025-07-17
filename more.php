<?php
include ("engine/kernel.php");

if (isset($_REQUEST['op']))
	{
	// ����������� ��������
	switch ($_REQUEST['op'])
		{
		case 'as_main' : {
			$result = as_main_arr();
			return print json_encode($result);
			break;
			}

		case 'as_item' : {
			$result = as_item_arr();
			return print json_encode($result);
			break;
			}

		case 'user' : {
			$result = as_user_arr();			
			return print json_encode($result);
			break;
			}

		case 'contents' : {
			$result = as_contents_arr();			
			return print json_encode($result);
			break;
			}


		default : {
			break;
			}
		}
	} else	{
		// ��� ������ ���������� itFeed
		$data = unserialize(simple_decrypt($_REQUEST['data']));
		$o_feed = new itFeed($data);
		$o_feed->compile($data['fewer']);
		$body = $o_feed->code();                            
		unset ($o_feed);

		return print json_encode(['result' => '1', 'value'=> ($body)], JSON_ALLOWED);
		}	
?>