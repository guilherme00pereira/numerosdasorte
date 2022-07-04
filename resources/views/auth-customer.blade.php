@extends('platform::app')


@section('body-right')


    <div class="form-signin container p-0 px-sm-5 py-5 my-sm-5 authwrapper">
        <div class="authbrand">
            @includeFirst([config('platform.template.header'), 'platform::header'])
            <div>
                <a href="{{ route('platform.main') }}" style="color: white;">ADMIN</a>
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="p-4 p-sm-5">
                    @yield('content')
                </div>
            </div>
        </div>
    </div>

    <div class="mt-4 text-center">
        @includeFirst([config('platform.template.footer'), 'platform::footer'])
    </div>

@endsection
