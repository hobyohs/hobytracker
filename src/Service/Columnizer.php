<?php
    
    // src/Service/Columnizer.php
    
    namespace App\Service;
    
    class Columnizer
    {

        private $columnNames;
   
        public function __construct($columnNames) {
            $this->columnNames = $columnNames;
        }
        
        public function readColumnName($colName) {
            return $this->columnNames[$colName];
        }
        
        public function spitShowRow($entity, $col, $showifnull = FALSE) {
            
            if(substr($col, 0, 3) == "get") $getter = $col;
            else $getter = "get".$col;
            
            $data = nl2br($entity->$getter());
            
            if ($data == "1") $data = "Yes";
            elseif ($data == "0") $data = "No";
            elseif ($data == "2") $data = "Maybe";
            
            if (!$showifnull AND empty($data)) return null;
            else return <<< EOF
                <tr>
                    <th>{$this->readColumnName($col)}</th>
                    <td>{$data}</td>
                </tr>    
    EOF;
        }

    }
    
    
?>