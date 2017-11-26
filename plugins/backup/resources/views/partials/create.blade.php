<div class="form-group">
    <label for="name" class="control-label required">{{ trans('bases::forms.name') }}</label>
    {!! Form::text('name', old('name'), ['class' => 'form-control', 'id' => 'name', 'placeholder' => trans('bases::forms.name'), 'data-counter' => 120]) !!}
</div>
<div class="form-group">
    <label for="description" class="control-label required">{{ trans('bases::forms.description') }}</label>
    {!! Form::textarea('description', old('description'), ['class' => 'form-control', 'rows' => 4, 'id' => 'description', 'placeholder' => trans('bases::forms.description'), 'data-counter' => 400]) !!}
</div>