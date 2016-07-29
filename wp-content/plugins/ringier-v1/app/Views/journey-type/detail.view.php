<?php
get_header();
!empty($journey_type_info) ? $journey_type_info = $journey_type_info : $journey_type_info = '';
//var_dump($journey_type_info);
?>

<div class="journey-detail">
    <div class="featured-image">
        <img src="<?php echo $journey_type_info->images->full ?>" alt="<?php echo $journey_type_info->post_title ?>" >

        <div class="info">
            <h1>CLASSIC MEKONG</h1>
            <h2>
                <?php the_excerpt() ?>
            </h2>
            <ul>
                <li><b>7 nights 6 days</b></li>
                <li><b>Promotion:</b> Save up to 20% on selected dates</li>
            </ul>
            <a href="#">choose your date</a>
            <span>from <span class="price-if">US$1,755</span> pp</span>
        </div>
    </div>

    <div class="container container-big">
        <div class="row">
            <div class="col-xs-12 col-sm-4 list-img-left">
                <h3 class="title-main">Journey photos</h3>
                <div class="list-galary">
                    <div><img src="<?php echo VIEW_URL .'/images/laos.png' ?>"></div>
                    <div><img src="<?php echo VIEW_URL .'/images/laos.png' ?>"></div>
                </div>

                <h3 class="title-main">Journey map</h3>
                <img src="<?php echo VIEW_URL.'/images/jouney-map.jpg' ?>" alt="">

                <h3 class="title-main">The ship</h3>
                <div class="list-galary">
                    <div><img src="<?php echo VIEW_URL .'/images/room.jpg' ?>"></div>
                    <div><img src="<?php echo VIEW_URL .'/images/room.jpg' ?>"></div>
                </div>
            </div>

            <div class="col-xs-12 col-sm-8">
                <h3 class="title-main">Itinerary</h3>
                <div class="box-day-in">
                    <div class="day-in">DAY 1 SAIGON</div>
                    <p>Transfer from the InterContinental Asiana Saigon Hotel to the port of My Tho by coach to embark cruise.
                        <b>Please note</b>: Your guide will collect your passports at the meeting point so we can arrange the immigration formalities.</p>
                        <p><b>Registration Details<br>
                        INTERCONTINENTAL ASIANA SAIGON HOTEL, Purple Jade Bar (Level One) /</b><p>
                        <p>39 Le Duan, Ben Nghe, Ho Chi Minh City, Vietnam</p>
                        <p>Tel: (+84) 8 3520 9999</p>
                        <p>Registration is at 10.30am</p>
                </div>

                <div class="box-day-in">
                    <div class="day-in">DAY 2 CAI BE</div>
                    <p>In the morning passengers will visit Cai Be and its colourful floating market. In the afternoon take an exciting Sampan boat excursion to Sa Dec via Vinh Long, along canals and backwaters and see the local market and the ancient house of Mr, Huyn Thuy Le, the 'lover' of Marguerite Duras, a famous French novelist.</p>
                </div>

                <div class="box-day-in">
                    <div class="day-in">DAY 3 CHAU DOC</div>
                    <p>Visit a Cham tribal village and a cat fish farm in Chau Doc. Return to the ship by boat for lunch and cast off for the Cambodian border for the usual formalities</p>
                </div>

                <div class="box-day-in">
                    <div class="day-in">DAY 4 PHNOM PENH</div>
                    <p>Passengers will visit Phnom Penh and will be provided with their own private cyclo*. The Cyclo Centre Phnom Penh which provides the cyclos is a charity that provides basic welfare and medical services to cyclo drivers. In the afternoon, optional excursion (by coach) to the Killing Fields and the Khmer Rouge's grim Tuol Sleng or S21 detention centre. This tour is included in the cost of your cruise but must be requested at the time of booking in the comments box.</p>
                </div>

                <div class="box-day-in">
                    <div class="day-in">DAY 1 SAIGON</div>
                    <p>In the morning passengers will visit Cai Be and its colourful floating market. In the afternoon take an exciting Sampan boat excursion to Sa Dec via Vinh Long, along canals and backwaters and see the local market and the ancient house of Mr, Huyn Thuy Le, the 'lover' of Marguerite Duras, a famous French novelist.</p>
                </div>

                <div class="box-day-in">
                    <div class="day-in">DAY 5 TONLE RIVER</div>
                    <p>Excursion up the Tonle River to Kampong Chhnang either by ship or by coach depending on water levels. Beyond Kampong Chhnang travel by the Pandaw Explorer to discover the vast wetlands around the mouth of the river. Here many fishtraps may be seen.</p>
                </div>

                <div class="box-day-in">
                    <div class="day-in">DAY 6 KAMPONG CHAM</div>
                    <p>We stop at the little-known Chong Koh silk-weaving village for a morning walk at leisure. In the afternoon we stop at Peam Chi Kang village to visit the wat or monastery and school. In the wat the splendid village racing boats are stored.</p>
                </div>

                <div class="box-day-in">
                    <div class="day-in">DAY 7 KAMPONG CHAM</div>
                    <p>Passengers visit the hilltop temple of Wat Hanchey in the morning. In the afternoon, travel by bus to visit the Twin Holy mountains of Phnom Pros and Phnom Srey (Man and Woman Hill). Continuation to the ecotourism village of Choeungkok supported by the Frech-Cambodian NGO Amica.</p>
                </div>

                <div class="box-day-in">
                    <div class="day-in">DAY 8 SIEM REAP</div>
                    <p>We stop at the little-known Chong Koh silk-weaving village for a morning walk at leisure. In the afternoon we stop at Peam Chi Kang village to visit the wat or monastery and school. In the wat the splendid village racing boats are stored.</p>
                    <em>* Transfer by coach from Kampong Cham to Siem Reap takes approx 5 hours.</em>
                </div>

                <div class="title-main" style="font-size: 18px;margin-top: 20px">
                    What's included
                </div>
                <p><b>Cruise Price Includes:</b> Entrance fees, guide services (English language), gratuities to crew, main meals, locally made soft drinks, local beer and local spirits, jugged coffee and selection of teas and tisanes, mineral water. Transfers between the meeting point and the ship at the start and end of a voyage. </p>

                    <p><b>Cruise Price Excludes:</b> International flights, port dues (if levied), laundry, all visa costs, fuel surcharges (see terms and conditions), imported beverages such as wines, premium spirits and liqueurs, fancy soft drinks like Perrier, espressos and cappuccinos at bar and tips to tour guides, local guides, bus drivers, boat operators and cyclo drivers.</p>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-12 col-sm-12">
                <h3 class="title-related">Extensions and Service Addons</h3>

                <div class="row">
                    <div class="col-xs-12 col-sm-2">
                        <div class="related">
                            <div class="images">
                                <a href="#" title="">
                                    <img src="<?php echo VIEW_URL.'/images/related-1.jpg' ?>" alt="">
                                </a>
                            </div>
                            <div class="title">
                                <a href="#" title="">
                                    PRE CRUISE EXTENSIONS: Saigon & Surroundings (InterContinental Asiana)
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="col-xs-12 col-sm-2">
                        <div class="related">
                            <div class="images">
                                <a href="#" title="">
                                    <img src="<?php echo VIEW_URL.'/images/related-1.jpg' ?>" alt="">
                                </a>
                            </div>
                            <div class="title">
                                <a href="#" title="">
                                    PRE CRUISE EXTENSIONS: Saigon & Surroundings (InterContinental Asiana)
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="col-xs-12 col-sm-2">
                        <div class="related">
                            <div class="images">
                                <a href="#" title="">
                                    <img src="<?php echo VIEW_URL.'/images/related-1.jpg' ?>" alt="">
                                </a>
                            </div>
                            <div class="title">
                                <a href="#" title="">
                                    PRE CRUISE EXTENSIONS: Saigon & Surroundings (InterContinental Asiana)
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="col-xs-12 col-sm-2">
                        <div class="related">
                            <div class="images">
                                <a href="#" title="">
                                    <img src="<?php echo VIEW_URL.'/images/related-1.jpg' ?>" alt="">
                                </a>
                            </div>
                            <div class="title">
                                <a href="#" title="">
                                    PRE CRUISE EXTENSIONS: Saigon & Surroundings (InterContinental Asiana)
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="col-xs-12 col-sm-2">
                        <div class="related">
                            <div class="images">
                                <a href="#" title="">
                                    <img src="<?php echo VIEW_URL.'/images/related-1.jpg' ?>" alt="">
                                </a>
                            </div>
                            <div class="title">
                                <a href="#" title="">
                                    PRE CRUISE EXTENSIONS: Saigon & Surroundings (InterContinental Asiana)
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="col-xs-12 col-sm-2">
                        <div class="related">
                            <div class="images">
                                <a href="#" title="">
                                    <img src="<?php echo VIEW_URL.'/images/related-1.jpg' ?>" alt="">
                                </a>
                            </div>
                            <div class="title">
                                <a href="#" title="">
                                    PRE CRUISE EXTENSIONS: Saigon & Surroundings (InterContinental Asiana)
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<?php get_footer() ?>
