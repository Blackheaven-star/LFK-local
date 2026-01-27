jQuery(function($){

	console.log('library.js loaded!');

	$('#barcode').focus(); // put focus on the login input on load

    // trigger on enter inside input
    $('#barcode').on('keypress', function (e) {
        if (e.which === 13) { 
            e.preventDefault();
            $('#library_submit_btn').click();
        }
    });

    // attempt to login via ajax
    $('#library_submit_btn').on('click', function(e){

    	// clear error message first
		$('.lib__error').html('').hide();

    	// check if barcode is blank
		if ($('#barcode').val().trim() === '') { $('.lib__error').html('Please enter your barcode.').fadeIn(); return; }

        $('#library_submit_btn').prop('disabled', true);
    	$('#library_submit_btn').html('<i class="loading lni lni-spinner-2-sacle"></i> Validating...');

        e.preventDefault();

        let barcode  	= $('#barcode').val();
        let library_id  = $('#library_id').val();
        let remember 	= $('#remember_me').is(':checked') ? 1 : 0;

        $.ajax({
            url: library_ajax.url, // WP auto-provides this in admin; for frontend use wp_localize_script
            type: 'POST',
            data: {
                action: 	'l4k_loginToLibrary',
                barcode: 	barcode,
                library_id: library_id,
                remember: 	remember
            },
            success: function(response){
			    setTimeout(function() {
			        $('.ajax-response__wrapper').html(response);

			        if (response.status == 0) {
						$('#library_submit_btn').prop('disabled', false);
			        	$('#library_submit_btn').html('<i class="lni lni-locked-2"></i> Secure Login');
			        	$('.lib__error').html(response.message).fadeIn();
			        }
					
					if (response.status == 1) {
			        	$('#library_submit_btn').html('<i class="lni lni-check-circle-1"></i> Login successful! Redirecting...');

			        	const host = window.location.hostname; // get the current hostname
			        	let redirectPath = '/member-home'; // default path for live domain

			        	// if on localhost, preserve the folder name
			        	if (host === 'localhost') {
			        	    const folder = window.location.pathname.split('/')[1];
			        	    redirectPath = '/' + folder + '/member-home';
			        	}

			        	window.location.href = redirectPath;
			        }

			        // for debugging purposes
			        // $('.ajax-response__wrapper').html(response.raw_data);

			    }, 1000);
            }
        });
    });

    /* 
        ----------------------------------------------------------------
        MARC overlay
        ----------------------------------------------------------------
    */

	// when clicked -> show marc overlay
	$('.btn-marc').on('click', function() {
		$('.embed__overlay.marc').fadeIn();
		$('body').addClass('_noscroll'); // prevent scrolling
	});

    /* 
        ----------------------------------------------------------------
        Language breakdown overlay
        ----------------------------------------------------------------
    */

	// when clicked -> show marc overlay
	$('.btn-breakdown').on('click', function() {
		$('.embed__overlay.breakdown').fadeIn();
		$('body').addClass('_noscroll'); // prevent scrolling
	});

    /* 
        ----------------------------------------------------------------
        Functions for ALL overlays in the sidebar
        ----------------------------------------------------------------
    */

	// close overlay when background is clicked
	$('.embed__overlay').on('click', function() {
		$(this).fadeOut();
		$('body').removeClass('_noscroll'); // restore scroll
	});

	// close overlay when X is clicked
	$('.embed__close').on('click', function(e) {
        e.preventDefault(); 
        $(this).closest('.embed__overlay').fadeOut();
        $('body').removeClass('_noscroll'); // restore scroll
	});

	// close overlay when ESC is pressed
	$(document).on('keyup', function(e) {
	    if (e.key === "Escape") {
	        $('.embed__overlay:visible').fadeOut();
	        $('body').removeClass('_noscroll'); // restore scroll
	    }
	});

	// do NOT close when the box inside the overlay is clicked
	$('.embed__overlay .embed__wrap').on('click', function(e) {
		e.stopPropagation();
	});

});

function printDashboardReport() 
{
    const iframe = document.querySelector('iframe');
    
    if (!iframe) {
        alert("No iframe found on the page!");
        return;
    }

    const iframeSrc = iframe.src;
    const newWin = window.open('', '_blank');

    newWin.document.write(`
        <html>
            <head><title>Print</title></head>
            <body data-rsssl=1 style="margin:0;overflow:hidden">
                <iframe src="${iframeSrc}" 
                        style="border:none;width:100%;height:100vh"></iframe>
                <script>
                    setTimeout(() => {
                        window.print();
                        setTimeout(() => window.close(), 2000);
                    }, 3000);
                <\/script>
            </body>
        </html>
    `);
    newWin.document.close();
}