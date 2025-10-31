<x-mail::message>
# Verzoek om documenten

Beste {{ $internalRequest->colleague_name ?? 'collega' }},

{{ $caseManager->name }} verzoekt u om documenten aan te leveren voor het volgende WOO-verzoek:

**{{ $wooRequest->title }}**

## Toelichting

{{ $internalRequest->description }}

## Documenten uploaden

U kunt de gevraagde documenten uploaden via onderstaande link. Deze link is geldig tot **{{ $internalRequest->token_expires_at->format('d-m-Y') }}**.

<x-mail::button :url="$uploadUrl">
Documenten uploaden
</x-mail::button>

---

**Let op:** U kunt meerdere keren documenten uploaden via dezelfde link tot de vervaldatum.

Met vriendelijke groet,

{{ $caseManager->name }}  
{{ config('app.name') }}

<small>Deze email is automatisch gegenereerd. Bij vragen kunt u contact opnemen met {{ $caseManager->name }} via {{ $caseManager->email }}</small>
</x-mail::message>
