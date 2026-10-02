<?php
if (PHP_SAPI !== 'cli') exit;
require __DIR__ . '/vendor/codeigniter4/framework/system/Test/bootstrap.php';
$db = db_connect('default');
echo json_encode([
    'grupos' => $db->table('group_user')->where('id >=', 1000)->where('id <=', 1003)->get()->getResultArray(),
    'menus' => $db->table('menus')->select('id, parent_id, name, url')->where('id >=', 1000)->where('id <=', 1061)->orderBy('id')->get()->getResultArray(),
], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
