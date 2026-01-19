<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';

$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

$email = 'ajat.sudrajat@unpad.ac.id';

$user = User::where('email', $email)->first();

if ($user) {
    echo "User: {$user->email}\n";
    echo "Roles: " . implode(', ', $user->getRoleNames()->toArray()) . "\n";
    echo "Permissions: " . implode(', ', $user->getPermissionNames()->toArray()) . "\n";
} else {
    echo "User with email '{$email}' not found.\n";
}

?>