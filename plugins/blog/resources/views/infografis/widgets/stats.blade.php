<div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
    <a class="dashboard-stat dashboard-stat-v2 red" href="{{ route('posts.list') }}">
        <div class="visual">
            <i class="fa fa-newspaper-o"></i>
        </div>
        <div class="details">
            <div class="number">
                <span data-counter="counterup" data-value="{{ $posts }}">0</span></div>
            <div class="desc"> {{ trans('blog::posts.posts') }} </div>
        </div>
    </a>
</div>