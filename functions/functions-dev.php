<?php
/**
 * ----------------------------------------------------------------
 * Maintenance mode for visitors not logged in
 * ----------------------------------------------------------------
 */
/*
function l4k_maintenance() {

    if (!is_user_logged_in()) {

        if (strpos($_SERVER['REQUEST_URI'], 'wp-login.php') !== false ||
            strpos($_SERVER['REQUEST_URI'], 'wp-admin') !== false ||
            strpos($_SERVER['REQUEST_URI'], 'admin-ajax.php') !== false
        ) {
            return;
        }

        // Show maintenance page
        wp_die(
            '<h1>Coming Soon!</h1><p>We’re cooking something new, please check again in a couple of weeks.</p>',
            'Coming Soon',
            array('response' => 503)
        );
    }

}

add_action( 'init', 'l4k_maintenance' );
*/

/**
 * ----------------------------------------------------------------
 * Quick insert of new titles (to be deleted when not needed)
 * ----------------------------------------------------------------
 */
/*
add_action('init', function () {

    if (!isset($_GET['seed_videos'])) return; // run only with URL trigger

    // use http://lote4kids.local/?seed_videos=1

    $titles = explode(',', "A Fox Alone,A Shipwreck Mystery,Charlie and Chad,Chick\'s Trick,Chicken Licken,Cupcake,Green, Green Grapes,Hide and Cheep,My Colours,My Garden,Polly Panda and the Red Thing,The Dreadful Drip,The Golden Goose,The Princess and the Pea,The Princess Twins,The Trike to Trenton,Then the Wind Came,Theo\'s Wobbly Tooth,What a Lot of Fun,Zack\'s Socks");
    $descriptions = explode(',', '<i>Eric Fein</i><br>Nakahanap ang isang mangingisda ng isang lumang bote at, mula sa loob ng bote, lumitaw ang isang masamang genie at nagbabantang kakainin ang mangingisda. Makatakas kaya ang mangingisda?,<i>Robert Southey</i><br>,<i>Upton Sinclair</i><br>,<i>Charles Perrault</i><br>,<i>Aesop</i><br>Isang aso ang may dalang kapirasong karne pauwi nang makita niya ang sarili niyang repleksyon sa isang batis. Sa pag-iisip na ito ay isa pang aso na may isa pang piraso ng karne, matakaw siyang tumalon, nawala ang pagkain.,<i>Aesop</i><br>Ipinagyayabang ng Hare kung gaano siya kabilis tumakbo ngunit pumayag ang Pagong na makipagkarera laban sa kanya. Sino ang mananalo sa karerang ito?,<i>Aesop</i><br>Isang daga ang gumising sa isang leon at matapos mahuli ay nangako na kung ang leon ay magligtas sa kanyang buhay ay magiging kaibigan niya ito magpakailanman. Paano magiging maayos ang pagkakaibigang ito?,<i>Aesop</i><br>Ang Araw at ang Hangin ay nakipagpustahan upang makita kung sino ang maaaring magtanggal ng amerikana ng isang lalaki. Sino ang mananalo sa taya na ito?,<i>Peter Christen Asbjørnsen and Jørgen Moe</i><br>,<i>Aesop</i><br>,<i>Amanda Graham & Naomi Lewis</i><br>Gusto ni Aryo na diwata ang kanyang mapangasawa. Kaya ninakaw niya ang alampay ng isang diwata, para hindi ito makalipad. Pero may mahika pa rin ang diwata. Ano ang gagawin niya?,<i>Richard Liu & Si-Cong Ding</i><br>Si Cao Cao, ang pinuno ng kaharian, ay nakatanggap ng isang elepante bilang regalo. Dapat malaman ng kanyang mga ministro kung gaano ito kabigat. Paano nila titimbangin ang elepante?,<i>Josephine Croser  & Donna Gynell</i><br>Hindi gusto ng dalawang palaka ang kanilang mga tahanan. Kaya iniisip nila kung dapat ba silang lumipat ng tahanan. Pero mas magugustuhan kaya nila ang tahanan ng isa\'t isa?,<i>Richard Liu & Gabriel Cunnett</i><br>Si Unggoy ay nakarinig ng nakakatakot, malakas na tunog. Kaya sinabi niya ito kina Ardilya, Kuneho at Usa. Natakot din ang mga ito. Ngunit hindi natakot si Tigre. Ano kaya ang tunog na iyon?,<i>Richard Liu & Kai-Lun Wang</i><br>Si Oriole ay masyadong mahiyain para kumanta sa konsiyerto sa kagubatan. Kaya humingi siya ng tulong sa iba pang mga ibon. Magkakaroon ba siya ng sapat na tapang upang kumanta?,<i>Jill McDougall  & Carol McLean-Carr</i><br>Ang Emperador ay nagdaraos ng paligsahan para sa kanyang mga anak. Ang bawat prinsipe ay dapat magluto ng isang espesyal na putahe, at ang mananalo ay ang magiging bagong Emperador.,<i>Richard Lui & Connie Mavromatis</i><br>Ang mga manggagawang langgam ay nagagalit sa isa pang langgam. Sa tingin nila ay hindi siya tumutulong nang sapat. Pero ano nga ba talaga ang ginagawa ng isa pang langgam?,<i>Rodney Martin & Leanne Argent</i><br>Nais ng nakatataas na daga sa bayan ng isang malakas na asawa para sa kanyang anak na babae. Sino o ano ang magiging pinakamalakas na asawa?,<i>Richard Liu & Marsha Wajer</i><br>Ang buwan ay nahulog sa balon! Ang sabi ng batang unggoy sa mga nakatatandang unggoy. Kaya gumawa ng plano ang mga unggoy. Ano ang gagawin nila?,<i>Josephine Croser & Pat Reynolds</i><br>Inaakala ng isang unggoy na siya ay nagmamay-ari ng puno ng mangga. Hindi niya hinayaang kainin ng asong lobo, ibon o oso ang mangga. Pero naloko ng ibang hayop ang unggoy.,<i>Nigel Croser & Naomi Lewis</i><br>Isang lalaki ang nangongolekta ng niyog para iuwi sa kanyang asawa. Sabi ng isang batang lalaki, "Kung nagmamadali ka, gagabihin ka pag-uwi." Ano ang gagawin niya?,<i>Josephine Croser & Marsha Wajer</i><br>Ang isang  asong racoon ay nailigtas mula sa isang patibong ng isang mabait na lalaki. Kaya ginamit ng asong raccoon ang kanyang espesyal na mahika upang bayaran ang kabaitang iyon. Ano ang ginawa niya?,<i>Jill McDougall & Laura Peterson</i><br>Isang matandang lalaki at babae ang nakakita ng isang batang lalaki sa loob ng isang peach. Momotaro ang tawag nila sa kanya. Nang maglaon, isang grupo ng masasamang dambuhala ang pumasok sa nayon at nangambala sa mga tao. Mapaalis kaya ni Momotaro ang mga dambuhala?,<i>Yvonne Winer & Susy Boyer</i><br>Matalik na magkaibigan ang Prinsesang Dragon at si Dikya. Ngunit si Pugita ay nagseselos at gumawa ng isang malupit at masamang panlilinlang kay Dikya. Ano ang mangyayari sa kanila?,<i>Josephine Croser & Leanne Argent</i><br>Isang magsasaka at ang kanyang asawa ay napakahirap. Isang araw, isang misteryosong dalaga ang lumitaw sa kanilang pintuan. Naghahabi siya ng magandang tela para sa kanila. Sino ang babaeng ito?,<i>Michael Steer & Nathan Kolic</i><br>Si Prinsipe Rama ay pinalayas mula sa kaharian kasama sina Sita, ang kanyang asawa, at si Lakshmana na kanyang kapatid. Ninakaw ng masamang hari na si  Haring Ravana ang kanyang asawa kaya tinawag nila si Hanuman, ang Haring Unggoy para tumulong.,<i>Richard Liu & Vy Vu</i><br>Si Si Ma Guang ay naglalaro ng taguan kasama ang kanyang mga kaibigan. Ngunit ang isa sa mga batang lalaki ay nahulog sa isang banga ng tubig! Maililigtas kaya siya ni Si Ma Guang?,<i>Jill McDougall  & Annie McQueen</i><br>Gustong tumawid ni Pilandok sa ilog. Ngunit may mga buwaya roon! Kaya nag-isip siya ng plano. Paano niya malalampasan ang mga buwaya?,<i>Josephine Croser  & Connie Mavromatis</i><br>Si Pilandok ay naipit sa isang butas! Pagkatapos ay narinig niya na paparating si Elepante. Tutulungan kaya siya nito? Paano siya makakalabas sa butas?,<i>Josephine Croser & Donna Gynell</i><br>Magkaibigan sina Pilandok at Unggoy. Nagtanim sila ng ilang puno ng saging upang pagsaluhan. Subalit hindi nagtagal ay hindi na sila naghahati at nag-aaway! Maghahati pa ba sila?,<i>Jill McDougall & Bill Wood</i><br>Si Lobo ay nakakita ng isang bagay na nakakatakot. Sinabihan niya si Oso na tumakbo. Ano ang nakakatakot? Makakalayo kaya sila mula rito?,<i>Amanda Graham & Greg Holfeld</i><br>Basa sa labas. Kailangan ng hari ng bagong bota. Ano ang gagawin niya?,<i>Jill McDougall & Bill Wood</i><br>Habang natutulog si Oso, si Lobo ay naghahanap ng pulot. Sinusundan niya ang mga langgam. Ipapakita ba ng mga langgam kay Lobo kung nasaan ang pulot? Makakakuha ba ng pulot si Oso?,<i>Amanda Graham & Greg Holfeld</i><br>Pumitas si Joan ng mga gulay sa kanyang hardin. Pumitas si Joan ng prutas sa kanyang hardin. Kakainin ba niya ang mga ito?,<i>Amanda Graham & Greg Holfeld</i><br>Si Joan ay may kambing noon, isang makulit, napakakulit na kambing. Kinain nito ang kanyang tinapay at damit at sabon. Kaya bang pigilan ni Joan ang kambing sa pagkain?,<i>Nigel Croser & Neil Curtis</i><br>Nakakita si Max ng mga hayop sa mga ulap. Nakakita sina Min at Mop ng mga hayop sa lupa. Pagkatapos ay nakakita sina Min at Mop ng isang soro sa lupa. Makukuha ba ng soro ang mga tupa?,<i>Nigel Croser & Neil Curtis</i><br>Si Max ay namasyal sa bukid. Ang gutom na soro ay sumusunod sa kanyang likuran. Mahuhuli kaya si Max?,<i>Nigel Croser & Neil Curtis</i><br>Ang malamig na gabi ay nagdala ng niyebe, kaya nagpadausdos si Max sa burol. Pagkatapos ay dumating ang napakalakas na hangin at nawala ang mga bibe. Ang lahat ng iba pang mga hayop ay naghanap. Mahahanap kaya nila ang mga bibe?,<i>Nigel Croser & Neil Curtis</i><br>Sina Max, Min at Mop ay pumunta sa perya. Nanalo ng premyo sina Min at Mop. Mananalo kaya ng premyo si Max?,<i>Nigel Croser & Neil Curtis</i><br>Mayroong yelo sa lawa. Kaya nag-skating si Max. Ngunit nakita ng soro si Max at plano nitong hulihin siya.,<i>Amanda Graham & Greg Holfeld</i><br>Sina Joan at Mick ay nakakita ng melon. Pareho nilang gusto ang melon para sa tsaa. Sino ang kakain sa melon?,<i>Bill Wood & Bill Wood</i><br>Gusto ni Oso na maglaro sa parke. Pero ayaw maglaro ni Lobo. Hanggang sa gusto ni Lobo na maglaro sa isang bagay. Makikipaglaro kaya sa kanya si Oso?,<i>Nigel Croser & Neil Curtis</i><br>Oras na ng paggugupit at hinabol ng asong tupa ang mga tupa. Mahahanap ba ng asong tupa si Max? Gugupitan ba ng manggugupit si Max?,<i>Nigel Croser & Neil Curtis</i><br>Hinabol ng asong tupa ang mga tupa. Kaya hinabol ni Max ang asong tupa. Ano ang mangyayari sa mga tupa? Pwede bang maging asong tupa si Max?,<i>Jill McDougall & Bill Wood</i><br>Sumakay sina Oso at Lobo sa isang troso sa putik. Si Oso ay naging maputik. Magiging maputik din ba si Lobo?,<i>Jill McDougall & Bill Wood</i><br>Binibigyan ni Lobo ng mga seresa si Oso. Ngunit si Lobo ay kumukuha ng higit pang seresa kapag hindi nakatingin si Oso. Sino ang makakakuha ng mas maraming seresa?,<i>Amanda Graham & Greg Holfeld</i><br>Nais ng hari na lumipad ng mataas. Paano siya lilipad? Makakalapag ba siya nang ligtas?,<i>Amanda Graham & Greg Holfeld</i><br>Nawala ng hari ang kanyang tsinelas. Tinulungan siya ng reyna na hanapin ang mga ito. Sa tingin mo, nasaan ang mga ito?,<i>Amanda Graham & Greg Holfeld</i><br>Sinusubukan ng reyna na matulog sa pamamagitan ng pagbibilang ng mga tupa, ngunit ang mga tupa ay dumaragdag lamang sa kanyang mga problema. Makakatulog pa kaya ang reyna?,<i>Amanda Graham & Greg Holfeld</i><br>Ang hari ay mahilig tumalon. Ang hari ay mahilig lumipad at umakyat. Ngunit may isang bagay na hindi gusto ng hari.,<i>Sarah Reynolds & Karl Saludar</i><br>Ang mabalahibong soro ay nagtatago. Bakit kaya? Basahin natin at alamin.,<i>Sarah Reynolds & Lance Patrick</i><br>Maaari kong pinturahan ang bakod ng pula, dilaw, at bughaw. Ano ang mangyayari pagkatapos?,<i>Sarah Reynolds & Karl Saludar</i><br>Gusto ko ang aking saranggola. Gusto ko ang aking bola. Pero ano ba talaga ang pinakagusto ko?,<i>Sarah Reynolds & Karl Saludar</i><br>Nakikita ko ang aking pamilya. Nakikita ko ang aking bayan. Ano pa ang makikita ng bata?,<i>Sarah Reynolds & Lance Patrick</i><br>Narito ang aking pamilya. Ano ang mangyayari kapag nakilala nila ang aking alagang hayop?,<i>Sarah Reynolds & Karl Saludar</i><br>Ang aking kamelyo ay kayang pumunta sa kaliwa, kanan, pataas at pababa. Ano ang hindi kayang gawin ng aking kamelyo?,<i>Sarah Reynolds & Lance Patrick</i><br>Ito ang aking kwarto. Ano ang kanyang makikita?,<i>Sarah Reynolds & Karl Saludar</i><br>Ang mga daga ay gusto kumain. Ano ang kanilang makikita?,<i>Sarah Reynolds & Lance Patrick</i><br>Ang itim na pusa ay tumatakbo. Ang itim na pusa ay tumatalon. Ano pa kayang gawin ng pusa?,<i>Sarah Reynolds & Lance Patrick</i><br>Ang babae ay abala ngayong araw. Ano ang ginawa niya?,<i>Sarah Reynolds & Lance Patrick</i><br>Tatlong bata ang pumunta sa panaderya. Ano kaya ang kukunin nila?,<i>Sarah Reynolds & Karl Saludar</i><br>Kailangan natin ng mansanas. Kailangan natin ng peras. Ano ang gagawin natin?,<i>Sarah Reynolds & Karl Saludar</i><br>Ang batang babae ay maraming gutom na kambing. Ilang kambing meron siya?,<i>Sarah Reynolds & Lance Patrick</i><br>Hinahanap ng batang lalaki ang kanyang sapatos. Saan kaya ito napunta?,<i>Sarah Reynolds & Karl Saludar</i><br>Ang batang babae ay gusto ng alagang hayop. Ano kaya ang kukunin niya?,<i>Sarah Reynolds & Lance Patrick</i><br>Ang batang babae ay gustong lumabas. Ano kaya ang susuotin niya ngayong malamig ang panahon?,<i>Sarah Reynolds & Lance Patrick</i><br>Isang batang lalaki ang gustong lumabas. Ano ang kanyang isusuot sa mainit na araw?,<i>Sarah Reynolds & Lance Patrick</i><br>Ang batang lalaki ay may mapa. Ano kaya ang makikita dito?,<i>Sarah Reynolds & Karl Saludar</i><br>Isang batang babae ang may robot. Ano ang kayang gawin nito? Basahin natin at alamin!,<i>Sarah Reynolds & Karl Saludar</i><br>Ang batang lalaki ay kayang tumakbo sa disyerto. Kaya niya tumakbo sa masukal gubat. Saan siya di makatakbo?,<i>Sarah Reynolds & Karl Saludar</i><br>May nakita ang isang batang babae na malaking kalat. Ano kaya ang makikita niya sa kalat na ito?,<i>Sarah Reynolds & Lance Patrick</i><br>May limang hugis ang panadero. Ano kaya ang magagawa niya sa mga ito?,<i>Josephine Croser & Lance Patrick</i><br>Puwede kang gumawa ng nakakatawang mukha. Ipapakita ng aklat na ito kung paano.,<i>Sarah Reynolds & Lance Patrick</i><br>Isang batang lalaki ang kayang gumawa ng mga maskara. Para kanino kaya ang mga ito?,<i>Sarah Reynolds & Karl Saludar</i><br>Ipinakita ng isang batang lalaki ang ginawa niya ngayong linggo. Ano ang mga ginawa niya?,<i>Josephine Croser & Lance Patrick</i><br>Nagpipinta ang mga bata ng mga numero. Bakit sila nagpipinta?,<i>Sarah Reynolds & Karl Saludar</i><br>May isang batang babae na nagsasalita tungkol sa panahon. Ano kaya ang mangyayari ngayong araw?,<i>Josephine Croser &amp; Lance Patrick</i> Ilang paraan ba ang meron para umakyat at bumaba? Tara, alamin natin!,<i>Sarah Reynolds & Karl Saludar</i><br>Panahon na para gumising. Ano ang ginawa ng batang lalaki ngayong araw?,<i>Sarah Reynolds & Karl Saludar</i><br>Gusto ng mga batang ito ang isports. Anong mga isports kaya ang kanilang lalaruin?');
    $published_dates = explode(',', '2021-02-19 04:32:47,2021-02-19 04:34:38,2021-02-19 04:35:29,2021-02-19 04:36:25,2021-02-19 04:36:56,2021-02-19 05:16:34,2021-02-19 05:17:06,2021-02-19 05:17:33,2021-02-19 05:24:20,2021-02-19 05:24:47,2022-09-13 03:55:25,2022-09-13 03:55:26,2022-09-13 03:55:26,2022-09-13 03:55:26,2022-09-13 03:55:26,2022-09-13 03:55:26,2022-09-13 03:55:26,2022-09-13 03:55:26,2022-09-13 03:55:26,2022-09-13 03:55:27,2022-09-13 03:55:27,2022-09-13 03:55:27,2022-09-13 03:55:27,2022-09-13 03:55:27,2022-09-13 03:55:28,2022-09-13 03:55:28,2022-09-13 03:55:28,2022-09-13 03:55:28,2022-09-13 03:55:28,2022-09-13 03:55:28,2023-04-04 06:53:13,2023-04-04 06:53:13,2023-04-04 06:53:13,2023-04-04 06:53:13,2023-04-04 06:53:13,2023-04-04 06:53:13,2023-04-04 06:53:14,2023-04-04 06:53:14,2023-04-04 06:53:14,2023-04-04 06:53:14,2023-04-04 06:53:14,2023-04-04 06:53:14,2023-04-04 06:53:14,2023-04-04 06:53:15,2023-04-04 06:53:15,2023-04-04 06:53:15,2023-04-04 06:53:15,2023-04-04 06:53:15,2023-04-04 06:53:15,2023-04-04 06:53:15,2024-05-14 06:44:27,2024-05-14 06:44:27,2024-05-14 06:44:27,2024-05-14 06:44:27,2024-05-14 06:44:27,2024-05-14 06:44:27,2024-05-14 06:44:27,2024-05-14 06:44:27,2024-05-14 06:44:27,2024-05-14 06:44:27,2025-05-08 01:03:00,2025-05-08 01:03:08,2025-05-08 01:03:16,2025-05-08 01:03:24,2025-05-08 01:03:32,2025-05-08 01:03:39,2025-05-08 01:03:47,2025-05-08 01:03:55,2025-05-08 01:03:55,2025-05-08 01:03:56,2025-11-05 01:15:19,2025-11-05 01:15:28,2025-11-05 01:15:36,2025-11-05 01:15:45,2025-11-05 01:15:53,2025-11-05 01:16:02,2025-11-05 01:16:11,2025-11-05 01:16:19,2025-11-05 01:16:28,2025-11-05 01:16:37');

    foreach ($titles as $index => $title) {

        $date = $published_dates[$index]; 
        $desc = $descriptions[$index]; 

        // echo $date . ' | ' . $title . ' | ' . $desc . '<br/>';

        wp_insert_post([
            'post_title'  => trim($title),
            'post_type'   => 'video',
            'post_status' => 'publish',
            'post_date'      => $date, 
            'post_date_gmt'  => get_gmt_from_date($date),
            'meta_input'  => [
                'description' => $desc,
                'language' => 101,
            ],
        ]);

    }

    exit('Inserted!');
});
*/

/**
 * ----------------------------------------------------------------
 * Quick insert of new titles (to be deleted when not needed)
 * ----------------------------------------------------------------
 */
/*
add_action('init', function () {

    if (!isset($_GET['seed_stories'])) return; // run only with URL trigger

    // use http://lote4kids.local/?seed_stories=1

    $titles = explode(',', "Birds are Amazing,Emergency Vehicles,Follow Me!,Good morning,How are you?,How many sleeps?,I can jump,I know some Māori words,Introduce yourself,Let's Go!,Māori Gods,Matariki,My Pet,Outside fun,Puanga and Matariki,Remembering,Shapes,The Journey,The Matariki Star Cluster,The night has arrived,The spirit of Waitangi,Tidy up time,Wait, my friend!,We love fruit ,What is this?,Where's my hat?,Who do these belong to?,Work together");

    foreach ($titles as $index => $title) {

        wp_insert_post([
            'post_title'  => trim($title),
            'post_type'   => 'story',
            'post_status' => 'publish'
        ]);

    }

    exit('Inserted!');
});
*/
?>