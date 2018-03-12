<section data-background="{{ Theme::asset()->url('images/page-intro-02.jpg') }}" class="section page-intro pt-100 pb-100 bg-cover">
    <div style="opacity: 0.7" class="bg-overlay"></div>
    
</section>
<section class="section pt-50 pb-100">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="page-content">
						<h3 class="block-title"><span>Gallery</span></h3>
                        <div class="post__content">
						 @if (isset($galleries) && !$galleries->isEmpty())
							@foreach ($galleries as $number=>$gallery)	 
							 <ul id="lightgallery-{{ $number+1 }}" class="thumbnail">
								<li data-title="#" data-responsive-src="{{ get_object_image($gallery->image) }}" data-src="{{ get_object_image($gallery->image) }}"> 
								<a href="#"> <img src="{{ get_object_image($gallery->image) }}"> </a> 
								<p>{{ $gallery->name }}</p>
								</li>
									@foreach (gallery_meta_data($gallery->id, 'gallery') as $image)	
									<li data-title="#" data-responsive-src="{{ url(array_get($image, 'img')) }}" data-src="{{ url(array_get($image, 'img')) }}"> 
									<a href="#"> <img src="{{ url(array_get($image, 'img')) }}" alt=""> </a> 
									<p>{{ array_get($image, 'description') }}</p>
									</li>
									@endforeach
							 </ul>	
							@endforeach
                         @endif
                        </div>
                    
                </div>
            </div>
        </div>
    </div>
</section>
