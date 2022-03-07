<?php
/*
*  @copyright Copyright @ 2022 - 2023 Diego Garcia
*/

chdir(__DIR__);

require_once('../src/public/config/config.priv.php');
require_once('../src/public/include/Database.php');
$cfg['sqlite_db'] = $cfg['appname'] . '.db';
unlink($cfg['sqlite_db']);

$db = new Database($cfg);
$db->connect();

// USERS
$db->query('CREATE TABLE IF NOT EXISTS "users" (
                    "id" INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
                    "username" VARCHAR NOT NULL UNIQUE,
                    "password" VARCHAR NULL,
                    "sid" VARCHAR NULL,
                    "sid_expire" INTEGER DEFAULT 0,
                    "isAdmin" INTEGER DEFAULT 0,
                    "email" VARCHAR NULL,
                    "ip" VARCHAR NULL,
                    "created" TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL
       )');

$db->insert('users', ["username" => "admin", "isAdmin" => 1]);

// CONFIG
// type: 1 string, 2 int, 3 bool, 4 reserve, 5 reserve 6 reserve  7 mixedarray, 8 stringarray, 9 intarray,
$db->query('CREATE TABLE IF NOT EXISTS "config" (
        "id" INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
        "cfg_key" VARCHAR NOT NULL,
        "cfg_value" VARCHAR NOT NULL,
        "cfg_perms" VARCHAR NULL,
        "cfg_desc" VARCHAR NULL,
        "type" VARCHAR NOT NULL,
        "category" VARCHAR NOT NULL,
        "public" INTEGER NULL,
        "weight" INTEGER DEFAULT 0,
        "modify" TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL,
        "created" TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL,
        UNIQUE (cfg_key)
    )');

/*
    PREFERENCES
*/
$db->query('CREATE TABLE `prefs` (
    `id` int NOT NULL,
    `uid` int NOT NULL,
    `pref_name` char(255) NOT NULL,
    `pref_value` char(255) NOT NULL
  )');
  
// MODULES


$db->query('CREATE TABLE IF NOT EXISTS "modules" (
    "id" INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
    "name" VARCHAR NOT NULL,
    "enable" INTEGER DEFAULT 0,
    "created" TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL
    )');

$db->insert('modules', ["name" => "test"]);

chown($cfg['sqlite_db'], 'www-data');
chgrp($cfg['sqlite_db'], 'www-data');
copy($cfg['sqlite_db'], '../src/public/data/' . $cfg['sqlite_db']);
