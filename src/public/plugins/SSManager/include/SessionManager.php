<?php
/*
*  @copyright Copyright @ 2022 - 2023 Diego Garcia
*/

class SessionManager
{

    private array $cfg;
    private Database $db;
    private $user = [];
    private array $prefs = [];

    public function __construct(array $cfg, Database $db)
    {
        $this->db = $db;
        $this->cfg = $cfg;

        session_start();

        if (isset($_SESSION['uid']) && $_SESSION['uid'] > 0) {
            $this->user = $this->getProfile($_SESSION['uid']);
            if (empty($this->user['sid']) || $this->user['sid'] != session_id()) {
                $this->user = [];
                $this->user['id'] = -1;
            }
        } else if (!empty($_COOKIE['uid']) && !empty($_COOKIE['sid'])) {
            $this->user = $this->getProfile($_COOKIE['uid']);
            if (!empty($this->user['sid']) && $this->user['sid'] == $_COOKIE['sid']) {
                $_SESSION['uid'] = $_COOKIE['uid'];
                $this->updateSessionId();
            } else {
                $this->user = [];
                $this->user['id'] = -1;
            }
        } else {
            $this->user = [];
            $this->user['id'] = -1;
        }
        empty($this->user['lang']) ? $this->user['lang'] = $this->cfg['lang'] : null;
        empty($this->user['theme']) ? $this->user['theme'] = $this->cfg['theme'] : null;

        $this->user['id'] > 0 ? $this->loadPrefs() : null;
    }

    public function getId()
    {
        return $this->user['id'];
    }

    public function getUser()
    {
        return $this->user;
    }

    public function getLang()
    {
        return $this->user['lang'];
    }

    public function getTheme()
    {
        return $this->user['theme'];
    }

    public function getEmail()
    {
        return $this->user['email'] ? $this->user['email'] : false;
    }

    public function getUsername()
    {
        return $this->user['username'] ? $this->user['username'] : false;
    }

    public function getPassword()
    {
        return $this->user['password'] ? $this->user['password'] : false;
    }

    public function isAdmin()
    {
        return empty($this->user['isAdmin']) ? false : true;
    }

    public function getProfiles()
    {
        $results = $this->db->select('users');

        return $this->db->fetchAll($results);
    }

    public function getProfile(int $uid)
    {
        $result = $this->db->select('users', '*', ['id' => ['value' => $uid]], 'LIMIT 1');
        $user = $this->db->fetch($result);

        return $user ? $user : false;
    }

    public function checkUser(string $username, string $password)
    {

        $result = $this->db->select('users', '*', ['username' => ['value' => $username]], 'LIMIT 1');

        $user_check = $this->db->fetch($result);

        if (empty($user_check) || empty($user_check['id'])) {
            return false;
        }
        !empty($password) ? $password_hashed = $this->encryptPassword($password) : $password_hashed = '';

        if (($user_check['password'] == $password_hashed)) {

            return $user_check['id'];
        }

        return false;
    }

    public function setUser(int $user_id)
    {
        $_SESSION['uid'] = $user_id;
        $this->user = $this->getProfile($user_id);
        $this->updateSessionId();

        return true;
    }

    private function updateSessionId()
    {
        if (defined('PHP_VERSION_ID') && PHP_VERSION_ID >= 70300) {
            setcookie('sid', session_id(), [
                'expires' => time() + $this->cfg['sid_expire'],
                'secure' => true,
                'samesite' => 'lax',
            ]);
            setcookie('uid', $this->getId(), [
                'expires' => time() + $this->cfg['sid_expire'],
                'secure' => true,
                'samesite' => 'lax',
            ]);
        } else {
            setcookie("sid", session_id(), time() + $this->cfg['sid_expire'], $this->cfg['rel_path']);
            setcookie("uid", $this->getId(), time() + $this->cfg['sid_expire'], $this->cfg['rel_path']);
        }
        $new_sid = session_id();

        $this->db->update('users', ['sid' => $new_sid], ['id' => ['value' => $this->getId()]], 'LIMIT 1');
        $this->user['sid'] = $new_sid;
    }

    function encryptPassword(string $password)
    {
        return sha1($password);
    }

    function destroy()
    {
        session_destroy();
        setcookie('sid', null, -1, $this->cfg['rel_path']);
        setcookie('uid', null, -1, $this->cfg['rel_path']);
    }

    private function loadPrefs()
    {

        $prefs = [];
        $query = 'SELECT * FROM prefs WHERE uid = ' . $this->getId();
        $results = $this->db->query($query);

        $prefs = $this->db->fetchAll($results);
        if ($prefs && is_array($prefs)) {
            foreach ($prefs as $pref) {
                if (!empty($pref['pref_name']) && $pref['uid'] == 0) {
                    $this->prefs[$pref['pref_name']] = $pref['pref_value'];
                } else if (!empty($pref['pref_name'])) {
                    $this->prefs[$pref['pref_name']] = $pref['pref_value'];
                }
            }
        }
    }

    public function getPref(string $r_key)
    {
        return !empty($this->prefs[$r_key]) ? $this->prefs[$r_key] : false;
    }

    public function setPref(string $key, string $value)
    {

        if (isset($this->prefs[$key])) {
            if ($this->prefs[$key] !== $value) {
                $where['uid'] = ['value' => $this->getId()];
                $where['pref_name'] = ['value' => $key];
                $set['pref_value'] = $value;
                $this->db->update('prefs', $set, $where, 'LIMIT 1');
            }
        } else {
            $new_item = [
                'uid' => $this->getId(),
                'pref_name' => $key,
                'pref_value' => $value,
            ];
            $this->db->insert('prefs', $new_item);
        }
        $this->prefs[$key] = $value;
    }
}
