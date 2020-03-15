@extends('backend.layouts.app')

@section('title', app_name() . ' | ' . __('strings.backend.dashboard.title'))

@section('content')
    <div class="row">
        <div class="col">
            <div class="card">
                <div class="card-header">
                    <strong>@lang('strings.backend.dashboard.welcome') {{ $logged_in_user->name }}!</strong>
                </div><!--card-header-->
                <div class="card-body">
                    {!! __('strings.backend.welcome') !!}
                </div><!--card-body-->
            </div><!--card-->
        </div><!--col-->
    </div><!--row-->
    <div class="row">
        <div class="col">
            <div class="card">
                <div class="card-header">
                    <strong>Upload Enrolment Data In Excell</strong>
                </div><!--card-header-->
                <div class="card-body">
                    <form action="{{ route('import') }}" class="form-horizontal form-bordered" id="upload-contacts" method="post" enctype="multipart/form-data">
                        <input name="_token" type="hidden" value="{{ csrf_token() }}">
                        <div class="form-group">
                            <div class="col-xs-12 col-sm-9 col-md-6">
                                <input type="file" class="filestyle" id="filename" name="pupils" data-buttonText="Browse">
                            </div>
                        </div>
                        <div class="form-actions fluid">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="col-sm-12 col-md-12">
                                        <button class="btn btn-info" type="submit"><i class="fa fa-upload"></i> Upload Contacts File</button>
                                    </div>
                                </div>
                            </div>
                        </div>                    
                    </form>
                </div><!--card-body-->
            </div><!--card-->
        </div><!--col-->
    </div><!--row-->
@endsection
