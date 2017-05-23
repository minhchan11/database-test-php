<?php
  class Person
  {
      //property
      private $name;
      private $id;

      //constructor
      function __construct($name, $id = null)
      {
        $this->name = $name;
        $this->id = $id;
      }

      //getters and setters
      function getName()
      {
        return $this->name;
      }

      function setName($new_name)
      {
        $this->name = (string) $new_name;
      }

      function getId()
      {
        return $this->id;
      }

      //Save in database using SQL command
      function save()
      {
        //plural $GLOBALS
        $executed = $GLOBALS['DB'] -> exec("INSERT INTO people (name) VALUES ('{$this->getName()}');");
        if ($executed) {
          $this->id = $GLOBALS['DB']->lastInsertId();
            return true;
          } else {
            return false;
          }
      }

      static function getAll()
      {
        $dtb_people = $GLOBALS['DB']->query("SELECT * FROM people;");
        $local_people = array();
        foreach ($dtb_people as $person) {
          $new_name = $person['name'];
          $new_id = $person['id'];
          $new_person = new Person($new_name,$new_id);
          array_push($local_people, $new_person);
        }

        return $local_people;
      }

      static function deleteAll()
      {
        $executed = $GLOBALS['DB']->query("DELETE FROM people;");
        if ($executed) {
            return true;
          } else {
            return false;
          }
      }

      static function find($search_id)
      {
        $found_person = $GLOBALS['DB']->prepare("SELECT * FROM people WHERE id = :id");
        $found_person->bindParam(':id', $search_id, PDO::PARAM_STR);
        $found_person->execute();
        foreach ($found_person as $person) {
          $new_name = $person['name'];
          $new_id = $person['id'];
          if ($new_id == $search_id)
          {
            $found_person = new Person($new_name,$new_id);
          }
          return $found_person;
        }
      }

  }
 ?>
