<div class="col-md-6 col-sm-6">
    <h4><a href="#">{{ __($config['name']) }}</a></h4>
    <ul role="menu" class="footer-menu">
        @foreach (get_recent_posts($config['number_display']) as $post)
            <li class="menu-item">
                <a href="{{ route('public.single.detail', $post->slug) }}"
                   title="{{ $post->name }}">
                    {{ $post->name }}
                </a>
            </li>
        @endforeach
    </ul>
</div>