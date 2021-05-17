# Паттерны

- **[Adapter](#adapter)** - 2 класса с разными интерфейсами и методами. Заменить 1 на 2, не меняя код по всему проекту.
  В прослойке методы 1 вызывают методы 2.

- **[Builder](#builder)** - строитель для создания объектов пошагово по разным сценариям.
- **[Command](#command)** - заворачивает команды в отдельные классы, команды как отдельные объекты, прослойка между
  объектами которые вызывают команды, и объектом который обрабатывает команды.
- **[Dependency Injection](#dependency-injection)** - для реализации слабосвязанной архитектуры.
- **[Facade](#facade)** - предназначен для разделения клиента и сложной подсистемы, путем внедрения одного интерфейса и
  уменьшения общей сложности.
- **[Factory](#factory)** - фабрика для создания объектов разных типов но одинаковой структуры.
- **[MVC](#mvc)** - модель вью контроллер.
- **[Observer](#observer)** - для реализации публикации и подписки на поведение объекта, когда объект subject меняет
  состояние, прикрепленные объекты observers будут уведомлены. Используется чтобы сократить количество связанных
  напрямую объектов и использует слабую связь.
- **[Repository](#repository)** - посредних между контроллером и разными хранилищами, инкапсулирует в себе коллекцию
  объектов хранилища и сами операции выборки и сохранения.
- **[Strategy](#strategy)** - выносить однообразные алгоритмы в отдельные классы и легко их заменять.

## Adapter

2 класса с разными интерфейсами и методами. Заменить 1 на 2, не меняя код по всему проекту. В прослойке методы 1
вызывают методы 2.

**Класс 1**

```php
interface Interface1 {public function method1();}
class Class1 implements Interface1 {public function method1() {}}
```

**Класс 2**

```php
interface Interface2 {public function method2();}
class Class2 implements Interface2 {public function method2() {}}
```

**Адаптер. объект класса Class2 создать в конструкторе**

```php
class Adapter implements Interface1 {
  //подключить класс 2
  public function __construct() {$this->adapter = new Class2;}
  
  //названия методов как в классе 1, но внутри вызывают методы класса 2
  public function method1() {$this->adapter->method2();}
}
```

**Адаптер. класс Adapter наследовать от класса Class2**

```php
class Adapter extends Class2 {
  //названия методов как в классе 1, но внутри вызывают методы класса 2
  public function method1() {$this->method2();}
}
```

```php
//Работа с классом 1
$var = app(Class1::class);
$var->method1();
	
//Работа с адаптером. По факту с классом 2
$var = app(Adapter::class);
$var->method1();
	
//Можно улучшить так
$var = app(Interface1::class);
$var->method1();

//в провайдере указать ларавелу, объект какого класса создавать, когда идет обращение к интерфейсу
$bindings = [Interface1::class => Adapter::class];
```

## Builder

Строитель это отдельный класс, который создает объект, мы сами пошагово заполняем его свойства, и он возвращает готовый
объект. Логику создания объекта выносим в отдельный класс.

Проблема которую решаем: большой конструктор

```php
function create($property1, $property2, $property3,.. $property10) {}
create(1, 2, null,.. 5);
create(null, null, null,.. 4);
```

**Интерфейс билдера**

```php
interface Interface {
  public function create()
  public function setProperty1($value)
  public function setProperty2()
  public function getObject()
}
```

**Объект который строим**

```php
class Object {}
```

**Билдер строит объект Object**

```php
class Builder implements Interface {

  public function __construct() {
    $this->create();
  }
  
  public function create() {
    $this->object = new Object;
    return $this;
  }

  //заполняем свойства объекта  
  public function setProperty1($value) {
    $this->object->property1 = $value;
    return $this;
  }
  
  //заполняем свойства объекта
  public function setProperty2() {
    $this->object->property2 = 'default';
    return $this;
  }

  //отдать готовый объект  
  public function getObject() {
    $object= $this->object;
	
    //обнулить болванку
    $this->create();
	
    return $object;
  }
  
  //отдать готовый объект. другая реализация метода getObject
  public function build() {
    return new Object($this);
  }
}
```

```php
//Создали билдер, он создал объект, заполнили свойства, получили объект
$builder = new Builder;
$builder->setProperty1('value1')->setProperty2();
$object = $builder->getObject();
```

В билдер можно добавить геттеры. Билдеры часто делаются для моделей.

**Менеджер** создает сценарии создания объектов. Руководит билдером. Сценарии создают объекты, с по-разному заполненными
полями.

```php
class Manager {

  private $builder;
  
  public function setBuilder($builder) {
    $this->builder = $builder;
    return $this;
  }
  
  public function scenario1() {
    return $this->builder->setProperty1('value1')->getObject();
  }
  
  public function scenario2() {
    return $this->builder->setProperty1('value2')->getObject();
  }
}

$manager = new Manager;
$manager->setBuilder($builder);

$object = $manager->scenario1();
$object = $manager->scenario2();
```

Менеджер - другое название: Директор, Абстрактный билдер

В ларавел паттерн билдер реализован в Eloquent Query Builder для запросов.

## Command

Заворачивает команды в отдельные классы. Команды как отдельные объекты. Прослойка между объектами которые вызывают
команды, и объектом который обрабатывает.

```php
class Receiver {
  public function command1() {}
  public function command2() {}
}
```

```php
interface Command {public function execute()}

class Command1 implements Command {
  public function __construction(Receiver $receiver) {$this->receiver= $receiver}
  public function execute() {$this->receiver->command1()}
}

class Command2 implements Command {
  public function __construction(Receiver $receiver) {$this->receiver= $receiver}
  public function execute() {$this->receiver->command2()}
}
```

```php
class Invoker {
  public function __constructor(Command $command1, Command $command2) {
    $this->command1 = $command1;
    $this->command2 = $command2;
  }
  public function run1() {$this->command1->execute()}
  public function run2() {$this->command2->execute()}
}
```

```php
$receiver= new Receiver;
$invoker = new Invoker(new Command1($receiver), new Command2($receiver));
$invoker->run1();
$invoker->run2();
```

## Dependency Injection

Инъекция зависимостей, это когда конструктор класса ждет зависимость в виде объекта нужного интерфейса или класса. При
создании класса, в конструктор передаем объект, который реализует этот интерфейс или класс. Инъекция зависимостей это
просто передача аргумента в конструктор или метод.

Инверсия зависимостей, это когда конструктор класса ждет зависимость в виде объекта только нужного интерфейса. А не
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

Сложная подсистема которую надо скрыть под фасадом

```php
class Class1 {public function method1() {}}
class Class2 {public function method2() {}}
```

Фасад скрывает реализацию под собой, объединяя вызовы с разных мест, под одним методом

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
$facade =  new Facade;
$facade->start();
```

Под разными фасадами можно скрывать разные наборы вызываемых методов подсистемы. В ларавел папка Facades. В итоге все
вызовы сложной подсистемы сводятся к вызову 1 метода у 1 объекта.

## Factory

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

```php
class Factory {
  public function create($type) {
    if ($type == 'audi') {return new Audi}
    if ($type == 'ford') {return new Ford}
  }
}

$factory = new Factory;
$audi = factory->create('audi');
$ford = factory->create('ford');
```

```php
class Factory1 {public function create() {return new Audi}}
class Factory2 {public function create() {return new Ford}}
```

**Абстрактная фабрика**

```php
class Main {
  public function make($type) {
    if ($type == 'audi') {return new Factory1}
    if ($type == 'ford') {return new Factory2}
  }
}

$factory = new Main;
$audi = factory->make('audi');
$ford = factory->make('ford');
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

Объект observable

- издатель. создает события
- содержит список observers
- методы: add, remove, notify
- метод notify перебирает observers и вызывает у каждого метод handle

Объекты observers

- подписчики. разного типа
- наблюдают за событиями у observable
- метод handle

Использование в ларавел

- при сохранении, изменении, удалении модели
- вызываются классы из папки Observers
- там методы с такими же названиями
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

- Обертка для модели. Содержит логику работы с данными. Модель как источник данных
- Как книжный шкаф. Только брать и класть. Не может создавать и изменять
- Репозитарий это коллекция. Абстрактный слой между контроллером и разными хранилищами
- В ларавел папка Repositories где репозитарии и интерфейсы

**Методы. получить все записи и только записи юзера**

```php
interface Interface {
  public function method1()
  public function method2()
}
```

**Реализации. юзеры лежат в базе или в файлах**

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

В ларавел вместо внедрения в конструкторе, можно привязать в провайдере

```php
public function register() {
  $this->app->bind(Interface::class, Class1::class);
  $this->app->bind(Interface::class, Class2::class);
}
```

## Strategy

- Выносить однообразные алгоритмы в отдельные классы и легко их заменять
- Каждый алгоритм в своем классе. Семейство алгоритмов.
- Выбирать алгоритм путем создания класса алгоритма.
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
