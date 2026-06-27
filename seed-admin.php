<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\Hash;

$admin = User::where('email', 'admin@eledji.com')->first();

if (!$admin) {
    User::create([
        'name' => 'Super Admin',
        'email' => 'admin@eledji.com',
        'password' => Hash::make('Admin@2026!'),
        'role' => 'admin',
        'is_blocked' => false,
    ]);
    echo "Admin créé avec succès!\n";
} else {
    echo "L'admin existe déjà.\n";
}

echo "Email: admin@eledji.com\n";
echo "Mot de passe: Admin@2026!\n";