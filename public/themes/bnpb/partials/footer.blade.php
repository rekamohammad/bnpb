<footer class="footer" id="footer">
    <div class="container">
        <div class="row">
        @php
            $detect = new App\MobileDetect\Mobile_Detect;
        @endphp
        @if ($detect->isMobile())
            <div class="col-md-12 about-footer">
                <img src="/uploads/3/logofooter.png" class="img-responsive">
                <address>
                    <p>Graha BNPB - Jl. Pramuka Kav.38 Jakarta <br>Timur 13120</p>
                    <span><a href="#">Telp.021-29827793</a> <br> Fax.021-21281200 <br> Email: <a href="#">contact@bnpb.go.id</a></span>
                    <h5>Pusdalop bnpb</h5>
                    <span>Telp. +62 21 29827444 , 29827666 <br><i class="wa"></i>  +62 812 1237 575 <br><a>Email:pusdalops@bnpb.go.id</a></span>
                </address>
            </div><br></br>
        @else
            {!! dynamic_sidebar('footer_sidebar') !!}
        @endif
        </div>
    </div>
    @if ($detect->isMobile())
    <div class="footer-end fixed">
        <center>
        <div class="hi-icon-wrap hi-icon-effect-3 hi-icon-effect-3a">
            <a href="https://www.facebook.com/infobnpb" title="Facebook" class="hi-icon fa fa-facebook fa-lg"></a>
            <a href="https://twitter.com/BNPB_Indonesia" title="Twitter" class="hi-icon fa fa-twitter fa-lg"></a>
            <a href="https://www.instagram.com/bnpb_indonesia/" title="Instagram" class="hi-icon fa fa-instagram fa-lg"></a>
            <a href="https://www.youtube.com/user/BNPBIndonesia/" title="Youtube" class="hi-icon fa fa-youtube-play fa-lg"></a>
        </div>
        </center>
    </div>
    @else
    <div class="footer-end">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <p>{!! __(theme_option('copyright')) !!}</p>
                </div>
                <div class="col-md-6">
                    {!!
                        Menu::generateMenu([
                            'slug' => 'footer-end-menu',
                            'options' => ['class' => 'footer-end-menu'],
                            'view' => 'main-menu'
                        ])
                    !!}
                </div>
            </div>
        </div>
    </div>
    @endif     
</footer>

@if (app()->environment() != 'production')
    <div class="theme-panel-wrap">
            <span class="theme-panel-control">
                <i class="fa fa-cogs"></i>
                <i class="fa fa-times"></i>
            </span>
        <div class="theme-panel">
            <div class="theme-options">
                <div class="theme-option theme-colors">
                    <h4>THEME COLOR</h4>
                    <ul>
                        <li><a href="#" data-style="blue"></a></li>
                        <li><a href="#" data-style="green"></a></li>
                        <li><a href="#" data-style="red"></a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
@endif

</div>

{!! Theme::footer() !!}
        <!--[if lt IE 9]>
<script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
<script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
<![endif]-->

<!-- <script type="text/javascript" src="//platform-api.sharethis.com/js/sharethis.js#property=58b80e5cfacf57001271be31&product=sticky-share-buttons"></script> -->
<script type="text/javascript" src="/themes/bnpb/assets/js/lightgallery.js"></script>
<link media="all" type="text/css" rel="stylesheet" href="/themes/bnpb/assets/css/fancybox.css">
<script src="/themes/bnpb/assets/js/fancybox.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/lightgallery/1.6.6/js/lightgallery-all.js"></script>
<script type="text/javascript" src="/themes/bnpb/assets/js/jquery.gdocsviewer.min.js"></script>
@if (Request::segment(1) == 'gpr')
<script type="text/javascript" src="https://widget.kominfo.go.id/gpr-widget-kominfo.min.js"></script>
@endif
<script>
@if (Request::segment(1) == 'galleries')
	     for($i=1; $i<$('.thumbnail').length; $i++){
			$('#lightgallery-'+$i).lightGallery();
		}
	
@endif						
    $(document).ready(function () {
        $('.banner-slider-wrap').slick({
            dots: true
        });
        $('#list-diorama').slick({
            slidesToShow: 2,
            slidesToScroll: 1,
            dots: false,
            arrows: false,
            centerMode: true,
            focusOnSelect: true
        });  
        $('#list-photo').slick({
            slidesToShow: 1,
            slidesToScroll: 1,
            arrows: false,
            fade: true,
            asNavFor: '#list-thumb'
        });
        $('#list-thumb').slick({
            slidesToShow: 3,
            slidesToScroll: 1,
            asNavFor: '#list-photo',
            dots: true,
            centerMode: true,
            focusOnSelect: true
        });   
        $("[data-fancybox]").fancybox({
            loop : true,
        });   
        var windowsize = $(window).width();
        if (windowsize < 768) {
            $('body').on('click', '.menu-item-has-children > a', function(){
                $(this).parent().siblings().find('.dropdown-menu').hide();
                $(this).parent().find('> .dropdown-menu').slideToggle('fast');
                console.log('jalan');
            });
        }
        $('.popup-info').lightGallery({
            thumbnail:true
        });
        $('.view-pdf').lightGallery({
            selector: 'this',
            iframeMaxWidth: '85%',
        });
        $('a.embed').gdocsViewer();
    });
</script>
@if(!empty(theme_option('facebook-app-id')))
<div id="fb-root"></div>
<script>(function (d, s, id) {
        var js, fjs = d.getElementsByTagName(s)[0];
        if (d.getElementById(id)) return;
        js = d.createElement(s);
        js.id = id;
        js.src = "//connect.facebook.net/id_ID/sdk.js#xfbml=1&version=v2.8&appId={{ theme_option('facebook-app-id') }}";
        fjs.parentNode.insertBefore(js, fjs);
    }(document, 'script', 'facebook-jssdk'));</script>
@endif
</body>
</html>