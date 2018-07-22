@extends('bases::layouts.master')
@section('content') 
<style>
.js .inputfile {
    width: 0.1px;
    height: 0.1px;
    opacity: 0;
    overflow: hidden;
    position: absolute;
    z-index: -1;
}

.inputfile + label {
    max-width: 80%;
    font-size: 1.25rem;
    /* 20px */
    font-weight: 700;
    text-overflow: ellipsis;
    white-space: nowrap;
    cursor: pointer;
    display: inline-block;
    overflow: hidden;
    padding: 0.625rem 1.25rem;
    /* 10px 20px */
}

.no-js .inputfile + label {
    display: none;
}

.inputfile:focus + label,
.inputfile.has-focus + label {
    outline: 1px dotted #000;
    outline: -webkit-focus-ring-color auto 5px;
}

.inputfile + label * {
    /* pointer-events: none; */
    /* in case of FastClick lib use */
}

.inputfile + label svg {
    width: 1em;
    height: 1em;
    vertical-align: middle;
    fill: currentColor;
    margin-top: -0.25em;
    /* 4px */
    margin-right: 0.25em;
    /* 4px */
}
/* style 6 */

.inputfile-6 + label {
    color: #3B5999;
}

.inputfile-6 + label {
    border: 1px solid #3B5999;
    background-color: #f1e5e6;
    padding: 0;
}

.inputfile-6:focus + label,
.inputfile-6.has-focus + label,
.inputfile-6 + label:hover {
    border-color: #3B5999;
}

.inputfile-6 + label span,
.inputfile-6 + label strong {
    padding: 0.625rem 1.25rem;
    /* 10px 20px */
}

.inputfile-6 + label span {
    width: 300px;
    min-height: 2em;
    display: inline-block;
    text-overflow: ellipsis;
    white-space: nowrap;
    overflow: hidden;
    vertical-align: top;
}

.inputfile-6 + label strong {
    height: 100%;
    color: #f1e5e6;
    background-color: #3B5999;
    display: inline-block;
}

.inputfile-6:focus + label strong,
.inputfile-6.has-focus + label strong,
.inputfile-6 + label:hover strong {
    background-color: #3B5999;
}

