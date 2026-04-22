@extends('layouts.app')

@section('title', 'Analyse IA — Sellvantix')

@section('styles')
<style>
    :root {
        --orange: #f97316; --orange-dark: #ea580c; --orange-pale: #fff7ed;
        --bg: #f1f5f9; --card: #ffffff; --border: #e2e8f0;
        --text: #0f172a; --text-2: #475569; --text-3: #94a3b8;
        --purple: #7c3aed; --purple-pale: #f5f3ff;
        --radius: 20px; --radius-sm: 12px;
        --shadow-md: 0 4px 16px rgba(15,23,42,.08);
        --shadow-orange: 0 8px 24px rgba(249,115,22,.25);
    }
    * { box-sizing: border-box; margin: 0; padding: 0; }
    body { font-family: 'Inter', system-ui, sans-serif; background: var(--bg); color: var(--text); }

    @keyframes fadeUp { from { opacity:0; transform:translateY(12px); } to { opacity:1; transform:translateY(0); } }
    @keyframes pulse { 0%,100% { opacity:1; } 50% { opacity:.5; } }

    .ai-page { max-width: 1000px; margin: 0 auto; padding: 32px 24px 64px; }

    /* Header */
    .ai-header { display:flex; align-items:center; gap:16px; margin-bottom:32px; animation: fadeUp .35s ease both; }
    .ai-hex {
        width:52px; height:52px; flex-shrink:0;
        background: linear-gradient(135deg, var(--purple), #6d28d9);
        clip-path: polygon(50% 0%,100% 25%,100% 75%,50% 100%,0% 75%,0% 25%);
        display:flex; align-items:center; justify-content:center;
        box-shadow: 0 8px 24px rgba(124,58,237,.35);
    }
    .ai-hex svg { width:24px; height:24px; stroke:#fff; fill:none; }
    .ai-header h1 { font-size:26px; font-weight:800; letter-spacing:-.3px; }
    .ai-header h1 span { color:var(--purple); }
    .ai-header p { font-size:13px; color:var(--text-3); margin-top:4px; }

    /* Cards de sélection d'analyse */
    .ai-cards { display:grid; grid-template-columns:1fr 1fr; gap:20px; margin-bottom:32px; animation: fadeUp .35s .07s ease both; }
    @media(max-width:640px) { .ai-cards { grid-template-columns:1fr; } }

    .ai-card {
        background:var(--card); border:2px solid var(--border);
        border-radius:var(--radius); padding:28px;
        transition: all .2s; cursor:pointer; text-decoration:none; display:block;
    }
    .ai-card:hover { border-color:var(--purple); box-shadow: 0 8px 24px rgba(124,58,237,.15); transform:translateY(-2px); }
    .ai-card-icon {
        width:48px; height:48px; border-radius:14px;
        display:flex; align-items:center; justify-content:center; margin-bottom:16px;
    }
    .ai-card-icon.products { background:linear-gradient(135deg,#f97316,#ea580c); }
    .ai-card-icon.reports  { background:linear-gradient(135deg,#7c3aed,#6d28d9); }
    .ai-card-icon svg { width:24px; height:24px; stroke:#fff; fill:none; }
    .ai-card h3 { font-size:16px; font-weight:700; margin-bottom:8px; }
    .ai-card p  { font-size:13px; color:var(--text-2); line-height:1.5; }

    /* Formulaire période */
    .ai-form-row {
        display:flex; align-items:center; gap:16px; flex-wrap:wrap;
        background:var(--card); border:1px solid var(--border);
        border-radius:var(--radius-sm); padding:20px 24px; margin-bottom:24px;
        animation: fadeUp .35s .1s ease both;
    }
    .ai-form-row label { font-size:13px; font-weight:600; color:var(--text-2); }
    .ai-period-select {
        padding:9px 14px; border:1.5px solid var(--border); border-radius:10px;
        font-size:13px; background:#fafbfd; color:var(--text); cursor:pointer;
        outline:none; transition: border-color .2s;
    }
    .ai-period-select:focus { border-color:var(--purple); }

    .btn-ai {
        display:inline-flex; align-items:center; gap:8px;
        padding:11px 24px; border-radius:40px; border:none; cursor:pointer;
        font-size:14px; font-weight:700; color:#fff; transition: all .2s;
    }
    .btn-ai.products { background:linear-gradient(135deg,var(--orange),var(--orange-dark)); box-shadow:var(--shadow-orange); }
    .btn-ai.products:hover { transform:translateY(-2px); box-shadow:0 12px 28px rgba(249,115,22,.4); }
    .btn-ai.reports  { background:linear-gradient(135deg,var(--purple),#6d28d9); box-shadow:0 8px 24px rgba(124,58,237,.3); }
    .btn-ai.reports:hover  { transform:translateY(-2px); box-shadow:0 12px 28px rgba(124,58,237,.45); }
    .btn-ai svg { width:18px; height:18px; stroke:#fff; fill:none; }
    .btn-ai:disabled { opacity:.6; cursor:not-allowed; transform:none !important; }

    /* Zone résultat IA */
    .ai-result {
        background:var(--card); border:1px solid var(--border);
        border-radius:var(--radius); padding:36px;
        margin-bottom:24px; animation: fadeUp .35s .05s ease both;
    }
    .ai-result-header {
        display:flex; align-items:center; gap:12px;
        margin-bottom:24px; padding-bottom:20px;
        border-bottom:1px dashed var(--border);
    }
    .ai-result-header .ai-badge {
        display:inline-flex; align-items:center; gap:6px;
        padding:5px 14px; border-radius:40px;
        background: linear-gradient(135deg,var(--purple),#6d28d9);
        color:#fff; font-size:12px; font-weight:700;
    }
    .ai-result-header h2 { font-size:18px; font-weight:700; flex:1; }

    .ai-content { font-size:14px; line-height:1.8; color:var(--text-2); }
    .ai-content h1,.ai-content h2,.ai-content h3 {
        color:var(--text); font-weight:700; margin:20px 0 10px;
    }
    .ai-content h1 { font-size:20px; border-bottom:2px dashed var(--border); padding-bottom:8px; }
    .ai-content h2 { font-size:17px; color:var(--purple); }
    .ai-content h3 { font-size:15px; }
    .ai-content p  { margin-bottom:12px; }
    .ai-content strong, .ai-content b { color:var(--text); font-weight:700; }
    .ai-content em  { color:var(--orange-dark); font-style:normal; font-weight:600; }
    .ai-content ul, .ai-content ol { padding-left:20px; margin-bottom:14px; }
    .ai-content li { margin-bottom:6px; }
    .ai-content li::marker { color:var(--orange); }
    .ai-content blockquote {
        border-left:4px solid var(--purple); padding:10px 16px;
        background:var(--purple-pale); border-radius:0 8px 8px 0;
        margin:12px 0; color:#6d28d9; font-style:italic;
    }
    .ai-content hr { border:none; border-top:1px dashed var(--border); margin:20px 0; }
    .ai-content code {
        background:#f1f5f9; padding:2px 8px; border-radius:6px;
        font-family:monospace; font-size:12px; color:var(--orange-dark);
    }

    /* Stats rapides */
    .ai-stats { display:grid; grid-template-columns:repeat(3,1fr); gap:16px; margin-bottom:24px; }
    @media(max-width:640px) { .ai-stats { grid-template-columns:1fr; } }
    .ai-stat {
        background:var(--card); border:1px solid var(--border);
        border-radius:var(--radius-sm); padding:20px;
        text-align:center;
    }
    .ai-stat-value { font-size:22px; font-weight:800; color:var(--orange); margin-bottom:4px; }
    .ai-stat-label { font-size:12px; color:var(--text-3); }

    /* Loading */
    .ai-loading {
        display:none; text-align:center; padding:48px;
        background:var(--card); border-radius:var(--radius);
        border:1px solid var(--border); margin-bottom:24px;
    }
    .ai-loading.active { display:block; }
    .ai-spinner {
        width:48px; height:48px; border:3px solid var(--border);
        border-top-color:var(--purple); border-radius:50%;
        animation: spin 1s linear infinite; margin:0 auto 16px;
    }
    @keyframes spin { to { transform:rotate(360deg); } }
    .ai-loading p { color:var(--text-3); font-size:14px; animation: pulse 2s ease infinite; }

    /* Setup banner */
    .ai-setup-banner {
        background: linear-gradient(135deg,#f5f3ff,#ede9fe);
        border:1px solid #c4b5fd; border-radius:var(--radius-sm);
        padding:20px 24px; margin-bottom:24px;
        display:flex; align-items:flex-start; gap:14px;
    }
    .ai-setup-banner svg { width:24px; height:24px; stroke:var(--purple); fill:none; flex-shrink:0; margin-top:2px; }
    .ai-setup-banner h4 { font-size:14px; font-weight:700; color:var(--purple); margin-bottom:6px; }
    .ai-setup-banner p  { font-size:13px; color:#6d28d9; line-height:1.5; }
    .ai-setup-banner code {
        background:#ddd6fe; padding:2px 8px; border-radius:6px;
        font-family:monospace; font-size:12px; color:#4c1d95;
    }
</style>
@endsection

@section('content')
<div class="ai-page">

    {{-- Header --}}
    <div class="ai-header">
        <div class="ai-hex">
            <svg viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
            </svg>
        </div>
        <div>
            <h1>Analyse <span>IA</span></h1>
            <p>Analyse intelligente de vos produits et rapports — propulsé par Groq AI (Llama 3)</p>
        </div>
    </div>

    {{-- Banner setup si clé non configurée --}}
    @php $keyOk = config('services.groq.api_key') && config('services.groq.api_key') !== 'your_groq_api_key_here'; @endphp
    @if(!$keyOk)
    <div class="ai-setup-banner">
        <svg viewBox="0 0 24 24" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/>
        </svg>
        <div>
            <h4>Configuration requise — Clé API Groq</h4>
            <p>
                1. Créez un compte gratuit sur <strong>groq.com</strong> → API Keys → Create API Key<br>
                2. Ouvrez le fichier <code>.env</code> à la racine du projet<br>
                3. Remplacez <code>GROQ_API_KEY=your_groq_api_key_here</code> par votre vraie clé<br>
                4. Relancez le serveur : <code>php artisan serve</code>
            </p>
        </div>
    </div>
    @endif

    {{-- Résultat IA --}}
    @isset($analysis)
    @if(isset($totalRevenue) && isset($totalSales))
    <div class="ai-stats">
        <div class="ai-stat">
            <div class="ai-stat-value">{{ number_format($totalRevenue, 0, ',', ' ') }}</div>
            <div class="ai-stat-label">FCFA générés (période)</div>
        </div>
        <div class="ai-stat">
            <div class="ai-stat-value">{{ $totalSales }}</div>
            <div class="ai-stat-label">Ventes réalisées</div>
        </div>
        <div class="ai-stat">
            <div class="ai-stat-value">{{ $period }}j</div>
            <div class="ai-stat-label">Période analysée</div>
        </div>
    </div>
    @endif

    @if(isset($currentRevenue))
    <div class="ai-stats">
        <div class="ai-stat">
            <div class="ai-stat-value">{{ number_format($currentRevenue, 0, ',', ' ') }}</div>
            <div class="ai-stat-label">CA période actuelle (FCFA)</div>
        </div>
        <div class="ai-stat">
            <div class="ai-stat-value {{ ($evolutionPct ?? 0) >= 0 ? '' : 'text-red-500' }}">
                {{ ($evolutionPct ?? 0) >= 0 ? '+' : '' }}{{ $evolutionPct ?? 0 }}%
            </div>
            <div class="ai-stat-label">Évolution vs période précédente</div>
        </div>
        <div class="ai-stat">
            <div class="ai-stat-value">{{ $currentSales }}</div>
            <div class="ai-stat-label">Ventes réalisées</div>
        </div>
    </div>
    @endif

    <div class="ai-result">
        <div class="ai-result-header">
            <div class="ai-badge">
                <svg viewBox="0 0 24 24" stroke-width="2" style="width:14px;height:14px;stroke:#fff;fill:none;">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                </svg>
                Analyse IA — Groq Llama 3
            </div>
            <h2>Résultats de l'analyse</h2>
            <span style="font-size:12px;color:var(--text-3);">{{ now()->format('d/m/Y à H:i') }}</span>
        </div>
        <div class="ai-content" id="aiContent"></div>
    </div>
    @endisset

    {{-- Sélection du type d'analyse --}}
    @unless(isset($analysis))
    <div class="ai-cards">
        <a href="#products-form" onclick="showForm('products')" class="ai-card">
            <div class="ai-card-icon products">
                <svg viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                </svg>
            </div>
            <h3>Analyse des produits</h3>
            <p>Identifiez les produits à fort potentiel, ceux qui stagnent et recevez des recommandations d'achat personnalisées.</p>
        </a>
        <a href="#reports-form" onclick="showForm('reports')" class="ai-card">
            <div class="ai-card-icon reports">
                <svg viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                </svg>
            </div>
            <h3>Analyse financière</h3>
            <p>Analysez l'évolution de votre chiffre d'affaires, les meilleures catégories et obtenez des conseils stratégiques.</p>
        </a>
    </div>
    @endunless

    {{-- Formulaire produits --}}
    <div id="products-form" style="{{ isset($analysis) ? '' : 'display:none;' }}">
        <form method="POST" action="{{ route('ai.products') }}" onsubmit="startLoading(this)">
            @csrf
            <div class="ai-form-row">
                <label>Période d'analyse :</label>
                <select name="period" class="ai-period-select">
                    <option value="7"  {{ ($period ?? 30) == 7  ? 'selected' : '' }}>7 derniers jours</option>
                    <option value="30" {{ ($period ?? 30) == 30 ? 'selected' : '' }}>30 derniers jours</option>
                    <option value="60" {{ ($period ?? 30) == 60 ? 'selected' : '' }}>60 derniers jours</option>
                    <option value="90" {{ ($period ?? 30) == 90 ? 'selected' : '' }}>90 derniers jours</option>
                </select>
                <button type="submit" class="btn-ai products" {{ !$keyOk ? 'disabled' : '' }}>
                    <svg viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                    </svg>
                    Analyser les produits
                </button>
                <button type="button" onclick="showForm('reports')" class="btn-ai reports" {{ !$keyOk ? 'disabled' : '' }}>
                    <svg viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                    </svg>
                    Analyse financière
                </button>
            </div>
        </form>
    </div>

    {{-- Formulaire rapports --}}
    <div id="reports-form" style="display:none;">
        <form method="POST" action="{{ route('ai.reports') }}" onsubmit="startLoading(this)">
            @csrf
            <div class="ai-form-row">
                <label>Période d'analyse :</label>
                <select name="period" class="ai-period-select">
                    <option value="7">7 derniers jours</option>
                    <option value="30" selected>30 derniers jours</option>
                    <option value="60">60 derniers jours</option>
                    <option value="90">90 derniers jours</option>
                </select>
                <button type="submit" class="btn-ai reports" {{ !$keyOk ? 'disabled' : '' }}>
                    <svg viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                    </svg>
                    Analyser les finances
                </button>
                <button type="button" onclick="showForm('products')" class="btn-ai products" {{ !$keyOk ? 'disabled' : '' }}>
                    <svg viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                    </svg>
                    Analyse produits
                </button>
            </div>
        </form>
    </div>

    {{-- Loading --}}
    <div class="ai-loading" id="aiLoading">
        <div class="ai-spinner"></div>
        <p>L'IA analyse vos données… cela peut prendre quelques secondes.</p>
    </div>

</div>

@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/marked/marked.min.js"></script>
<script>
function showForm(type) {
    document.getElementById('products-form').style.display = type === 'products' ? 'block' : 'none';
    document.getElementById('reports-form').style.display  = type === 'reports'  ? 'block' : 'none';
}

function startLoading(form) {
    document.getElementById('aiLoading').classList.add('active');
    form.querySelector('button[type="submit"]').disabled = true;
}

@isset($analysis)
    document.addEventListener('DOMContentLoaded', function () {
        const raw = @json($analysis);
        document.getElementById('aiContent').innerHTML = marked.parse(raw);
    });
    document.getElementById('products-form').style.display = 'block';
@endisset
</script>
@endsection
