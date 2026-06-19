@extends('layouts.app')

@section('title', 'Modifier un événement - ELEDJI')

@section('content')
<div style="padding: 80px 24px 48px; background: #F5F2F2; min-height: calc(100vh - 120px);">
    <div style="max-width: 1200px; margin: 0 auto; display: grid; grid-template-columns: 2.2fr 1fr; gap: 32px;">
        <div style="background: #FFFFFF; border-radius: 28px; padding: 36px; box-shadow: 0 24px 80px rgba(0,0,0,0.08);">
            <div style="display: flex; align-items: center; justify-content: space-between; gap: 18px; margin-bottom: 30px;">
                <div>
                    <h1 style="font-size: 2rem; font-weight: 800; color: #171717; margin-bottom: 6px;">Modifier l'événement</h1>
                    <p style="color: #666;">Mettez à jour les informations de votre événement ou changez son statut.</p>
                </div>
                <span style="background: #F6E6E6; color: #AA1F1F; border-radius: 999px; padding: 10px 18px; font-weight: 700; font-size: 0.95rem;">Édition</span>
            </div>

            @if($errors->any())
                <div style="background: #FDE8EA; color: #8A191F; border-radius: 18px; padding: 20px; margin-bottom: 24px;">
                    <ul style="margin: 0; padding-left: 18px;">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('events.update', $event) }}" method="POST" enctype="multipart/form-data" style="display: grid; gap: 24px;">
                @csrf
                @method('PUT')

                <div style="display: grid; gap: 22px;">
                    <label style="display: grid; gap: 10px; font-weight: 700; color: #1D1D1D;">Titre de l'événement
                        <input type="text" name="titre" value="{{ old('titre', $event->titre) }}" required
                            style="width: 100%; border: 1px solid #E5E5E5; border-radius: 18px; padding: 18px 20px; font-size: 1rem;">
                    </label>

                    <label style="display: grid; gap: 10px; font-weight: 700; color: #1D1D1D;">Catégorie
                        <select name="category_id" required style="width: 100%; border: 1px solid #E5E5E5; border-radius: 18px; padding: 18px 20px; font-size: 1rem; background: white;">
                            <option value="">Sélectionnez une catégorie</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" @selected(old('category_id', $event->category_id) == $category->id)>{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </label>

                    <label style="display: grid; gap: 10px; font-weight: 700; color: #1D1D1D;">Description de l'événement
                        <textarea name="description" rows="8" required
                            style="width: 100%; border: 1px solid #E5E5E5; border-radius: 18px; padding: 18px 20px; font-size: 1rem; resize: vertical;">{{ old('description', $event->description) }}</textarea>
                    </label>
                </div>

                <div style="display: grid; gap: 22px;">
                    <label style="display: grid; gap: 10px; font-weight: 700; color: #1D1D1D;">Image de couverture
                        <div style="border: 2px dashed #E5E5E5; border-radius: 22px; min-height: 200px; display: flex; align-items: center; justify-content: center; background: #FCFCFC; position: relative;">
                            <input type="file" name="image_couverture" accept="image/*" style="position: absolute; inset: 0; opacity: 0; cursor: pointer;">
                            <div style="text-align: center; color: #9B9B9B; max-width: 70%;">
                                <div style="font-size: 2rem; margin-bottom: 12px;">+</div>
                                <div>Cliquer pour remplacer l'image</div>
                            </div>
                        </div>
                        @if($event->image_couverture)
                            <p style="margin-top: 12px; color: #666; font-size: 0.95rem;">Image actuelle : {{ basename($event->image_couverture) }}</p>
                        @endif
                    </label>

                    <div style="display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 20px;">
                        <label style="display: grid; gap: 10px; font-weight: 700; color: #1D1D1D;">Date
                            <input type="date" name="date_heure" value="{{ old('date_heure', optional($event->date_heure)->format('Y-m-d')) }}" required
                                style="width: 100%; border: 1px solid #E5E5E5; border-radius: 18px; padding: 18px 20px; font-size: 1rem;">
                        </label>
                        <label style="display: grid; gap: 10px; font-weight: 700; color: #1D1D1D;">Heure
                            <input type="time" name="heure" value="{{ old('heure', optional($event->date_heure)->format('H:i')) }}"
                                style="width: 100%; border: 1px solid #E5E5E5; border-radius: 18px; padding: 18px 20px; font-size: 1rem;">
                        </label>
                    </div>

                    <label style="display: grid; gap: 10px; font-weight: 700; color: #1D1D1D;">Lieu
                        <input type="text" name="lieu" value="{{ old('lieu', $event->lieu) }}" required
                            style="width: 100%; border: 1px solid #E5E5E5; border-radius: 18px; padding: 18px 20px; font-size: 1rem;">
                    </label>

                    <label style="display: grid; gap: 10px; font-weight: 700; color: #1D1D1D;">Prix
                        <input type="number" name="prix" value="{{ old('prix', $event->prix) }}" min="0" step="0.01"
                            style="width: 100%; border: 1px solid #E5E5E5; border-radius: 18px; padding: 18px 20px; font-size: 1rem;">
                    </label>
                </div>

                <div style="display: flex; flex-wrap: wrap; gap: 16px; justify-content: space-between; align-items: center;">
                    <label style="display: inline-flex; align-items: center; gap: 12px; font-weight: 700; color: #1D1D1D;">
                        <input type="checkbox" name="is_featured" value="1" @checked(old('is_featured', $event->is_featured)) style="width: 18px; height: 18px;">
                        Mise en avant de l'événement
                    </label>
                    <div style="display: flex; gap: 14px; flex-wrap: wrap;">
                        <button type="submit" name="statut" value="brouillon" style="background: #F5F5F5; color: #333; border: 1px solid #E5E5E5; border-radius: 999px; padding: 14px 24px; font-weight: 700; cursor: pointer;">Enregistrer un brouillon</button>
                        <button type="submit" name="statut" value="publie" style="background: #E8192C; color: white; border: none; border-radius: 999px; padding: 14px 24px; font-weight: 700; cursor: pointer;">Publier</button>
                    </div>
                </div>
            </form>
        </div>

        <aside style="background: #FFFFFF; border-radius: 28px; padding: 32px; box-shadow: 0 24px 80px rgba(0,0,0,0.06); display: grid; gap: 24px; align-content: start;">
            <div style="background: #F7F2F2; border-radius: 20px; min-height: 280px; display: flex; align-items: center; justify-content: center; color: #A19C9C; font-weight: 700; font-size: 1rem; text-align: center;">
                Template de la page de modification d'événement
            </div>

            <div style="background: #F8E8E8; border-radius: 24px; padding: 24px;">
                <h2 style="font-size: 1rem; font-weight: 800; color: #1F1F1F; margin-bottom: 16px;">Option de mise en avant</h2>
                <div style="display: grid; gap: 14px;">
                    <div style="display: flex; gap: 12px; align-items: flex-start; color: #333;">
                        <span style="width: 18px; height: 18px; border: 2px solid #333; border-radius: 4px;"></span>
                        Mise en avant (2000/jour)
                    </div>
                    <div style="display: flex; gap: 12px; align-items: flex-start; color: #333;">
                        <span style="width: 18px; height: 18px; border: 2px solid #333; border-radius: 4px;"></span>
                        Newsletter (5000/jour)
                    </div>
                    <div style="display: flex; gap: 12px; align-items: flex-start; color: #333;">
                        <span style="width: 18px; height: 18px; border: 2px solid #333; border-radius: 4px;"></span>
                        Réseaux sociaux (3000/jour)
                    </div>
                </div>
            </div>
        </aside>
    </div>
</div>
@endsection
