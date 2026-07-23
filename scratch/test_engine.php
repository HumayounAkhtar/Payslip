<?php
define('LARAVEL_START', microtime(true));
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

// Test Authentication
echo "=== Testing Authentication ===\n";
use App\Models\User;
use Illuminate\Support\Facades\Auth;

$user = User::where('email', 'admin@mockengine.com')->first();
if ($user) {
    echo "Admin User Found: " . $user->name . " (" . $user->email . ")\n";
    $authCheck = Auth::attempt(['email' => 'admin@mockengine.com', 'password' => 'admin123']);
    echo "Auth credentials attempt: " . ($authCheck ? "SUCCESS" : "FAILED") . "\n";
} else {
    echo "Error: Seeded Admin User not found in database.\n";
}

// Test Receipt Generation Controller
echo "\n=== Testing Receipt Generation Engine ===\n";
try {
    $request = new \Illuminate\Http\Request([
        'device_time' => '15:57',
        'battery_status' => 'full',
        'signal_status' => '3-bars',
        'net_amount' => '-178.5',
        'net_asset' => 'USDT',
        'network' => 'TRX',
        'address' => "T9z4hQQte8K9Mvy4eFtDvU3TxzseryEDy",
        'txid' => "cf28915cb8a90c8bc6559610907fe3e0fc7de757448eef104041917de3797fa9",
        'amount' => '180',
        'amount_asset' => 'USDT',
        'network_fee' => '1.5',
        'fee_asset' => 'USDT',
        'withdrawal_wallet' => 'Spot Account',
        'date' => '2026-07-11 18:49:04'
    ]);
    
    $controller = new \App\Http\Controllers\ReceiptController();
    $response = $controller->generateReceipt($request);
    
    if ($response->getStatusCode() === 200) {
        $binary = $response->getContent();
        $size = strlen($binary);
        echo "Receipt generation status: SUCCESS\n";
        echo "Output size: " . $size . " bytes\n";
        
        // Save file to artifacts directory so we can view it
        $artifactPath = "C:\\Users\\Hamanyoon _AI Engine\\.gemini\\antigravity\\brain\\72748315-8795-41a6-a3c4-e64bcf5dad13\\test_output.png";
        file_put_contents($artifactPath, $binary);
        echo "Saved compiled PNG to artifacts: " . $artifactPath . "\n";
    } else {
        echo "Receipt generation status: FAILED (HTTP " . $response->getStatusCode() . ")\n";
    }
} catch (\Exception $e) {
    echo "Exception occurred: " . $e->getMessage() . "\n";
    echo $e->getTraceAsString() . "\n";
}
