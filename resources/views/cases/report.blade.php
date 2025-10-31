<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Case Rapport - {{ $wooRequest->title }}</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }
        h1, h2, h3 {
            color: #1e293b;
        }
        .header {
            border-bottom: 2px solid #3b82f6;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        .status-badge {
            display: inline-block;
            padding: 6px 12px;
            border-radius: 6px;
            font-size: 14px;
            font-weight: 600;
            margin-top: 10px;
        }
        .info-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
            margin: 20px 0;
        }
        .info-box {
            background: #f8fafc;
            padding: 15px;
            border-radius: 8px;
            border-left: 4px solid #3b82f6;
        }
        .info-box strong {
            display: block;
            margin-bottom: 5px;
            color: #1e293b;
        }
        .progress-bar {
            background: #e2e8f0;
            height: 24px;
            border-radius: 12px;
            overflow: hidden;
            margin: 10px 0;
        }
        .progress-fill {
            background: #3b82f6;
            height: 100%;
            transition: width 0.3s;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 12px;
            font-weight: 600;
        }
        .question-item {
            background: #f8fafc;
            padding: 15px;
            margin: 10px 0;
            border-radius: 8px;
            border-left: 4px solid #3b82f6;
        }
        .question-number {
            display: inline-block;
            background: #3b82f6;
            color: white;
            width: 24px;
            height: 24px;
            border-radius: 50%;
            text-align: center;
            line-height: 24px;
            font-weight: 600;
            margin-right: 10px;
        }
        .document-list {
            list-style: none;
            padding: 0;
            margin: 10px 0;
        }
        .document-list li {
            padding: 8px;
            background: white;
            margin: 5px 0;
            border-radius: 4px;
            border: 1px solid #e2e8f0;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        table th, table td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #e2e8f0;
        }
        table th {
            background: #f1f5f9;
            font-weight: 600;
            color: #1e293b;
        }
        .footer {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px solid #e2e8f0;
            text-align: center;
            color: #64748b;
            font-size: 12px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Case Rapport</h1>
        <h2>{{ $wooRequest->title }}</h2>
        <p><strong>Gegenereerd op:</strong> {{ now()->format('d F Y H:i') }}</p>
        <span class="status-badge" style="background: #dbeafe; color: #1e40af;">
            {{ config('woo.woo_request_statuses')[$wooRequest->status] ?? $wooRequest->status }}
        </span>
    </div>

    <div class="info-grid">
        <div class="info-box">
            <strong>Aanvrager</strong>
            {{ $wooRequest->user->name }}<br>
            <small>{{ $wooRequest->user->email }}</small>
        </div>
        <div class="info-box">
            <strong>Case Manager</strong>
            {{ $wooRequest->caseManager?->name ?? 'Niet toegewezen' }}
        </div>
        <div class="info-box">
            <strong>Ingediend op</strong>
            {{ $wooRequest->submitted_at?->format('d F Y') ?? $wooRequest->created_at->format('d F Y') }}
        </div>
        <div class="info-box">
            <strong>Voortgang</strong>
            <div class="progress-bar">
                <div class="progress-fill" style="width: {{ $progressPercentage }}%">
                    {{ $progressPercentage }}%
                </div>
            </div>
        </div>
    </div>

    <h2>Statistieken</h2>
    <table>
        <thead>
            <tr>
                <th>Item</th>
                <th>Aantal</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Vragen (totaal)</td>
                <td>{{ $wooRequest->questions->count() }}</td>
            </tr>
            <tr>
                <td>Onbeantwoord</td>
                <td>{{ $questionStats['unanswered'] }}</td>
            </tr>
            <tr>
                <td>Gedeeltelijk beantwoord</td>
                <td>{{ $questionStats['partially_answered'] }}</td>
            </tr>
            <tr>
                <td>Beantwoord</td>
                <td>{{ $questionStats['answered'] }}</td>
            </tr>
            <tr>
                <td>Documenten</td>
                <td>{{ $wooRequest->documents->count() }}</td>
            </tr>
            <tr>
                <td>Upload verzoeken</td>
                <td>{{ $wooRequest->internalRequests->count() }}</td>
            </tr>
            <tr>
                <td>Uploads</td>
                <td>{{ $wooRequest->submissions->count() }}</td>
            </tr>
        </tbody>
    </table>

    <h2>Vragen</h2>
    @foreach($wooRequest->questions as $question)
        <div class="question-item">
            <span class="question-number">{{ $question->order }}</span>
            <strong>{{ $question->question_text }}</strong>
            <div style="margin-top: 10px;">
                <span style="background: #f1f5f9; padding: 4px 8px; border-radius: 4px; font-size: 12px;">
                    {{ config('woo.question_statuses')[$question->status] ?? $question->status }}
                </span>
                @if($question->documents->count() > 0)
                    <span style="margin-left: 10px; color: #64748b; font-size: 12px;">
                        {{ $question->documents->count() }} document(en) gekoppeld
                    </span>
                @endif
            </div>
            @if($question->ai_summary)
                <div style="margin-top: 10px; padding: 10px; background: #dbeafe; border-radius: 4px; font-size: 13px;">
                    <strong>AI Samenvatting:</strong><br>
                    {{ $question->ai_summary }}
                </div>
            @endif
            @if($question->documents->count() > 0)
                <ul class="document-list">
                    @foreach($question->documents as $document)
                        <li>{{ $document->file_name }}</li>
                    @endforeach
                </ul>
            @endif
        </div>
    @endforeach

    <h2>Documenten</h2>
    @if($wooRequest->documents->count() > 0)
        <table>
            <thead>
                <tr>
                    <th>Bestandsnaam</th>
                    <th>Grootte</th>
                    <th>Geüpload op</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($wooRequest->documents as $document)
                    <tr>
                        <td>{{ $document->file_name }}</td>
                        <td>{{ $document->getFileSizeFormatted() }}</td>
                        <td>{{ $document->created_at->format('d-m-Y') }}</td>
                        <td>
                            @if($document->api_processing_status === 'completed')
                                Verwerkt
                            @elseif($document->api_processing_status === 'processing')
                                Thinking...
                            @elseif($document->api_processing_status === 'failed')
                                Mislukt
                            @else
                                In wachtrij
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <p>Geen documenten geüpload.</p>
    @endif

    <h2>Upload Verzoeken</h2>
    @if($wooRequest->internalRequests->count() > 0)
        @foreach($wooRequest->internalRequests as $internalRequest)
            <div class="question-item" style="margin-bottom: 20px;">
                <strong>{{ $internalRequest->colleague_name ?? $internalRequest->colleague_email }}</strong><br>
                <small>{{ $internalRequest->colleague_email }}</small><br>
                <p style="margin: 10px 0;">{{ $internalRequest->description }}</p>
                <div style="font-size: 12px; color: #64748b;">
                    Status: {{ config('woo.internal_request_statuses')[$internalRequest->status] ?? $internalRequest->status }} |
                    Uploads: {{ $internalRequest->submissions->count() }} |
                    Verstuurd: {{ $internalRequest->sent_at->format('d-m-Y H:i') }}
                </div>
            </div>
        @endforeach
    @else
        <p>Geen upload verzoeken verstuurd.</p>
    @endif

    <div class="footer">
        <p>Dit rapport is automatisch gegenereerd door het WOO Wijzer systeem.</p>
    </div>
</body>
</html>

