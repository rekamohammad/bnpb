<div class="col-lg-7">
    <div class="chart" id="stats-chart"></div>
</div>
<div class="col-lg-5" style="margin-bottom: 30px;">
    <div id="world-map"></div>
</div>
<div class="clearfix"></div>

<div class="col-lg-3 col-md-4 col-sm-6">
    <div class="info-box">
        <div class="info-box-icon bg-yellow-casablanca font-white">
            <i class="fa fa-eye"></i>
        </div>
        <div class="info-box-content">
            <span class="info-box-text">{{ trans('analytics::analytics.sessions') }}</span>
            <span class="info-box-number" id="sessions_total">{{ $total['ga:sessions'] }}</span>
        </div>
    </div>
</div>

<div class="col-lg-3 col-md-4 col-sm-6">
    <div class="info-box">
        <div class="info-box-icon bg-blue">
            <i class="fa fa-users"></i>
        </div>
        <div class="info-box-content">
            <span class="info-box-text">{{ trans('analytics::analytics.visitors') }}</span>
            <span class="info-box-number" id="users_total">{{ $total['ga:users'] }}</span>
        </div>
    </div>
</div>

<div class="col-lg-3 col-md-4 col-sm-6">
    <div class="info-box border-green-haze">
        <div class="info-box-icon bg-green-haze font-white">
            <i class="icon icon-traffic-cone"></i>
        </div>
        <div class="info-box-content">
            <span class="info-box-text">{{ trans('analytics::analytics.pageviews') }}</span>
            <span class="info-box-number" id="page_views_total">{{ $total['ga:pageviews'] }}</span>
        </div>
    </div>
</div>

<div class="col-lg-3 col-md-4 col-sm-6">
    <div class="info-box">
        <div class="info-box-icon bg-yellow">
            <i class="icon-energy"></i>
        </div>
        <div class="info-box-content">
            <span class="info-box-text">{{ trans('analytics::analytics.bounce_rate') }}</span>
            <span class="info-box-number" id="bounce_rate_total">{{ round($total['ga:bounceRate'], 2) }}%</span>
        </div>
    </div>
</div>

<div class="col-lg-3 col-md-4 col-sm-6">
    <div class="info-box">
        <div class="info-box-icon bg-purple">
            <i class="fa fa-pie-chart"></i>
        </div>
        <div class="info-box-content">
            <span class="info-box-text">{{ trans('analytics::analytics.percent_new_session') }}</span>
            <span class="info-box-number" id="percent_new_session_total">{{ round($total['ga:percentNewSessions'], 2) }}%</span>
        </div>
    </div>
</div>

<div class="col-lg-3 col-md-4 col-sm-6">
    <div class="info-box">
        <div class="info-box-icon bg-yellow-crusta font-white">
            <i class="icon-graph"></i>
        </div>
        <div class="info-box-content">
            <span class="info-box-text">{{ trans('analytics::analytics.page_session') }}</span>
            <span class="info-box-number" id="page_views_per_visit_total">{{ round($total['ga:pageviewsPerVisit'], 2) }}</span>
        </div>
    </div>
</div>

<div class="col-lg-3 col-md-4 col-sm-6">
    <div class="info-box">
        <div class="info-box-icon bg-red">
            <i class="fa fa-clock-o"></i>
        </div>
        <div class="info-box-content">
            <span class="info-box-text">{{ trans('analytics::analytics.avg_duration') }}</span>
            <span class="info-box-number" id="session_duration_total">{{ gmdate('H:i:s', $total['ga:avgSessionDuration']) }}</span>
        </div>
    </div>
</div>

<div class="col-lg-3 col-md-4 col-sm-6">
    <div class="info-box">
        <div class="info-box-icon bg-yellow-casablanca">
            <i class="fa fa-user-plus"></i>
        </div>
        <div class="info-box-content">
            <span class="info-box-text">{{ trans('analytics::analytics.new_users') }}</span>
            <span class="info-box-number" id="session_duration_total">{{ $total['ga:newUsers'] }}</span>
        </div>
    </div>
</div>

<div class="clearfix"></div>

<div data-stats='{{ json_encode($stats, JSON_HEX_APOS) }}'></div>
<div data-country-stats='{{ json_encode($country_stats, JSON_HEX_APOS) }}'></div>
<div data-lang-pageviews='{{ trans("analytics::analytics.pageviews") }}'></div>
<div data-lang-visits='{{ trans("analytics::analytics.visits") }}'></div>

<script>
    $(document).ready(function () {
        var stats = $('div[data-stats]').data('stats');
        var country_stats = $('div[data-country-stats]').data('country-stats');
        var lang_pageviews = $('div[data-lang-pageviews]').data('lang-pageviews');
        var lang_visits = $('div[data-lang-visits]').data('lang-visits');


        var statArray = [];
        $.each(stats, function (index, el) {
            statArray.push({axis: el.axis, visitors: el.visitors, pageViews: el.pageViews});
        });


        /* Morris.js Charts */
        var area = new Morris.Area({
            element: 'stats-chart',
            resize: true,
            data: statArray,
            xkey: 'axis',
            ykeys: ['visitors', 'pageViews'],
            labels: [lang_visits, lang_pageviews],
            lineColors: ['#DD4D37', '#3c8dbc'],
            hideHover: 'auto',
            parseTime: false
        });

        //jvectormap data
        var visitorsData = {};

        $.each(country_stats, function (index, el) {
            visitorsData[el[0]] = el[1];
        });

        //World map by jvectormap
        $('#world-map').vectorMap({
            map: 'world_mill_en',
            backgroundColor: 'transparent',
            regionStyle: {
                initial: {
                    fill: '#e4e4e4',
                    'fill-opacity': 1,
                    stroke: 'none',
                    'stroke-width': 0,
                    'stroke-opacity': 1
                }
            },
            series: {
                regions: [{
                    values: visitorsData,
                    scale: ['#C64333', '#dd4b39'],
                    normalizeFunction: 'polynomial'
                }]
            },
            onRegionLabelShow: function (e, el, code) {
                if (typeof visitorsData[code] != 'undefined')
                    el.html(el.html() + ': ' + visitorsData[code] + ' ' + lang_visits);
            }
        });
    });
</script>