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
    <section class="breadcrumb-area banner-1">
		<div class="text-block">
			<div class="container">
				<div class="row">
					<div class="col-lg-12 v-center">
						<div class="bread-inner">
							<div class="bread-menu">
								<ul>
									<li><a href="index.html">Home</a></li>
									<li><a href="#">Services</a></li>
								</ul>
							</div>
							<div class="bread-title">
								<h2>Products</h2>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>


    <section class="service-section-app  dark-bg2 pb-5 mt-5">

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
                </div>
                <div class="col-lg-4 col-md-6 mt30 wow fadeIn" data-wow-delay="0.5s">
                    <div class="service-card-app hoshd">
                        <h4>24/7 Security</h4>
                        <ul class="-service-list mt10">

                        </ul>
                        <div class="tec-icon mt30">
                            <ul class="servc-icon-sldr">
                                <img src="./img/download/10.jpeg" alt="">
                            </ul>
                        </div>
                        <p class="mt20 text-justify">  CCTV surveillance and a secure environment for your safety.
                        </p>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 mt30 wow fadeIn" data-wow-delay="0.5s">
                    <div class="service-card-app hoshd">
                        <h4>Power Backup</h4>
                        <ul class="-service-list mt10">

                        </ul>
                        <div class="tec-icon mt30">
                            <ul class="servc-icon-sldr">
                                <img src="./img/download/12.jpeg" alt="">
                            </ul>
                        </div>
                        <p class="mt20 text-justify">Uninterrupted power supply to ensure your comfort.
                        </p>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 mt30 wow fadeIn" data-wow-delay="0.5s">
                    <div class="service-card-app hoshd">
                        <h4>Common Amenities</h4>
                        <ul class="-service-list mt10">

                        </ul>
                        <div class="tec-icon mt30">
                            <ul class="servc-icon-sldr">
                                <img src="./img/download/3.jpeg" alt="">
                            </ul>
                        </div>
                        <p class="mt20 text-justify">Spacious common areas for recreation and relaxation.
                        </p>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 mt30 wow fadeIn" data-wow-delay="0.5s">
                    <div class="service-card-app hoshd">
                        <h4>Affordable Pricing</h4>
                        <ul class="-service-list mt10">

                        </ul>
                        <div class="tec-icon mt30">
                            <ul class="servc-icon-sldr">
                                <img src="./img/download/7.jpeg" alt="">
                            </ul>
                        </div>
                        <p class="mt20 text-justify">Budget-friendly accommodation without compromising quality.
                        </p>
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