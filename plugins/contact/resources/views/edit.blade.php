@extends('bases::layouts.master')
@section('content')
    {!! Form::open() !!}
    @php do_action(BASE_ACTION_EDIT_CONTENT_NOTIFICATION, CONTACT_MODULE_SCREEN_NAME, request(), $contact) @endphp
    <div class="row">
        <div class="col-md-9">
            <div class="widget meta-boxes" style="margin-top: 0;">
                <div class="widget-title">
                    <h4>
                        <span>{{ trans('contact::contact.contact_information') }}</span>
                    </h4>
                </div>
                <div class="widget-body">
                    <p>{{ trans('contact::contact.tables.fullname') }}: {{ $contact->name }}</p>
                    <p>{{ trans('contact::contact.tables.email') }}: {{ $contact->email }}</p>
                    <p>{{ trans('contact::contact.tables.phone') }}: {{ $contact->phone }}</p>
                    <p>{{ trans('contact::contact.tables.address') }}: {{ $contact->address }}</p>
                    <p>{{ trans('contact::contact.tables.content') }}: {{ $contact->content }}</p>
                </div>
            </div>
            @php do_action(BASE_ACTION_META_BOXES, CONTACT_MODULE_SCREEN_NAME, 'advanced', $contact) @endphp
        </div>
        <div class="col-md-3 right-sidebar">
            @include('bases::elements.form-actions')

            <div class="widget meta-boxes">
                <div class="widget-title">
                    <h4>
                        <span>{{ trans('contact::contact.form.status') }}</span>
                    </h4>
                </div>
                <div class="widget-body">
                    <input type="checkbox" class="styled" name="is_read" id="is_read" value="1"
                           @if ($contact->is_read == 1) checked="checked" @endif>
                    <label for="is_read">{{ trans('contact::contact.form.is_read') }}</label>
                    {!! Form::error('is_read', $errors) !!}
                </div>
            </div>
            @php do_action(BASE_ACTION_META_BOXES, CONTACT_MODULE_SCREEN_NAME, 'top', $contact) @endphp
        </div>
    </div>
    {!! Form::close() !!}
@stop
