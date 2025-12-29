<x-mail::message>
# Welcome to Kibo!

Hello {{ $user->name }},

Your account has been successfully created for **{{ $entity->name }}** ({{ $entity->type_label }}).

## Your Login Credentials

**Email:** {{ $user->email }}  
**Password:** {{ $password }}

<x-mail::button :url="$loginUrl">
Login to Your Account
</x-mail::button>

<x-mail::panel>
**Important:** For security reasons, please change your password after your first login. You can do this in your account settings.
</x-mail::panel>

If you have any questions or need assistance, please don't hesitate to contact our support team.

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
