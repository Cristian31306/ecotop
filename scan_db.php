<?php
$data = file_get_contents('vps_database.sqlite');
// Extract printable strings of length >= 10
preg_match_all('/[a-zA-Z0-9\.\_\@\-\: ]{10,}/', $data, $matches);

$emails = [
    'cinthiamarcela2509@hotmail.com',
    '1096mpgr@gmail.com'
];

foreach ($emails as $email) {
    echo "--- Searching for $email ---\n";
    foreach ($matches[0] as $i => $match) {
        if (strpos($match, $email) !== false) {
            echo "Found context around: \n";
            for ($j = max(0, $i - 5); $j <= min(count($matches[0]) - 1, $i + 5); $j++) {
                echo "  " . $matches[0][$j] . "\n";
            }
            echo "------------------------\n";
        }
    }
}
