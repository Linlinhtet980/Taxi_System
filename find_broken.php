<?php
$dir = new RecursiveIteratorIterator(new RecursiveDirectoryIterator(__DIR__ . '/public/css'));

$brokenClasses = [];

foreach ($dir as $file) {
    if ($file->isDir() || $file->getExtension() !== 'css') continue;
    
    $content = file_get_contents($file->getPathname());
    
    // Find all CSS rules
    // .class-name { ... }
    preg_match_all('/\.([a-zA-Z0-9\-_]+)\s*\{([^}]*\{[^}]*\}[^}]*|[^}]*)\}/s', $content, $matches, PREG_SET_ORDER);
    
    foreach ($matches as $match) {
        $className = $match[1];
        $ruleBody = $match[2];
        
        if (strpos($ruleBody, '$') !== false || strpos($ruleBody, '@if') !== false || strpos($ruleBody, '@foreach') !== false) {
            $brokenClasses[$className] = [
                'file' => $file->getPathname(),
                'body' => trim($ruleBody)
            ];
        }
    }
}

echo "Found " . count($brokenClasses) . " broken classes with Blade logic.\n";
foreach ($brokenClasses as $className => $data) {
    echo ".$className\n";
}
