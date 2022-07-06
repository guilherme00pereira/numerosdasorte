@extends('platform::app')


@section('body-right')


    <div class="form-signin container p-0 px-sm-5 py-5 my-sm-5 authwrapper">
        <div class="authbrand">
            @includeFirst([config('platform.template.header'), 'platform::header'])
            <div>
                <span>ÁREA DO CLIENTE</span>
                <h4>NÚMERO DA SORTE</h4>
                <small>Acesse e confira todos seus números</small>
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="p-4 p-sm-5">
                <form class="m-t-md"
                    role="form"
                    method="POST"
                    data-controller="form"
                    data-action="form#submit"
                    data-form-button-animate="#button-login"
                    data-form-button-text="{{ __('Loading...') }}"
                    action="{{ route('index') }}">
                    @csrf
                    <div class="mb-3">
                        {!!  \Orchid\Screen\Fields\Input::make('cpf')
                            ->type('text')
                            ->required()
                            ->tabindex(1)
                            ->autofocus()
                            ->placeholder("Digite seu CPF")
                        !!}
                    </div>

                    <div class="row align-items-center">
                        <div class="col-md-6 col-xs-12">
                            <button id="button-login" type="submit" class="btn btn-default btn-block" tabindex="3">
                                <x-orchid-icon path="login" class="small me-2"/>
                                {{__('Login')}}
                            </button>
                        </div>
                    </div>

                </form>
                </div>
            </div>
        </div>
    </div>

    @include('platform::partials.alert')

    <div>
        <a href="{{ route('platform.main') }}" style="color: white;">ADMIN</a>
    </div>

    <div class="mt-4 text-center">
        @includeFirst([config('platform.template.footer'), 'platform::footer'])
    </div>

@endsection
