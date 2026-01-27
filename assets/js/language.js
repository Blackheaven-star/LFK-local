jQuery(function ($) {
    
    console.log('language.js loaded!');

   	// add active indicator to main menu when in languages
	$('.menu-item-books').addClass('current-menu-item');

    /* 
        ----------------------------------------------------------------
        Fun facts
        ----------------------------------------------------------------
    */

	// show fun facts overlay when fun facts icon is clicked
	$('#fun-facts-btn').on('click', function() {
		$('#fun-facts-iframe').attr('src', $(this).data('html-src'));
		$('.fun-facts .embed__overlay').fadeIn();
		$('body').addClass('_noscroll'); // prevent scrolling
	});

	// close overlay when background is clicked
	$('.fun-facts .embed__overlay').on('click', function() {
		$(this).fadeOut();
		$('body').removeClass('_noscroll'); // restore scroll
	});

	// close overlay when X is clicked
	$('.fun-facts .embed__close').on('click', function(e) {
        e.preventDefault(); 
        $(this).closest('.embed__overlay').fadeOut();
        $('body').removeClass('_noscroll'); // restore scroll
	});

	// close overlay when ESC is pressed
	$(document).on('keyup', function(e) {
	    if (e.key === "Escape") {
	        $('.fun-facts .embed__overlay:visible').fadeOut();
	        $('body').removeClass('_noscroll'); // restore scroll
	    }
	});

	// do NOT close when the box inside the overlay is clicked
	$('.fun-facts .embed__overlay .embed__wrap').on('click', function(e) {
		e.stopPropagation();
	});

    /* 
        ----------------------------------------------------------------
        Determine what items to show in filter section
        ----------------------------------------------------------------
    */

	var hasLevel0 		= $('.book-item[data-level="P"]').length > 0;
	var hasLevel1 		= $('.book-item[data-level="1"]').length > 0;
	var hasLevel2 		= $('.book-item[data-level="2"]').length > 0;
	var hasLevel3 		= $('.book-item[data-level="3"]').length > 0;
	var hasLevel4 		= $('.book-item[data-level="4+"]').length > 0;
	var hasNonFiction 	= $('.book-item[data-non-fiction="1"]').length > 0;
	var hasQuiz 		= $('.book-item[data-quiz="1"]').length > 0;

	// hide if no elements of that level/non-fiction/quiz
	if (!hasLevel0) 	{ $('[class="filter-P"]').hide(); }
	if (!hasLevel1) 	{ $('[class="filter-1"]').hide(); }
	if (!hasLevel2) 	{ $('[class="filter-2"]').hide(); }
	if (!hasLevel3) 	{ $('[class="filter-3"]').hide(); }
	if (!hasLevel4) 	{ $('[class="filter-4+"]').hide(); }
	if (!hasNonFiction) { $('[class="filter-nf"]').hide(); }
	if (!hasQuiz) 		{ $('[class="filter-q"]').hide(); }

    /* 
        ----------------------------------------------------------------
        Shuffle by tier 
        ----------------------------------------------------------------
    */

    const $wrap = $('.books-wrap');
    const $items = $wrap.find('.book-item');

    // Utility: shuffle an array
    function shuffle(array) {
        for (let i = array.length - 1; i > 0; i--) {
            const j = Math.floor(Math.random() * (i + 1));
            [array[i], array[j]] = [array[j], array[i]];
        }
        return array;
    }

    // Group items by tier
    const tiers = {
        1: [],
        2: [],
        3: [],
        4: []
    };

    $items.each(function () {
        let tier = parseInt($(this).data('tier'), 10);

        // if tier is empty, invalid, or missing → default to tier 4
        if (!tier || tier < 1 || tier > 4) tier = 4;

        tiers[tier].push(this);
    });

    // Clear container
    $wrap.empty();

    // Append in order: tier 1 → 2 → 3 → 4
    [1,2,3,4].forEach(function (tier) {
        const shuffled = shuffle(tiers[tier]);
        shuffled.forEach(item => $wrap.append(item));
    });

    /* 
        ----------------------------------------------------------------
        Sort 
        ----------------------------------------------------------------
    */

    const $bookWrap = $('.books-wrap');
    const $sortLinks = $('.sort a');
    const $searchInput = $('.search input'); // adjust selector if needed

    // keep a copy of the original order
    const originalOrder = $bookWrap.find('.book-item').get();

    // sort
    function sortBooks(type = 'az') {
        const items = $bookWrap.find('.book-item').get();

        items.sort(function(a, b) {
            const nameA = ($(a).data('native-title') || '').toString().toLowerCase().trim();
            const nameB = ($(b).data('native-title') || '').toString().toLowerCase().trim();

            const dateA = new Date($(a).data('published'));
            const dateB = new Date($(b).data('published'));

			const viewsA = ($(a).data('views') || '').toString().toLowerCase().trim();
            const viewsB = ($(b).data('views') || '').toString().toLowerCase().trim();

            switch (type) {
                case 'az':
                    if (nameA < nameB) return -1;
                    if (nameA > nameB) return 1;
                    return 0;

                case 'za':
                    if (nameA < nameB) return 1;
                    if (nameA > nameB) return -1;
                    return 0;

                case 'views':
                    if (parseInt(viewsA) < parseInt(viewsB)) return 1;
                    if (parseInt(viewsA) > parseInt(viewsB)) return -1;
                    return 0;

                case 'latest':
                    return dateB - dateA; // most recent first
            }
        });

        $bookWrap.append(items);
    }

    $(document).on('click', '.sort a', function(e) {
        e.preventDefault();

        // handle active state
        $sortLinks.removeClass('active');
        $(this).addClass('active');

        if ($(this).hasClass('sort-az')) {
            sortBooks('az');
        } else if ($(this).hasClass('sort-za')) {
            sortBooks('za');
        } else if ($(this).hasClass('sort-latest')) {
            sortBooks('latest');
		} else if ($(this).hasClass('sort-views')) {
            sortBooks('views');
        } else if ($(this).hasClass('sort-default active')) {
            // restore original order
            $bookWrap.append(originalOrder);
        }
    });

	/* 
	    ----------------------------------------------------------------
	    Filter 
	    ----------------------------------------------------------------
	*/

	$(document).on('click', '.filter__item.type a', function(e) {
	    e.preventDefault();

	    // handle active state
	    $('.filter__item.type a').removeClass('active');
	    $(this).addClass('active');

	    if ($(this).hasClass('filter-all')) {
	        $bookWrap.find('.book-item').show();
	    } else if ($(this).hasClass('filter-P')) {
	        $bookWrap.find('.book-item').each(function() {
	            $(this).toggle($(this).data('level') == 'P');
	        });
	    } else if ($(this).hasClass('filter-1')) {
	        $bookWrap.find('.book-item').each(function() {
	            $(this).toggle($(this).data('level') == '1');
	        });
	    } else if ($(this).hasClass('filter-2')) {
	        $bookWrap.find('.book-item').each(function() {
	            $(this).toggle($(this).data('level') == '2');
	        });
	    } else if ($(this).hasClass('filter-3')) {
	        $bookWrap.find('.book-item').each(function() {
	            $(this).toggle($(this).data('level') == '3');
	        });
	    } else if ($(this).hasClass('filter-4+')) {
	        $bookWrap.find('.book-item').each(function() {
	            $(this).toggle($(this).data('level') == '4+'); // treat 4+ as literal string match
	        });
    	} else if ($(this).hasClass('filter-q')) {
	        $bookWrap.find('.book-item').each(function() {
	            $(this).toggle($(this).data('quiz') == '1');
	        });
	    } else if ($(this).hasClass('filter-nf')) {
	        $bookWrap.find('.book-item').each(function() {
	            $(this).toggle($(this).data('non-fiction') == '1');
	        });
	    }
	});

    /* 
        ----------------------------------------------------------------
        Search 
        ----------------------------------------------------------------
    */

	$searchInput.on('keyup', function() {

	    const query = $(this).val().toLowerCase().trim();

	    $bookWrap.find('.book-item').each(function() {
	        const englishTitle = ($(this).data('english-title') || '').toString().toLowerCase();
	        const nativeTitle  = ($(this).data('native-title')  || '').toString().toLowerCase();

	        if (englishTitle.includes(query) || nativeTitle.includes(query)) {
	            $(this).show();
	        } else {
	            $(this).hide();
	        }
	    });
	});

}); 