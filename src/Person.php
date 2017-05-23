<?php
  class Person
  {
      //property
      private $name;

      //constructor
      function __construct($name)
      {
        $this->name = $name;
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

      //Save in database using SQL command
      function save()
      {
        //plural $GLOBALS
        $executed = $GLOBALS['DB'] -> exec("INSERT INTO people (name) VALUES ('{$this->getName()}');");
        if ($executed) {
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
          $new_person = new Person($new_name);
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

      // static function console_debug( $data ) {
      // $output = $data;
      // if ( is_array( $output ) )
      //     $output = implode( ',', $output);
      //
      // echo "'Debug Objects: " . $output ;
      // }

  }
 ?>
