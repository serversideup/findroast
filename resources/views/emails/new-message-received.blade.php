<x-mail::message>
# New Contact Form Message

You've received a new message from your contact form.

## Contact Information

**Name:** {{ $message->name }}
**Email:** {{ $message->email }}
**Subject:** {{ ucfirst(str_replace('_', ' ', $message->subject)) }}

@if($message->company_name)
**Company/Roaster:** {{ $message->company_name }}
@endif

@if($message->company_url)
**Website:** {{ $message->company_url }}
@endif

## Message

{{ $message->message }}

<x-mail::button :url="config('app.url') . '/platform/messages'">
View in Admin Panel
</x-mail::button>

---

**Submitted:** {{ $message->created_at->format('F j, Y g:i A') }}
**IP Address:** {{ $message->ip_address }}

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
