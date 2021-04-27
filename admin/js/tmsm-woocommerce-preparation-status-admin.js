(function( $ ) {
	'use strict';

  $("#doaction, #doaction2").on("click", function (event) {
    var actionselected = $(this).attr("id").substr(2);
    var action = $('select[name="' + actionselected + '"]').val();

    if (typeof(wpo_wcpdf_ajax) !== 'undefined') {
      if ( $.inArray(action, wpo_wcpdf_ajax.bulk_actions) !== -1 ) {

        console.log('reloading 3');
        event.preventDefault();
        setTimeout(window.location.reload.bind(window.location), 2000);


      }
    }

  });

})( jQuery );
