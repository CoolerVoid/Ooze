<?php
// class crud by COoler_
class crud
{
    public $db;

    public function conn($x=NULL) {

     if (!$this->db instanceof PDO) {
      $this->db = new PDO("sqlite:../db/ronin.db" );
      $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
     }
     
    } 

        public function dbSelect($table, $fieldname=null, $id=null)
        {
            $sgdb=$this->conn(1);

             $sql = "SELECT * FROM $table WHERE $fieldname = :id";


            $this->conn();
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

        public function dbSelect2($table, $fieldname=null, $id=null, $field2 , $value)
        {
            $this->conn();
            $sql = "SELECT * FROM $table WHERE $fieldname = :id and  $field2  = :value";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':id', $id);
             $stmt->bindParam(':value', $value);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

        public function dbAll($table)
        {

            $sgdb=$this->conn(1);
 
             $sql = " SELECT * FROM $table ";


            $this->conn(); 
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }


        public function rawSelect($sql)
        {
            $this->conn();
            return $this->db->query($sql);
        }


        public function rawQuery($sql)
        {
            $this->conn();
            $this->db->query($sql);
        }


        public function dbInsert($table, $values)
        {

            $this->conn();
            $fieldnames = array_keys($values[0]);
            $size = sizeof($fieldnames);
            $sql = "INSERT INTO $table";
            $fields = '( ' . implode(' ,', $fieldnames) . ' )';
            $bound = '(:' . implode(', :', $fieldnames) . ' )';
            $sql .= $fields.' VALUES '.$bound;
            $stmt = $this->db->prepare($sql);
            $stmt->execute($values[0]);
            

        }

        public function Update($table, $values, $pk, $id)
        {
            $this->conn();
 
            foreach($values as $key => $value)
            {

                $sql = "UPDATE $table SET "." $key".' = :valor'." WHERE $pk = :id";
                $this->conn();
                $stmt = $this->db->prepare($sql);
                $stmt->bindParam(':valor', $value, PDO::PARAM_STR);
                $stmt->bindParam(':id', $id, PDO::PARAM_STR);
                $stmt->execute();
            }

        }

        public function dbUpdate($table, $fieldname, $value, $pk, $id)
        {
         $this->conn();
             $sql = "UPDATE $table SET $fieldname = :value WHERE $pk = :id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_STR);
	    $stmt->bindParam(':value', $value, PDO::PARAM_STR);
            $stmt->execute();
        }


        public function dbDelete($table, $fieldname, $id)
        {
            $sgdb=$this->conn(1);

             $sql = "DELETE FROM $table WHERE $fieldname = :id";


            
            $this->conn();

            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_STR);
            $stmt->execute();
        }
}
?>
