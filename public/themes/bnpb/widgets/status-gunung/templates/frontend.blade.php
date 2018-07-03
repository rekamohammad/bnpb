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
				
					@if (count(get_all_mountains_awas()) != 0)
						<div class="danger">
							<div class="label-mounth">
								<div class="mounth" style="color: red;"> {{ __('AWAS') }} </div>
							</div>
							<br>
						@foreach(get_all_mountains_awas() as $data)
							@php
								setlocale(LC_TIME, 'Indonesian');
								$date = date_create($data->date_of_the_incident);
							@endphp

							<div class="mounth"> {{ strtoupper($data->name) }}
								<span class="orange pull-right">{{ date_format($date,"j F Y") }}</span>
							</div><br>
						@endforeach
						</div>
					@endif

					@if (count(get_all_mountains_siaga()) != 0)
						<div class="warning">
							<div class="label-mounth">
								<div class="mounth" style="color: #f7a610;">{{ __('SIAGA') }} </div>
							</div>
							<br>
						@foreach(get_all_mountains_siaga() as $data)
							@php
								setlocale(LC_TIME, 'Indonesian');
								$date = date_create($data->date_of_the_incident);
							@endphp

							<div class="mounth"> {{ strtoupper($data->name) }}
								<span class="orange pull-right">{{ date_format($date,"j F Y") }}</span>
							</div><br>
						@endforeach
						</div>
					@endif

					@if (count(get_all_mountains_waspada()) != 0)
						<div class="warning">
							<div class="label-mounth">
								<div class="mounth" style="color: #f7a610;">{{ __('WASPADA') }} </div>
							</div>
							<br>
						@foreach(get_all_mountains_waspada() as $data)
							@php
								setlocale(LC_TIME, 'Indonesian');
								$date = date_create($data->date_of_the_incident);
							@endphp

							<div class="mounth"> {{ strtoupper($data->name) }}
								<span class="orange pull-right">{{ date_format($date,"j F Y") }}</span>
							</div><br>
						@endforeach
						</div>
					@endif
				@else
					<span> No Data </span>
				@endif
			</div>				
		</div>
		<br/>
		<p style="text-align: right;"><strong><a href="mountain-status" target=_blank>{{ __('tabs.selengkapnya') }}>></a></strong></p>
    </div>
</div>