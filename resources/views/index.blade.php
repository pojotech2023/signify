<!DOCTYPE html>
<html lang="en" class="no-js">

<head>
    <meta charset="utf-8" />
    <title>Signify</title>
    <meta name="description" content="">
    <meta name="keywords" content="">

    <meta name="viewport" content="width=device-width,initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="theme-color" content="#4302b2">

    <link href="images/logo/logo.png" rel="icon">
    <link href="css/swiper.min.css" rel="stylesheet">
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/plugin.min.css" rel="stylesheet">
    <link href="css/font-awesome/5.11.2/css/all.min.css" rel="stylesheet">
    <link
        href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600;700&amp;family=Poppins:wght@300;400;500;600;700;800;900&amp;display=swap"
        rel="stylesheet">

    <link href="css/style.css" rel="stylesheet">
    <link href="css/responsive.css" rel="stylesheet">
    <link href="css/darkmode.css" rel="stylesheet">
    <link href="css/custom.css" rel="stylesheet">
    <style>
        .slide-bg-image {
            position: relative;
            background-size: cover;
            background-position: center;
            height: 100vh;
            /* Adjust height as needed */
        }

        .gradient-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(rgba(0, 0, 0, 0.6), rgba(0, 0, 0, 0.6));
            /* Dark gradient overlay */
            z-index: 1;
            /* Make sure the gradient is on top of the background image */
        }

        .slide-inner .container {
            position: relative;
            z-index: 2;
            /* Ensure content is on top of the gradient */
        }

        .text-white {
            color: #ffffff;
        }

        .slide-title h3,
        .slide-text p {
            color: white;
            z-index: 2;
        }
    </style>
</head>

