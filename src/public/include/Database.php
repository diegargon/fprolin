<?php

class Database
{

    private string $dbpath = '';
    private SQLite3 $db;

    public function __construct(array $cfg)
    {
        $this->dbpath = $cfg['sqlite_db'];
    }

    public function connect()
    {

        if (!is_writable(dirname($this->dbpath))) {
            echo dirname($this->dbpath) . ' directory must be writable<br/>';
            exit(1);
        }
        try {
            $this->db = new SQLite3($this->dbpath, SQLITE3_OPEN_CREATE | SQLITE3_OPEN_READWRITE);
        } catch (Exception $e) {
            print('Error<br>' . $e);
            exit(1);
        }

        $this->db->busyTimeout(5000);
        //$this->db->enableExceptions(true);
        $this->db->exec('PRAGMA journal_mode = wal;');
    }

    /*

    */

    public function count(string $table)
    {
        $result = $this->query('SELECT COUNT (*) FROM ' . $table);
        $row = $result->fetchArray();

        return $row[0];
    }

    public function insert(string $table, array $values)
    {
        $query = 'INSERT INTO "' . $table . '" (';
        $query_binds = '';
        $query_keys = '';
        $bind_values = [];

        foreach ($values as $key => $value) {
            $query_keys .= '"' . $key . '"';
            $query_binds .= ':' . $key;
            $bind_values[':' . $key] = $this->escape($value);

            if ($key != array_key_last($values)) {
                $query_keys .= ', ';
                $query_binds .= ', ';
            }
        }
        $query .= $query_keys . ') VALUES (' . $query_binds;
        $query .= ')';
        $this->querys[]['query'] = $query;

        $st = $this->db->prepare($query);

        $last_ary_id = array_key_last($this->querys);
        $this->querys[$last_ary_id]['bind'] = print_r($bind_values, true);
        foreach ($bind_values as $bkey => $bvalue) {
            $st->bindValue($bkey, $bvalue);
        }

        $response = $st->execute();

        return $response;
    }

    /*
      $where['userid'] = [ 'value' => diego, 'op' => '=', 'logic' => 'AND' ];
     */

    public function select(string $table, string $what = null, array $where = null, string $extra = null)
    {

        $bind_values = [];

        $query = 'SELECT ';

        if (!empty($what) && $what != "*") {
            $what_string = '';
            foreach (explode(',', $what) as $what_ary) {
                empty($what_string) ? $what_string .= '"' . $what_ary . '"' : $what_string .= ',"' . $what_ary . '"';
            }
            $query .= $what_string;
        } else {
            $query .= '*';
        }

        $query .= ' FROM "' . $table . '"';
        if ($where != null) {
            $query .= ' WHERE ';
            foreach ($where as $where_k => $where_v) {
                !empty($where_v['op']) ? $operator = $where_v['op'] : $operator = '=';
                !empty($where_v['logic']) ? $logic = $where_v['logic'] : $logic = 'AND';

                $query .= ' "' . $where_k . '" ' . $operator . ' :' . $where_k;
                if ($where_k != array_key_last($where)) {
                    $query .= ' ' . $logic . ' ';
                }
                $bind_values[':' . $where_k] = $this->escape($where_v['value']);
            }
        }
        !empty($extra) ? $query .= ' ' . $extra : null;
        $this->querys[]['query'] = $query;

        $st = $this->db->prepare($query);



        if (!empty($bind_values)) {
            $last_ary_id = array_key_last($this->querys);
            $this->querys[$last_ary_id]['bind'] = print_r($bind_values, true);
            foreach ($bind_values as $bkey => $bvalue) {
                $st->bindValue($bkey, $bvalue);
            }
        }

        $response = $st->execute();

        return $response;
    }

    /* values can be array or comma separate */

    public function selectMultiple(string $table, $field, $values, $what = null)
    {
        !isset($what) ? $what = '*' : null;
        if (!is_array($values)) {
            $final_values = array_map('trim', explode(',', $values));
        } else {
            $final_values = $values;
        }

        $prep_values = '';
        foreach ($final_values as $final_value) {
            empty($prep_values) ? $prep_values = '\'' . trim($final_value) . '\'' : $prep_values .= ',\'' . $this->escape(trim($final_value)) . '\'';
        }
        $query = 'SELECT ' . $what . ' FROM ' . $table . ' WHERE ' . $field . ' IN(' . $prep_values . ')';

        $result = $this->query($query);
        $rows = $this->fetchAll($result);

        return $rows;
    }

