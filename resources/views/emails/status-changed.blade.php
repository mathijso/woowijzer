<x-mail::message>
# Status wijziging WOO-verzoek

Beste {{ $wooRequest->user->name }},

De status van uw WOO-verzoek is gewijzigd.

**{{ $wooRequest->title }}**

## Nieuwe status

De status is gewijzigd van **{{ config('woo.woo_request_statuses')[$oldStatus] ?? $oldStatus }}** naar **{{ $statusLabel }}**.

@if($newStatus === 'completed')
## Verzoek afgerond

Uw WOO-verzoek is afgerond. U kunt alle documenten en antwoorden bekijken via de onderstaande link.
@elseif($newStatus === 'in_progress')
## In behandeling

Uw verzoek wordt momenteel behandeld. U ontvangt een update zodra er meer informatie beschikbaar is.
@elseif($newStatus === 'rejected')
## Afgewezen

Helaas kunnen we uw verzoek niet honoreren. Voor meer informatie kunt u contact met ons opnemen.
@endif

<x-mail::button :url="route('woo-requests.show', $wooRequest)">
Bekijk uw verzoek
</x-mail::button>

---

Met vriendelijke groet,

{{ config('app.name') }}
</x-mail::message>
