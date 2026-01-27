</main><!-- .site-content -->

<footer class="site-footer">

    <div class='_maxwrap'>

    	<div class='footer-elements'>

	        <div class='footer-contact'>
	            
	            <h3>Stay in Touch</h3>

	            <?php echo do_shortcode('[wpforms id="'.get_field('footer_contact_form', 'option').'" title="false"]'); ?>

	        </div>

	        <div class='spacer'></div>

	        <div class='footer-links'>
	            <nav class="footer-nav">
	                <?php
	                wp_nav_menu([
	                    'menu'       => 'Footer Menu',
	                    'container'  => false,
	                    'menu_class' => 'menu'
	                ]);
	                ?>

	                <div class='download-app'>

	                    <span>Download The Lote4Kids App</span>

	                    <?php if (get_field('footer_mobile_app_links_apple_download_link', 'option')) { ?>
	                        <a href='<?php echo get_field('footer_mobile_app_links_apple_download_link', 'option'); ?>' target='_blank'>
	                            <img src='<?php echo get_stylesheet_directory_uri(); ?>/assets/img/download-apple.png' />
	                        </a>
	                    <?php } ?>

	                    <?php if (get_field('footer_mobile_app_links_google_download_link', 'option')) { ?>
	                        <a href='<?php echo get_field('footer_mobile_app_links_google_download_link', 'option'); ?>' target='_blank'>
	                            <img src='<?php echo get_stylesheet_directory_uri(); ?>/assets/img/download-google.png' />
	                        </a>
	                    <?php } ?>

	                </div>

	                <div class='_share socials'>

	                    <?php if (get_field('footer_social_media_links_general_facebook_url', 'option')) { ?>
	                        <a href='<?php echo get_field('footer_social_media_links_general_facebook_url', 'option'); ?>' target='_blank' class='facebook'>
	                            <svg style="display:block;" focusable="false" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="100%" height="100%" viewBox="0 0 32 32"><path fill="#fff" d="M28 16c0-6.627-5.373-12-12-12S4 9.373 4 16c0 5.628 3.875 10.35 9.101 11.647v-7.98h-2.474V16H13.1v-1.58c0-4.085 1.849-5.978 5.859-5.978.76 0 2.072.15 2.608.298v3.325c-.283-.03-.775-.045-1.386-.045-1.967 0-2.728.745-2.728 2.683V16h3.92l-.673 3.667h-3.247v8.245C23.395 27.195 28 22.135 28 16Z"></path></svg>
	                        </a>
	                    <?php } ?>

	                    <?php if (get_field('footer_social_media_links_general_linkedin_url', 'option')) { ?>
	                        <a href='<?php echo get_field('footer_social_media_links_general_linkedin_url', 'option'); ?>' target='_blank' class='linkedin'>
	                            <svg style="display:block;" focusable="false" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="100%" height="100%" viewBox="0 0 32 32"><path d="M6.227 12.61h4.19v13.48h-4.19V12.61zm2.095-6.7a2.43 2.43 0 0 1 0 4.86c-1.344 0-2.428-1.09-2.428-2.43s1.084-2.43 2.428-2.43m4.72 6.7h4.02v1.84h.058c.56-1.058 1.927-2.176 3.965-2.176 4.238 0 5.02 2.792 5.02 6.42v7.395h-4.183v-6.56c0-1.564-.03-3.574-2.178-3.574-2.18 0-2.514 1.7-2.514 3.46v6.668h-4.187V12.61z" fill="#fff"></path></svg>
	                        </a>
	                    <?php } ?>

	                    <?php if (get_field('footer_social_media_links_general_email', 'option')) { ?>
	                        <a href='<?php echo get_field('footer_social_media_links_general_email', 'option'); ?>' class='mail'>
	                            <svg style="display:block;" focusable="false" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="100%" height="100%" viewBox="-.75 -.5 36 36"><path d="M 5.5 11 h 23 v 1 l -11 6 l -11 -6 v -1 m 0 2 l 11 6 l 11 -6 v 11 h -22 v -11" stroke-width="1" fill="#fff"></path></svg>
	                        </a>
	                    <?php } ?>

	                </div>
	            </nav>
	        </div>

        </div>

    </div>

    <div class="container">
        <div class='_maxwrap'>
            <span><?php echo get_field('footer_copyright', 'option'); ?></span>
        </div>    
    </div>

</footer>

