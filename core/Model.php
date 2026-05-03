<?php
class Model {
    protected $db;
    protected $table;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    public function find($id, $pk = 'id') {
        return $this->db->fetchOne("SELECT * FROM {$this->table} WHERE {$pk} = ?", [$id], 'i');
    }

    public function all($where = '', $params = [], $types = '', $order = '') {
        $sql = "SELECT * FROM {$this->table}";
        if ($where) $sql .= " WHERE $where";
        if ($order) $sql .= " ORDER BY $order";
        return $this->db->fetchAll($sql, $params, $types);
    }

    public function count($where = '', $params = [], $types = '') {
        $sql = "SELECT COUNT(*) as cnt FROM {$this->table}";
        if ($where) $sql .= " WHERE $where";
        $row = $this->db->fetchOne($sql, $params, $types);
        return $row['cnt'] ?? 0;
    }
}
