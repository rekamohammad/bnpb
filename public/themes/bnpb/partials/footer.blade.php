<footer class="footer" id="footer">
    <div class="container">
        <div class="row">
            {!! dynamic_sidebar('footer_sidebar') !!}
        </div>
    </div>
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
<link media="all" type="text/css" rel="stylesheet" href="/themes/bnpb/assets/css/fancybox.css">
<script src="/themes/bnpb/assets/js/fancybox.min.js"></script>

<script>
    $(document).ready(function () {
        $('.banner-slider-wrap').slick({
            dots: true
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