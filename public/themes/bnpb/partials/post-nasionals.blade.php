<div class="col-md-12">
	<h3 class="block-title"><span><a href="{{ url('/nasionals') }}" title="Publikasi BNPB">Link Nasional</a></span></h3>
</div>
<div class="col-md-12">
	<div class="page-content">
		<div class="row">
			<div class="col-md-3">{!! Theme::partial('links-menu') !!}</div>
			<div class="col-md-9">
				<!-- start content !-->
				
				
				<div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
				@foreach(get_all_nasional() as $number=>$nasional)
					@if($number+1 ==1)
					  <div class="panel panel-default">
						<a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse{{$number+1}}" aria-expanded="true" aria-controls="collapse{{$number+1}}">
						<div class="panel-heading" role="tab" id="heading{{$number+1}}" style="background: #3b5999;color: #fff;">
						  <h4 class="panel-title">
							{{$number+1}}. {{ $nasional->name }} <span class="fa fa-angle-down"></span>
						  </h4>
						</div>
						</a>
						<div id="collapse{{$number+1}}" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="heading{{$number+1}}">
						  <div class="panel-body">
							{{ $nasional->address }} 
						  </div>
						</div>
					  </div>
					@else
						<div class="panel panel-default">
						<a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse{{$number+1}}" aria-expanded="true" aria-controls="collapseOne">
						<div class="panel-heading" role="tab" id="heading{{$number+1}}" style="background: #3b5999;color: #fff;">
						  <h4 class="panel-title">
							{{$number+1}}. {{ $nasional->name }} <span class="fa fa-angle-down"></span>
						  </h4>
						</div>
						</a>
						<div id="collapse{{$number+1}}" class="panel-collapse collapse" role="tabpanel" aria-labelledby="heading{{$number+1}}">
						  <div class="panel-body">
							{{ $nasional->address }}
						  </div>
						</div>
					  </div>
					@endif		
				  @endforeach
				</div>
				
				<!-- end start content !-->
			</div>
		</div>
	</div>
</div>