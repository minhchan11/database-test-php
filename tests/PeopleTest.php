<?php

/**
 * @backupGlobals disabled
 * @backupStaticAttributes disabled
 */

  require_once "src/Person.php";
  require_once "src/Place.php";

  $server = 'mysql:host=localhost:8889;dbname=travel_test';
  $username = 'root';
  $password = 'root';
  $DB = new PDO($server,$username,$password);

  //Same name as file
  class PeopleTest extends PHPUnit_Framework_TestCase
  {

    function test_getAll()
    {
      //Arrange
      $place_name1= "Paris";
      $test_place1 = new Place($place_name1);
      $test_place1->save();

      $name1 = "Min";
      $place_id = $test_place1->getId();
      $test_person1 = new Person($name1, $place_id);
      $test_person1->save();

      $name2 = "Minh";
      $test_person2 = new Person($name2, $place_id);
      $test_person2->save();
      //Act
      $result = Person::getAll();
      //Assert
      $this->assertEquals([$test_person1, $test_person2], $result);
    }

    function test_deleteAll()
    {
      //Arrange
      $place_name1= "Paris";
      $test_place1 = new Place($place_name1);
      $test_place1->save();
      $place_id = $test_place1->getId();

      $name1 = "Min";
      $test_person1 = new Person($name1, $place_id);
      $test_person1->save();

      $name2 = "Minh";
      $test_person2 = new Person($name2, $place_id);
      $test_person2->save();
      Person::deleteAll();
      //Act
      $result = Person::getAll();
      //Assert
      $this->assertEquals([], $result);
    }

    function test_getId()
    {
      //Arrange
      $place_name1= "Paris";
      $test_place1 = new Place($place_name1);
      $test_place1->save();

      $name1 = "Min";
      $place_id = $test_place1->getId();
      $test_person1 = new Person($name1, $place_id);
      $test_person1->save();

      //Act
      $all_people = Person::getAll();
      $expected = $test_person1->getId();
      $result = $all_people[0]->getId();

      //Assert
      $this->assertEquals($expected, $result);
    }

    function test_find()
    {
      //Arrange
      $place_name1= "Paris";
      $test_place1 = new Place($place_name1);
      $test_place1->save();
      $place_id = $test_place1->getId();

      $name1 = "Min";
      $test_person1 = new Person($name1, $place_id);
      $test_person1->save();

      $name2 = "Minh";
      $test_person2 = new Person($name2, $place_id);
      $test_person2->save();

      //Act
      $id = $test_person1->getId();
      $result = Person::find($id);

      //Assert
      $this->assertEquals($test_person1, $result);
    }

    protected function tearDown()
    {
      Person::deleteAll();
      Place::deleteAll();
    }
  }
 ?>
