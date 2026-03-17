@extends('wishes.layout')

@section('title', 'Hantar Ucapan')

@push('styles')
<style>
    .create-hero {
        text-align: center;
        padding: 20px 0 30px;
    }

    .create-hero .emoji {
        font-size: 3.5rem;
        display: block;
        margin-bottom: 10px;
        animation: bounce 2s ease-in-out infinite;
    }

    @keyframes bounce {
        0%, 100% { transform: translateY(0); }
        50% { transform: translateY(-8px); }
    }

    .create-hero h1 {
        font-family: 'Bebas Neue', sans-serif;
        font-size: 2.4rem;
        color: var(--gold);
        letter-spacing: 3px;
    }

    .create-hero p {
        color: rgba(255,248,231,.5);
        font-size: .9rem;
        margin-top: 6px;
    }

    .photo-upload-area {
        border: 2px dashed rgba(245,200,66,.3);
        border-radius: 16px;
        padding: 30px;
        text-align: center;
        cursor: pointer;
        transition: all .25s;
        position: relative;
        overflow: hidden;
    }

    .photo-upload-area:hover {
        border-color: var(--gold);
        background: rgba(245,200,66,.05);
    }

    .photo-upload-area input[type="file"] {
        position: absolute;
        inset: 0;
        opacity: 0;
        cursor: pointer;
        width: 100%;
        height: 100%;
    }

    .photo-upload-area .upload-icon { font-size: 2rem; margin-bottom: 8px; }

    .photo-upload-area p {
        color: rgba(255,248,231,.5);
        font-size: .85rem;
    }

    .photo-upload-area strong {
        color: var(--gold);
        display: block;
        font-size: .9rem;
        margin-bottom: 4px;
    }

    #photoPreview {
        width: 100%;
        max-height: 250px;
        object-fit: cover;
        border-radius: 12px;
        margin-top: 12px;
        display: none;
    }

    .char-count {
        text-align: right;
        font-size: .75rem;
        color: rgba(255,248,231,.4);
        margin-top: 4px;
    }

    .char-count.warn { color: #e67e22; }
    .char-count.danger { color: #e74c3c; }

    .submit-btn {
        width: 100%;
        padding: 18px;
        font-size: 1.1rem;
        border-radius: 14px;
        margin-top: 8px;
    }
</style>
@endpush

@section('content')
<div class="create-hero">
    <span class="emoji">✉️</span>
    <h1>Hantar Ucapan Raya</h1>
    <p>Tulis ucapan ikhlas kamu, lepas tu tunggu roll duit raya! 🎰</p>
</div>

<div class="card" style="max-width: 560px; margin: 0 auto;">
    <form action="{{ route('wishes.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        {{-- Nama --}}
        <div class="field">
            <label>👤 Nama Kamu</label>
            <input
                type="text"
                name="name"
                value="{{ old('name') }}"
                placeholder="contoh: Ahmad, Siti Nora..."
                maxlength="100"
                required
            >
            @error('name')
                <div class="field-error">{{ $message }}</div>
            @enderror
        </div>
        <div class="field">
    <label>🎂 Umur Kamu</label>
    <input 
        type="number" 
        name="age" 
        min="1" 
        max="99"
        placeholder="Berapa tahun?"
        id="ageInput"
        required
    >
    <div id="ageWarning" style="
        display:none;
        margin-top:8px;
        padding:12px 16px;
        background:rgba(231,76,60,.15);
        border:1px solid rgba(231,76,60,.3);
        border-radius:10px;
        color:#e74c3c;
        font-weight:700;
        font-size:.9rem;
        text-align:center;
    ">
        😂 Dah besar dah! Mana dapat duit raya!
    </div>
</div>

        {{-- Ucapan --}}
        <div class="field">
            <label>💌 Ucapan Raya</label>
            <textarea
                name="message"
                rows="4"
                placeholder="Selamat Hari Raya! Maaf zahir dan batin..."
                maxlength="500"
                id="messageInput"
                required
            >{{ old('message') }}</textarea>
            <div class="char-count" id="charCount">0 / 500</div>
            @error('message')
                <div class="field-error">{{ $message }}</div>
            @enderror
        </div>

        {{-- Upload Gambar --}}
        <div class="field">
            <label>📸 Gambar Raya (optional)</label>
            <div class="photo-upload-area" id="uploadArea">
                <input type="file" name="photo" accept="image/*" id="photoInput">
                <div id="uploadPlaceholder">
                    <div class="upload-icon">🖼️</div>
                    <strong>Klik untuk upload gambar</strong>
                    <p>JPG, PNG, WEBP — maksimum 2MB</p>
                </div>
                <img id="photoPreview" src="" alt="Preview">
            </div>
            @error('photo')
                <div class="field-error">{{ $message }}</div>
            @enderror
        </div>

        <div class="divider">
            <div class="divider-line"></div>
            <span>✦</span>
            <div class="divider-line"></div>
        </div>

        <button type="submit" class="btn btn-gold submit-btn">
            🧧 Hantar Ucapan & Tunggu Roll!
        </button>
    </form>
</div>
@endsection

@push('scripts')
<script>
    // Character counter
    const messageInput = document.getElementById('messageInput');
    const charCount = document.getElementById('charCount');

    messageInput.addEventListener('input', () => {
        const len = messageInput.value.length;
        charCount.textContent = `${len} / 500`;
        charCount.className = 'char-count';
        if (len > 400) charCount.classList.add('warn');
        if (len > 470) charCount.classList.add('danger');
    });
    // Age warning
const ageInput = document.getElementById('ageInput');
const ageWarning = document.getElementById('ageWarning');

ageInput.addEventListener('input', () => {
    if (parseInt(ageInput.value) >= 20) {
        ageWarning.style.display = 'block';
    } else {
        ageWarning.style.display = 'none';
    }
});
    // Photo preview
    const photoInput = document.getElementById('photoInput');
    const photoPreview = document.getElementById('photoPreview');
    const uploadPlaceholder = document.getElementById('uploadPlaceholder');

    photoInput.addEventListener('change', (e) => {
        const file = e.target.files[0];
        if (!file) return;

        const reader = new FileReader();
        reader.onload = (ev) => {
            photoPreview.src = ev.target.result;
            photoPreview.style.display = 'block';
            uploadPlaceholder.style.display = 'none';
        };
        reader.readAsDataURL(file);
    });
</script>
@endpush