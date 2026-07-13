@extends('layouts.app')

@section('title', 'Publier un produit - Marketplace ELEDJI')

@push('styles')
<style>
:root {
    --rouge: #CC0000;
    --rouge-dark: #910000;
    --rose: #F7D6D3;
    --gris-bg: #F9F9F9;
    --gris-border: #E0E0E0;
    --texte: #1a1a1a;
    --texte-doux: #666;
    --poppins: 'Poppins', sans-serif;
    --radius: 16px;
    --shadow: 0 4px 24px rgba(0, 0, 0, 0.08);
    --or: #F5A623;
    --or-bg: #FFFBF0;
}
*, *::before, *::after { box-sizing: border-box; }

.create-page {
    min-height: calc(100vh - 80px);
    padding: 40px 24px 60px;
    background: var(--gris-bg);
    font-family: var(--poppins);
}

.create-container {
    max-width: 700px;
    margin: 0 auto;
}

.page-header {
    text-align: center;
    margin-bottom: 32px;
}

.page-title {
    font-family: 'Eras Medium ITC', serif;
    font-size: 28px;
    font-weight: 700;
    color: var(--texte);
    margin: 0 0 8px;
}

.vip-badge {
    display: inline-block;
    background: var(--or-bg);
    color: var(--or);
    padding: 8px 16px;
    border-radius: 20px;
    font-size: 13px;
    font-weight: 600;
    border: 1px solid var(--or);
}

.form-card {
    background: white;
    border-radius: var(--radius);
    box-shadow: var(--shadow);
    padding: 32px;
}

.form-group {
    margin-bottom: 24px;
}

.form-label {
    display: block;
    font-size: 14px;
    font-weight: 600;
    color: var(--texte);
    margin-bottom: 8px;
}

.form-label span {
    color: var(--rouge);
}

.form-input,
.form-textarea {
    width: 100%;
    padding: 14px 16px;
    border: 2px solid var(--gris-border);
    border-radius: 12px;
    font-size: 15px;
    font-family: inherit;
    transition: all 0.2s;
    background: white;
}

.form-input:focus,
.form-textarea:focus {
    outline: none;
    border-color: var(--rouge);
}

.form-textarea {
    min-height: 150px;
    resize: vertical;
    font-family: Calibri, sans-serif;
    font-size: 14px;
    line-height: 1.6;
}

.form-hint {
    font-size: 12px;
    color: var(--texte-doux);
    margin-top: 6px;
}

.file-upload {
    border: 2px dashed var(--gris-border);
    border-radius: 12px;
    padding: 32px;
    text-align: center;
    cursor: pointer;
    transition: all 0.2s;
    background: #fafafa;
}

.file-upload:hover {
    border-color: var(--rouge);
    background: #fff5f5;
}

.file-upload input {
    display: none;
}

.file-upload-icon {
    width: 48px;
    height: 48px;
    margin: 0 auto 12px;
    color: var(--texte-doux);
}

.file-upload-text {
    font-size: 14px;
    color: var(--texte-doux);
}

.file-upload-text strong {
    color: var(--rouge);
}

.image-preview {
    margin-top: 16px;
    display: none;
}

.image-preview img {
    max-width: 100%;
    max-height: 200px;
    border-radius: 12px;
    object-fit: cover;
}

.btn-submit {
    width: 100%;
    padding: 16px;
    background: var(--rouge);
    color: white;
    border: none;
    border-radius: 30px;
    font-size: 16px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.25s ease;
}

.btn-submit:hover {
    background: var(--rouge-dark);
}

.back-link {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    color: var(--texte-doux);
    text-decoration: none;
    font-size: 14px;
    margin-bottom: 24px;
    transition: color 0.2s;
}

.back-link:hover {
    color: var(--rouge);
}

@media (max-width: 640px) {
    .form-card {
        padding: 24px;
    }
}
</style>
@endpush

@section('content')
<div class="create-page">
    <div class="create-container">

        <a href="{{ route('marketplace.index') }}" class="back-link">
            <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
            </svg>
            Retour à la Marketplace
        </a>

        <div class="page-header">
            <h1 class="page-title">Publier un produit</h1>
            <span class="vip-badge">Publication réservée aux membres VIP</span>
        </div>

        <div class="form-card">
            <form action="{{ route('marketplace.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="form-group">
                    <label class="form-label" for="titre">Titre <span>*</span></label>
                    <input type="text" id="titre" name="titre" class="form-input" placeholder="Ex: Service de photography événementiel" required>
                </div>

                <div class="form-group">
                    <label class="form-label" for="description">Description</label>
                    <textarea id="description" name="description" class="form-textarea" placeholder="Décrivez votre produit ou service en détail..." required></textarea>
                    <p class="form-hint">Police utilisée : Calibri</p>
                </div>

                <div class="form-group">
                    <label class="form-label" for="prix">Prix (FCFA)</label>
                    <input type="number" id="prix" name="prix" class="form-input" placeholder="Ex: 50000" min="0">
                    <p class="form-hint">Laissez vide pour afficher "Prix sur demande"</p>
                </div>

                <div class="form-group">
                    <label class="form-label" for="image">Image</label>
                    <label class="file-upload" for="image">
                        <input type="file" id="image" name="image" accept="image/jpeg,image/jpg,image/png,image/webp">
                        <svg class="file-upload-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        <p class="file-upload-text">
                            <strong>Cliquez pour uploader</strong> ou glissez une image<br>
                            JPG, JPEG, PNG, WEBP — Max 5MB
                        </p>
                    </label>
                    <div class="image-preview" id="imagePreview">
                        <img id="previewImg" src="" alt="Aperçu">
                    </div>
                </div>

                <button type="submit" class="btn-submit">Publier mon produit</button>
            </form>
        </div>

    </div>
</div>

@push('scripts')
<script>
document.getElementById('image').addEventListener('change', function(e) {
    const file = e.target.files[0];
    const preview = document.getElementById('imagePreview');
    const previewImg = document.getElementById('previewImg');

    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            previewImg.src = e.target.result;
            preview.style.display = 'block';
        };
        reader.readAsDataURL(file);
    } else {
        preview.style.display = 'none';
    }
});
</script>
@endpush
@endsection