@extends(config('media.layouts.master'))

@section(config('media.layouts.header'))
    {!! RvMedia::renderHeader() !!}
@endsection

@section(config('media.layouts.main'))
    {!! RvMedia::renderContent() !!}
@endsection

@section(config('media.layouts.footer'))
    {!! RvMedia::renderFooter() !!}
@endsection