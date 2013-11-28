$(document).ready(function (e) {
	window.setInterval('fnticker()',10000);
});

var settickeritem = function(data, varname) {
		if (typeof data.ticker[varname] !== "undefined") {
			var item = $('#ticker span.' + varname);
			if (data.ticker[varname] != item.text()) {
				item.text(data.ticker[varname]);
				//item.effect('highlight', {}, 2000);
			}
		}
	}

function fnticker() {
	$.get('op.php?type=getticker', function (data) {
		if (typeof data.ticker.vol !== 'undefined') {
			data.ticker.vol = toFixed(Number(data.ticker.vol), 3);
		}

		var cbb = parseFloat($('#current_btc_balance').text());	
		var ccb = parseFloat($('#current_cny_balance').text());
		var cbf = parseFloat($('#current_btc_frozen').text());
		var ccf = parseFloat($('#current_cny_frozen').text());
		$('#current_desc').text(toFixed(parseFloat(data.ticker.last) * (cbb + cbf) + ccf + ccb, 2));

		settickeritem(data, 'last');
		settickeritem(data, 'high');
		settickeritem(data, 'low');
		settickeritem(data, 'buy');
		settickeritem(data, 'sell');
		settickeritem(data, 'vol');
	}, 'json');
}

function toFixed(value, precision) {
    var power = Math.pow(10, precision || 0);
    return String((Math.round(value * power) / power).toFixed(precision));
}