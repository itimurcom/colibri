function beep()
	{
	if (is_on('USER_BEEP'))
		{
		var snd = document.getElementById('beep');
		snd.play();
		}
	}

function clk()
	{
	var snd = document.getElementById('clk');
	snd.play();
	}
