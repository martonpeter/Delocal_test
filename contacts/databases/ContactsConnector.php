<?php
/**
 * Contact adatbázis műveletek
 * 
 */
class ContactsConnector {
    
    /**
     * MysqlConnector példány
     * 
     * @var MysqlConnector
     */
    private $mysqlConnector;
    
    /**
     * Mysql kapcsolat
     * 
     * @var mysqli_connect
     */
    private $conn;
    
    /**
     * PUT kérésnél a content
     * 
     * @var file_get_contents
     */
    private $myEntireBody;
    
    /**
     * PUT kérésnél a content
     * 
     * @var Object
     */
    private $myEntireBodyObject;
    
    /**
     * PUT vagy GET
     * 
     * @var type string
     */
    private $requestMethod;
    
    /**
     * A config.php contacts tömb elemei
     * 
     * @var type Array
     */
    private $config;
    
    /**
     * 
     * A contact rekord mezőit tartalazza
     * 
     * @var type Array
     */
    private $fieldList;
    
    /**
     * A visszaadandó válasz
     * 
     * @var type Arrax
     */
    public $resultArray = [];

    public function __construct() {

        $this->mysqlConnector = MysqlConnector::getInstance();
        $this->conn = $this->mysqlConnector->getConnection();
        $this->myEntireBody = file_get_contents('php://input');
        $this->myEntireBodyObject = json_decode($this->myEntireBody);
        $this->requestMethod = strtoupper(filter_input(INPUT_SERVER, 'REQUEST_METHOD'));
        $this->config = getConfig('contacts');
        $this->fieldList = $this->config['fieldList'];
    }
    
    /**
     * Fő eljárás, ezt kell meghívni
     * visszaadja az eredményt JSON formában
     * 
     * @return type JSON
     */
    public function process() {

        if ($this->requestMethod === 'PUT') {

            $this->escapeRequestField();

            if (isset($this->myEntireBodyObject->save)) {

                if ($this->processSave()) {

                    return json_encode($this->resultArray);
                }


                return json_encode(['error' => $this->mysqlConnector->error]);
            }

            if (isset($this->myEntireBodyObject->update)) {

                if ($this->processUpdate()) {

                    return json_encode($this->resultArray);
                }


                return json_encode(['error' => $this->mysqlConnector->error]);
            }
        }

        if ($this->requestMethod === 'GET') {

            $id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_SPECIAL_CHARS);
            $delete = filter_input(INPUT_GET, 'delete', FILTER_SANITIZE_SPECIAL_CHARS);

            if (!empty($id)) {

                $this->id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_SPECIAL_CHARS);

                if (!empty($delete)) {

                    if ($this->processDelete()) {

                        return json_encode($this->resultArray);
                    }

                    return json_encode(['error' => $this->mysqlConnector->error]);
                }

                if ($this->processContactById()) {

                    return json_encode($this->resultArray);
                }

                return json_encode(['error' => $this->mysqlConnector->error]);
            }

            if ($this->processGetAllContacts()) {

                return json_encode($this->resultArray);
            }

            return json_encode(['error' => $this->mysqlConnector->error]);
        }


        return $this->resultArray;
    }
    
    /**
     * Visszaadja az összes rekordot
     * 
     * @return boolean
     */
    private function processGetAllContacts() {

        $sql = "SELECT * FROM contacts";

        if ($this->mysqlConnector->query($sql)) {

            $result = $this->mysqlConnector->result;
            $this->resultArray = [];
            while ($row = mysqli_fetch_array($result)) {

                $this->resultArray[] = [
                    'id' => $row['id'],
                    'name' => $row['name'],
                    'email' => $row['email'],
                    'phone_number' => $row['phone_number'],
                    'address' => $row['address'],
                ];
            }

            return true;
        }

        return false;
    }
    
    /**
     * Visszaadja IS szerinti rekordot
     * 
     * @return boolean
     */
    private function processContactById() {

        $sql = "SELECT * FROM contacts WHERE id='$this->id'";

        if ($this->mysqlConnector->query($sql)) {

            $result = $this->mysqlConnector->result;

            while ($row = mysqli_fetch_array($result)) {

                $this->resultArray['id'] = $row['id'];
                $this->resultArray['name'] = $row['name'];
                $this->resultArray['email'] = $row['email'];
                $this->resultArray['phone_number'] = $row['phone_number'];
                $this->resultArray['address'] = $row['address'];
            }

            return true;
        }

        return false;
    }
    /**
     * Kitörli az ID szerinti rekordot
     * 
     * Ez a feladat kiírásában nem szerepelt, de így egyszerűbben
     * tesztelhető a felületről
     * 
     * @return boolean
     */
    private function processDelete() {

        $sql = "DELETE FROM contacts WHERE id=" . $this->id;

        if ($this->mysqlConnector->query($sql)) {

            return true;
        }

        return false;
    }
    
    /**
     * Az ID szerinti rekordon módosítja az emailcímet
     * A felületen csak ezt engedjük módosítani.
     * 
     * @return boolean
     */
    private function processUpdate() {

        $sql = sprintf("UPDATE contacts SET email = '%s' WHERE id = '%d'",
                $this->email,
                $this->id,
        );

        if ($this->mysqlConnector->query($sql)) {

            $this->resultArray['id'] = $this->id;
            $this->resultArray['name'] = $this->name;
            $this->resultArray['email'] = $this->email;
            $this->resultArray['phone_number'] = $this->phone_number;
            $this->resultArray['address'] = $this->address;

            return true;
        }

        return false;
    }
    
    /**
     * Új Contact rekord felvitelét valósítja meg.
     * 
     * @return boolean
     */
    private function processSave() {

        $sql = sprintf("INSERT INTO contacts (name, email, phone_number, address) VALUES ('%s', '%s', '%s', '%s')",
                $this->name,
                $this->email,
                $this->phone_number,
                $this->address,
        );

        if ($this->mysqlConnector->query($sql)) {

            $this->resultArray['id'] = $this->mysqlConnector->id;
            $this->resultArray['name'] = $this->name;
            $this->resultArray['email'] = $this->email;
            $this->resultArray['phone_number'] = $this->phone_number;
            $this->resultArray['address'] = $this->address;

            return true;
        }

        return false;
    }
    
    /**
     * Szűri a kapott tartalmat biztonsági okokból
     */
    private function escapeRequestField() {

        foreach ($this->fieldList as $field) {

            $this->$field = !empty($this->myEntireBodyObject->$field) ? $this->escape($this->myEntireBodyObject->$field) : '';
        }
    }
    
    /**
     * Az adatbázisba történő biztonságos
     * felvételhez alakítunk az input adatokon
     * 
     * @param string $string
     * @return type string
     */
    private function escape(string $string) {

        return mysqli_real_escape_string($this->conn, $string);
    }

}
