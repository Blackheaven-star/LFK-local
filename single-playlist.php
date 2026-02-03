<?php 
l4k_checkMemberLoggedIn(); 
get_header(); 

$playlistID = get_the_ID();
$playlist_js_all = l4k_getVideosByPlaylistID($playlistID);
$video_index_map = l4k_getVideoIndexMap($playlist_js_all);

l4k_updateBookNumViews($playlist_js_all[0]['id']); // increment views for the 1st book

// on load, log 1062 event
$dataArr = array(
    "Story ID"    => $playlist_js_all[0]['id'],
    "Story Title" => get_the_title($playlist_js_all[0]['id']) . ' Playlist',
    "Language"    => get_field('language', $playlist_js_all[0]['id']),
    "Type"        => get_field('book_type', $playlist_js_all[0]['id'])
);
l4k_addWebActivity(1062, $dataArr);
?>

<div class='main-mid'> 
    <div class='_maxwrap medium'> 
        <div class='book-wrap'> 
            
            <div class="book-parent__wrapper"> 
                <?php if (!empty($playlist_js_all)) : ?>
                    <div class="video-wrapper">
                        <?php 
                            $v_init = $playlist_js_all[0];
                            $src = "https://player.vimeo.com/video/" . $v_init['vimeo_id'] . "?title=0&byline=0&portrait=0&autoplay=1&muted=1&app_id=122963";
                            if ($v_init['vimeo_hash']) $src .= "&h=" . $v_init['vimeo_hash'];
                        ?>
                        <div class="aiovg-player">
                            <iframe id="vimeo-main-player" src="<?php echo esc_url($src); ?>" frameborder="0"allow="autoplay; fullscreen; picture-in-picture"  allowfullscreen ></iframe>
                        </div>
                    </div>

                    <div class='book-info__wrapper'>
                        <div class='actions-wrapper'>
		                	<div class="playlist-nav-btns">
		                        <button id="playlist-prev-btn" class="_btn small" style="min-width: 120px;"><i class="lni lni-arrow-left"></i> Previous</button>
		                        <button id="playlist-next-btn" class="_btn small" style="min-width: 120px;">Next <i class="lni lni-arrow-right"></i></button>
		                    </div>
                            <div class='action-item playback-reminder'>You can change the playback speed using the settings menu in the bottom right corner of the player.</div>
                            <a href="#" class='_btn copy-link' id="js-copy-link" data-url="<?php echo l4k_generateQuickCopyLink($playlist_js_all[0]['id']); ?>">
                                <i class="lni lni-share-1"></i> Copy Video Link
                            </a>
                        </div>

                        <div class='book-info'>
                            <h1 id="js-native-title"><?php echo $playlist_js_all[0]['native_title']; ?></h1>
                            <div class='meta'>
                                <span class='post-title' id="js-post-title"><i class="lni lni-book-1"></i><?php echo $playlist_js_all[0]['title']; ?></span>
                                <span class='views'><i class="lni lni-eye"></i> <span id="js-views"><?php echo $playlist_js_all[0]['views']+1; ?></span> views</span>
                                <span class='date'><i class="lni lni-calendar-days"></i> <span id="js-date"><?php echo $playlist_js_all[0]['date']; ?></span></span>
                                <span class='author' <?php echo ($playlist_js_all[0]['author']) ? '' : 'style="display: none;"';?>><i class="lni lni-pencil-1"></i> <span id="js-author"><?php echo $playlist_js_all[0]['author']; ?></span></span>
                            </div>
                            <div class='description' id="js-description">
                                <?php echo $playlist_js_all[0]['description']; ?>
                            </div>
                        </div>
                    </div>

                    <script src="https://player.vimeo.com/api/player.js"></script>
                    <script>
					    const prevBtn = document.getElementById('playlist-prev-btn');
					    const nextBtn = document.getElementById('playlist-next-btn');

                        const playlist = <?php echo wp_json_encode($playlist_js_all); ?>;
                        const videoIndexMap = <?php echo wp_json_encode($video_index_map); ?>;
                        
                        let currentIndex = 0;
                        let player = null;

                        window.aiovgLoadVideo = function(videoID, isManual = true) {

                            const index = videoIndexMap[videoID];
                            if (index === undefined) return;
                            currentIndex = index;
                           
							updateButtonStates(currentIndex, playlist, prevBtn, nextBtn);

							recordActivityLogToDB(	playlist[index].id, 
													playlist[index].title, 
													playlist[index].language, 
													playlist[index].book_type);

							incrementBookview(playlist[index].id);

                            document.getElementById('js-native-title').textContent = playlist[index].native_title;
                            document.getElementById('js-post-title').innerHTML = `<i class="lni lni-book-1"></i>${playlist[index].title}`;
                            document.getElementById('js-description').innerHTML = playlist[index].description;
                            document.getElementById('js-views').textContent = playlist[index].views;
                            document.getElementById('js-date').textContent = playlist[index].date;

                            if (playlist[index].author) {
                            	document.querySelector('.author').style.display = 'inline-flex';
                            	document.getElementById('js-author').textContent = playlist[index].author;	
                            } else {
                            	document.querySelector('.author').style.display = 'none';
                            }
                            
                            document.querySelectorAll('.playlist-row').forEach(row => row.classList.remove('active-video'));
                            const activeRow = document.querySelector(`.playlist-row[data-id="${videoID}"]`);
                            if(activeRow) activeRow.classList.add('active-video');

                         
                            const playerWrapper = document.querySelector('.aiovg-player');
                            const data = new URLSearchParams({ 
                                action: 'aiovg_load_video', 
                                video_id: videoID,
                                page_id: '<?php echo $playlistID; ?>'
                            });
                            
                            fetch('<?php echo admin_url('admin-ajax.php'); ?>', { method: 'POST', body: data })
                            .then(response => response.json())
                            .then(result => {
                                if (result.success && result.data.player_html) {
                                    playerWrapper.innerHTML = result.data.player_html;
                                    setTimeout(() => { 
                                        setupPlayer(isManual); 
                                        window.dispatchEvent(new Event('resize')); 
                                    }, 200);
                                }
                            });
                        };

                        function incrementBookview(bookID) 
                        {
							fetch('<?php echo admin_url('admin-ajax.php'); ?>', {
							    method: 'POST',
							    headers: {
							        'Content-Type': 'application/x-www-form-urlencoded',
							    },
							    body: new URLSearchParams({
							        action: 'l4k_incrementViewsViaAjax',
							        book_id: bookID,
							    })
							})
							.then(response => response.json())
							.then(data => { 
								console.log(data); 
								if (data.view_count) {
                                    document.getElementById('js-views').textContent = data.view_count;
                                }
							})
							.catch(error => { console.error('Error:', error); });
                        }

                        function recordActivityLogToDB(bookID, storyTitle, lang, type) 
                        {
							fetch('<?php echo admin_url('admin-ajax.php'); ?>', {
							    method: 'POST',
							    headers: {
							        'Content-Type': 'application/x-www-form-urlencoded',
							    },
							    body: new URLSearchParams({
							        action: 'l4k_addWebActivityViaAjax',
							        alert_code: '1062',
							        story_id: bookID,
							        story_title: storyTitle + ' Playlist',
							        language: lang,
							        type: type,
							    })
							})
							.then(response => response.json())
							.then(data => { console.log(data); })
							.catch(error => { console.error('Error:', error); });
                        }

                        function setupPlayer(autoPlay) 
                        {
                            const iframe = document.querySelector('.aiovg-player iframe');
                            if (!iframe) return;
                            player = new Vimeo.Player(iframe);
                            player.on('ended', () => {
                                if (currentIndex + 1 < playlist.length) {
                                    window.aiovgLoadVideo(playlist[currentIndex + 1].id, false);
                                } else {
                                    document.querySelector('.embed__overlay.end-playlist').style.display = 'block';
                                }
                            });
                            if (autoPlay) player.play().catch(() => {});
                        }

						// reusable function to update button states
						const updateButtonStates = (currentIndex, playlist, prevBtn, nextBtn) => {
						    if (currentIndex <= 0) { prevBtn.disabled = true; } 
						    else { prevBtn.disabled = false; }
						    
						    if (currentIndex >= playlist.length - 1) { nextBtn.disabled = true; } 
						    else { nextBtn.disabled = false; }
						};

						document.addEventListener('DOMContentLoaded', () => {
						    setupPlayer(false);
						 
						    const firstRow = document.querySelector('.playlist-row');
						    if (firstRow) { firstRow.classList.add('active-video') };
						    
						    // initial button state
						    updateButtonStates(currentIndex, playlist, prevBtn, nextBtn);
						    
						    // previous button is clicked
						    prevBtn.addEventListener('click', () => {
						        if (currentIndex > 0) {
						            window.aiovgLoadVideo(playlist[currentIndex-1].id);
						            updateButtonStates(currentIndex, playlist, prevBtn, nextBtn);
						        }
						    });
						    
						    // next button is clicked
						    nextBtn.addEventListener('click', () => {
						        if (currentIndex < playlist.length - 1) {
						            window.aiovgLoadVideo(playlist[currentIndex+1].id);
						            updateButtonStates(currentIndex, playlist, prevBtn, nextBtn);
						        }
						    });
						});
                    </script>
                <?php else : ?>
                    <p>No books found for the selected language.</p>
                <?php endif; ?>
            </div>

            <div class="book-sidebar">
                <div class="sidebar-section">
                    <h3 class="sidebar-title">Playlist Items</h3>
                    <div class="playlist-list">
                        <?php foreach ($playlist_js_all as $v) : ?>
                            <div class="playlist-row" 
                                 data-id="<?php echo $v['id']; ?>"
                                 onclick="window.aiovgLoadVideo(<?php echo $v['id']; ?>)">
                                
                                <div class='img-wrap'>
                                    <img src="<?php echo esc_url($v['image']); ?>">
									<i class="lni lni-play"></i>
                                </div>

                                <div class='title-wrap'>
                                	<div class="video-playing"><div class="playing"></div></div>
                                    <div class="video-title"><?php echo esc_html($v['native_title']); ?></div>
                                    <div class='video-title-english'><?php echo esc_html($v['english_title']); ?></div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div> 

        </div> 
    </div> 
</div> 

<div class='embed__overlay end-playlist'>
	<div class='embed__wrap'>
		<div class='embed__wrap__inner'>
			<div class='embed__decoration'><img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/img/blowing-leaf1.png" alt="leaf"></div>
			<div class='embed__decoration-2'><img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/img/blowing-leaf3.png" alt="leaf"></div>
			<a class='embed__close'><i class="lni lni-xmark"></i></a>
			<div class='embed__title'>Congratulations!</div>
			<div class='embed__content'>
				<p>You've finished the playlist.</p>
				<p>Check out Lekti <a href='<?php echo home_url(); ?>/avatar-creation'>here</a> and dress up your avatar.</p>
				<a href='<?php echo home_url(); ?>/avatar-creation'>
					<img class='avatar' src='https://lote4kids.com/wp-content/uploads/2025/10/567B4B78-D21A-435B-8925-C25CC70865EF.gif' />
				</a>
			</div>
		</div>
	</div>
</div>

<?php get_footer(); ?>