@component('mail::message')
# Assalamualaykum Warahmatullahi wabarokatuh

Kamu telah melakukan checkout, jangan lupa bayar, jangan tunda- tunda.

@component('mail::button', ['url' => 'products'])
Kembali Ke Halaman Utama
@endcomponent

Terima Kasih,<br>
{{ config('app.name') }}
@endcomponent