@media screen and (max-width: 50em) {
	.inputfile-6 + label strong {
		display: block;
	}
}
</style>
    {!! Form::open(array('files' => true)) !!}
        @php do_action(BASE_ACTION_CREATE_CONTENT_NOTIFICATION, POST_MODULE_SCREEN_NAME, request(), null) @endphp
        <div class="row">
            <div class="col-md-9">
                <div class="tabbable-custom tabbable-tabdrop">
                    <ul class="nav nav-tabs">
                        <li class="active">
                            <a href="#tab_file" data-toggle="tab">UPLOAD BY FILE</a>
                        </li>
                        <li>
                            <a href="#tab_url" data-toggle="tab">UPLOAD BY URL</a>
                        </li>
                    </ul>
                    <div class="tab-content">
                        <div class="form-body">
                            <div class="form-group @if ($errors->has('title')) has-error @endif">
                                <label for="title" class="control-label required">Masukan Title...</label>
                                {!! Form::text('title', null, ['class' => 'form-control', 'id' => 'title', 'placeholder' => 'Masukan Title Banner ...']) !!}
                                {!! Form::error('title', $errors) !!}
                            </div>
                        </div>
                        <div class="tab-pane active" id="tab_file">
                            <div class="form-body">
                                <div class="form-group @if ($errors->has('upload')) has-error @endif">
                                    <label for="upload" class="control-label required">Masukan File image disini...</label>
                                    <div class="box" style="border:0;">
                                        <input type="file" name="upload" id="upload" class="inputfile inputfile-6" />
                                        <label for="upload"><span></span> <strong><svg xmlns="http://www.w3.org/2000/svg" width="20" height="17" viewBox="0 0 20 17"><path d="M10 0l-5.2 4.9h3.3v5.1h3.8v-5.1h3.3l-5.2-4.9zm9.3 11.5l-3.2-2.1h-2l3.4 2.6h-3.5c-.1 0-.2.1-.2.1l-.8 2.3h-6l-.8-2.2c-.1-.1-.1-.2-.2-.2h-3.6l3.4-2.6h-2l-3.2 2.1c-.4.3-.7 1-.6 1.5l.6 3.1c.1.5.7.9 1.2.9h16.3c.6 0 1.1-.4 1.3-.9l.6-3.1c.1-.5-.2-1.2-.7-1.5z"/></svg> Choose a file&hellip;</strong></label>
                                    </div>
                                </div>
                            </div>
                            <input type="hidden" name="type" id="type" value="file"/>
                        </div>
                        <div class="tab-pane" id="tab_url">
                            <div class="form-body">
                                <div class="form-group @if ($errors->has('link')) has-error @endif">
                                    <label for="link" class="control-label required">Masukan URL image disini...</label>
                                    {!! Form::text('link', null, ['class' => 'form-control', 'id' => 'link', 'placeholder' => 'Masukan URL image disini...']) !!}
                                    {!! Form::error('link', $errors) !!}
                                </div>
                            </div>
                            <input type="hidden" name="type" id="type" value="url"/>
                        </div>
                        <div class="form-body">
                            <div class="form-group @if ($errors->has('url')) has-error @endif">
                                <label for="url" class="control-label required">Masukan URL...</label>
                                {!! Form::text('url', null, ['class' => 'form-control', 'id' => 'url', 'placeholder' => 'Masukan URL ...']) !!}
                                {!! Form::error('url', $errors) !!}
                            </div>
                        </div>
                        <div class="form-body">
                            <div class="form-group @if ($errors->has('target')) has-error @endif">
                                <label for="target" class="control-label required">Pilih Link Target...</label>
                                <select class="form-control" id="target" name="target">
                                    <option value="_blank">_blank</option>
                                    <option value="_top">_top</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 right-sidebar">
                @include('bases::elements.form-actions')

                @include('bases::elements.forms.status')

                @php do_action(BASE_ACTION_META_BOXES, POST_MODULE_SCREEN_NAME, 'side') @endphp
            </div>
        </div>
        <script>
            jQuery('.multi-choices-widget .mt-checkbox input').prop('checked', true);  
        </script>
    {!! Form::close() !!}
    <script>
        /*
            By Osvaldas Valutis, www.osvaldas.info
            Available for use under the MIT License
        */

        'use strict';

        ;( function ( document, window, index )
        {
            var inputs = document.querySelectorAll( '.inputfile' );
            Array.prototype.forEach.call( inputs, function( input )
            {
                var label	 = input.nextElementSibling,
                    labelVal = label.innerHTML;

                input.addEventListener( 'change', function( e )
                {
                    var fileName = '';
                    if( this.files && this.files.length > 1 )
                        fileName = ( this.getAttribute( 'data-multiple-caption' ) || '' ).replace( '{count}', this.files.length );
                    else
                        fileName = e.target.value.split( '\\' ).pop();

                    if( fileName )
                        label.querySelector( 'span' ).innerHTML = fileName;
                    else
                        label.innerHTML = labelVal;
                });

                // Firefox bug fix
                input.addEventListener( 'focus', function(){ input.classList.add( 'has-focus' ); });
                input.addEventListener( 'blur', function(){ input.classList.remove( 'has-focus' ); });
            });
        }( document, window, 0 ));

        /*
            By Osvaldas Valutis, www.osvaldas.info
            Available for use under the MIT License
        */

        'use strict';

        ;( function( $, window, document, undefined )
        {
            $( '.inputfile' ).each( function()
            {
                var $input	 = $( this ),
                    $label	 = $input.next( 'label' ),
                    labelVal = $label.html();

                $input.on( 'change', function( e )
                {
                    var fileName = '';

                    if( this.files && this.files.length > 1 )
                        fileName = ( this.getAttribute( 'data-multiple-caption' ) || '' ).replace( '{count}', this.files.length );
                    else if( e.target.value )
                        fileName = e.target.value.split( '\\' ).pop();

                    if( fileName )
                        $label.find( 'span' ).html( fileName );
                    else
                        $label.html( labelVal );
                });

                // Firefox bug fix
                $input
                .on( 'focus', function(){ $input.addClass( 'has-focus' ); })
                .on( 'blur', function(){ $input.removeClass( 'has-focus' ); });
            });
        })( jQuery, window, document );
    </script>
@stop
