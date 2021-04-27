(function( $ ) {
	'use strict';

  $('#doaction, #doaction2').on("click", function (event) {
    var actionselected = $(this).attr("id").substr(2);
    var action = $('select[name="' + actionselected + '"]').val();

    if (typeof(wpo_wcpdf_ajax) !== 'undefined') {
      if ( $.inArray(action, wpo_wcpdf_ajax.bulk_actions) !== -1 ) {

        event.preventDefault();
        setTimeout(window.location.reload.bind(window.location), 5000);


      }
    }

  });

  $('.button.wpo_wcpdf').on("click", function (event) {
    console.log('.button.wpo_wcpdf');
    setTimeout(window.location.reload.bind(window.location), 10000);
  });

})( jQuery );
