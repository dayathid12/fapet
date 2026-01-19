<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';

$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Spatie\Permission\Models\Role;

$roleName = 'Pengemudi';

$role = Role::where('name', $roleName)->first();

if ($role) {
    echo "Role: {$role->name}\n";
    echo "Permissions: " . implode(', ', $role->getPermissionNames()->toArray()) . "\n";
} else {
    echo "Role with name '{$roleName}' not found.\n";
}

?>