<div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
    <a class="dashboard-stat dashboard-stat-v2 purple" href="{{ route('categories.list') }}">
        <div class="visual">
            <i class="fa fa-list-alt"></i>
        </div>
        <div class="details">
            <div class="number">
                <span data-counter="counterup" data-value="{{ $categories }}"></span></div>
            <div class="desc"> {{ trans('blog::categories.models') }} </div>
        </div>
    </a>
</div>