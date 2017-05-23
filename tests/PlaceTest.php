<?php

/**
 * @backupGlobals disabled
 * @backupStaticAttributes disabled
 */

  require_once "src/Place.php";

  $server = 'mysql:host=localhost:8889;dbname=travel_test';
  $username = 'root';
  $password = 'root';
  $DB = new PDO($server,$username,$password);

  //Same name as file
  class PlaceTest extends PHPUnit_Framework_TestCase
  {

    function test_getAll()
    {
      //Arrange
      $name1 = "Min";
      $test_place1 = new Place($name1);
      $test_place1->save();

      $name2 = "Minh";
      $test_place2 = new Place($name2);
      $test_place2->save();
      //Act
      $result = Place::getAll();
      //Assert
      $this->assertEquals([$test_place1, $test_place2], $result);
    }

    function test_deleteAll()
    {
      //Arrange
      $name1 = "Min";
      $test_place1 = new Place($name1);
      $test_place1->save();

      $name2 = "Minh";
      $test_place2 = new Place($name2);
      $test_place2->save();
      Place::deleteAll();
      //Act
      $result = Place::getAll();
      //Assert
      $this->assertEquals([], $result);
    }

    function test_getId()
    {
      //Arrange
      $name1 = "Min";
      $test_place1 = new Place($name1);
      $test_place1->save();

      //Act
      $all_people = Place::getAll();
      $expected = $test_place1->getId();
      $result = $all_people[0]->getId();

      //Assert
      $this->assertEquals($expected, $result);
    }

    function test_find()
    {
      //Arrange
      $name1 = "Min";
      $test_place1 = new Place($name1);
      $test_place1->save();

      $name2 = "Minh";
      $test_place2 = new Place($name2);
      $test_place2->save();

      //Act
      $id = $test_place1->getId();
      $result = Place::find($id);

      //Assert
      $this->assertEquals($test_place1, $result);
    }

    protected function tearDown()
    {
      Place::deleteAll();
    }
  }
 ?>