    public function delete(string $table, array $where = null, string $extra = null)
    {

        $query = 'DELETE FROM ' . $table . ' ';

        if ($where != null) {
            $query .= ' WHERE ';
            foreach ($where as $where_k => $where_v) {
                !empty($where_v['op']) ? $operator = $where_v['op'] : $operator = '=';
                !empty($where_v['logic']) ? $logic = $where_v['logic'] : $logic = 'AND';

                $query .= ' "' . $where_k . '" ' . $operator . ' :' . $where_k;
                if ($where_k != array_key_last($where)) {
                    $query .= ' ' . $logic . ' ';
                }
                $bind_values[':' . $where_k] = $where_v['value'];
            }
        }
        !empty($extra) ? $query .= ' ' . $extra : null;
        $this->querys[]['query'] = $query;
        $st = $this->db->prepare($query);

        if (!empty($bind_values)) {
            $last_ary_id = array_key_last($this->querys);
            $this->querys[$last_ary_id]['bind'] = print_r($bind_values, true);
            foreach ($bind_values as $bkey => $bvalue) {
                $st->bindValue($bkey, $bvalue);
            }
        }

        $response = $st->execute();

        return $response;
    }

    /*
      $set['username'] = 'diego';
      $where['userid'] = [ 'value' => diego, 'op' => '=', 'logic' => 'AND' ];
     */

    public function update(string $table, array $set, array $where = null, string $extra = null)
    {
        $bind_values = [];

        $query = 'UPDATE ' . $table;

        $query .= ' SET ';
        foreach ($set as $set_k => $set_v) {
            $query .= ' "' . $set_k . '" = ' . ':' . $set_k;
            ($set_k != array_key_last($set)) ? $query .= ', ' : null;
            $bind_values[':' . $set_k] = $set_v;
        }

        if ($where != null) {
            $query .= ' WHERE ';
            foreach ($where as $where_k => $where_v) {
                !empty($where_v['op']) ? $operator = $where_v['op'] : $operator = '=';
                !empty($where_v['logic']) ? $logic = $where_v['logic'] : $logic = 'AND';

                $query .= ' "' . $where_k . '" ' . $operator . ' :' . $where_k;
                if ($where_k != array_key_last($where)) {
                    $query .= ' ' . $logic . ' ';
                }
                $bind_values[':' . $where_k] = $this->escape($where_v['value']);
            }
        }
        !empty($extra) ? $query .= ' ' . $extra : null;
        $this->querys[]['query'] = $query;

        $st = $this->db->prepare($query);

        if (!empty($bind_values)) {
            $last_ary_id = array_key_last($this->querys);
            $this->querys[$last_ary_id]['bind'] = print_r($bind_values, true);
            foreach ($bind_values as $bkey => $bvalue) {
                $st->bindValue($bkey, $bvalue);
            }
        }

        $response = $st->execute();

        return $response;
    }

    public function escape($string)
    {
        if ($string === null) {
            return null;
        } else if ($string === '') {
            return '';
        }
        return $this->db->escapeString($string);
    }

    public function fetch($result)
    {
        return $result->fetchArray(SQLITE3_ASSOC);
    }

    public function fetchAll($result)
    {
        $rows = [];
        while ($row = $this->fetch($result)) {
            $rows[] = $row;
        }

        $this->finalize($result);
        return $rows;
    }

    public function getLastId()
    {
        return $this->db->lastInsertRowID();
    }

    public function finalize($results)
    {
        $results->finalize();
    }

    public function free($results)
    {
        $results->finalize();
    }

    public function getDbVersion()
    {
        global $cfg;

        return !empty($cfg['db_version']) ? $cfg['db_version'] : false;
    }

    public function getQuerys()
    {
        return $this->querys;
    }

    public function qSingle($query)
    {
        $this->querys[]['query'] = $query;
        return $this->db->querySingle($query);
    }

    public function query($query)
    {
        $this->querys[]['query'] = $query;
        return $this->db->query($query);
    }

    public function close()
    {
        $this->db->close();
        unset($this->db);
    }
}
