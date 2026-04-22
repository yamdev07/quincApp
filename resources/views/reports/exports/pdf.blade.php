<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<style>
    body { font-family: DejaVu Sans, sans-serif; font-size: 12px; color: #0f172a; margin: 0; padding: 20px; }
    .header { margin-bottom: 24px; border-bottom: 2px solid #f97316; padding-bottom: 12px; }
    .header h1 { font-size: 20px; font-weight: 700; color: #f97316; margin: 0 0 4px; }
    .header .meta { font-size: 11px; color: #64748b; }
    table { width: 100%; border-collapse: collapse; margin-top: 16px; }
    th { background: #f97316; color: white; padding: 8px 10px; text-align: left; font-size: 11px; font-weight: 600; }
    td { padding: 7px 10px; border-bottom: 1px solid #e2e8f0; font-size: 11px; }
    tr:nth-child(even) td { background: #fff7ed; }
    .footer { margin-top: 20px; font-size: 10px; color: #94a3b8; text-align: center; }
</style>
</head>
<body>
<div class="header">
    <h1>{{ $title }}</h1>
    <div class="meta">Généré le {{ now()->format('d/m/Y à H:i') }} &bull; Sellvantix</div>
</div>

<table>
    <thead>
        <tr>
            @foreach($headers as $h)
                <th>{{ $h }}</th>
            @endforeach
        </tr>
    </thead>
    <tbody>
        @foreach($rows as $row)
        <tr>
            @foreach($row as $cell)
                <td>{{ $cell }}</td>
            @endforeach
        </tr>
        @endforeach
    </tbody>
</table>

<div class="footer">Sellvantix &mdash; {{ now()->format('Y') }}</div>
</body>
</html>
