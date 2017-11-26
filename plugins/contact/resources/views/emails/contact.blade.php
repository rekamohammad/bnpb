
<h3>{{ trans('contact::contact.contact_sent_from') }} <strong>{{ $name }}</strong></h3>
<p>{{ trans('contact::contact.email.header') }}: {{ $email }}<p>
<p>{{ trans('contact::contact.address') }}: {{ $address }}<p>
<p>{{ trans('contact::contact.phone') }}: {{ $phone }}<p>
<p>{{ trans('contact::contact.message') }}: {{ $content }}<p><br>
<p><strong>{{ trans('contact::contact.sent_from') }} <a href="{{ route('public.index') }}">{{ setting('site_title') }}</a>.</strong></p>
