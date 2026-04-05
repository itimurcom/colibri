// ================ CRC ================
// version: 1.15.02
// hash: 5f716950ab78b3c24e0f78e1abfbb6023518055cb95f83bba506eaf177ab3556
// date: 27 June 2019  9:14
// ================ CRC ================
function social_popup (type, url)
	{
	var p_link 	= url;
	var p_title 	= encodeURIComponent($('meta[property=og\\:title]').attr('content'));
	var p_img	= encodeURIComponent($('meta[property=og\\:image]').attr('content'));
	var p_desc	= encodeURIComponent($('meta[property=og\\:description]').attr('content'));
	var win_w	= 640;	
	var win_h	= 480;

	switch (type)
		{
		case 'l-fb' : {
			share_link = 'https://www.facebook.com/sharer/sharer.php'
			+ '?u=' + p_link;
			break;
			}
		case 'l-tw' : {
			share_link = 'https://twitter.com/share'
			+ '?url=' + p_link
			+ '&text=' + p_title;
			win_h = 320;
			break;
			}
		case 'l-gg' : {
			share_link = 'https://plus.google.com/share'
			+ '?url=' + p_link;
			win_w = 520;
			break;
			}
		case 'l-ok' : {
			share_link = 'https://connect.ok.ru/offer?'
			+ '&url=' + p_link
			+ '&title=' + p_title
			+ '&description=' + p_desc
			+ '&imageUrl=' + p_img;
			win_w = 520;
			break;
			}
		case 'l-vk' : {
			share_link = 'https://vk.com/share.php'
			+ '?url=' + p_link
			+ '&title=' + p_title
			+ '&image=' + p_img
			+ '&text=' + p_desc
			+ '&noparse=true';
			win_w = 520;
			break;
			}
		}
        window.open(share_link,'','toolbar=0,status=0,width='+ win_w +',height='+win_h);
   	}
