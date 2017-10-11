/* http://keith-wood.name/countdown.html
 * Croatian initialisation for the jQuery countdown extension
 * Written by Renato Barisic (renato.barisic@gmail.com) (2016) */
(function($) {
	$.countdown.regional['hr'] = {
		labels: ['Godina', 'Mjeseci', 'Tjedana', 'Dana', 'Sati', 'Minuta', 'Sekundi'],
		labels1: ['Godina', 'Mjesec', 'Tjedan', 'Dan', 'Sat', 'Minuta', 'Sekunda'],
		labels2: ['Godine', 'Mjeseca', 'Tjedna', 'Dana', 'Sata', 'Minute', 'Sekunde'],
		compactLabels: ['g', 'm', 't', 'd'],
		whichLabels: function(amount) {
			var units = amount % 10;
			var tens = Math.floor((amount % 100) / 10);
			return (units == 1 && tens != 1 ? 1 : (units >= 2 && units <= 4 && tens != 1 ? 2 : 0));
		},
		timeSeparator: ':', isRTL: false};
	$.countdown.setDefaults($.countdown.regional['hr']);
})(jQuery);
