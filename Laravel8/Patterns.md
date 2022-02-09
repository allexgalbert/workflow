# Паттерны

- [Adapter](#adapter) - заменить класс 1, на класс 2, не меняя код по всему проекту. В адаптере методы класса 1,
  вызывают методы класса 2.

- [Builder](#builder) - это класс, в котором логика создания другого объекта.

- [Command](#command) - прослойка между объектами которые вызывают команды, и объектом который исполняет команды.

- [Dependency Injection](#dependency-injection) - для реализации слабосвязанной архитектуры.

- [Facade](#facade) - скрывает реализацию под собой, объединяя вызовы с разных классов, под одним своим методом.

- [Factory](#factory) - для создания объектов одинаковой структуры, но разных типов.

- [MVC](#mvc) - модель представление контроллер.

- [Observer](#observer) - для реализации публикации и подписки на поведение объекта.

- [Repository](#repository) - посредник между контроллером и хранилищами.

- [Singleton](#singleton) - наличие только 1 объекта у класса.

- [Strategy](#strategy) - выносить алгоритмы в отдельные классы, чтобы легко менять поведение объекта.

- [Active Record](#active-record) - работа с бд. Каждая таблица это 1 класс. Каждая строка в таблице это 1 объект.
  Данные и поведение в 1 классе.

## Adapter

Заменить класс 1, на класс 2, не меняя код по всему проекту. В адаптере методы класса 1, вызывают методы класса 2.

```php
class Class1 {
  public function method1() {
    echo 'Class1, method1';
  }
}
```

```php
class Class2 {
  public function method2() {
    echo 'Class2, method2';
  }
}
```

**Адаптер. Вариант 1**

```php
class Adapter1 {

  //объект класса Class2 создать в конструкторе
  public function __construct() {
    $this->adapter = new Class2;
  }

  //названия методов как в классе 1, но внутри они вызывают методы класса 2
  public function method1() {
    $this->adapter->method2();
  }
}
```

**Адаптер. Вариант 2**

```php
//адаптер наследовать от класса Class2
class Adapter2 extends Class2 {

  //названия методов как в классе 1, но внутри они вызывают методы класса 2
  public function method1() {
    $this->method2();
  }
}
```

```php
//работа с классом 1
$var = new Class1;
$var->method1();

//работа с адаптером. по факту работа с классом 2
$var = new Adapter1;
$var->method1(); //вызовет method2

$var = new Adapter2;
$var->method1(); //вызовет method2
```

## Builder

Это класс, в котором логика создания другого объекта.

**Билдер**

```php
class Builder {

  public function __construct() {
    $this->create();
  }

  //создать объект
  public function create() {
    $this->object = new stdClass;
    return $this;
  }

  //заполнить его свойство
  public function setProperty($value) {
    $this->object->property = $value;
    return $this;
  }

  //отдать объект
  public function getObject() {
    $object = $this->object;

    //обнулить объект
    $this->create();

    return $object;
  }
}
```

```php
$builder = new Builder;
$builder->setProperty('value');
$object = $builder->getObject();
```

В билдер можно добавить Getters. В Laravel билдер реализован как Eloquent Query Builder.

**Менеджер** (Директор, Абстрактный билдер) - создает сценарии создания объектов. По сути управляет билдером. Сценарии
создают объекты, с по-разному заполненными полями.

```php
class Manager {

  private $builder;

  public function setBuilder($builder) {
    $this->builder = $builder;
    return $this;
  }

  //сценарий 1
  public function scenario1() {
    return $this->builder->setProperty(1)->getObject();
  }

  //сценарий 2
  public function scenario2() {
    return $this->builder->setProperty(2)->getObject();
  }
}
```

```php
$manager = new Manager;
$manager->setBuilder($builder);

$object = $manager->scenario1();
$object = $manager->scenario2();
```

## Command

Прослойка между объектами которые вызывают команды, и объектом который исполняет команды.

**Класс с командами**

```php
class Receiver {
  public function command() {
    echo 'command';
  }
}
```

**Класс для каждой команды**

```php
class Command {
  public function __construct(Receiver $receiver) {
    $this->receiver = $receiver;
  }

  public function execute() {
    $this->receiver->command();
  }
}
```

**Класс который принимает класс команды**

```php
class Invoker {
  public function __construct($command) {
    $this->command = $command;
  }

  public function run() {
    $this->command->execute();
  }
}
```

```php
$receiver = new Receiver;
$invoker = new Invoker(new Command($receiver));
$invoker->run();
```

## Dependency Injection

**Инъекция зависимостей** - это когда конструктор класса ждет зависимость в виде объекта нужного интерфейса или класса. При
создании класса, в конструктор передаем объект, который реализует этот интерфейс или класс.

**Инверсия зависимостей** - это когда конструктор класса ждет зависимость в виде объекта только нужного интерфейса, а не
конкретного класса.

**Конструктор класса ждет зависимость в виде объекта нужного класса**

```php
class Class1 {
  public function method() {
    echo 'method';
  }
}

class Dependency {
  public function __construct(Class1 $object) {
    $this->object = $object;
    $this->object->method();
  }
}

new Dependency(new Class1);
```

**Конструктор класса ждет зависимость в виде объекта нужного интерфейса**

```php
interface Interface1 {
  public function method();
}

class Class1 implements Interface1 {
  public function method() {
    echo 'method';
  }
}

class Dependency {
  public function __construct(Interface1 $object) {
    $this->object = $object;
    $this->object->method();
  }
}

new Dependency(new Class1);
```

## Facade

Скрывает реализацию под собой, объединяя вызовы с разных классов, под одним своим методом.

**Классы**

```php
class Class1 {
  public function method1() {
    echo 'method1';
  }
  public function method2() {
    echo 'method2';
  }
}
```

**Фасад**

```php
class Facade {

  protected $class1;

  public function __construct() {
    $this->class1 = new Class1;
  }

  public function start() {
    $this->class1->method1();
    $this->class1->method2();
  }
}
```

```php
$facade = new Facade;
$facade->start();
```

## Factory

Для создания объектов одинаковой структуры, но разных типов.

**Кодеры**

```php
class Php {
  public function coding() {
    echo 'coding';
  }
}
```

**Фабрики для создания кодеров**

```php
class PhpFactory {
  public function create() {
    return new Php;
  }
}
```

```php
//без фабричного метода. сами вызываем фабрику
$factory = new PhpFactory;
$developer = $factory->create();
$developer->coding();

//фабричный метод. сам вызывает фабрику

function createMake($type) {
  if ($type == 'php') {
    return new PhpFactory;
  }
}

$factory = createMake('php');
$developer = $factory->create();
$developer->coding();
```

## MVC

Модель представление контроллер.

**Модель** отдает данные

```php
class Model {
  public function getData() {
    return 'data';
  }
}
```

**Представление** выводит данные в форматах html, xml, csv, json

```php
class View {
  public function showData($data) {
    echo $data;
  }
}
```

**Контроллер** реагирует на события
  
```php
class Controller {

  public function __construct(Model $model, View $view) {
    $this->model = $model;
    $this->view = $view;
  }

  public function execute() {
    $data = $this->model->getData();
    $this->view->showData($data);
  }
}
```

```php
$controller = new Controller(new Model, new View);
$controller->execute();
```

## Observer

Для реализации публикации и подписки на поведение объекта.

**Издатель**

```php
class Observable {

  //подписчики
  private $observers = [];

  //добавить подписчика
  public function add($observer) {
    $this->observers[] = $observer;
  }

  //удалить подписчика
  public function remove($observer) {
    foreach ($this->observers as $k => $v) {
      if ($observer === $v) {
        unset($this->observers[$k]);
      }
    }
  }

  //оповестить подписчиков. передать объект Observable
  public function notify1() {
    foreach ($this->observers as $observer) {
      $observer->handle1($this);
    }
  }

  //оповестить подписчиков. передать произвольную нагрузку
  public function notify2($payload) {
    foreach ($this->observers as $observer) {
      $observer->handle2($payload);
    }
  }

  //оповестить подписчиков. передать любой объект типа как Event
  public function notify3($event) {
    foreach ($this->observers as $observer) {
      $observer->handle3($event);
    }
  }
}
```

**Подписчик**

```php
class Observer {

  //обработка события от издателя
  public function handle1(Observable $observable) {}
  public function handle2($payload) {}
  public function handle3(Event $event) {}
}
```

```php

//издатель
$observable = new Observable;

//подписчики
$observer = new Observer;

//в издатель добавить подписчика
$observable->add($observer);

//удалить подписчика
$observable->remove($observer);

//оповестить подписчиков
$observable->notify1();
$observable->notify2('payload');
$observable->notify3(new Event);
```

## Repository

Посредник между контроллером и хранилищами.

**Интерфейс метода**

```php
interface Interface1 {
  public function method();
}
```

**Реализации метода**

```php
class Class1 implements Interface1 {
  public function method() {
    echo 'Class1, method';
  }
}

class Class2 implements Interface1 {
  public function method() {
    echo 'Class2, method';
  }
}
```

**Контроллер**

```php
class Controller {

  private $repository;

  public function __construct(Interface1 $class) {
    $this->repository = $class;
  }

  public function method() {
    $this->repository->method();
  }
}
```

```php
$class = new Controller(new Class1);
$class->method();

$class = new Controller(new Class2);
$class->method();
```

## Singleton

Наличие только 1 объекта у класса.

```php
class Singleton {

  //запрещаем создание
  private function __construct() {}

  //запрещаем клонирование
  private function __clone() {}

  //запрещаем десериализацию
  private function __wakeup() {}

  
  private static $instance;

  public static function get() {
    if (empty(self::$instance)) {
      self::$instance = new self();
    }

    return self::$instance;
  }

  public function method() {
    echo 'method';
  }
}
```

```php
$object = Singleton::get();
$object->do();
```

## Strategy

Выносить алгоритмы в отдельные классы, чтобы легко менять поведение объекта.

**Интерфейс метода**

```php
interface Interface1 {
  public function method();
}
```

**Реализации метода**

```php
class Class1 implements interface1 {
  public function method() {
    echo 'Class1, method';
  }
}

class Class2 implements interface1 {
  public function method() {
    echo 'Class2, method';
  }
}
```

**Объект**

```php
class Object {

  private $activity;

  public function set(interface1 $activity) {
    $this->activity = $activity;
  }

  public function run() {
    $this->activity->method();
  }
}
```

```php
$object= new Object;

$object->set(new Class1);
$object->run();

$object->set(new Class2);
$object->run();
```

## Active Record

Работа с бд. Каждая таблица это 1 класс. Каждая строка в таблице это 1 объект. Данные и поведение в 1 классе.

В базе данных таблица users: id, name, email

```php
class User {
  public $id;
  public $name;
  public $email;
  
  public function create() {}
  public function select() {}
  public function update() {}
  public function delete() {}
  public function findFirst() {}
}

$user = new User;

$user->name = 'name';
$user->create();

$user->id = 1;
$user->select();

$user->name = 'name2';
$user->update();

$user->name = 'name';
$user->delete();

$user->id = 2;
$user->findFirst();
```