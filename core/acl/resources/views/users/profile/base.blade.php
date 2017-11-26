@extends('bases::layouts.master')

@section('content')
    <div class="user-profile">
        <div class="col-md-3 col-sm-5 crop-avatar">
            <!-- Profile links -->
            <div class="block">
                <div class="block mt-element-card mt-card-round mt-element-overlay">
                    <div class="thumbnail">
                        <div class="thumb">
                            <div class="profile-userpic mt-card-item">
                                <div class="avatar-view mt-card-avatar mt-overlay-1">
                                    <img src="{{ url($user->getProfileImage()) }}" class="img-responsive" alt="avatar" h>
                                    <div class="mt-overlay">
                                        <ul class="mt-info">
                                            <li>
                                                <a class="btn default btn-outline" href="javascript:;">
                                                    <i class="icon-note"></i>
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="mt-card-content">
                                    <h3 class="mt-card-name">{{ $user->getFullName() }}</h3>
                                    <p class="mt-card-desc font-grey-mint">{{ $user->job_position }}</p>
                                    <div class="mt-card-social">
                                        <ul>
                                            <li>
                                                <a href="{{ $user->facebook }}">
                                                    <i class="icon-social-facebook"></i>
                                                </a>
                                            </li>
                                            <li>
                                                <a href="skype:{{ $user->skype }}?chat">
                                                    <i class="icon-social-skype"></i>
                                                </a>
                                            </li>
                                            <li>
                                                <a href="{{ $user->github }}">
                                                    <i class="icon-social-github"></i>
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /profile links -->

            <div class="modal fade" id="avatar-modal" tabindex="-1" role="dialog" aria-labelledby="avatar-modal-label"
                 aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <form class="avatar-form" method="post" action="{{ route('users.profile.image') }}" enctype="multipart/form-data">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                <h4 class="modal-title" id="avatar-modal-label"><i class="til_img"></i><strong>{{ trans('acl::users.change_profile_image') }}</strong></h4>
                            </div>
                            <div class="modal-body">

                                <div class="avatar-body">

                                    <!-- Upload image and data -->
                                    <div class="avatar-upload">
                                        <input class="avatar-src" name="avatar_src" type="hidden">
                                        <input class="avatar-data" name="avatar_data" type="hidden">
                                        <input type="hidden" name="user_id" value="{{ $user->id }}"/>
                                        {!! Form::token() !!}
                                        <label for="avatarInput">{{ trans('acl::users.new_image') }}</label>
                                        <input class="avatar-input" id="avatarInput" name="avatar_file" type="file">
                                    </div>

                                    <div class="loading" tabindex="-1" role="img" aria-label="{{ trans('acl::users.loading') }}"></div>

                                    <!-- Crop and preview -->
                                    <div class="row">
                                        <div class="col-md-9">
                                            <div class="avatar-wrapper"></div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="avatar-preview preview-lg"></div>
                                            <div class="avatar-preview preview-md"></div>
                                            <div class="avatar-preview preview-sm"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button class="btn btn-default" type="button" data-dismiss="modal">{{ trans('acl::users.close') }}</button>
                                <button class="btn btn-primary avatar-save" type="submit">{{ trans('acl::users.save') }}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div><!-- /.modal -->

        </div>
        <div class="col-md-9 col-sm-7">
            <div class="profile-content">
                <div class="row">
                    <div class="col-md-12">
                        <div class="tabbable-custom tabable-tabdrop">
                            <ul class="nav nav-tabs">
                                <li class="active">
                                    <a href="#tab_1_1" data-toggle="tab" aria-expanded="true">Personal Info</a>
                                </li>
                                <li>
                                    <a href="#tab_1_3" data-toggle="tab" aria-expanded="false">Change Password</a>
                                </li>
                            </ul>
                            <div class="tab-content">
                            <!-- PERSONAL INFO TAB -->
                            <div class="tab-pane active" id="tab_1_1">
                                {!! Form::open(['route' => ['users.update-profile', $user->id]]) !!}
                                <div class="row">
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <div class="form-group @if ($errors->has('first_name')) has-error @endif">
                                            <label for="first_name" class="control-label">First Name</label>
                                            {!! Form::text('first_name', $user->first_name, ['class' => 'form-control', 'id' => 'first_name', 'placeholder' => 'John', 'data-counter' => 60]) !!}
                                        </div>
                                        <div class="form-group @if ($errors->has('last_name')) has-error @endif">
                                            <label for="last_name" class="control-label">Last Name</label>
                                            {!! Form::text('last_name', $user->last_name, ['class' => 'form-control', 'id' => 'last_name', 'placeholder' => 'Smith', 'data-counter' => 60]) !!}
                                        </div>
                                        <div class="form-group @if ($errors->has('username')) has-error @endif">
                                            <label for="username" class="control-label">Username</label>
                                            {!! Form::text('username', $user->username, ['class' => 'form-control', 'id' => 'username', 'placeholder' => 'Username', 'data-counter' => 30]) !!}
                                        </div>
                                        <div class="form-group @if ($errors->has('email')) has-error @endif">
                                            <label for="email" class="control-label">Email</label>
                                            {!! Form::text('email', $user->email, ['class' => 'form-control', 'id' => 'email', 'placeholder' => 'contact@example.com', 'data-counter' => 60]) !!}
                                        </div>
                                        <div class="form-group @if ($errors->has('secondary_email')) has-error @endif">
                                            <label for="secondary_email" class="control-label">Secondary Email</label>
                                            {!! Form::text('secondary_email', $user->secondary_email, ['class' => 'form-control', 'id' => 'secondary_email', 'placeholder' => 'contact@example.com', 'data-counter' => 60]) !!}
                                        </div>
                                        <div class="form-group @if ($errors->has('adress')) has-error @endif">
                                            <label for="address" class="control-label">Address</label>
                                            {!! Form::text('address', $user->address, ['class' => 'form-control', 'id' => 'address', 'placeholder' => 'Address', 'data-counter' => 255]) !!}
                                        </div>
                                        <div
                                            class="form-group @if ($errors->has('secondary_address')) has-error @endif">
                                            <label for="secondary_address" class="control-label">Secondary
                                                Address</label>
                                            {!! Form::text('secondary_address', $user->secondary_address, ['class' => 'form-control', 'id' => 'secondary_address', 'placeholder' => 'Address', 'data-counter' => 255]) !!}
                                        </div>
                                        <div class="form-group @if ($errors->has('dob')) has-error @endif">
                                            <label for="dob" class="control-label">Day of birth</label>
                                            {!! Form::text('dob', $user->dob, ['class' => 'form-control datepicker', 'id' => 'dob', 'placeholder' => '1993-03-23', 'data-date-format' => config('cms.date_format.js.date'), 'data-counter' => 30]) !!}
                                        </div>
                                        <div class="form-group @if ($errors->has('job_position')) has-error @endif">
                                            <label for="job_position" class="control-label">Job Position</label>
                                            {!! Form::text('job_position', $user->job_position, ['class' => 'form-control', 'id' => 'job_position', 'placeholder' => 'PHP Developer', 'data-counter' => 255]) !!}
                                        </div>
                                        <div class="form-group @if ($errors->has('phone')) has-error @endif">
                                            <label for="phone" class="control-label">Mobile Number</label>
                                            {!! Form::text('phone', $user->phone, ['class' => 'form-control', 'id' => 'phone', 'placeholder' => '+84 0123 245', 'data-counter' => 15]) !!}
                                        </div>
                                        <div class="form-group @if ($errors->has('secondary_phone')) has-error @endif">
                                            <label for="secondary_phone" class="control-label">Secondary Phone</label>
                                            {!! Form::text('secondary_phone', $user->secondary_phone, ['class' => 'form-control', 'id' => 'secondary_phone', 'placeholder' => '+84 0123 245', 'data-counter' => 15]) !!}
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <div class="form-group @if ($errors->has('gender')) has-error @endif">
                                            <label for="gender" class="control-label">Gender</label>
                                            <p>
                                                {!! Form::radio('gender', 1, $user->gender == 1 ? true : false, ['id' => 'gender_male']) !!}
                                                <label for="gender_male">Male</label>
                                                {!! Form::radio('gender', 0, $user->gender == 0 ? true : false, ['id' => 'gender_female']) !!}
                                                <label for="gender_female">Female</label>
                                            </p>
                                        </div>
                                        <div class="form-group @if ($errors->has('interest')) has-error @endif">
                                            <label for="interest" class="control-label">Interests</label>
                                            {!! Form::text('interest', $user->interest, ['class' => 'form-control', 'id' => 'interest', 'placeholder' => 'Design, Web etc.', 'data-counter' => 255]) !!}
                                        </div>
                                        <div class="form-group @if ($errors->has('about')) has-error @endif">
                                            <label for="about" class="control-label">About</label>
                                            {!! Form::textarea('about', $user->about, ['class' => 'form-control', 'rows' => 3, 'id' => 'about', 'placeholder' => 'We are Botble Team!!!', 'data-counter' => 400]) !!}
                                        </div>
                                        <div class="form-group @if ($errors->has('skype')) has-error @endif">
                                            <label for="skype" class="control-label">Skype</label>
                                            {!! Form::text('skype', $user->skype, ['class' => 'form-control', 'id' => 'skype', 'placeholder' => 'minhsang2603', 'data-counter' => 60]) !!}
                                        </div>
                                        <div class="form-group @if ($errors->has('facebook')) has-error @endif">
                                            <label for="facebook" class="control-label">Facebook</label>
                                            {!! Form::text('facebook', $user->facebook, ['class' => 'form-control', 'id' => 'facebook', 'placeholder' => 'https://facebook.com', 'data-counter' => 255]) !!}
                                        </div>
                                        <div class="form-group @if ($errors->has('twitter')) has-error @endif">
                                            <label for="twitter" class="control-label">Twitter</label>
                                            {!! Form::text('twitter', $user->twitter, ['class' => 'form-control', 'id' => 'twitter', 'placeholder' => 'http://www.twitter.com', 'data-counter' => 255]) !!}
                                        </div>
                                        <div class="form-group @if ($errors->has('google_plus')) has-error @endif">
                                            <label for="google_plus" class="control-label">Google Plus</label>
                                            {!! Form::text('google_plus', $user->google_plus, ['class' => 'form-control', 'id' => 'google_plus', 'placeholder' => 'http://www.plus.google.com', 'data-counter' => 255]) !!}
                                        </div>
                                        <div class="form-group @if ($errors->has('youtube')) has-error @endif">
                                            <label for="youtube" class="control-label">Youtube</label>
                                            {!! Form::text('youtube', $user->youtube, ['class' => 'form-control', 'id' => 'youtube', 'placeholder' => 'http://www.youtube.com', 'data-counter' => 255]) !!}
                                        </div>
                                        <div class="form-group @if ($errors->has('github')) has-error @endif">
                                            <label for="github" class="control-label">Github</label>
                                            {!! Form::text('github', $user->github, ['class' => 'form-control', 'id' => 'github', 'placeholder' => 'http://www.github.com', 'data-counter' => 255]) !!}
                                        </div>
                                        <div class="form-group @if ($errors->has('website')) has-error @endif">
                                            <label for="website" class="control-label">Website</label>
                                            {!! Form::text('website', $user->website, ['class' => 'form-control', 'id' => 'website', 'placeholder' => 'http://www.example.com', 'data-counter' => 255]) !!}
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="form-actions">
                                        <div class="btn-set text-center">
                                            <button type="submit" name="submit" value="submit" class="btn btn-success">
                                                <i class="fa fa-check-circle"></i> {{ trans('acl::users.update') }}
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                {!! Form::close() !!}
                            </div>
                            <!-- END PERSONAL INFO TAB -->
                            <!-- CHANGE PASSWORD TAB -->
                            <div class="tab-pane" id="tab_1_3">
                                {!! Form::open(['route' => ['users.change-password', $user->id]]) !!}
                                @if (!acl_get_current_user()->isSuperUser())
                                    <div class="form-group">
                                        <label class="control-label" for="old_password">Current Password</label>
                                        {!! Form::password('old_password', ['class' => 'form-control', 'id' => 'old_password', 'data-counter' => 60]) !!}
                                    </div>
                                @endif
                                <div class="row">
                                    <div class="col-sm-6 col-xs-12">
                                        <div class="form-group">
                                            <label class="control-label" for="password">New Password</label>
                                            {!! Form::password('password', ['class' => 'form-control', 'id' => 'password', 'data-counter' => 60]) !!}
                                            <div class="pwstrength_viewport_progress"></div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6 col-xs-12">
                                        <div class="form-group">
                                            <label class="control-label" for="password_confirmation">Re-type New
                                                Password</label>
                                            {!! Form::password('password_confirmation', ['class' => 'form-control', 'id' => 'password_confirmation', 'data-counter' => 60]) !!}
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="form-actions">
                                        <div class="btn-set pull-right">
                                            <button type="submit" name="submit" value="submit" class="btn btn-success">
                                                <i class="fa fa-check-circle"></i> {{ trans('acl::users.update') }}
                                            </button>
                                        </div>
                                        <div class="clearfix"></div>
                                    </div>
                                </div>
                                {!! Form::close() !!}
                            </div>
                            <!-- END CHANGE PASSWORD TAB -->
                        </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- END PROFILE CONTENT -->
        </div>
        <div class="clearfix"></div>
    </div>
@stop
