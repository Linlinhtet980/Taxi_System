<?php
$files = [];
$dir = new RecursiveIteratorIterator(new RecursiveDirectoryIterator(__DIR__ . '/resources/views'));
foreach ($dir as $file) {
    if ($file->isFile() && $file->getExtension() === 'php') {
        $content = file_get_contents($file->getPathname());
        if (preg_match('/title="[^"]*"/', $content) || preg_match("/title='[^']*'/", $content)) {
            $files[] = $file->getPathname();
        }
    }
}
echo "Found " . count($files) . " files with title attributes:\n";
foreach ($files as $f) {
    echo $f . "\n";
}
