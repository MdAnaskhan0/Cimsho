<?php
abstract class Model {
    protected PDO $db;
    public function __construct() { $this->db = Database::getInstance(); }
    protected function query(string $sql, array $p = []): PDOStatement { $s=$this->db->prepare($sql);$s->execute($p);return $s; }
    protected function fetchOne(string $sql, array $p = []): ?array { $r=$this->query($sql,$p)->fetch(); return $r?:null; }
    protected function fetchAll(string $sql, array $p = []): array { return $this->query($sql,$p)->fetchAll(); }
    protected function execute(string $sql, array $p = []): bool { return $this->query($sql,$p)->rowCount()>0; }
    protected function lastId(): string { return $this->db->lastInsertId(); }
}
