<?php
/*
*  @copyright Copyright @ 2022 - 2023 Diego Garcia
*/

chdir(__DIR__);

require_once('../src/public/config/config.priv.php');
require_once('../src/public/include/Database.php');
$cfg['sqlite_db'] = $cfg['appname'] . '.db';
if (file_exists($cfg['sqlite_db'])) {
    unlink($cfg['sqlite_db']);
}

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

$db->insert('users', ['username' => 'admin', 'password' => 'd033e22ae348aeb5660fc2140aec35850c4da997', 'isAdmin' => 1]);
#$db->insert('users', ['username' => 'admin', 'password' => '', 'isAdmin' => 1]);

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
$db->query('CREATE TABLE "prefs" (
    "id" int NOT NULL,
    "uid" int NOT NULL,
    "pref_name" char(255) NOT NULL,
    "pref_value" char(255) NOT NULL
  )');

// MODULES


$db->query('CREATE TABLE IF NOT EXISTS "plugins" (
    "id" INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
    "name" VARCHAR NOT NULL,
    "provide" VARCHAR DEFAULT NULL,
    "enable" INTEGER DEFAULT 0,
    "missing" INTEGER DEFAULT 0,
    "priority" INTEGER DEFAULT 5,
    "version" VARCHAR NOT NULL,

    "created" TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL
    )');

$db->insert('plugins', ['name' => 'fprolin', 'provide' => 'main', 'enable' => 1, 'priority' => 2, 'version' => 0.1]);
$db->insert('plugins', ['name' => 'netconn', 'provide' => 'netconn', 'enable' => 1, 'priority' => 3, 'version' => 0.1]);
$db->insert('plugins', ['name' => 'netrouting', 'provide' => 'netrouting', 'enable' => 1, 'priority' => 3, 'version' => 0.1]);
$db->insert('plugins', ['name' => 'SSManager', 'provide' => 'SessionManager', 'enable' => 1, 'priority' => 1, 'version' => 0.1]);
$db->insert('plugins', ['name' => 'dashboard', 'enable' => 0, 'version' => 0.1]);
$db->insert('plugins', ['name' => 'Admin', 'provide' => "Administration", 'enable' => 0, 'version' => 0.1]);
$db->close();

copy($cfg['sqlite_db'], '../src/public/data/' . $cfg['sqlite_db']);

chown('../src/public/data/'. $cfg['sqlite_db'], 'www-data');
chgrp('../src/public/data/'.$cfg['sqlite_db'], 'www-data');
