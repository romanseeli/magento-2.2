/**
 * WeArePlanet Magento 2
 *
 * This Magento 2 extension enables to process payments with WeArePlanet (https://www.weareplanet.com//).
 *
 * @package WeArePlanet_Payment
 * @author wallee AG (http://www.wallee.com/)
 * @license http://www.apache.org/licenses/LICENSE-2.0  Apache Software License (ASL 2.0)
 */
require([
    'jquery',
], function ($) {
	$(function () {
		$('.weareplanet-label-container').each(function(){
			var container = $(this),
			
				toggleTable = function(){
					container.toggleClass('active');
				};
			
			container.find('> a').on('click', toggleTable);
		});
	});
});