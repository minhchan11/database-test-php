<?php
  //load modules
  require_once __DIR__."/../vendor/autoload.php";
  require_once __DIR__."/../src/Person.php";

  $app = new Silex\Application();

  //Instantiate new PDO connection
  $server = 'mysql:host=localhost:8889;dbname=travel';
  $username = 'root';
  $password = 'root';
  $DB = new PDO($server,$username,$password);

  //Instantiate twig template
  $app->register(new Silex\Provider\TwigServiceProvider(), array(
    'twig.path' => __DIR__.'/../views'
  ));

  //get routes
  $app->get("/", function() use ($app) {
    return $app['twig'], render('people.html.twig', array('people' => Person::getAll()));
  });

  $app->post("/create_person", function() use ($app) {
    $new_person = new Person($_Post['name']);
    $new_person->save();
    return $app['twig']->render('create.html.twig', array('newPerson' => $new_person));
  });

  $app->post("/delete_people", function() use($app){
    Person::deleteAll();
    return $app['twig']->render('delete_all.html.twig');
  });

  return $app;
 ?>
