

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/balloon-css/0.5.0/balloon.min.css">
<style>
.no-padding {
	padding: 0;
}
.module_long{
	border: 1px solid #b3b3b3;
}
.gunung-api {
	width: 100%			
}

.gunung-api td {
	vertical-align: top;
	padding:5px;
	margin:0;
	font-size: 10pt;
}
.top {
	vertical-align: top;
	padding-top: 5px !important;
}
.middle {
	vertical-align: middle;	
}
</style>
<div class="aside-box">
    <div class="aside-box-header">
		<h4>{{ __($config['name']) }}</h4>
    </div>
    <div class="aside-box-content no-padding">
        <div class="date-status">
            <div class="title-head">
                <div class="label-status">
                    <div class="line pull-right"></div>
                </div>
                <div class="module module_long">
                    <table class="gunung-api" border="0">
                        <tbody>
                            @if (count(get_all_mountains_awas()) != 0)
                                @foreach(get_all_mountains_awas() as $data)
                                    @php
                                        setlocale(LC_TIME, 'Indonesian');
                                        $date = date_create($data->date_of_the_incident);
                                    @endphp

                                    <tr class="middle">
                                        <td data-balloon="AWAS" data-balloon-pos="left">
                                            <img alt="led_red_blink" src="http://vsi.esdm.go.id/images/stories/alert/led_red_blink.gif" class="img-responsive" width="12" height="12">
                                        </td>
                                        <td>{{ strtoupper($data->name) }}</td>
                                        <td>{{ date_format($date,"j F Y") }}</td>
                                    </tr>
                                @endforeach
                                </div>
                            @endif

                            @if (count(get_all_mountains_siaga()) != 0)
                                @foreach(get_all_mountains_siaga() as $data)
                                    @php
                                        setlocale(LC_TIME, 'Indonesian');
                                        $date = date_create($data->date_of_the_incident);
                                    @endphp

                                    <tr>
                                        <td data-balloon="SIAGA" data-balloon-pos="left">
                                            <img alt="led_orange_blink" src="http://vsi.esdm.go.id/images/stories/alert/led_orange_blink.gif" class="img-responsive" width="12" height="12">
                                        </td>
                                        <td>{{ strtoupper($data->name) }}</td>
                                        <td>{{ date_format($date,"j F Y") }}</td>
                                    </tr>
                                @endforeach
                                </div>
                            @endif

                            @if (count(get_all_mountains_waspada()) != 0)
                                @foreach(get_all_mountains_waspada() as $data)
                                    @php
                                        setlocale(LC_TIME, 'Indonesian');
                                        $date = date_create($data->date_of_the_incident);
                                    @endphp

                                    <tr>
                                        <td data-balloon="WASPADA" data-balloon-pos="left">
                                            <img alt="led_yellow" src="http://vsi.esdm.go.id/images/stories/alert/led_yellow.gif" class="img-responsive" width="12" height="12">
                                        </td>
                                        <td>{{ strtoupper($data->name) }}</td>
                                        <td>{{ date_format($date,"j F Y") }}</td>
                                    </tr>
                                @endforeach
                                </div>
                            @endif
                        </tbody>
                    </table>
                    <br>
                    <p style="padding: 5px;">Keterangan :</p>
                    <table class="gunung-api">
                        <tbody>
                            <tr>
                                <td class="middle">
                                    <img alt="led_red_blink" src="http://vsi.esdm.go.id/images/stories/alert/led_red_blink.gif" class="img-responsive"
                                        width="12" height="12">
                                </td>
                                <td class="top">Level IV (AWAS)</td>
                            </tr>
                            <tr>
                                <td class="middle">
                                    <img alt="led_orange_blink" src="http://vsi.esdm.go.id/images/stories/alert/led_orange_blink.gif" class="img-responsive"
                                        width="12" height="12">
                                </td>
                                <td class="top">Level III (SIAGA)</td>
                            </tr>
                            <tr>
                                <td class="middle">
                                    <img alt="led_yellow" src="http://vsi.esdm.go.id/images/stories/alert/led_yellow.gif" class="img-responsive" width="12"
                                        height="12">
                                </td>
                                <td class="top">Level II (WASPADA)</td>
                            </tr>
                            <tr>
                                <td class="middle">
                                    <img alt="led_green" src="http://vsi.esdm.go.id/images/stories/alert/led_green.gif" class="img-responsive" width="12"
                                        height="12">
                                </td>
                                <td class="top">Level I (NORMAL)</td>
                            </tr>
                        </tbody>
                    </table>
					<br>
                </div>
            </div>
        </div>
        <br/>
        <p style="text-align: right;">
            <strong>
                <a href="mountain-status" target=_blank>{{ __('tabs.selengkapnya') }}>></a>
            </strong>
        </p>
    </div>
</div>
