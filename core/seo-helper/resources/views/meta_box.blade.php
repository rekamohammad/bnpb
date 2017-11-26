<div class="form-group">
    <label for="seo_title" class="control-label">{{ __('SEO title') }}</label>
    {!! Form::text('seo_meta[seo_title]', !empty($meta['seo_title']) ? $meta['seo_title'] : old('seo_meta[seo_title]'), ['class' => 'form-control', 'id' => 'seo_title', 'placeholder' => __('SEO title'), 'data-counter' => 120]) !!}
    {!! Form::helper(__('Title tags are displayed on search engine results pages (SERPs) as the clickable headline for a given result, and are important for usability, SEO, and social sharing. The title tag of a web page is meant to be an accurate and concise description of a page\'s content.')) !!}
</div>
<div class="form-group">
    <label for="seo_keyword" class="control-label">{{ __('SEO keywords') }}</label>
    {!! Form::text('seo_meta[seo_keyword]', !empty($meta['seo_keyword']) ? $meta['seo_keyword'] : old('seo_meta[seo_keyword]'), ['class' => 'form-control', 'id' => 'seo_keyword', 'placeholder' => __('SEO keywords'), 'data-counter' => 120]) !!}
    {!! Form::helper(__('Your SEO keywords are the key words and phrases in your web content that make it possible for people to find your site via search engines. A website that is well optimized for search engines "speaks the same language" as its potential visitor base with keywords for SEO that help connect searchers to your site.')) !!}
</div>
<div class="form-group">
    <label for="seo_description" class="control-label">{{ __('SEO description') }}</label>
    {!! Form::text('seo_meta[seo_description]', !empty($meta['seo_description']) ? $meta['seo_description'] : old('seo_meta[seo_description]'), ['class' => 'form-control', 'id' => 'seo_description', 'placeholder' => __('SEO description'), 'data-counter' => 120]) !!}
    {!! Form::helper(__('The meta description is a ~160 character snippet, a tag in HTML, that summarizes a page\'s content. Search engines show the meta description in search results mostly when the searched for phrase is contained in the description. Optimizing the meta description is a very important aspect of on-page SEO.')) !!}
</div>