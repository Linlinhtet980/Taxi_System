<?php

$replacements = [
    // Models
    'App\Models\User' => 'App\Models\Auth\User',
    'App\Models\Customer' => 'App\Models\Auth\Customer',
    'App\Models\driver' => 'App\Models\Auth\Driver', // Handle lowercase driver case
    'App\Models\Driver' => 'App\Models\Auth\Driver',
    'App\Models\Booking' => 'App\Models\Core\Booking',
    'App\Models\Vehicle' => 'App\Models\Core\Vehicle',
    'App\Models\Transaction' => 'App\Models\Core\Transaction',
    'App\Models\Withdrawal' => 'App\Models\Core\Withdrawal',
    'App\Models\Notification' => 'App\Models\Core\Notification',
    'App\Models\Message' => 'App\Models\Core\Message',
    'App\Models\OnlineLog' => 'App\Models\Core\OnlineLog',
    'App\Models\Setting' => 'App\Models\Core\Setting',
    
    // Admin Controllers
    'App\Http\Controllers\AnalyticsController' => 'App\Http\Controllers\Admin\AnalyticsController',
    'App\Http\Controllers\BookingController' => 'App\Http\Controllers\Admin\BookingController',
    'App\Http\Controllers\CustomerController' => 'App\Http\Controllers\Admin\CustomerController',
    'App\Http\Controllers\DashboardController' => 'App\Http\Controllers\Admin\DashboardController',
    'App\Http\Controllers\DriverController' => 'App\Http\Controllers\Admin\DriverController',
    'App\Http\Controllers\NotificationController' => 'App\Http\Controllers\Admin\NotificationController',
    'App\Http\Controllers\ReportController' => 'App\Http\Controllers\Admin\ReportController',
    'App\Http\Controllers\SettingController' => 'App\Http\Controllers\Admin\SettingController',
    'App\Http\Controllers\TransactionController' => 'App\Http\Controllers\Admin\TransactionController',
    'App\Http\Controllers\VehicleController' => 'App\Http\Controllers\Admin\VehicleController',
    'App\Http\Controllers\WithdrawalController' => 'App\Http\Controllers\Admin\WithdrawalController',
    
    // Customer Controllers
    'App\Http\Controllers\CustomerBookingController' => 'App\Http\Controllers\Customer\CustomerBookingController',
    
    // Driver Controllers
    'App\Http\Controllers\DriverPortalController' => 'App\Http\Controllers\Driver\DriverPortalController',
];

$directories = [
    __DIR__ . '/app',
    __DIR__ . '/config',
    __DIR__ . '/routes',
    __DIR__ . '/resources/views',
    __DIR__ . '/database',
];

function processDirectory($dir, $replacements) {
    $iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dir));
    foreach ($iterator as $file) {
        if ($file->isFile() && $file->getExtension() === 'php') {
            $path = $file->getPathname();
            $content = file_get_contents($path);
            $originalContent = $content;

            // 1. Update Namespaces in Models and Controllers
            if (strpos($path, 'app\Models\Auth') !== false || strpos($path, 'app/Models/Auth') !== false) {
                $content = str_replace('namespace App\Models;', 'namespace App\Models\Auth;', $content);
            } elseif (strpos($path, 'app\Models\Core') !== false || strpos($path, 'app/Models/Core') !== false) {
                $content = str_replace('namespace App\Models;', 'namespace App\Models\Core;', $content);
            } elseif (strpos($path, 'app\Http\Controllers\Admin') !== false || strpos($path, 'app/Http/Controllers/Admin') !== false) {
                $content = str_replace('namespace App\Http\Controllers;', 'namespace App\Http\Controllers\Admin;', $content);
            } elseif (strpos($path, 'app\Http\Controllers\Customer') !== false || strpos($path, 'app/Http/Controllers/Customer') !== false) {
                $content = str_replace('namespace App\Http\Controllers;', 'namespace App\Http\Controllers\Customer;', $content);
            } elseif (strpos($path, 'app\Http\Controllers\Driver') !== false || strpos($path, 'app/Http/Controllers/Driver') !== false) {
                $content = str_replace('namespace App\Http\Controllers;', 'namespace App\Http\Controllers\Driver;', $content);
            }

            // 2. Perform general find & replace for classes
            foreach ($replacements as $old => $new) {
                // We must be careful not to replace `App\Models\Driver` inside `App\Models\Auth\Driver` (already replaced!)
                // If it already has Auth\ or Core\, don't replace.
                // Best way: string replace, but ensure we don't double replace.
                
                // Add slash escaping for strings
                $oldSlashes = addslashes($old);
                $newSlashes = addslashes($new);
                
                // standard replacement
                $content = preg_replace("/(?<!Auth\\\\)(?<!Core\\\\)(?<!Admin\\\\)(?<!Customer\\\\)(?<!Driver\\\\)" . preg_quote($old, '/') . "\\b/", $new, $content);
                
                // string replacement (e.g. in config/auth.php "App\\Models\\Customer")
                $content = preg_replace("/(?<!Auth\\\\\\\\)(?<!Core\\\\\\\\)(?<!Admin\\\\\\\\)(?<!Customer\\\\\\\\)(?<!Driver\\\\\\\\)" . preg_quote($oldSlashes, '/') . "\\b/", $newSlashes, $content);
            }

            if ($content !== $originalContent) {
                file_put_contents($path, $content);
                echo "Updated: $path\n";
            }
        }
    }
}

foreach ($directories as $dir) {
    if (is_dir($dir)) {
        processDirectory($dir, $replacements);
    }
}

echo "Refactoring complete.\n";
