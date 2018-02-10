@component('mail::message')

Order has been shipped.


Thank you {{ $content['name'] }} for purchasing our products 

Qty: {{ $content['quantity'] }}  Total: £{{ $content['amount'] }}

Receipt from stripe will be sent to you later.


@component('mail::button', ['url' => 'http://localhost:8080/#/'])
Button Text
@endcomponent

<br>
{{ config('app.name') }}
@endcomponent
