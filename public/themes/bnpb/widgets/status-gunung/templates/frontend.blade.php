<div class="aside-box">
    <div class="aside-box-header">
        <h4>{{ __($config['name']) }}</h4>
    </div>
    <div class="aside-box-content">
        <div class="date-status">
			<div class="title-head">
				<div class="label-status">
					<div class="line pull-right"></div>
				</div>
				@if (! empty(get_all_mountains()))
					@foreach(get_all_mountains() as $data)
						@php
							setlocale(LC_TIME, 'Indonesian');
							$date = date_create($data->date_of_the_incident);
						@endphp
						@if ($data->mountain_status == 'awas')
							<div class="danger">
								<div class="label-mounth">
									<div class="mounth" style="color: red;"> {{ __('AWAS') }} </div>
								</div>
								<br>
								<div class="mounth">{{ strtoupper($data->name) }} <span class="orange pull-right">{{ date_format($date,"j F Y") }}</span></div>
							</div>
						@elseif ($data->mountain_status == 'siaga')
							<div class="warning"><br>
								<div class="label-mounth">
									<div class="mounth" style="color: #f7a610;">{{ __('SIAGA') }} </div>
								</div><br>
								<div class="mounth">{{ strtoupper($data->name) }} <span class="orange pull-right">{{ date_format($date,"j F Y") }}</span></div>
							</div>
						@else
							<div class="warning"><br>
								<div class="label-mounth">
									<div class="mounth" style="color: #f7a610;">{{ __('WASPADA') }} </div>
								</div><br>
								<div class="mounth">{{ strtoupper($data->name) }} <span class="orange pull-right">{{ date_format($date,"j F Y") }}</span></div>
							</div>
						@endif
					@endforeach
				@else
					<span> No Data </span>
				@endif
			</div>				
		</div>
		<br/>
		<p style="text-align: right;"><strong><a href="mountain-status" target=_blank>{{ __('tabs.selengkapnya') }}>></a></strong></p>
    </div>
</div>