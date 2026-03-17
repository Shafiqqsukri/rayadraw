@extends('wishes.layout')

@section('title', 'Wall of Wishes')

@push('styles')
<style>
    /* Hero */
    .hero {
        text-align: center;
        padding: 20px 0 32px;
    }

    .hero h1 {
        font-family: 'Bebas Neue', sans-serif;
        font-size: 3rem;
        color: var(--gold);
        letter-spacing: 4px;
        text-shadow: 0 0 30px rgba(245,200,66,.3);
        line-height: 1;
    }

    .hero p {
        color: rgba(255,248,231,.5);
        font-size: .9rem;
        margin-top: 8px;
    }

    .stats-row {
        display: flex;
        gap: 12px;
        justify-content: center;
        margin: 16px 0 28px;
        flex-wrap: wrap;
    }

    .stat-pill {
        background: rgba(245,200,66,.1);
        border: 1px solid rgba(245,200,66,.2);
        border-radius: 30px;
        padding: 8px 18px;
        font-size: .85rem;
        font-weight: 700;
        color: var(--gold);
    }

    /* Roll button */
    .roll-section {
        text-align: center;
        margin-bottom: 36px;
    }

    .roll-btn {
        font-size: 1.2rem;
        padding: 18px 48px;
        border-radius: 16px;
        letter-spacing: 1px;
        position: relative;
        overflow: hidden;
    }

    .roll-btn::after {
        content: '';
        position: absolute;
        inset: 0;
        background: linear-gradient(90deg, transparent, rgba(255,255,255,.2), transparent);
        transform: translateX(-100%);
        animation: shimmer 2.5s infinite;
    }

    @keyframes shimmer {
        100% { transform: translateX(100%); }
    }

    .roll-btn:disabled {
        opacity: .5;
        cursor: not-allowed;
        transform: none !important;
    }

    /* Grid wishes */
    .wishes-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(260px, 1fr));
        gap: 16px;
    }

    .wish-card {
        background: rgba(255,255,255,.05);
        border: 1px solid rgba(245,200,66,.12);
        border-radius: 18px;
        overflow: hidden;
        transition: transform .2s, border-color .2s;
        position: relative;
    }

    .wish-card:hover {
        transform: translateY(-3px);
        border-color: rgba(245,200,66,.3);
    }

    .wish-card.revealed {
        border-color: var(--gold);
        animation: revealPulse .6s ease;
    }

    @keyframes revealPulse {
        0% { transform: scale(1); }
        50% { transform: scale(1.03); box-shadow: 0 0 30px rgba(245,200,66,.4); }
        100% { transform: scale(1); }
    }

    .wish-photo {
        width: 100%;
        height: 160px;
        object-fit: cover;
        display: block;
    }

    .wish-photo-placeholder {
        width: 100%;
        height: 120px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: rgba(255,255,255,.03);
        font-size: 2.5rem;
    }

    .wish-body { padding: 16px; }

    .wish-name {
        font-weight: 800;
        font-size: 1rem;
        color: var(--cream);
        margin-bottom: 6px;
    }

    .wish-message {
        font-size: .85rem;
        color: rgba(255,248,231,.6);
        line-height: 1.6;
        margin-bottom: 12px;
    }

    /* Amount reveal */
    .amount-wrap {
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .amount-badge {
        font-family: 'Bebas Neue', sans-serif;
        font-size: 1.4rem;
        letter-spacing: 2px;
        padding: 4px 14px;
        border-radius: 20px;
        transition: all .4s;
    }

    .amount-badge.pending {
        background: rgba(255,255,255,.07);
        color: rgba(255,248,231,.3);
    }

    .amount-badge.rolling {
        background: rgba(245,200,66,.1);
        color: var(--gold);
        animation: spin 0.15s linear infinite;
    }

    @keyframes spin {
        from { transform: rotateY(0deg); }
        to { transform: rotateY(360deg); }
    }

    .amount-badge.revealed {
        background: var(--gold);
        color: var(--green);
        animation: popIn .4s cubic-bezier(.175,.885,.32,1.275);
    }

    @keyframes popIn {
        0% { transform: scale(0.5); opacity: 0; }
        100% { transform: scale(1); opacity: 1; }
    }

    .wish-time {
        font-size: .72rem;
        color: rgba(255,248,231,.25);
    }

    /* Empty state */
    .empty-state {
        text-align: center;
        padding: 60px 20px;
        color: rgba(255,248,231,.35);
    }

    .empty-state .empty-icon { font-size: 3rem; margin-bottom: 12px; }
    .empty-state p { font-size: .9rem; }

    /* Confetti */
    .confetti-piece {
        position: fixed;
        width: 9px; height: 9px;
        top: -10px;
        animation: confettiFall linear forwards;
        z-index: 9999;
        border-radius: 2px;
        pointer-events: none;
    }

    @keyframes confettiFall {
        to { transform: translateY(110vh) rotate(720deg); opacity: 0; }
    }

    .reset-wrap {
        text-align: center;
        margin-top: 28px;
    }
</style>
@endpush

@section('content')

{{-- Hero --}}
<div class="hero">
    <h1>🌟 Wall of Wishes</h1>
    <p>Semua ucapan raya dikumpul di sini — tunggu roll untuk reveal duit raya!</p>
</div>

{{-- Stats --}}
<div class="stats-row">
    <div class="stat-pill">✉️ {{ $wishes->count() }} Ucapan</div>
    <div class="stat-pill">🎰 {{ $wishes->where('is_rolled', true)->count() }} Dah Roll</div>
    <div class="stat-pill">⏳ {{ $wishes->where('is_rolled', false)->count() }} Menunggu</div>
</div>

{{-- Roll button --}}
@if($wishes->where('is_rolled', false)->count() > 0)
<div class="roll-section">
    <button class="btn btn-gold roll-btn" id="rollBtn" onclick="doRoll()">
        🎰 ROLL SEMUA DUIT RAYA!
    </button>
    <p style="margin-top: 10px; font-size: .8rem; color: rgba(255,248,231,.35);">
        {{ $wishes->where('is_rolled', false)->count() }} orang menunggu...
    </p>
</div>
@elseif($wishes->count() > 0)
<div class="roll-section">
    <p style="color: var(--gold); font-weight: 700; margin-bottom: 12px;">✅ Semua dah dapat duit raya!</p>
    <form action="{{ route('wishes.reset') }}" method="POST" style="display:inline;">
        @csrf
        <button type="submit" class="btn btn-outline" onclick="return confirm('Reset semua rolls?')">
            🔄 Roll Semula
        </button>
    </form>
</div>
@endif

{{-- Wishes grid --}}
@if($wishes->isEmpty())
    <div class="empty-state">
        <div class="empty-icon">🌙</div>
        <p>Belum ada ucapan lagi.<br>Jadi yang pertama!</p>
        <a href="{{ route('wishes.create') }}" class="btn btn-gold" style="margin-top: 16px; display: inline-block;">
            ✉️ Hantar Ucapan Pertama
        </a>
    </div>
@else
    <div class="wishes-grid" id="wishesGrid">
        @foreach($wishes as $wish)
        <div class="wish-card" id="wish-{{ $wish->id }}">

            {{-- Gambar --}}
            @if($wish->photo_path)
                <img src="{{ $wish->photo_url }}" alt="{{ $wish->name }}" class="wish-photo">
            @else
                <div class="wish-photo-placeholder">🌙</div>
            @endif

            <div class="wish-body">
                <div class="wish-name">{{ $wish->name }}</div>
                @if($wish->is_adult)
                    <span style="
                        font-size:.65rem;
                        background:rgba(231,76,60,.2);
                        color:#e74c3c;
                        padding:2px 8px;
                        border-radius:10px;
                        font-weight:700;
                        margin-left:6px;
                        ">Dah Besar 😂</span>
                @endif
                <div class="wish-message">"{{ $wish->message }}"</div>

                <div class="amount-wrap">
                    <div
                        class="amount-badge {{ $wish->is_rolled ? 'revealed' : 'pending' }}"
                        id="amount-{{ $wish->id }}"
                    >
                     {{ $wish->is_rolled ? $wish->formatted_amount : '???' }}
                    </div>
                    <div class="wish-time">{{ $wish->created_at->diffForHumans() }}</div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
@endif

@endsection

@push('scripts')
<script>
    async function doRoll() {
        const btn = document.getElementById('rollBtn');
        btn.disabled = true;
        btn.textContent = '🎰 Rolling...';

        // Set semua pending cards ke rolling state
        document.querySelectorAll('.amount-badge.pending').forEach(el => {
            el.classList.remove('pending');
            el.classList.add('rolling');
            el.textContent = '???';
        });

        try {
            const res = await fetch('{{ route("wishes.roll") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                },
            });

            const data = await res.json();

            // Reveal satu-satu dengan delay
            data.wishes.forEach((wish, index) => {
                setTimeout(() => {
                    const badge = document.getElementById(`amount-${wish.id}`);
                    const card = document.getElementById(`wish-${wish.id}`);

                    if (badge) {
                        badge.classList.remove('rolling');
                        badge.classList.add('revealed');
                        badge.textContent = `RM ${wish.amount}`;
                    }

                    if (card) {
                        card.classList.add('revealed');
                    }

                    // Confetti untuk reveal terakhir
                    if (index === data.wishes.length - 1) {
                        launchConfetti();
                        btn.closest('.roll-section').innerHTML = `
                            <p style="color:var(--gold);font-weight:700;margin-bottom:12px;">✅ Semua dah dapat duit raya!</p>
                            <form action="{{ route('wishes.reset') }}" method="POST" style="display:inline;">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                <button type="submit" class="btn btn-outline" onclick="return confirm('Reset semua rolls?')">
                                    🔄 Roll Semula
                                </button>
                            </form>
                        `;
                    }

                }, index * 400); // 400ms delay antara setiap reveal
            });

        } catch (err) {
            alert('Ralat semasa roll. Cuba semula!');
            btn.disabled = false;
            btn.textContent = '🎰 ROLL SEMUA DUIT RAYA!';
        }
    }

    function launchConfetti() {
        const colors = ['#F5C842', '#E8453C', '#2ECC71', '#fff', '#e6a820', '#ff6b6b'];
        for (let i = 0; i < 80; i++) {
            setTimeout(() => {
                const p = document.createElement('div');
                p.className = 'confetti-piece';
                p.style.left = Math.random() * 100 + 'vw';
                p.style.background = colors[Math.floor(Math.random() * colors.length)];
                p.style.width = (5 + Math.random() * 8) + 'px';
                p.style.height = (5 + Math.random() * 8) + 'px';
                p.style.borderRadius = Math.random() > .5 ? '50%' : '2px';
                p.style.animationDuration = (2 + Math.random() * 2.5) + 's';
                p.style.animationDelay = Math.random() * .5 + 's';
                document.body.appendChild(p);
                setTimeout(() => p.remove(), 5000);
            }, i * 20);
        }
    }
</script>
@endpush