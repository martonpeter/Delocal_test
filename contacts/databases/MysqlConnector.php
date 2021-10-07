<?php

/**
 * Mysql connector
 * 
 * singleton
 */
class MysqlConnector {

    private static $INSTANCE;
    private $config;
    private $conn;
    public $result;
    public $id;
    public $error;
    
    /**
     * Biztosítja, hogy ne lehessen a new kulcsszóval létrehozni példányt
     */
    private function __construct() {

        $this->config = getConfig('mysgl');
        $this->conn = mysqli_connect(
                $this->config['hostname'],
                $this->config['username'],
                $this->config['password'],
                $this->config['database']
        );
        if (!$this->conn) {
            logMessage('ERROR', 'Kapcsolódási hiba!');
            die('Kapcsolódási hiba!');
        }
        mysqli_set_charset($this->conn, $this->config['charset']);
    }
    
    /**
     * Egy időben csak egy kapcsolat lehet
     * 
     * @return type MysqlConnector
     */
    public static function getInstance() {
        if (self::$INSTANCE == null) {
            self::$INSTANCE = new self();
        }
        return self::$INSTANCE;
    }
    
    /**
     * getter
     * 
     * @return type mysqli_connect
     */
    public function getConnection() {

        return $this->conn;
    }

    /**
     * Végrehajtja a lekérdezést, logolja fáljba a hibát.
     * logfájl: backendTest.log
     * 
     * @param string $sql
     * @return boolean
     */
    public function query(string $sql) {

        $this->result = mysqli_query($this->conn, $sql);

        if ($this->result) {

            $this->id = mysqli_insert_id($this->conn);

            return true;
        }

        $this->error = mysqli_error($this->conn);
        logMessage('ERROR', mysqli_error($this->conn));

        return false;
    }

}
