@extends('layouts.librenmsv1')

@section('title', __('Edit User'))

@section('content')
<div class="row">
    <form action="users/{{ $user->user_id }}" method="POST" role="form" class="form-horizontal col-sm-offset-3 col-sm-6">
        <legend>@lang('Edit User'): {{ $user->username }}</legend>
        {{ method_field('PUT') }}

        @include('user.form')

        @config('twofactor')
        <br/>
        <div class="panel panel-default col-sm-offset-3">
            <div class="panel-heading">
                <h3 class="panel-title">Two-Factor Authentication</h3>
            </div>
            <div class="panel-body">

            @if($twofactor_enabled)
                @if($twofactor_locked)
                    <div class="form-group" id="twofactor-unlock-form">
                        <button type="button" id="twofactor-unlock" class="btn btn-default col-sm-4 col-sm-offset-1">@lang('Unlock')</button>
                        <label for="twofactor-unlock" class="col-sm-7 control-label">@lang('User exceeded failures')</label>
                    </div>
                @endif
                <div class="form-group">
                    <button type="button" id="twofactor-disable" class="btn btn-danger col-sm-offset-1">@lang('Disable TwoFactor')</button>
                </div>
            @else
                <p>@lang('No TwoFactor key generated for this user, Nothing to do.')</p>
            @endif
            </div>
        </div>
        @endconfig

        <div class="form-group">
            <div class="col-sm-9 col-sm-offset-3">
                <button type="submit" class="btn btn-primary">@lang('Save')</button>
                <a type="button" class="btn btn-danger" href="{{ route('users.index') }}">@lang('Cancel')</a>
            </div>
        </div>
    </form>
</div>
@endsection

@section('javascript')
    <script type="application/javascript">
        $(document).ready(function () {
            $('#twofactor-unlock').click(function () {
                console.log('unlock');
                $.ajax({
                    type: 'POST',
                    url: '{{ route('2fa.unlock', ['user' => $user->user_id]) }}',
                    dataType: "json",
                    success: function(data){
                        if (data.status === 'ok') {
                            $('#twofactor-unlock-form').remove();
                            toastr.success('@lang('Unlocked Two Factor.')');
                        } else {
                            toastr.error('@lang('Failed to unlock Two Factor')<br />' + data.message);
                        }
                    },
                    error: function(){
                        toastr.error('@lang('Failed to unlock Two Factor')');
                    }
                });
            });

            $('#twofactor-disable').click(function () {
                $.ajax({
                    type: 'DELETE',
                    url: '{{ route('2fa.delete', ['user' => $user->user_id]) }}',
                    dataType: "json",
                    success: function(data){
                        if (data.status === 'ok') {
                            toastr.success('@lang('Removed Two Factor.')');
                        } else {
                            toastr.error('@lang('Failed to remove Two Factor')<br />' + data.message);
                        }
                    },
                    error: function(){
                        toastr.error('@lang('Failed to remove Two Factor')');
                    }
                });
            });
        });
    </script>
@endsection

