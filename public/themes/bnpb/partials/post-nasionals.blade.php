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
				<ol>
				@foreach(get_all_nasional() as $number=>$nasional)
					<li><a href="{{ $nasional->url }}">{{ $nasional->name }}</a></li>		
				@endforeach
				</ol>	  
				</div>
				
				<!-- end start content !-->
			</div>
		</div>
	</div>
</div>