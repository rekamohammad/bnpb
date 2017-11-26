@if ($header)
    <h2 class="group-title text-uppercase">{{ $header }}</h2>
@endif
{!! Form::open(['route' => 'public.send.contact', 'method' => 'POST']) !!}
@if(session()->has('success_msg') || session()->has('error_msg') || isset($errors))
    @if (session()->has('success_msg'))
        <div class="alert alert-success">
            <p>{{ session('success_msg') }}</p>
        </div>
    @endif
    @if (session()->has('error_msg'))
        <div class="alert alert-danger">
            <p>{{ session('error_msg') }}</p>
        </div>
    @endif
    @if (isset($errors) && count($errors))
        <div class="alert alert-danger">
            @foreach ($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        </div>
    @endif
@endif

<div class="row">
    <div class="col-md-6 col-sm-12 col-xs-12">
        <div class="form-group">
            <label for="contact_name" class="control-label required">{{ trans('contact::contact.form_name') }}</label>
            <input type="text" class="form-control" name="name" value="{{ old('name') }}" id="contact_name"
                   placeholder="Name">
        </div>
    </div>
    <div class="col-md-6 col-sm-12 col-xs-12">
        <div class="form-group">
            <label for="contact_email" class="control-label required">{{ trans('contact::contact.form_email') }}</label>
            <input type="email" class="form-control" name="email" value="{{ old('email') }}" id="contact_email"
                   placeholder="Email...">
        </div>
    </div>
    <div class="col-md-6 col-sm-12 col-xs-12">
        <div class="form-group">
            <label for="contact_address" class="control-label">{{ trans('contact::contact.form_address') }}</label>
            <input type="text" class="form-control" name="address" value="{{ old('address') }}" id="contact_address"
                   placeholder="Address...">
        </div>
    </div>
    <div class="col-md-6 col-sm-12 col-xs-12">
        <div class="form-group">
            <label for="contact_phone" class="control-label">{{ trans('contact::contact.form_phone') }}</label>
            <input type="text" class="form-control" name="phone" value="{{ old('phone') }}" id="contact_phone"
                   placeholder="Phone...">
        </div>
    </div>
    <div class="col-md-12">
        <div class="form-group">
            <label for="contact_content" class="control-label required">{{ trans('contact::contact.form_message') }}</label>
            <textarea name="content" id="contact_content" class="form-control" rows="5" placeholder="{{ trans('contact::contact.form_message') }}">{{ old('content') }}</textarea>
        </div>
    </div>
    @if (setting('enable_captcha'))
        <div class="col-md-12">
            <div class="form-group">
                <label for="contact_robot" class="control-label required">{{ trans('contact::contact.confirm_not_robot') }}</label>
                {!! Captcha::display('captcha') !!}
                {!! Captcha::script() !!}
            </div>
        </div>
    @endif
    <div class="col-md-12">
        <div class="form-group"><p>{!! trans('contact::contact.required_field') !!}</p></div>
    </div>
</div>
<div class="form-group text-right">
    <button type="submit" class="btn btn-primary cyan text">{{ trans('contact::contact.send_btn') }}</button>
</div>
{!! Form::close() !!}
