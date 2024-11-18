<?php

ob_clean();

$jsonData = json_encode($users);

header('Content-Type: application/json');
header('Content-Disposition: attachment; filename="data.json"');

echo $jsonData;
exit;
