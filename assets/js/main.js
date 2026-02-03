jQuery(function ($) {
    
    console.log('main.js loaded!');

    /* 
        ----------------------------------------------------------------
        Prevent right click
        ----------------------------------------------------------------
    */

	$(document).on("contextmenu", function(e) {
	    e.preventDefault();

	    const duration = 6000;
	    const toastId = 'global-context-toast';
        const $toastWrap = $('._toast');

        let $toastItem = $toastWrap.find(`._toast-item[data-toast-id="${toastId}"]`); // find existing toast for THIS button only

        if ($toastItem.length) {
            // RESET existing toast
            $toastItem.stop(true, true).removeClass('counting-down').show();
        } else {
            // CREATE new toast
            $toastItem = $(`
                <div class="_toast-item error" data-toast-id="${toastId}">
                    <i class="lni lni-xmark-circle"></i>
                    <div>Content protected!<span>You are not allowed to copy content or view source.</span></div>
                </div>
            `).hide();

            $toastWrap.prepend($toastItem);
            $toastItem.fadeIn(250);
        }

        $toastItem.css('--duration', duration + 'ms'); // set this toast's timer
        setTimeout(() => { $toastItem.addClass('counting-down'); }, 20); // restart countdown bar
        clearTimeout($toastItem.data('removeTimeout')); // clear old removal timer FOR THIS toast
        const removeTimeout = setTimeout(function () { $toastItem.fadeOut(250, function () { $(this).remove(); }); }, duration);
        $toastItem.data('removeTimeout', removeTimeout); // store timer on element
	});

    /* 
        ----------------------------------------------------------------
        Announcement bar
        ----------------------------------------------------------------
    */

    // only show if not previously closed
    if (!localStorage.getItem('announcementClosed')) {
        $('.announcement-bar').show();
    }

    // when user clicks the close button
    $(document).on('click', '.announcement-bar .close', function() {
        $('.announcement-bar').slideUp(250);
        localStorage.setItem('announcementClosed', 'true');
    });

    /* 
        ----------------------------------------------------------------
        Toolbox menu item actions
        ----------------------------------------------------------------
    */

    // show or hide accessiblity menu
	$('#accessibility-toggle').on('click', function (e) {
		e.stopPropagation();

		$('.toolbox-link').not('#accessibility-toggle').removeClass('active'); // remove active status from all other toolbox menus
		$(this).toggleClass('active');

		$('.toolbox-content').not('#accessibility-menu').removeClass('active'); // hide all other toolbox menus
		$('#accessibility-menu').toggleClass('active');

		if (window.botpress) { window.botpress.close(); }
	});

	// show or hide flag picker
	$('#flag-toggle').on('click', function (e) {
		e.stopPropagation();
	
		$('.toolbox-link').not('#flag-toggle').removeClass('active'); // remove active status from all other toolbox menus
		$(this).toggleClass('active');

		$('.toolbox-content').not('#flag-menu').removeClass('active'); // hide all other toolbox menus
		$('#flag-menu').toggleClass('active');

		if (window.botpress) { window.botpress.close(); }
	});

	// show or hide botpress
	$('#chat-toggle').on('click', function (e) {
		e.stopPropagation();
	
		$('.toolbox-link').not('#chat-toggle').removeClass('active'); // remove active status from all other toolbox menus
		$('.toolbox-content').not('#chat-menu').removeClass('active'); // hide all other toolbox menus
		
		if (window.botpress) { 
			if ($('iframe.bpWebchat').hasClass('bpClose')) { 
				window.botpress.open(); 
				$('#chat-toggle').addClass('active');
				$('#chat-menu').removeClass('active');
			}
			else { 
				window.botpress.close(); 
				$('#chat-menu').addClass('active');
				$('#chat-toggle').addClass('active');
			}
		} 
	});

	// show or hide cookie policy
	$('#cookie-notice-toggle').on('click', function (e) {
		e.stopPropagation();
	
		$('.toolbox-link').not('#cookie-notice-toggle').removeClass('active'); // remove active status from all other toolbox menus
		$(this).toggleClass('active');

		$('.toolbox-content').not('#cookie-notice-menu').removeClass('active'); // hide all other toolbox menus
		$('#cookie-notice-menu').toggleClass('active');

		if (window.botpress) { window.botpress.close(); }
	});

	// by default, the cookie policy is shown
	// hide it in the succeeding visits if the accept button is clicked
	$('#accept-cookie').on('click', function () {
		localStorage.setItem('cookiePolicy', '1'); 
		$('.toolbox-link').removeClass('active');
		$('.toolbox-content').not('#cookie-notice-menu').removeClass('active'); // hide all other toolbox menus
		$('#cookie-notice-menu').toggleClass('active');
		$('#cookie-notice-menu').one('transitionend webkitTransitionEnd oTransitionEnd', function() {
    		$('.toolbox-item.cookie-notice-wrapper').remove();
		});
	});

    /* 
        ----------------------------------------------------------------
        Accessibility menu items
        ----------------------------------------------------------------
    */

	var textScaleStep = 0; // -1, 0, 1, 2
	var grayscaleOn = false;
	var highContrastOn = false;
	var selectedLangImg = $('#flag-toggle img').attr('src');

	// determine what HTML elements to apply the increase/decrease text to
	const contentSelectors = [
		'p',
		'span',
		'a',
		'li',
		'h1',
		'h2',
		'h3',
		'h4',
		'h5',
		'h6',
		'button',
		'label',
		'td',
		'th',
		'small',
		'strong',
		'em',
		'input',
		'textarea',
		'div'
	];

	const contentExclusions = [
		'#wpadminbar',
		'#wpadminbar *',
		'.fixed-elements',
		'.fixed-elements *'
	];

	const $contentElements = $(contentSelectors.join(', ')).not(contentExclusions.join(', '));

	// store original font sizes and line-heights for all elements
	$contentElements.each(function() {
	    const $el = $(this);
	    $el.data('original-size', parseInt($el.css('font-size')));
	    $el.data('current-size', parseInt($el.css('font-size')));

	    const lineHeight = parseFloat($el.css('line-height'));
	    // if line-height is "normal", approximate it as 1.2 times font-size
	    $el.data('original-line-height', isNaN(lineHeight) ? $el.data('original-size') * 1.2 : lineHeight);
	    $el.data('current-line-height', $el.data('original-line-height'));
	});

	// increase text size and line-height proportionally
	$('#acce-increase-text').on('click', function () {
		if (isNaN(textScaleStep)) { textScaleStep = 0; }
	    if (textScaleStep < 4) {
	        textScaleStep++;
	        applyTextScale();
	        saveAccessibilityState();
	    }
	});

	// decrease text size and line-height proportionally
	$('#acce-decrease-text').on('click', function () {
	    if (textScaleStep > -1) {
	        textScaleStep--;
	        applyTextScale();
	        saveAccessibilityState();
	    }
	});

	// perform increase or decrease in text size
	function applyTextScale() {
	    $contentElements.each(function() {
	        const $el = $(this);

	        const originalSize = $el.data('original-size');
	        const originalLineHeight = $el.data('original-line-height');

	        const newSize = originalSize + (textScaleStep * 2);
	        const scaleRatio = newSize / originalSize;
	        const newLineHeight = originalLineHeight * scaleRatio;

	        this.style.setProperty('font-size', newSize + 'px', 'important');
	        this.style.setProperty('line-height', newLineHeight + 'px', 'important');

	        $el.data('current-size', newSize);
	        $el.data('current-line-height', newLineHeight);
	    });
	}

	// determine what HTML elements to apply the grayScale and highContrast to
	const grayHighSelectors = [
		'header',
		'footer',
		'.announcement-bar',
		'.main-mid',
		'.book-parent__wrapper',
		'.sidebar.main',
		'.sidebar__item.read-it-your-way',
		'.sidebar__item.related-activities',
		'.sidebar__item.similar-books',
		'.sidebar__item.reward-driven .sidebar__links',
		'.playlist-wrap',
		'.books-wrap',
		'.heading > *:not(.fun-facts)',
		'.heading .fun-facts .fun-facts__inner > *'
	];

	const grayHighExclusions = [
		'.main-mid.book',
		'.main-mid.language'
	];

	const $grayHighElements = $(grayHighSelectors.join(', ')).not(grayHighExclusions.join(', '));

	// grayscale
    $('#acce-grayscale').on('click', function() {
        if (!grayscaleOn) { removeHighContrast(); applyGrayscale(); } 
        else { removeGrayscale(); }

	    $('.toolbox-link').removeClass('active');
	    $('#accessibility-menu').toggleClass('active');
    });

    function applyGrayscale() {
    	$('body').css('background-color', '#ccc');
    	$('.blowing-leaves').css('display', 'none'); // hide blowing leaves

    	/*
    	$('header').css('filter', 'grayscale(100%)');
        $('main').css('filter', 'grayscale(100%)');
        $('.announcement-bar').css('filter', 'grayscale(100%)');
        $('footer').css('filter', 'grayscale(100%)');
        */

		$grayHighElements.each(function() { $(this).css('filter', 'grayscale(100%)'); });

        grayscaleOn = true;
        saveAccessibilityState();
    }

    function removeGrayscale() {
        if (!highContrastOn) {
        	if ($('body').data('custom-bg')) { $('body').css('background-color', $('body').data('custom-bg')); } 
        	else { $('body').css('background-color', 'var(--default-body-color)'); }
        	$('.blowing-leaves').css('display', 'block'); // show blowing leaves

        	/*
			$('main').css('filter', 'none');
		    $('header').css('filter', 'none');
	        $('.announcement-bar').css('filter', 'none');		
	        $('footer').css('filter', 'none');
	        */

	        $grayHighElements.each(function() { $(this).css('filter', 'none'); });
	    }

        grayscaleOn = false;
        saveAccessibilityState();
    }

    // high contrast
    $('#acce-high-contrast').on('click', function() {
        if (!highContrastOn) { removeGrayscale(); applyHighContrast(); } 
        else { removeHighContrast(); }

	    $('.toolbox-link').removeClass('active');
	    $('#accessibility-menu').toggleClass('active');
    });

    function applyHighContrast() {
    	$('.blowing-leaves').css('display', 'none'); // hide blowing leaves
		$('body').css('background-color', '#000');
		$('#flipbook').css('filter', 'invert(1)');

    	/*
        $('main').css('filter', 'invert(1) contrast(150%)');
        $('main img').css('filter', 'invert(1)');
        $('header').css('filter', 'invert(1) contrast(150%)');
        $('header img').css('filter', 'invert(1)');
        $('.announcement-bar').css('filter', 'invert(1) contrast(150%)');
        $('.announcement-bar img').css('filter', 'invert(1)');
        $('footer').css('filter', 'invert(1) contrast(150%)');
        $('footer img').css('filter', 'invert(1)');
        */

        $grayHighElements.each(function() { 
        	$(this).css('filter', 'invert(1) contrast(150%)'); 
        	$(this).find('img').css('filter', 'invert(1)'); 
        });

        highContrastOn = true;
        saveAccessibilityState();
    }

    function removeHighContrast() {
        if (!grayscaleOn) {
	        if ($('body').data('custom-bg')) { $('body').css('background-color', $('body').data('custom-bg')); } 
        	else { $('body').css('background-color', 'var(--default-body-color)'); }

        	$('.blowing-leaves').css('display', 'block'); // show blowing leaves
        	$('#flipbook').css('filter', 'none');

        	/*
	        $('main').css('filter', 'none');
	        $('main img').css('filter', 'none');
	        $('header').css('filter', 'none');
	        $('header img').css('filter', 'none');
	        $('.announcement-bar').css('filter', 'none');
	        $('.announcement-bar img').css('filter', 'none');
	        $('footer').css('filter', 'none');
	        $('footer img').css('filter', 'none');
	        */
	       
	        $grayHighElements.each(function() { 
	        	$(this).css('filter', 'none'); 
	        	$(this).find('img').css('filter', 'none'); 
	        });
	    }

        highContrastOn = false;
        saveAccessibilityState();
    }

	// reset all changes to default
	$('#acce-reset').on('click', function () {
	    textScaleStep = 0;
	    applyTextScale();
	    removeGrayscale();
	    removeHighContrast();
	    saveAccessibilityState();

	    $('.toolbox-link').removeClass('active');
	    $('#accessibility-menu').toggleClass('active');
	});

    /* 
        ----------------------------------------------------------------
        Google translate
        ----------------------------------------------------------------
    */

	function setTranslateCookie(lang) {
	    document.cookie = `googtrans=/en/${lang};path=/`;
	    document.cookie = `googtrans=/en/${lang};domain=${location.hostname};path=/`;
	}

	function triggerTranslate(lang, imgSrc) {
	    waitForGTranslate(($select) => {
	        setTranslateCookie(lang);
	        $select.val(lang);
	        $select[0].dispatchEvent(new Event('change'));

	        $('#flag-toggle img').attr('src', imgSrc);
	        $('#flag-menu').removeClass('active');
	    });
	}

	$('.gtranslate-btn').on('click', function () {
		selectedLangImg = $(this).children('img').attr('src');
		triggerTranslate($(this).data('lang'), $(this).children('img').attr('src'));
		saveAccessibilityState();
	});

	function waitForGTranslate(callback) {
	    const interval = setInterval(() => {
	        const $select = $('.goog-te-combo');
	        if ($select.length) {
	            clearInterval(interval);
	            callback($select);
	        }
	    }, 100); // check every 100ms
	}

    /* 
        ----------------------------------------------------------------
        Botpress
        ----------------------------------------------------------------
    */

	if (window.botpress) {

		window.botpress.init({
			"botId": "ebe8822d-b22b-4ba2-b9f4-3b8dc8b708db",
			"configuration": {
				"hideWidget": true,
				"composerPlaceholder": "Type your response here...",
				"botName": "Find your library or school here!",
				"botAvatar": "https://files.bpcontent.cloud/2025/04/15/07/20250415072155-TGJELYZR.png",
				"botDescription": "",
				"website": {},
				"email": {},
				"phone": {},
				"termsOfService": {},
				"privacyPolicy": {},
				"color": "#F19E38",
				"variant": "solid",
				"themeMode": "light",
				"fontFamily": "inter",
				"radius": 1,
				"additionalStylesheetUrl": "https://files.bpcontent.cloud/2025/04/15/23/20250415231841-NVE7DNAU.css",
				"storageLocation": "sessionStorage"
			},
			"clientId": "0c4e01ac-74ee-4058-8099-a5e3301a71ae"
		});

		// detect if bpWebchat was closed from the widget itself

		$.fn.onClassChange = function(callback) {
		    return this.each(function() {
		        const target = this;
		        let hadClass = $(target).hasClass('bpClose'); // initial state

		        const observer = new MutationObserver(function(mutations) {
		            mutations.forEach(function(mutation) {
		                if (mutation.type === 'attributes' && mutation.attributeName === 'class') {
		                    const hasClassNow = $(target).hasClass('bpClose');

		                    if (hasClassNow !== hadClass) { // class added or removed
		                        hadClass = hasClassNow;
		                        callback.call(target, hasClassNow); // pass true/false
		                    }
		                }
		            });
		        });

		        observer.observe(target, { attributes: true });
		    });
		};

		$('.bpWebchat').onClassChange(function(isBpClose) {
		    if (isBpClose && $('#chat-toggle').hasClass('active')) { 
		    	$('#chat-menu').addClass('active');
		    }
		});

	}

    /* 
        ----------------------------------------------------------------
        Local storage
        ----------------------------------------------------------------
    */

	// persist accessibility preferences to localstorage
	function saveAccessibilityState() {
        localStorage.setItem('grayscaleOn', grayscaleOn ? '1' : '0');
        localStorage.setItem('highContrastOn', highContrastOn ? '1' : '0');
    	localStorage.setItem('textScaleStep', textScaleStep ? textScaleStep : '0');
    	localStorage.setItem('selectedLangImg', selectedLangImg);
	}

	loadToolboxState();

	// check and load saved values from localstorage
	function loadToolboxState() {
		const savedCookiePolicy = localStorage.getItem('cookiePolicy');
	    const savedGrayscale = localStorage.getItem('grayscaleOn');
	    const savedHighContrast = localStorage.getItem('highContrastOn');
	    const savedTextScale = parseInt(localStorage.getItem('textScaleStep'), 10);
	    const savedSelectedLangImg = localStorage.getItem('selectedLangImg');

		textScaleStep = savedTextScale;
		grayscaleOn = savedGrayscale;
		highContrastOn = savedHighContrast;
		selectedLangImg = savedSelectedLangImg;

		// check what current gtranslate language is selected
	    if (savedSelectedLangImg != null && savedSelectedLangImg !== '' && savedSelectedLangImg != 'null') {
	    	$('#flag-toggle img').attr('src', savedSelectedLangImg);
	    }

	    // check if cookie policy should be shown
	    if (savedCookiePolicy) {
			$('.toolbox-item.cookie-notice-wrapper').remove();
	    } else {
	    	$('#cookie-notice-menu').addClass('active');
	    	$('#cookie-notice-toggle').addClass('active');
	    }

	    // check if chat menu should be shown
	    // chat section is shown by default if the cookie policy has been accepted 
	    if (savedCookiePolicy) {
	    	$('#chat-menu').addClass('active');
	    	$('#chat-toggle').addClass('active');
	    }

	    // check if grayscale is applied
	    if (savedGrayscale === '1') { applyGrayscale(); }
	    else { removeGrayscale(); }

	    // check if highContrast is applied
	    if (savedHighContrast === '1') { applyHighContrast(); }
	    else { removeHighContrast(); }

	    // check if text scale is increase/decreased
	    if (!isNaN(savedTextScale)) {
	        textScaleStep = savedTextScale;
	        applyTextScale();
	    }
	}

}); 