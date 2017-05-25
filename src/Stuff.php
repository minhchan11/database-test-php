<?php
    class Stuff
    {
      //property
      private $stuffName;
      private $id;

      //constructor
      function __construct($stuffName, $id = null)
      {
        $this->stuffName = $stuffName;
        $this->id = $id;
      }

      function getName()
      {
        return $this->stuffName;
      }

      function setName($new_stuffName)
      {
        $this->stuffName = (string) $new_stuffName;
      }

      function getId()
      {
        return $this->id;
      }

      function save()
      {
        $executed = $GLOBALS['DB'] -> exec("INSERT INTO stuffs (stuffName) VALUES ('{$this->getName()}');");
        if ($executed) {
          $this->id = $GLOBALS['DB']->lastInsertId();
            return true;
          } else {
            return false;
          }
      }

      function update($new_name)
      {
        $executed = $GLOBALS['DB'] -> exec("UPDATE stuffs SET stuffName='{$new_name}' WHERE id = {$this->getId()};");
        if ($executed) {
          $this->setName($new_name);
          return true;
        } else {
          return false;
        }
      }

        function delete()
        {
          $executed = $GLOBALS['DB'] -> exec("DELETE FROM places_stuffs WHERE stuff_id = {$this->getId()};");
          if(!$executed){
            return false;
          }
          $executed = $GLOBALS['DB'] -> exec("DELETE FROM stuffs WHERE id = {$this->getId()}");
          if ($executed)
          {
            return true;
          } else {
            return false;
          }
        }

        function addPlace($place)
        {
          $executed = $GLOBALS['DB']->query("INSERT INTO places_stuffs (place_id, stuff_id) VALUES ({$place->getId()},{$this->getId()});");
          if ($executed) {
              return true;
            } else {
              return false;
            }
        }

        function getPlaces()
        {
          $return_places= $GLOBALS['DB']->query("SELECT places.* FROM places
            JOIN places_stuffs ON (places_stuffs.place_id = places.id)
            JOIN stuffs ON (places_stuffs.stuff_id = stuffs.id)
            WHERE stuff_id ={$this->getId()};");
          $places = array();
          foreach($return_places as $place){
            $name = $place['placeName'];
            $id = $place['id'];
            $new_place = new Place($name, $id);
            array_push($places, $new_place);
          }
          return $places;
        }

        static function getAll()
        {
          $dtb_stuffs = $GLOBALS['DB']->query("SELECT * FROM stuffs;");
          $local_stuffs = array();
          foreach ($dtb_stuffs as $stuff) {
            $new_stuffName = $stuff['stuffName'];
            $new_id = $stuff['id'];
            $new_stuff = new Stuff($new_stuffName,$new_id);
            array_push($local_stuffs, $new_stuff);
          }

          return $local_stuffs;
        }

        static function deleteAll()
        {
          $executed = $GLOBALS['DB']->query("DELETE FROM stuffs;");
          if ($executed) {
              return true;
            } else {
              return false;
            }
        }

        static function find($search_id)
        {
          $found_stuff = $GLOBALS['DB']->prepare("SELECT * FROM stuffs WHERE id = :id");
          $found_stuff->bindParam(':id', $search_id, PDO::PARAM_STR);
          $found_stuff->execute();
          foreach ($found_stuff as $stuff) {
            $new_stuffName = $stuff['stuffName'];
            $new_id = $stuff['id'];
            if ($new_id == $search_id)
            {
              $found_stuff = new Stuff($new_stuffName,$new_id);
            }
            return $found_stuff;
          }
        }

      }
?>
