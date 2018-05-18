@component('mail::layout')
    {{-- Header --}}
    @slot('header')
        @component('mail::header', ['url' => config('app.url')])
            {{ config('app.name') }}
        @endcomponent
    @endslot

    {{-- Body --}}

# Parabéns

Um novo investimento foi inserido!

@component('mail::table')
|        Tipo       |        Ticket       |     Quantidade     |        Preço          |           Data                                  |
|:-----------------:|:-------------------:|:------------------:|:---------------------:|:-----------------------------------------------:|
| {{$invest->type}} | {{$invest->symbol}} | {{$invest->quant}} | R$ {{$invest->price}} | {{$invest->date_invest->format('d/m/Y H:i:s')}} |
@endcomponent

@component('mail::button', ['url' => 'https://cacoma.tk/invests'])
Investimentos
@endcomponent

Obrigado,<br>
{{ config('app.name') }}


    {{-- Subcopy --}}
    @slot('subcopy')
        @component('mail::subcopy')
@lang(
    "Se você está tendo problemas para clicar no botão \":actionText\" , copie e cole o texto abaixo\n".
    'no seu navegador: [:actionURL](:actionURL)',
    [
        'actionText' => 'investimentos',
        'actionURL' => 'https://cacoma.tk/invests'
    ]
)
        @endcomponent
    @endslot


    {{-- Footer --}}
    @slot('footer')
        @component('mail::footer')
            <!-- footer here -->
        @endcomponent
    @endslot
@endcomponent