<x-mail::message>
# Documenten ontvangen

Beste case manager,

Er zijn nieuwe documenten geüpload voor het WOO-verzoek:

**{{ $wooRequest->title }}**

## Details

- **Ingediend door:** {{ $submission->getSubmitterName() }}
- **Aantal documenten:** {{ $documentsCount }}
- **Upload datum:** {{ $submission->created_at->format('d-m-Y H:i') }}

@if($submission->submission_notes)
## Notities

{{ $submission->submission_notes }}
@endif

## Acties

De documenten worden automatisch verwerkt en geanalyseerd. U kunt de resultaten bekijken in het case overzicht.

<x-mail::button :url="route('cases.show', $wooRequest)">
Bekijk case details
</x-mail::button>

---

Met vriendelijke groet,

{{ config('app.name') }}
</x-mail::message>
