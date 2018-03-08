@component('mail::message')
# Hello!

Thanks for registering to our medical surgery. Your generated (and temporary) credentials are:

@component('mail::table')
| Username        | Password        |
| :-------------: |:---------------:|
| {{ $username }} | {{ $password }} |
@endcomponent

You may now login into the application using the following button.

@component('mail::button', ['url' => config('app.url')])
Login
@endcomponent

N.B: at your first login, you will be asked to change your password! Failing in do so will prevent you from using any functionality!

Thanks,<br>
{{ config('app.name') }}
@endcomponent
