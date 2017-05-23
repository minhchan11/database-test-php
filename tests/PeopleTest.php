<?php

/**
 * @backupGlobals disabled
 * @backupStaticAttributes disabled
 */

  require_once "src/Person.php";

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
      $name1 = "Min";
      $test_person1 = new Person($name1);
      $test_person1->save();

      $name2 = "Minh";
      $test_person2 = new Person($name2);
      $test_person2->save();
      //Act
      $result = Person::getAll();
      //Assert
      $this->assertEquals([$test_person1, $test_person2], $result);
    }

    function test_deleteAll()
    {
      //Arrange
      $name1 = "Min";
      $test_person1 = new Person($name1);
      $test_person1->save();

      $name2 = "Minh";
      $test_person2 = new Person($name2);
      $test_person2->save();
      Person::deleteAll();
      //Act
      $result = Person::getAll();
      //Assert
      $this->assertEquals([], $result);
    }

    protected function tearDown()
    {
      Person::deleteAll();
    }
  }
 ?>