<body>

    <!--<div class="onloadpage" id="page_loader">
		<div class="pre-content">
			<div class="logo-pre"><img src="images/logo.png" alt="Logo" class="img-fluid" /></div>
			<div class="pre-text- text-radius text-light text-animation bg-b">Niwax - Creative Agency & Portfolio HTML
				Template Are 2 Seconds Away. Have Patience</div>
		</div>
	</div>-->


   @include('header')
    <section class="hero-slider hero-style">
        <div class="swiper-container">

            <div class="swiper-wrapper">

                <div class="swiper-slide">
                    <!-- <img src="" alt=""> -->
                    <div class="slide-inner slide-bg-image background-img" data-background="./img/download/12.jpeg">
                        <div class="gradient-overlay"></div> <!-- Gradient overlay -->
                        <div class="container">
                            <div class="row">
                                <div class="col-md-6">
                                    <div data-swiper-parallax="300" class="slide-title">
                                        <h3 class="text-white">Comfortable Metal</h3>
                                    </div>
                                    <div data-swiper-parallax="400" class="slide-text">
                                        <h4 class="text-white">Experience the Comfort of Home</h4>
                                        <p>Relax in fully furnished rooms designed for your comfort and convenience, ensuring a stress-free stay.</p>
                                    </div>


                                    <div class="clearfix"></div>
                                    <div data-swiper-parallax="500" class="slide-btns">
                                        <a href="{{route('contact')}}" class="btn-main bg-btn lnk">Enquiry Now <i
                                                class="fas fa-chevron-right fa-icon"></i><span
                                                class="circle"></span></a>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <!-- <img src="images/hero/mobile-app.png"> -->
                                </div>
                            </div>

                        </div>
                    </div>
                </div>


                <div class="swiper-slide">
                    <div class="slide-inner slide-bg-image background-image" data-background="./img/download/11.jpeg">
                        <div class="gradient-overlay"></div> <!-- Gradient overlay -->
                        <div class="container">
                            <div class="row">
                                <div class="col-md-6">
                                    <div data-swiper-parallax="300" class="slide-title">
                                        <h3 class="text-white">Hygienic Food</h3>
                                    </div>
                                    <div data-swiper-parallax="400" class="slide-text">
                                        <h4 class="text-white">Delicious and Healthy Meals</h4>
                                        <p>Enjoy fresh, home-style meals every day, catering to both vegetarian and non-vegetarian preferences.</p>
                                    </div>


                                    <div class="clearfix"></div>
                                    <div data-swiper-parallax="500" class="slide-btns">
                                        <a href="{{route('contact')}}" class="btn-main bg-btn lnk">Enquiry Now <i
                                                class="fas fa-chevron-right fa-icon"></i><span
                                                class="circle"></span></a>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <!-- <img src="images/hero/web-development.png"> -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>




                <div class="swiper-slide">
                    <div class="slide-inner slide-bg-image background-img" data-background="./img/download/1.jpeg">
                        <div class="gradient-overlay"></div> <!-- Gradient overlay -->
                        <div class="container">
                            <div class="row">
                                <div class="col-md-6">
                                    <div data-swiper-parallax="300" class="slide-title">
                                        <h3 class="text-white">Prime Location</h3>
                                    </div>
                                    <div data-swiper-parallax="400" class="slide-text">
                                        <h4 class="text-white">Convenience at Your Doorstep</h4>
                                        <p>Situated near DLF Back Gate, enjoy easy access to workplaces, colleges, and essential facilities.</p>
                                    </div>


                                    <div class="clearfix"></div>
                                    <div data-swiper-parallax="500" class="slide-btns">
                                        <a href="{{route('contact')}}" class="btn-main bg-btn lnk">Enquiry Now <i
                                                class="fas fa-chevron-right fa-icon"></i><span
                                                class="circle"></span></a>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <!-- <img src="images/hero/digital-marketing.png"> -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>


            <div class="swiper-pagination"></div>
            <div class="swiper-button-next"></div>
            <div class="swiper-button-prev"></div>

        </div>
    </section>


    <section class="about-sec-app pad-tb pt60 dark-bg2">
        <div class="container">
            <div class="row">
                <div class="col-lg-6">
                    <div class="common-heading text-l">
                        <h2 class="mb30">Welcome to <span class="text-second text-bold">Sai Saravana Men's Hostel </span></h2>
                        <p class="text-justify"> where comfort meets convenience. Located at Gate No. 4, Plot No. 4, near DLF Back Gate, Sri Lakshmi Nagar, Mugalivakkam, Chennai, Tamil Nadu 600116, our hostel offers a safe and secure living environment designed for students and professionals alike. </p>
                        <p class="mt10 text-justify">We pride ourselves on providing well-furnished rooms, modern amenities, and a peaceful atmosphere to make your stay enjoyable and stress-free. Whether you're in Chennai for work or studies, Sai Saravana Men's Hostel is your ideal home away from home.</p>

                    </div>



                </div>
                <div class="col-lg-6">
                    <div class="funfact">
                        <div class="row">

                            <div class="col-lg-6 col-md-6 col-sm-12 col-6">
                                <div class="funfct srcl2">
                                    <img src="images/icons/startup.svg" alt="startup icon">
                                    <span class="services-cuntr counter">6</span><span class="services-cuntr">+</span>
                                    <p>Years Experience </p>
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-12 col-6">
                                <div class="funfct srcl3">
                                    <img src="images/icons/team.svg" alt="team">
                                    <span class="services-cuntr counter">50</span><span class="services-cuntr">+</span>
                                    <p>Talented Squad </p>
                                </div>
                            </div>

                            <div class="col-lg-6 col-md-6 col-sm-12 col-6">
                                <div class="funfct srcl5">
                                    <img src="images/icons/computers.svg" alt="projects">
                                    <span class="services-cuntr counter">100</span><span class="services-cuntr">%</span>
                                    <p>Projects Delivered</p>
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-12 col-6">
                                <div class="funfct srcl1">
                                    <img src="images/icons/deal.svg" alt="Satisfaction">
                                    <span class="services-cuntr counter">100</span><span class="services-cuntr">%</span>
                                    <p>Client Satisfaction</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>


    <section class="service-section-app  dark-bg2 pb-5">

        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-12">
                    <div class="common-heading ptag">
                        <span>services</span>
                        <h2>At Sai Saravana Men's Hostel, we offer a range of services to ensure your stay is hassle-free:</h2>
                    </div>
                </div>
                <!-- <p class="mb30 text-center">Thanks to the creativity, our Web and
					Mobile Apps security and stability, integrated solutions and quality
					services, the Pojo Technology has turned into world's renowned
					destination for innovation, perceptive and business management

				</p> -->
            </div>
            <div class="row upset">
                <div class="col-lg-4 col-md-6 mt30 wow fadeIn" data-wow-delay="0.2s">
                    <div class="service-card-app hoshd">
                        <h4>Fully Furnished Rooms</h4>
                        <ul class="-service-list mt10">
                            <!-- <li> <a href="#">iPhone</a> </li>
							<li> <a href="#">Android</a> </li>
							<li> <a href="#">Cross Platform</a> </li> -->
                        </ul>
                        <div class="tec-icon mt30">
                            <img src="./img/download/1.jpeg" alt="">
                            <!-- <ul class="servc-icon-sldr">
								<li><a href="#">
										<div class="img-iconbb"><img src="images/icons/android.svg" alt="img"></div>
									</a></li>
								<li><a href="#">
										<div class="img-iconbb"><img src="images/icons/apple.svg" alt="img"></div>
									</a></li>
								<li><a href="#">
										<div class="img-iconbb"><img src="images/icons/tablet.svg" alt="img"></div>
									</a></li>
							</ul> -->
                        </div>
                        <p class="mt20 text-justify">Comfortable rooms with beds, wardrobes, and study tables.
                        </p>
                        <!-- <a href="mobile-app-development.html" class="mt20 link-prbs">Read More <i
								class="fas fa fa-arrow-circle-right"></i></a> -->
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 mt30 wow fadeIn" data-wow-delay="0.5s">
                    <div class="service-card-app hoshd">
                        <h4>Hygienic Food</h4>
                        <ul class="-service-list mt10">

                        </ul>
                        <div class="tec-icon mt30">
                            <ul class="servc-icon-sldr">
                                <img src="./img/download/8.jpeg" alt="">
                            </ul>
                        </div>
                        <p class="mt20 text-justify"> Fresh and healthy vegetarian and non-vegetarian meals served daily.
                        </p>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 mt30 wow fadeIn" data-wow-delay="0.5s">
                    <div class="service-card-app hoshd">
                        <h4>High-Speed Internet</h4>
                        <ul class="-service-list mt10">

                        </ul>
                        <div class="tec-icon mt30">
                            <ul class="servc-icon-sldr">
                                <img src="./img/download/11.jpeg" alt="">
                            </ul>
                        </div>
                        <p class="mt20 text-justify">24/7 Wi-Fi to stay connected for work, studies, or leisure.
                        </p>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 mt30 wow fadeIn" data-wow-delay="0.5s">
                    <div class="service-card-app hoshd">
                        <h4>Laundry Services</h4>
                        <ul class="-service-list mt10">

                        </ul>
                        <div class="tec-icon mt30">
                            <ul class="servc-icon-sldr">
                                <img src="./img/download/2.jpeg" alt="">
                            </ul>
                        </div>
                        <p class="mt20 text-justify"> Hassle-free laundry facilities for your convenience.
                        </p>
                    </div>
                    <a href="{{route('product')}}" class="btn btn-primary mt-2 d-flex justify-content-center">View More</a>
                </div>
            </div>
        </div>
        </div>
    </section>











    <!-- <section class="clients-section-app pad-tb">
		<div class="container">
			<div class="row justify-content-center">
				<div class="col-lg-8">
					<div class="common-heading text-w">

						<h2 class="mb30">Some of our Clients</h2>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-lg-12">
					<div class="client-logoset">
						<ul class="row text-center clearfix apppg">
							<li class="col-lg-2 col-md-3 col-sm-4 col-6 mt30 wow fadeIn" data-wow-delay=".2s">
								<div class="brand-logo hoshd"><img src="images/client/client-logo-1.jpg" alt="clients"
										class="img-fluid"></div>
								<p>Admire</p>
							</li>
							<li class="col-lg-2 col-md-3 col-sm-4 col-6 mt30 wow fadeIn" data-wow-delay=".4s">
								<div class="brand-logo hoshd"><img src="images/client/client-logo-2.jpg" alt="clients"
										class="img-fluid"></div>
								<p>Annai Masala</p>
							</li>
							<li class="col-lg-2 col-md-3 col-sm-4 col-6 mt30 wow fadeIn" data-wow-delay=".6s">
								<div class="brand-logo hoshd"><img src="images/client/client-logo-3.jpg" alt="clients"
										class="img-fluid"></div>
								<p>MK Auto Plast</p>
							</li>
							<li class="col-lg-2 col-md-3 col-sm-4 col-6 mt30 wow fadeIn" data-wow-delay=".8s">
								<div class="brand-logo hoshd"><img src="images/client/client-logo-4.jpg" alt="clients"
										class="img-fluid"></div>
								<p>Bestie</p>
							</li>
							<li class="col-lg-2 col-md-3 col-sm-4 col-6 mt30 wow fadeIn" data-wow-delay="1s">
								<div class="brand-logo hoshd"><img src="images/client/client-logo-5.jpg" alt="clients"
										class="img-fluid"></div>
								<p>BTM</p>
							</li>
							<li class="col-lg-2 col-md-3 col-sm-4 col-6 mt30 wow fadeIn" data-wow-delay="1.2s">
								<div class="brand-logo hoshd"><img src="images/client/client-logo-6.jpg" alt="clients"
										class="img-fluid"></div>
								<p>Camping</p>
							</li>
							<li class="col-lg-2 col-md-3 col-sm-4 col-6 mt30 wow fadeIn" data-wow-delay="1.4s">
								<div class="brand-logo hoshd"><img src="images/client/client-logo-7.jpg" alt="clients"
										class="img-fluid"></div>
								<p>CPS</p>
							</li>
							<li class="col-lg-2 col-md-3 col-sm-4 col-6 mt30 wow fadeIn" data-wow-delay="1.6s">
								<div class="brand-logo hoshd"><img src="images/client/client-logo-8.jpg" alt="clients"
										class="img-fluid"></div>
								<p>Escon Logo</p>
							</li>
							<li class="col-lg-2 col-md-3 col-sm-4 col-6 mt30 wow fadeIn" data-wow-delay="1.8s">
								<div class="brand-logo hoshd"><img src="images/client/client-logo-9.jpg" alt="clients"
										class="img-fluid"></div>
								<p>Event Service</p>
							</li>
							<li class="col-lg-2 col-md-3 col-sm-4 col-6 mt30 wow fadeIn" data-wow-delay="2s">
								<div class="brand-logo hoshd"><img src="images/client/client-logo-10.jpg" alt="clients"
										class="img-fluid"></div>
								<p>Jai Academy</p>
							</li>
							<li class="col-lg-2 col-md-3 col-sm-4 col-6 mt30 wow fadeIn" data-wow-delay="2.2s">
								<div class="brand-logo hoshd"><img src="images/client/client-logo-11.jpg" alt="clients"
										class="img-fluid"></div>
								<p>JJ</p>
							</li>
							<li class="col-lg-2 col-md-3 col-sm-4 col-6 mt30 wow fadeIn" data-wow-delay="2.4s">
								<div class="brand-logo hoshd"><img src="images/client/client-logo-12.jpg" alt="clients"
										class="img-fluid"></div>
								<p>Jungle Hikers</p>
							</li>
						</ul>
					</div>
					<div class="-cta-btn mt70">
						<div class="free-cta-title v-center wow zoomInDown">
							<a href="our-clients.html" class="btn-outline lnk">View More<span class="circle"></span></a>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section> -->





    <section class="testinomial-section-app bg-none pad-tb">
        <div class="container">
            <div class="row">
                <div class="col-lg-5">
                    <div class="common-heading text-l">
                        <span>What Our Residents Say</span>
                        <h2>Over 200+ Satisfied Guests</h2>
                    </div>
                    <!-- <div class="review-title-ref mt40">
						<div class="cta-card mt40">
							<a href="#" class="btn-outline lnk">Write a Reviews<span class="circle"></span></a>
						</div>
					</div>
					<div class="row mt30">
						<a href="#" target="blank" class="wow fadeIn col-lg-4 col-4" data-wow-delay=".2s"><img
								src="images/about/reviews-icon-1.png" alt="review" class="img-fluid"></a>

					</div> -->
                </div>
                <div class="col-lg-7">
                    <div class="pl50">
                        <div class="shape shape-a1"><img src="images/shape/shape-3.svg" alt="shape"></div>
                        <div class="testimonial-card-a tcd owl-carousel">
                            <div class="testimonial-card">
                                <div class="tt-text">
                                    <p>The rooms are comfortable, the food is delicious, and the location is perfect. Sai Saravana Men's Hostel feels like home!</p>
                                </div>
                                <div class="client-thumbs mt30">
                                    <div class="media v-center upset">
                                        <div class="user-image bdr-radius"><img
                                                src="images/user-thumb/pojo-testimonial.png" alt="girl"
                                                class="img-fluid rounded-circle" /></div>
                                        <div class="media-body user-info v-center">
                                            <h5> – Aisha K</h5>

                                            <i class="fas fa-quote-right posiqut"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="testimonial-card">
                                <div class="tt-text">
                                    <p>From Wi-Fi to laundry, everything is well-maintained. It's the best place for students looking for affordable and secure accommodation.
                                    </p>
                                </div>
                                <div class="client-thumbs mt30">
                                    <div class="media v-center upset">
                                        <div class="user-image bdr-radius"><img
                                                src="images/user-thumb/pojo-testimonial.png" alt="girl"
                                                class="img-fluid rounded-circle" /></div>
                                        <div class="media-body user-info v-center">
                                            <h5>– Suresh P</h5>

                                            <i class="fas fa-quote-right posiqut"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="testimonial-card">
                                <div class="tt-text">
                                    <p>The staff is friendly and helpful, and the hostel is always clean and secure. I would recommend it to anyone staying in Chennai.</p>
                                </div>
                                <div class="client-thumbs mt30">
                                    <div class="media v-center upset">
                                        <div class="user-image bdr-radius"><img
                                                src="images/user-thumb/pojo-testimonial.png" alt="girl"
                                                class="img-fluid rounded-circle" /></div>
                                        <div class="media-body user-info v-center">
                                            <h5>– Bharath S</h5>

                                            <i class="fas fa-quote-right posiqut"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    @include('footer')
    <!-- <div class="footer-row3">
		<div class="copyright">
			<div class="container">
				<div class="row">
					<div class="col-lg-12">
						<div class="footer-social-media-icons">
							<a href="https://www.facebook.com/careercounsellorkerala?mibextid=ZbWKwL
					" target="blank"><i class="fab fa-facebook"></i></a>
				
					<a href=" https://instagram.com/mycareercounsellor?igshid=OGQ5ZDc2ODk2ZA==" target="blank"><i
							class="fab fa-instagram"></i></a>
					<a href="https://youtube.com/@mycareercounsellor?si=deOSl3bAqhPsV4ph" target="blank"><i
							class="fab fa-youtube"></i></a>

						</div>

					</div>
				</div>
			</div>
		</div>
	</div> -->


    <script data-cfasync="false" src="../cdn-cgi/scripts/5c5dd728/cloudflare-static/email-decode.min.js"></script>
    <script src="js/vendor/modernizr-3.5.0.min.js"></script>
    <script src="js/jquery.min.js"></script>
    <script src="js/bootstrap.bundle.min.js"></script>
    <script src="js/plugin.min.js"></script>
    <script src="js/preloader.js"></script>
    <script src="js/dark-mode.js"></script>
    <script src="js/swiper.min.js"></script>

    <script src="js/main.js"></script>


</body>

</html>