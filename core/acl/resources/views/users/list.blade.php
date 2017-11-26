@extends('bases::layouts.master')
@section('content')
    @include('bases::elements.tables.datatables', ['title' => trans('acl::users.list'), 'dataTable' => $dataTable])

    <!-- Form modal -->
    <div id="invite_modal" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title"><i class="til_img"></i><strong>{{ trans('acl::users.invite_new_member') }}</strong></h4>
                </div>

                <!-- Form inside modal -->
                {!! Form::open(['route' => 'invite.user', 'method' => 'post', 'role'=>'form']) !!}
                    <div class="modal-body with-padding">
                        <p>{{ trans('acl::users.invite_description') }}</p>
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-6">
                                    <label>{{ trans('acl::users.email') }}:</label>
                                    {!! Form::text('email', null, ['class' => 'form-control']) !!}
                                    {!! Form::error('email', $errors) !!}
                                </div>
                                <div class="col-md-6">
                                    <label>{{ trans('acl::users.role') }}:</label>
                                    {!! Form::select('role', $roles, null, ['class' => 'form-control roles-list']) !!}
                                    {!! Form::error('role', $errors) !!}
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-6">
                                    <label>{{ trans('acl::users.first_name') }}:</label>
                                    {!! Form::text('first_name', null, ['class' => 'form-control']) !!}
                                    {!! Form::error('first_name', $errors) !!}
                                </div>
                                <div class="col-md-6">
                                    <label>{{ trans('acl::users.last_name') }}:</label>
                                    {!! Form::text('last_name', null, ['class' => 'form-control']) !!}
                                    {!! Form::error('last_name', $errors) !!}
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <div class="col-sm-12">
                                    <label>{{ trans('acl::users.message') }}:</label>
                                    {!! Form::textarea('message', null, ['class' => 'form-control']) !!}
                                    {!! Form::error('message', $errors) !!}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary"
                                data-dismiss="modal">{{ trans('acl::users.cancel_btn') }}</button>
                        <button type="submit" class="btn btn-success">{{ trans('acl::users.invite_btn') }}</button>
                    </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
    <!-- /form modal -->
@stop
