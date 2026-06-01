<?php
$data = file_get_contents('vps_database.sqlite');
// Simple substring count for each email
$emails = [
    '1096mpgr@gmail.com' => 'Maria Paz',
    'danieltolosa.1007@gmail.com' => 'Daniel Toloza',
    'dylan.pipe@gmail.com' => 'Dylan Moreno',
    'raaf452@gmail.com' => 'Rafael Alejandro',
    'paulaoviedo2000@gmail.com' => 'Paula Oviedo'
];

foreach ($emails as $email => $name) {
    $count = substr_count($data, $email);
    echo str_pad($name, 20) . " ($email): $count occurrences\n";
}
