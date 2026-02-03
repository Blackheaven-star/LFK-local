jQuery(function ($) {
    
    console.log('home.js loaded!');

	$('#lib-search').focus(); // put focus on the library search

    /* 
        ----------------------------------------------------------------
        Home language flag slider
        ----------------------------------------------------------------
    */

    $('.home-lang-slider__track').slick({
        slidesToShow: 5,
        slidesToScroll: 5,
        infinite: true,
        autoplay: true,
        autoplaySpeed: 2000,
        arrows: true,
        dots: false,

        prevArrow: '<button type="button" class="slick-prev"><i class="lni lni-arrow-left"></i></button>',
        nextArrow: '<button type="button" class="slick-next"><i class="lni lni-arrow-right"></i></i></button>',

        responsive: [
            { breakpoint: 1024, settings: { slidesToShow: 4, slidesToScroll: 4 }},
            { breakpoint: 768,  settings: { slidesToShow: 3, slidesToScroll: 3 }},
            { breakpoint: 480,  settings: { slidesToShow: 2, slidesToScroll: 2 }}
        ]
    });

    /* 
        ----------------------------------------------------------------
        Home stats counter animation
        ----------------------------------------------------------------
    */

	$('.count').each(function () {
	    var $this = $(this);
	    var final = $this.text();             // e.g. "67.4" or "7800+"
	    var num = parseFloat(final);          // extract numeric part
	    var suffix = final.replace(num, "");  // keep suffix like "+" or "M+"

	    // Determine decimal places
	    var decimals = (num.toString().split(".")[1] || "").length;

	    $({ n: 0 }).animate({ n: num }, {
	        duration: 1000,
	        easing: 'swing',
	        step: function (val) {
	            $this.text(val.toFixed(decimals) + suffix);
	        }
	    });
	});

    /* 
        ----------------------------------------------------------------
        Library search filter
        ----------------------------------------------------------------
    */

	$('#lib-search').on('keyup', function(){
	    $('.search-txt').removeClass('not-found'); 
	    $('.search-btn').attr('href', '');

	    let q = $(this).val().toLowerCase().trim();
	    let $sug = $('#suggestions');
	    $sug.empty();

	    if (q.length < 3) { return; }

	    $('#lib-list li').each(function(){
	        let text = $(this).text();
	        let url = $(this).attr('data-url');

	        if (text.toLowerCase().indexOf(q) > -1) {
	            $sug.append('<li data-url="'+url+'">'+text+'</li>');
	        }
	    });
	});

    // click to fill input
    $(document).on('click','#suggestions li', function(){
        $('#lib-search').val($(this).text());
        $('.search-btn').attr('href', $(this).attr('data-url'));
        $('#suggestions').empty(); // hide after choose
    });

	$('.search-btn').on('click', function(e) {
		e.preventDefault();
		if ($('.search-btn').attr('href') == '') { 
			$('.search-txt').addClass('not-found'); 
			$('#lib-search').focus(); // put focus on the library search
		} else { 
			window.location.href = $('.search-btn').attr('href'); 
		}	
	});

    /* 
        ----------------------------------------------------------------
        Home story slider
        ----------------------------------------------------------------
    */

    $('.home-story-slider__track').slick({
        slidesToShow: 1,
        slidesToScroll: 1,
        infinite: true,
        autoplay: true,
        autoplaySpeed: 2000,
        arrows: true,
        dots: false,

        prevArrow: '<button type="button" class="slick-prev"><i class="lni lni-arrow-left"></button>',
        nextArrow: '<button type="button" class="slick-next"><i class="lni lni-arrow-right"></button>',

        responsive: [
            { breakpoint: 1024, settings: { slidesToShow: 4, slidesToScroll: 4 }},
            { breakpoint: 768,  settings: { slidesToShow: 3, slidesToScroll: 3 }},
            { breakpoint: 480,  settings: { slidesToShow: 2, slidesToScroll: 2 }}
        ]
    });

    /* 
        ----------------------------------------------------------------
        Home reviews slider
        ----------------------------------------------------------------
    */

    $('.home-reviews-slider__track').slick({
        slidesToShow: 1,
        slidesToScroll: 1,
        infinite: true,
        autoplay: true,
        autoplaySpeed: 2000,
        arrows: true,
        dots: false,

        prevArrow: '<button type="button" class="slick-prev"><i class="lni lni-arrow-left"></button>',
        nextArrow: '<button type="button" class="slick-next"><i class="lni lni-arrow-right"></button>',

        responsive: [
            { breakpoint: 1024, settings: { slidesToShow: 4, slidesToScroll: 4 }},
            { breakpoint: 768,  settings: { slidesToShow: 3, slidesToScroll: 3 }},
            { breakpoint: 480,  settings: { slidesToShow: 2, slidesToScroll: 2 }}
        ]
    });

    /* 
        ----------------------------------------------------------------
        Home video play
        ----------------------------------------------------------------
    */

    const homeIframe = document.getElementById('promo-video');
    const homePlayer = new Vimeo.Player(homeIframe);

    const overlay = document.querySelector('.video-overlay');

    overlay.addEventListener('click', function() {
        homePlayer.play().then(() => {
            overlay.style.display = 'none';
        }).catch(err => {
            console.error('Error playing video:', err);
        });
    });

    homePlayer.on('play', () => overlay.style.display = 'none'); // hide overlay if video is played programmatically

}); 