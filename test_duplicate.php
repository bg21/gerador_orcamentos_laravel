<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

try {
    $quote = App\Models\Quote::first();
    if (!$quote) {
        echo "No quotes found.\n";
        exit;
    }
    
    echo "Found quote ID: " . $quote->id . "\n";
    $quote->load('items');

    $newQuote = $quote->replicate();
    $newQuote->quote_number = 'TEST-' . rand(1000, 9999);
    $newQuote->status = 'draft';
    $newQuote->issue_date = now()->format('Y-m-d');
    $newQuote->due_date = null;
    $newQuote->save();

    echo "Duplicated to new ID: " . $newQuote->id . "\n";
} catch (\Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
}
