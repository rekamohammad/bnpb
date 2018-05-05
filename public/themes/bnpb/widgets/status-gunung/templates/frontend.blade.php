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
			<div class="info-date">
				<div class="danger">
					<div class="label-mounth">
						<div class="mounth">
							<font color = red>{{ __('Awas') }}</font>
							<span class="orange pull-right">
							<font color = red>{{ $config['awas'] }}</font>
						</div>
					</div>
					<br>
					<div class="mounth">{{ $config['Gunung1'] }} <span class="orange pull-right">{{ $config['dategunung1'] }}</span></div>
				</div>							
			</div>				
				<div class="warning"><br>
					<div class="label-mounth">
						<div class="mounth" style="color: #f7a610;">{{ __('Siaga') }}<span class="orange pull-right">{{ $config['siaga'] }}</span></div>
					</div><br>
					<div class="mounth">{{ $config['Gunung2'] }} <span class="orange pull-right">{{ $config['dategunung2'] }}</span></div>
					<div class="mounth">{{ $config['Gunung3'] }}<span class="orange pull-right">{{ $config['dategunung3'] }}</span></div>
					<div class="mounth">{{ $config['Gunung4'] }} <span class="orange pull-right">{{ $config['dategunung4'] }}</span></div>
					<div class="mounth">{{ $config['Gunung5'] }} <span class="orange pull-right">{{ $config['dategunung5'] }}</span></div>
				</div>
			</div>				
		</div>
		<br/>
		<p style="text-align: right;"><strong><a href="status-gunung-api" target=_blank>{{ __('tabs.selengkapnya') }}>></a></strong></p>
    </div>
</div>