@extends('layout.master')
@section('breadcrumb', 'Applications')
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Create Application</h4>
                    <form class="form-sample" method="POST" action="{{url('/apps')}}">@csrf
                        {{-- <p class="card-description"> Personal info </p> --}}
                        @if ($errors->any())
                            @foreach ($errors->all() as $error)
                            <li class="text-danger">{{ $error }}</li>
                            @endforeach
                        @endif
                        @if(isset($app->id))
                            <input type="hidden" name="app_id" value="{{$app->id}}">
                        @endif
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group row">
                                    <label class="col-sm-3 col-form-label">name</label>
                                    <div class="col-sm-9">
                                        <input type="text" name="name" class="form-control" @if(isset($app->name)) value="{{$app->name}}" @endif/>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group row">
                                    <label class="col-sm-3 col-form-label">Package</label>
                                    <div class="col-sm-9">
                                        <input type="text" name="package_name" class="form-control" @if(isset($app->package_name)) value="{{$app->package_name}}" @endif />
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div>
                            <button type="submit" class="btn btn-primary">Submit</button>
                            <button type="button" class="btn btn-light" onclick="history.back()">Back</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    </div>
    </div>
    </div>
@endsection
