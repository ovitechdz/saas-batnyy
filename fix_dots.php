<?php
$path = 'lang/ar.json';
$json = json_decode(file_get_contents($path), true);
$json['Search animals, RFID, invoices...'] = 'البحث عن الحيوانات، RFID، الفواتير';
file_put_contents($path, json_encode($json, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
echo "Done";
