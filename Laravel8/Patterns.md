# Паттерны

- [Adapter](#adapter) - 2 разных класса. Заменить класс 1, на класс 2. Не меняя код по всему проекту. В адаптере методы
  класса 1, вызывают методы класса 2.

- [Builder](#builder) - это класс, в котором логика создания другого объекта. Сами пошагово заполняем его свойства.
  Можно создать разные сценарии создания объекта.

- [Command](#command) - оборачивает команды в отдельные классы. Прослойка между объектами которые вызывают команды, и
  объектом который исполняет команды.

- [Dependency Injection](#dependency-injection) - для реализации слабосвязанной архитектуры.

- [Facade](#facade) - скрыть сложную систему под фасадом в 1 метод.

- [Factory](#factory) - фабрика для создания объектов разных типов но одинаковой структуры.

- [MVC](#mvc) - модель вью контроллер.

- [Observer](#observer) - для реализации публикации и подписки на поведение объекта. Когда объект меняет состояние,
  прикрепленные объекты будут уведомлены. Чтобы сократить количество связанных напрямую объектов.

- [Repository](#repository) - посредник между контроллером и хранилищами. Оборачивает в себе коллекцию объектов, и
  операции.

- [Singleton](#singleton) - чтобы класс имел 1 объект. Используется для соединения с бд, ведения логов.

- [Strategy](#strategy) - выносить однообразные алгоритмы объекта в отдельные классы, легко их заменять как поведения.

- [Active Record](#active-record) - работа с бд. Каждая таблица это 1 класс. Каждая строка в таблице это 1 объект.
  Данные и поведение в 1 классе.

## Adapter

2 разных класса. Заменить класс 1, на класс 2. Не меняя код по всему проекту. В адаптере методы класса 1, вызывают
методы класса 2.

**Класс 1**

```php
class Class1 {
  public function method1() {
    echo 'Class1 method1';
  }
}
```

**Класс 2**

```php
class Class2 {
  public function method2() {
    echo 'Class2 method2';
  }
}
```

**Адаптер. объект класса Class2 создать в конструкторе**

```php
class Adapter1 {

  //подключить класс 2
  public function __construct() {
    $this->adapter = new Class2;
  }

  //названия методов как в классе 1, но внутри вызывают методы класса 2
  public function method1() {
    $this->adapter->method2();
  }
}
```

**Адаптер. наследовать от класса Class2**

```php
class Adapter2 extends Class2 {

  //названия методов как в классе 1, но внутри вызывают методы класса 2
  public function method1() {
    $this->method2();
  }
}
```

```php
//Работа с классом 1
$var = new Class1;
$var->method1();

//Работа с адаптером. По факту с классом 2
$var = new Adapter1;
$var->method1();

//Работа с адаптером. По факту с классом 2
$var = new Adapter2;
$var->method1();
```

## Builder

Это класс, в котором логика создания другого объекта. Сами пошагово заполняем его свойства. Можно создать разные
сценарии создания объекта.

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
  public function setProperty1($value) {
    $this->object->property1 = $value;
    return $this;
  }

  //заполнить его свойство
  public function setProperty2() {
    $this->object->property2 = 'default';
    return $this;
  }

  //отдать готовый объект
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
$builder->setProperty1('value')->setProperty2();
$object = $builder->getObject();
```

В билдер можно добавить геттеры. Билдеры часто делают для моделей. В ларавел билдер реализован в Eloquent Query Builder.

**Менеджер** создает сценарии создания объектов. Управляет билдером. Сценарии создают объекты, с по-разному заполненными
полями. Другое название менеджера - Директор, Абстрактный билдер.

```php
class Manager {

  private $builder;

  public function setBuilder($builder) {
    $this->builder = $builder;
    return $this;
  }

  //сценарий 1
  public function scenario1() {
    return $this->builder->setProperty1('scenario1')->setProperty2()->getObject();
  }

  //сценарий 2
  public function scenario2() {
    return $this->builder->setProperty1('scenario2')->setProperty2()->getObject();
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

Оборачивает команды в отдельные классы. Прослойка между объектами которые вызывают команды, и объектом который исполняет
команды.

- Receiver класс с командами
- На каждую команду делаем класс, который создает объект Receiver и вызывает эту команду
- Invoker класс, туда передаем классы команд, и дёргаем его методы, которые дёргают классы команд, и их методы

```php
class Receiver {
  public function command1() {
    echo 'command1';
  }

  public function command2() {
    echo 'command2';
  }
}
```

```php
class Command1 {
  public function __construct(Receiver $receiver) {
    $this->receiver = $receiver;
  }

  public function execute() {
    $this->receiver->command1();
  }
}

class Command2 {
  public function __construct(Receiver $receiver) {
    $this->receiver = $receiver;
  }

  public function execute() {
    $this->receiver->command2();
  }
}
```

```php
class Invoker {
  public function __construct($command1, $command2) {
    $this->command1 = $command1;
    $this->command2 = $command2;
  }

  public function run1() {
    $this->command1->execute();
  }

  public function run2() {
    $this->command2->execute();
  }
}
```

```php
$receiver = new Receiver;
$invoker = new Invoker(new Command1($receiver), new Command2($receiver));
$invoker->run1();
$invoker->run2();
```

## Dependency Injection

Инъекция зависимостей, это когда конструктор класса ждет зависимость в виде объекта: нужного интерфейса или класса. При
создании класса, в конструктор передаем объект, который реализует этот интерфейс или класс. Инъекция зависимостей это
передача аргумента в конструктор или метод.

Инверсия зависимостей, это когда конструктор класса ждет зависимость в виде объекта: только нужного интерфейса. А не
конкретного класса.

**Интерфейс**

```php
interface Interface {}
```

**Классы**

```php
class Class1 implements Interface {}
class Class2 implements Interface {}
```

**Класс**

```php
class Class {

  public function __construct(Interface $object) {
    $this->object = $object;
  }
  
  public function __construct(Class2 $object) {
    $this->object = $object;
  }
}
```

```php
new Class(new Class1);
new Class(new Class2);
```

## Facade

Скрыть сложную систему под фасадом в 1 метод.

```php
class Class1 {public function method1() {}}
class Class2 {public function method2() {}}
```

Фасад скрывает реализацию под собой, объединяя вызовы с разных мест, под 1 методом

```php
class Facade {

  protected $class1;
  protected $class2;

  public function _construct() {
    $this->class1 = new Class1;
    $this->class2 = new Class2;
  }
  
  public function start() {
    $this->class1->method1();
    $this->class2->method2();
  }
}
```

```php
$facade = new Facade;
$facade->start();
```

Под разными фасадами, можно скрывать разные наборы вызываемых методов подсистемы. В ларавел папка Facades. Все вызовы
сложной подсистемы сводятся к вызову 1 метода у 1 объекта.

## Factory

Фабрика для создания объектов разных типов но одинаковой структуры.

**Кодеры и их действия**

```php
interface Developer {public function coding()}
class Java implements Developer {public function coding() {код}}
class Perl implements Developer {public function coding() {код}}
```

**Фабрики для создания кодеров**

```php
interface Factory {public function create()}
class JavaFactory implements Factory {public function create() {return new Java}}
class PerlFactory implements Factory {public function create() {return new Perl}}
```

```php
class Class {

  //без фабричного метода. сами вызываем фабрику
  public function method() {
    $factory = new JavaFactory;
    $developer = $factory->create();
    $developer->coding();
  }
  
  //фабричный метод. вызывает фабрику по типу
  public static function createMake($type) {
    if ($type == 'java') {
      return new JavaFactory;
    }
    if ($type == 'perl') {
      return new PerlFactory;
    }
  }
  
  //с фабричным методом. передавая тип кодера которого нужно создать
  public function method() {
    $factory = self::createMake('java');
    $developer = $factory->create();
    $developer->coding();
  }
}
```

**Еще реализация**

```php
class Factory {
  public function create($type) {
    if ($type == 'audi') {return new Audi}
    if ($type == 'ford') {return new Ford}
  }
}

$factory = new Factory;
$audi = $factory->create('audi');
$ford = $factory->create('ford');
```

**Еще реализация**

```php
class Factory1 {public function create() {return new Audi}}
class Factory2 {public function create() {return new Ford}}
```

```php
class Main {
  public function make($type) {
    if ($type == 'audi') {return new Factory1}
    if ($type == 'ford') {return new Factory2}
  }
}

$factory = new Main;
$audi = $factory->make('audi');
$ford = $factory->make('ford');
```

## MVC

- Модель - бизнес логика, отдает данные.
- Вью - слой презентации, оборачивает данные в нужный формат html, xml, csv, json. Вьюх может быть много разных в одном
  контроллере.
- Контроллер - реагирует на события, принимает запросы от браузера или консоли, валидирует данные, делает запрос к
  модели, готовит данные для вью, отдает ответ.

```php
class Model {public function getData() {return $data}}
class View {public function showData($data) {echo $data}}

class Controller {
 
  private $model = new Model;
  private $view = new View;
  
  public function execute() {
    $data = $this->model->getData();
    $this->view->showData($data);
  }
}

$controller = new Controller;
$controller->execute();
```

## Observer

Для реализации публикации и подписки на поведение объекта. Когда объект меняет состояние, прикрепленные объекты будут
уведомлены. Чтобы сократить количество связанных напрямую объектов.

**Объект observable**

- издатель. создает события
- содержит список observers
- методы: add, remove, notify
- метод notify перебирает observers и вызывает у каждого метод handle

**Объекты observers**

- подписчики. разного типа
- наблюдают за событиями у observable
- метод handle

**Использование в ларавел**

- при сохранении, изменении, удалении модели, вызываются классы из папки Observers, методы с такими же названиями
- обсерверы создаются командой php artisan make:observer name --model=name

В PHP использовать SplSubject, SplObserver, SplObjectStorage

```php
class Observable {

  private $observers = [];
  
  public function add($observer) {$this->observers[] = $observer}
  
  public function remove($observer) {unset($this->observers[$observer])}
  
  public function notify($event) {
    foreach ($this->observers as $observer) {
      $observer->handle($this);
      $observer->handle($event);
    }
  }
  
  public function notify($payload) {
    foreach ($this->observers as $observer) {
      $observer->handle($payload);
    }
  }
}
```

```php
class Observer {
  public function handle($observable) {}
  public function handle($event) {}
  public function handle($payload) {}
}
```

```php
$observable = new Observable;

$observer1 = new Observer;
$observer2 = new Observer;

$observable->add($observer1);
$observable->add($observer2);

$observable->notify();

$observable->remove($observer2);


class Event {}
$observable->notify(new Event);


$observable->notify($payload);
```

## Repository

Посредник между контроллером и хранилищами. Оборачивает в себе коллекцию объектов, и операции.

- Обертка для модели. Содержит логику работы с данными. Модель как источник данных
- Как книжный шкаф. Только брать и класть. Не может создавать и изменять
- Репозитарий это коллекция. Абстрактный слой между контроллером и разными хранилищами
- В ларавел папка Repositories где репозитарии и интерфейсы

**Методы. получить все записи, и только записи юзера**

```php
interface Interface {
  public function method1()
  public function method2()
}
```

**Реализации. юзеры лежат в базе, или файлах**

```php
class Class1 implements Interface {
  public function method1() {}
  public function method2() {}
}

class Class2 implements Interface {
  public function method1() {}
  public function method2() {}
}
```

```php
class Class {

  private $repository;
  
  public function __construct(Interface $class) {
    $this->repository = $class;
  }

  public function method() {
    $this->repository->method1();
    $this->repository->method2();
  }
}

$class = new Class(new Class1);
$class = new Class(new Class2);
```

В ларавел вместо внедрения в конструкторе, можно привязать в провайдере.

```php
public function register() {
  $this->app->bind(Interface::class, Class1::class);
  $this->app->bind(Interface::class, Class2::class);
}
```

## Singleton

Чтобы класс имел 1 объект. Используется для соединения с бд, ведения логов.

```php
class Singleton {

  private static $instance;
  
  private function __construct() {запрещаем создание}
  private function __clone() {запрещаем клонирование}
  private function __wakeup() {запрещаем десериализацию}
  
  public static function get() {
    return static::$instance ?? (static::$instance = new static());
  }
  
  public function do() {что-то делаем}
}
```

```php
$object = Singleton::get();
$object->do();
```

## Strategy

Выносить однообразные алгоритмы объекта в отдельные классы, легко их заменять как поведения.

- Каждый алгоритм в своем классе.
- Выбирать алгоритм через создание класса алгоритма.
- Для легкой замены поведений. Стратегия заменяет поведения: алгоритм1, алгоритм2, алгоритм3,..

Активности разработчика: кодить, кушать

**Интерфейс**

```php
interface Interface {
  //выполнить активность
  public function do();
}
```

**Классы активностей**

```php
class Coding implements Interface {public function do() {кодить}}
class Eating implements Interface {public function do() {кушать}}
```

**Разработчик**

```php
class Developer {

  //активность
  private $activity;
  
  //установить активность
  public function set($activity) {
    $this->activity = $activity;
  }
  
  //выполнить активность
  public function execute() {
    $this->activity->do();
  }
}
```

```php
$developer = new Developer;

$developer->set(new Coding);
$developer->execute();

$developer->set(new Eating);
$developer->execute();
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