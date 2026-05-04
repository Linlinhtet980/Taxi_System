<?php
$dirs = glob(__DIR__ . '/app/Http/Controllers/{Admin,Customer,Driver}/*.php', GLOB_BRACE);
foreach($dirs as $f) {
    $c = file_get_contents($f);
    if(strpos($c, 'use App\Http\Controllers\Controller;') === false) {
        $c = preg_replace('/namespace (.*?);\s*/', "namespace $1;\n\nuse App\Http\Controllers\Controller;\n", $c);
        file_put_contents($f, $c);
        echo "Fixed $f\n";
    }
}