<div class='fixed-elements' translate="no" class="notranslate">

	<?php if (is_front_page() || is_singular('library')) : ?>
		<div class='blowing-leaves'>
		    <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/img/blowing-leaf1.png" class="blowing-leaf" alt="leaf">
		    <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/img/blowing-leaf2.png" class="blowing-leaf" alt="leaf">
		    <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/img/blowing-leaf3.png" class="blowing-leaf" alt="leaf">
		</div>
	<?php endif; ?>

	<div class='_toast'></div>

    <div class='fixed-elements-menu-items'>

		<div class="toolbox-item accessibility-wrapper">
			<div id="accessibility-toggle" class='toolbox-link' data-tooltip='Accessibility Tools'><i class="lni lni-layers-1"></i></div>
			<div id="accessibility-menu" class='toolbox-content'>
				<ul>
					<li><a class='heading'>Accessibility Tools</a></li>
					<li><a id='acce-increase-text'><i class="lni lni-plus"></i>Increase Text</a></li>
					<li><a id='acce-decrease-text'><i class="lni lni-minus"></i>Decrease Text</a></li>
					<li><a id='acce-grayscale'><i class="lni lni-text-paragraph"></i>Grayscale</a></li>
					<li><a id='acce-high-contrast'><i class="lni lni-colour-palette-3"></i>High Contrast</a></li>
					<li><a id='acce-reset'><i class="lni lni-refresh-circle-1-clockwise"></i>Reset</a></li>
				</ul>
			</div>
		</div>

		<div class="toolbox-item flag-wrapper">
			<div id="flag-toggle" class='toolbox-link' data-tooltip='Switch Language'><img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/img/flag-en.svg" alt=""></div>
			<div id="flag-menu" class='toolbox-content'>
				<ul>
					<li><a class='heading'>Switch Language</a></li>
					<li><a class='gtranslate-btn' data-lang='en'><img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/img/flag-en.svg" alt="">English</a></li>
					<li><a class='gtranslate-btn' data-lang='ar'><img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/img/flag-ar.svg" alt="">العربية</a></li>
					<li><a class='gtranslate-btn' data-lang='zh-CN'><img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/img/flag-zh.svg" alt="">简体中文</a></li>
					<li><a class='gtranslate-btn' data-lang='zh-TW'><img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/img/flag-zh.svg" alt="">繁體中文</a></li>
					<li><a class='gtranslate-btn' data-lang='de'><img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/img/flag-de.svg" alt="">Deutsch</a></li>
					<li><a class='gtranslate-btn' data-lang='es'><img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/img/flag-es.svg" alt="">Español</a></li>
					<li><a class='gtranslate-btn' data-lang='fr'><img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/img/flag-fr.svg" alt="">Français</a></li>
					<li><a class='gtranslate-btn' data-lang='hi'><img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/img/flag-hi.svg" alt="">हिन्दी</a></li>
					<li><a class='gtranslate-btn' data-lang='it'><img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/img/flag-it.svg" alt="">Italiano</a></li>
					<li><a class='gtranslate-btn' data-lang='ja'><img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/img/flag-ja.svg" alt="">日本語</a></li>
					<li><a class='gtranslate-btn' data-lang='ru'><img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/img/flag-ru.svg" alt="">Русский</a></li>
					<li><a class='gtranslate-btn' data-lang='mi'><img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/img/flag-mi.svg" alt="">Te Reo Māori</a></li>
					<li><a class='gtranslate-btn' data-lang='uk'><img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/img/flag-uk.svg" alt="">Українська</a></li>
					<li><a class='gtranslate-btn' data-lang='vi'><img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/img/flag-vi.svg" alt="">Tiếng Việt</a></li>
					<li><a class='gtranslate-btn' data-lang='cy'><img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/img/flag-cy.svg" alt="">Cymraeg</a></li>
				</ul>
				<div id="google_translate_element"></div>
				<script>
				function googleTranslateElementInit() {
				    new google.translate.TranslateElement({
				        pageLanguage: 'en',
				        includedLanguages: 'en,ar,zh-CN,zh-TW,de,es,fr,hi,it,ja,ru,mi,uk,vi,cy',
				        autoDisplay: false
				    }, 'google_translate_element');
				}	
				</script>
			</div>
		</div>

		<?php if (is_front_page()) : ?>
			<div class="toolbox-item chat-wrapper">
				<div id="chat-toggle" class='toolbox-link' data-tooltip='Find Your Library or School'><i class="lni lni-message-2"></i></div>
				<div id="chat-menu" class='toolbox-content'>
					Can't find your library or school? Let me help! <span class='pulse-shadow'></span>
				</div>
			</div>
		<?php endif; ?>

		<div class="toolbox-item cookie-notice-wrapper">
			<div id="cookie-notice-toggle" class='toolbox-link' data-tooltip='Cookie Policy'><i class="lni lni-hand-shake"></i></div>
			<div id="cookie-notice-menu" class='toolbox-content'>
			    <?php if (get_field('cookie_policy', 'option')) : ?>
					<?php echo apply_filters('the_content', get_field('cookie_policy', 'option')); ?>
			    <?php endif; ?>
			    <a href='javascript: void(0);' id='accept-cookie'>Accept & Close</a>
			</div>
		</div>

	</div>

</div>

<?php wp_footer(); ?>
</body>
</html>