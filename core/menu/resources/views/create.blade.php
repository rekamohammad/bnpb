@extends('bases::layouts.master')

@section('content')
    {!! Form::open(['class' => 'form-save-menu clearfix', 'novalidate' => true]) !!}
    @php do_action(BASE_ACTION_CREATE_CONTENT_NOTIFICATION, MENU_MODULE_SCREEN_NAME, request(), null) @endphp
    <div class="row">
        <div class="col-md-9">
            <div class="widget">
                <div class="widget-title">
                    <h4><i class="box_img_sale"></i><span>{{ trans('menu::menu.basic_info') }}</span></h4>
                </div>
                <div class="widget-body" style="min-height: 100px">
                    <div class="form-group @if ($errors->has('name')) has-error @endif">
                        <label for="name" class="control-label required">{{ trans('bases::forms.name') }}</label>
                        <input type="text" name="name" id="name" class="form-control" value="{{ old('name') }}" autocomplete="off">
                        {!! Form::error('name', $errors) !!}
                    </div>
                </div>
            </div>
            @php do_action(BASE_ACTION_META_BOXES, MENU_MODULE_SCREEN_NAME, 'advanced') @endphp
        </div>
        <div class="col-md-3 right-sidebar">
            @include('bases::elements.form-actions')
            @php do_action(BASE_ACTION_META_BOXES, MENU_MODULE_SCREEN_NAME, 'top') @endphp
        </div>
    </div>
    {!! Form::close() !!}
@endsection
