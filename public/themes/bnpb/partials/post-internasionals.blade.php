<div class="col-md-12">
	<h3 class="block-title"><span><a href="{{ url('/nasionals') }}" title="Publikasi BNPB">Link Internasional</a></span></h3>
</div>
<div class="col-md-12">
	<div class="page-content">
		<div class="row">
			<div class="col-md-3">{!! Theme::partial('links-menu') !!}</div>
			<div class="col-md-9">
				<!-- start content !-->
				
				
				<div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
				
				@if(count(get_all_internasional())>0)
					@foreach(get_all_internasional() as $number=>$international)
						<ol>
							<li><a href="{{ $international->url }}">{{ $international->name }}</a></li>		
						</ol>
					@endforeach
						
				@else
						<div style="text-align:center; font-weight:800">Empty data international</div>	
				@endif
				</div>
				
				<!-- end start content !-->
			</div>
		</div>
	</div>
</div>