@extends('layouts.app')

@section('title', 'Réinitialiser le mot de passe')

@section('content')
<div style="min-height: 70vh; display: flex; align-items: center; justify-content: center; padding: 120px 24px 48px;">
    <div style="width: 100%; max-width: 440px; background: #FFFFFF; border-radius: 16px; box-shadow: 0 4px 24px rgba(0,0,0,0.08); padding: 40px;">

        <h1 style="font-family: 'Eras Medium ITC', serif; font-weight: 700; font-size: 22px; color: #000000; text-align: center; margin-bottom: 28px;">
            Réinitialiser le mot de passe
        </h1>

        <form method="POST" action="{{ route('password.store') }}">
            @csrf

            <input type="hidden" name="token" value="{{ request()->route('token') }}">

            <label style="display: block; font-family: 'Poppins', sans-serif; font-size: 13px; font-weight: 500; color: #444444; margin-bottom: 6px;">
                Adresse email
            </label>
            <input type="email" name="email" value="{{ old('email', request()->email) }}" required autofocus
                   style="width: 100%; border: 1.5px solid #E0E0E0; border-radius: 8px; padding: 12px 16px; font-family: 'Poppins', sans-serif; font-size: 14px; outline: none; margin-bottom: 18px; -webkit-tap-highlight-color: transparent;"
                   onfocus="this.style.borderColor='#CC0000'; this.style.boxShadow='0 0 0 3px rgba(204,0,0,0.08)';"
                   onblur="this.style.borderColor='#E0E0E0'; this.style.boxShadow='none';">
            @error('email')
                <p style="color: #CC0000; font-family: 'Poppins', sans-serif; font-size: 12px; margin-top: -12px; margin-bottom: 18px;">{{ $message }}</p>
            @enderror

            <label style="display: block; font-family: 'Poppins', sans-serif; font-size: 13px; font-weight: 500; color: #444444; margin-bottom: 6px;">
                Nouveau mot de passe
            </label>
            <input type="password" name="password" required
                   style="width: 100%; border: 1.5px solid #E0E0E0; border-radius: 8px; padding: 12px 16px; font-family: 'Poppins', sans-serif; font-size: 14px; outline: none; margin-bottom: 18px; -webkit-tap-highlight-color: transparent;"
                   onfocus="this.style.borderColor='#CC0000'; this.style.boxShadow='0 0 0 3px rgba(204,0,0,0.08)';"
                   onblur="this.style.borderColor='#E0E0E0'; this.style.boxShadow='none';">
            @error('password')
                <p style="color: #CC0000; font-family: 'Poppins', sans-serif; font-size: 12px; margin-top: -12px; margin-bottom: 18px;">{{ $message }}</p>
            @enderror

            <label style="display: block; font-family: 'Poppins', sans-serif; font-size: 13px; font-weight: 500; color: #444444; margin-bottom: 6px;">
                Confirmer le mot de passe
            </label>
            <input type="password" name="password_confirmation" required
                   style="width: 100%; border: 1.5px solid #E0E0E0; border-radius: 8px; padding: 12px 16px; font-family: 'Poppins', sans-serif; font-size: 14px; outline: none; -webkit-tap-highlight-color: transparent;"
                   onfocus="this.style.borderColor='#CC0000'; this.style.boxShadow='0 0 0 3px rgba(204,0,0,0.08)';"
                   onblur="this.style.borderColor='#E0E0E0'; this.style.boxShadow='none';">

            <button type="submit"
                    style="width: 100%; margin-top: 24px; background: linear-gradient(to right, #CC0000, #910000); color: #FFFFFF; border: none; border-radius: 40px; padding: 14px; font-family: 'Poppins', sans-serif; font-weight: 600; font-size: 15px; cursor: pointer; outline: none; -webkit-tap-highlight-color: transparent; transition: all 0.25s ease;">
                Réinitialiser le mot de passe
            </button>
        </form>

    </div>
</div>
@endsection
