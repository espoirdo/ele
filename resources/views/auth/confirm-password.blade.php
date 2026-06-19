@extends('layouts.app')

@section('title', 'Confirmer le mot de passe')

@section('content')
<div style="min-height: 70vh; display: flex; align-items: center; justify-content: center; padding: 120px 24px 48px;">
    <div style="width: 100%; max-width: 440px; background: #FFFFFF; border-radius: 16px; box-shadow: 0 4px 24px rgba(0,0,0,0.08); padding: 40px;">

        <h1 style="font-family: 'Eras Medium ITC', serif; font-weight: 700; font-size: 22px; color: #000000; text-align: center; margin-bottom: 12px;">
            Zone sécurisée
        </h1>

        <p style="font-family: 'Poppins', sans-serif; font-size: 14px; color: #666666; text-align: center; line-height: 1.6; margin-bottom: 28px;">
            Veuillez confirmer votre mot de passe avant de continuer.
        </p>

        <form method="POST" action="{{ route('password.confirm') }}">
            @csrf

            <label style="display: block; font-family: 'Poppins', sans-serif; font-size: 13px; font-weight: 500; color: #444444; margin-bottom: 6px;">
                Mot de passe
            </label>
            <input type="password" name="password" required autofocus
                   style="width: 100%; border: 1.5px solid #E0E0E0; border-radius: 8px; padding: 12px 16px; font-family: 'Poppins', sans-serif; font-size: 14px; outline: none; -webkit-tap-highlight-color: transparent;"
                   onfocus="this.style.borderColor='#CC0000'; this.style.boxShadow='0 0 0 3px rgba(204,0,0,0.08)';"
                   onblur="this.style.borderColor='#E0E0E0'; this.style.boxShadow='none';">

            @error('password')
                <p style="color: #CC0000; font-family: 'Poppins', sans-serif; font-size: 12px; margin-top: 6px;">{{ $message }}</p>
            @enderror

            <button type="submit"
                    style="width: 100%; margin-top: 24px; background: linear-gradient(to right, #CC0000, #910000); color: #FFFFFF; border: none; border-radius: 40px; padding: 14px; font-family: 'Poppins', sans-serif; font-weight: 600; font-size: 15px; cursor: pointer; outline: none; -webkit-tap-highlight-color: transparent; transition: all 0.25s ease;">
                Confirmer
            </button>
        </form>

    </div>
</div>
@endsection
