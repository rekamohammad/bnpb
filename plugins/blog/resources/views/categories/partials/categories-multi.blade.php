<div class="widget meta-boxes">
    <div class="widget-title">
        <h4><span class="required">{{ trans('blog::posts.form.categories') }}</span></h4>
    </div>
    <div class="widget-body">
        <div class="form-group @if ($errors->has('categories')) has-error @endif">
            <div class="multi-choices-widget list-item-checkbox">
                @if(isset($categories) && (is_array($categories) || $categories instanceof \Illuminate\Support\Collection))
                    @include('blog::categories.partials._categories-checkbox-option-line', [
                        'categories' => $categories,
                        'value' => (isset($value) ? $value : []),
                        'currentId' => null,
                        'name' => (isset($name) ? $name : '')
                    ])
                @endif
            </div>
        </div>
    </div>
</div>