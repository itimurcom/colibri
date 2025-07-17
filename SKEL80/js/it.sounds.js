// ================ CRC ================
// version: 1.15.02
// hash: f8b9c1dce5fed65288ed8f25cb5f96f6aa3e44b88a75e403c2c2a12cd457fbf2
// date: 21 March 2019 20:47
// ================ CRC ================
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
