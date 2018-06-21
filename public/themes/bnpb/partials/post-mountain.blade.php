<div class="col-md-12">
	<h3 class="block-title"><span><a href="{{ url('/mountain-status') }}" title="Status Gunung Api">Status Gunung Api</a></span></h3>
</div>
<div class="col-md-12">
	<div class="page-content">
		<div class="row">
			<div class="col-md-12">
				<!-- start content !-->
				@if (! is_null(get_all_mountains()))		
					@foreach(get_all_mountains() as $data)
						@php
							setlocale(LC_TIME, 'Indonesian');
							$date = date_create($data->date_of_the_incident);
						@endphp
						@if ($data->mountain_status == 'awas')
							<div class="col-md-12 col-sm-12 col-xs-12" style="box-sizing: border-box; outline: 0px none currentcolor; position: relative; min-height: 1px; padding-right: 15px; padding-left: 15px; width: 738px; color: #333333; font-family: Arial; clear: both; margin-top: 5px;">
								<h2 style="box-sizing: border-box; outline: 0px none currentcolor; line-height: 1.3; font-size: 18px; padding: 10px 0px 0px; float: left; color: red;">AWAS</h2>
								<div class="col-md-12 col-sm-12 col-xs-12 container" style="box-sizing: border-box; outline: 0px none currentcolor; padding: 0px; margin: 0px; position: relative; min-height: 1px; width: auto !important; max-width: 100%; clear: both;">
									<div class="alt-grid row" style="box-sizing: border-box; outline: 0px none currentcolor; margin-right: -15px; margin-left: -15px;">
										<div class="col-md-7 col-sm-7 col-xs-7 fontarial font13" style="box-sizing: border-box; outline: 0px none currentcolor; position: relative; min-height: 1px; padding-right: 15px; padding-left: 15px; float: left; width: 430.5px; font-size: 13px; margin: 10px 0px;">{{ strtoupper($data->name) }}</div>
										<div class="col-md-5 col-sm-5 col-xs-5 fontarial font13" style="box-sizing: border-box; outline: 0px none currentcolor; position: relative; min-height: 1px; padding-right: 15px; padding-left: 15px; float: left; width: 307.5px; font-size: 13px; margin: 10px -15px 10px 0px; text-align: right;">{{ date_format($date,"j F Y") }}</div>
									</div>
								</div>
							</div>	
						@elseif ($data->mountain_status == 'siaga')
							<div class="col-md-12 col-sm-12 col-xs-12" style="box-sizing: border-box; outline: 0px none currentcolor; position: relative; min-height: 1px; padding-right: 15px; padding-left: 15px; width: 738px; color: #333333; font-family: Arial; clear: both; margin-top: 5px;">
								<h2 style="box-sizing: border-box; outline: 0px none currentcolor; line-height: 1.3; font-size: 18px; padding: 10px 0px 0px; float: left; color: #ff7f00;">SIAGA</h2>
								<div class="col-md-12 col-sm-12 col-xs-12 container" style="box-sizing: border-box; outline: 0px none currentcolor; padding: 0px; margin: 0px; position: relative; min-height: 1px; width: auto !important; max-width: 100%; clear: both;">
									<div class="alt-grid row" style="box-sizing: border-box; outline: 0px none currentcolor; margin-right: -15px; margin-left: -15px;">
										<div class="col-md-7 col-sm-7 col-xs-7 fontarial font13" style="box-sizing: border-box; outline: 0px none currentcolor; position: relative; min-height: 1px; padding-right: 15px; padding-left: 15px; float: left; width: 430.5px; font-size: 13px; margin: 10px 0px;">{{ strtoupper($data->name) }}</div>
										<div class="col-md-5 col-sm-5 col-xs-5 fontarial font13" style="box-sizing: border-box; outline: 0px none currentcolor; position: relative; min-height: 1px; padding-right: 15px; padding-left: 15px; float: left; width: 307.5px; font-size: 13px; margin: 10px -15px 10px 0px; text-align: right;">{{ date_format($date,"j F Y") }}</div>
									</div>
								</div>
							</div>	
						@else
							<div class="col-md-12 col-sm-12 col-xs-12" style="box-sizing: border-box; outline: 0px none currentcolor; position: relative; min-height: 1px; padding-right: 15px; padding-left: 15px; width: 738px; color: #333333; font-family: Arial; clear: both; margin-top: 5px;">
								<h2 style="box-sizing: border-box; outline: 0px none currentcolor; line-height: 1.3; font-size: 18px; padding: 10px 0px 0px; float: left; color: #ff7f00;">WASPADA</h2>
								<div class="col-md-12 col-sm-12 col-xs-12 container" style="box-sizing: border-box; outline: 0px none currentcolor; padding: 0px; margin: 0px; position: relative; min-height: 1px; width: auto !important; max-width: 100%; clear: both;">
									<div class="alt-grid row" style="box-sizing: border-box; outline: 0px none currentcolor; margin-right: -15px; margin-left: -15px;">
										<div class="col-md-7 col-sm-7 col-xs-7 fontarial font13" style="box-sizing: border-box; outline: 0px none currentcolor; position: relative; min-height: 1px; padding-right: 15px; padding-left: 15px; float: left; width: 430.5px; font-size: 13px; margin: 10px 0px;">{{ strtoupper($data->name) }}</div>
										<div class="col-md-5 col-sm-5 col-xs-5 fontarial font13" style="box-sizing: border-box; outline: 0px none currentcolor; position: relative; min-height: 1px; padding-right: 15px; padding-left: 15px; float: left; width: 307.5px; font-size: 13px; margin: 10px -15px 10px 0px; text-align: right;">{{ date_format($date,"j F Y") }}</div>
									</div>
								</div>
							</div>	
						@endif
					@endforeach
				@else
					<span> No Data </span>
				@endif
				<!-- end start content !-->
			</div>
			<hr>
		</div>
	</div>
</div>