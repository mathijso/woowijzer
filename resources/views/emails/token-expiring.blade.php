<x-mail::message>
# Reminder: Upload link verloopt binnenkort

Beste {{ $internalRequest->colleague_name ?? 'collega' }},

Dit is een herinnering dat de upload link voor het volgende WOO-verzoek over **{{ $daysLeft }} {{ $daysLeft === 1 ? 'dag' : 'dagen' }}** verloopt.

**{{ $wooRequest->title }}**

## Deadline

De upload link is geldig tot: **{{ $expiresAt->format('d-m-Y H:i') }}**

@if(!$internalRequest->hasSubmissions())
## Let op

U heeft nog geen documenten geüpload. Als u documenten wilt aanleveren, gebruik dan de onderstaande link vóór de vervaldatum.
@else
## Aanvullende documenten

U heeft al documenten geüpload. Als u nog aanvullende documenten wilt toevoegen, gebruik dan de onderstaande link.
@endif

<x-mail::button :url="$uploadUrl">
Documenten uploaden
</x-mail::button>

---

**Vragen?**

Neem contact op met {{ $caseManager->name }} via {{ $caseManager->email }}

Met vriendelijke groet,

{{ config('app.name') }}
</x-mail::message>
