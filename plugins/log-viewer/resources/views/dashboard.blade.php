@extends('bases::layouts.master')
@section('content')
    @include('log-viewer::partials.style')
    <div class="main-form">
        <div class="row">
            @if (count($percents) > 0)
            <div class="col-md-3">
                <canvas id="stats-doughnut-chart"></canvas>
            </div>
            <div class="col-md-9">
                <section class="box-body">
                    <div class="row">
                        @foreach($percents as $level => $item)
                            <div class="col-md-4">
                                <div
                                    class="info-box level level-{{ $level }} @if ($item['count'] === 0) level-empty @endif">
                                    <span class="info-box-icon">
                                        {!! log_styler()->icon($level) !!}
                                    </span>
                                    <a href="{{ route('log-viewer::logs.list') }}" class="box-href">
                                        <div class="info-box-content">
                                            <span class="info-box-text">{{ $item['name'] }}</span>
                                            <span class="info-box-number">
                                            {{ $item['count'] }} {{ trans('log-viewer::log-viewer.entries') }} - {!! $item['percent'] !!} %
                                        </span>
                                            <div class="progress">
                                                <div class="progress-bar" style="width: {{ $item['percent'] }}%"></div>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </section>
            </div>
            @else
                <div class="col-md-12">
                    <div class="alert alert-success">{{ trans('log-viewer::log-viewer.no_error') }}</div>
                </div>
            @endif
        </div>
    </div>
@endsection

@section('javascript')
    <script>
        $(function () {
            Chart.defaults.global.responsive = true;
            Chart.defaults.global.scaleFontFamily = "Roboto";
            Chart.defaults.global.animationEasing = "easeOutQuart";

            var data = {!! $reports !!};

            new Chart($('#stats-doughnut-chart')[0].getContext('2d'))
                .Doughnut(data, {
                    animationEasing: "easeOutQuart"
                });
        });
    </script>
@endsection
