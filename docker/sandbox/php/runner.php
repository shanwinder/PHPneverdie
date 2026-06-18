<?php

declare(strict_types=1);

$codePath = $argv[1] ?? '/workspace/main.php';
if (!is_file($codePath)) {
    fwrite(STDERR, "main.php not found\n");
    exit(2);
}

require $codePath;
