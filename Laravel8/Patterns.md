# Паттерны

- [Adapter](#adapter) - заменить класс 1, на класс 2, не меняя код по всему проекту. В адаптере методы класса 1,
  вызывают методы класса 2.

- [Builder](#builder) - это класс, в котором логика создания другого объекта.

- [Command](#command) - прослойка между объектами которые вызывают команды, и объектом который исполняет команды.

- [Dependency Injection](#dependency-injection) - для реализации слабосвязанной архитектуры.

- [Facade](#facade) - скрывает реализацию под собой, объединяя вызовы с разных классов, под одним своим методом.

- [Factory](#factory) - фабрика для создания объектов разных типов, но одинаковой структуры.

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
    echo 'Class1, method1';
  }
}

class Class2 {
  public function method2() {
    echo 'Class2, method2';
  }
}
```

**Фасад**

```php
class Facade {

  protected $class1;
  protected $class2;

  public function __construct() {
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

## Factory

Фабрика для создания объектов разных типов, но одинаковой структуры.

**Кодеры**

```php
class Java {
  public function coding() {
    echo 'Java coding';
  }
}

class Perl {
  public function coding() {
    echo 'Perl coding';
  }
}
```

**Фабрики для создания кодеров**

```php
class JavaFactory {
  public function create() {
    return new Java;
  }
}

class PerlFactory {
  public function create() {
    return new Perl;
  }
}
```

```php
//Без фабричного метода. Сами вызываем фабрику
$factory = new JavaFactory;
$developer = $factory->create();
$developer->coding();

//Фабричный метод. Вызывает фабрику по типу
function createMake($type) {
  if ($type == 'java') {
    return new JavaFactory;
  }
  if ($type == 'perl') {
    return new PerlFactory;
  }
}

//С фабричным методом. Передать тип кодера
$factory = createMake('perl');
$developer = $factory->create();
$developer->coding();
```

## MVC

- Модель - бизнес логика, отдает данные.
- Вью - слой презентации, оборачивает данные в нужный формат html, xml, csv, json. Вьюх может быть много разных в одном
  контроллере.
- Контроллер - реагирует на события, принимает запросы от браузера или консоли, валидирует данные, делает запрос к
  модели, готовит данные для вью, отдает ответ.

```php
class Model {
  public function getData() {
    return 'data';
  }
}
```

```php
class View {
  public function showData($data) {
    echo $data;
  }
}
```

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

  public function add($observer) {
    $this->observers[] = $observer;
  }

  public function remove($observer) {
    foreach ($this->observers as $k => $v) {
      if ($observer === $v) {
        unset($this->observers[$k]);
      }
    }
  }

  //Оповестить. Передать объект Observable
  public function notify1() {
    foreach ($this->observers as $observer) {
      $observer->handle1($this);
    }
  }

  //Оповестить. Передать произвольную нагрузку
  public function notify2($payload) {
    foreach ($this->observers as $observer) {
      $observer->handle2($payload);
    }
  }

  //Оповестить. Передать любой объект типа как Event
  public function notify3($event) {
    foreach ($this->observers as $observer) {
      $observer->handle3($event);
    }
  }
}
```

```php
class Observer {

  public function handle1(Observable $observable) {
    var_dump($observable);
  }

  public function handle2($payload) {
    var_dump($payload);
  }

  public function handle3(Event $event) {
    var_dump($event);
  }
}
```

```php
$observable = new Observable;

$observer1 = new Observer;
$observer2 = new Observer;

$observable->add($observer1);
$observable->add($observer2);

$observable->remove($observer2);

$observable->notify1();

$observable->notify2('payload');

class Event {}

$observable->notify3(new Event);
```

## Repository

Посредник между контроллером и хранилищами. Оборачивает в себе коллекцию объектов, и операции.

- Обертка для модели. Содержит логику работы с данными. Модель как источник данных
- Как книжный шкаф. Только брать и класть. Не может создавать и изменять
- Репозитарий это коллекция. Абстрактный слой между контроллером и разными хранилищами
- В ларавел папка Repositories где репозитарии и интерфейсы

**Методы. Получить всех юзеров**

```php
interface Interface1 {
  public function method1();
}
```

**Реализации. Юзеры лежат в базе, в файлах**

```php
class Class1 implements Interface1 {
  public function method1() {
    echo 'Class1';
  }
}

class Class2 implements Interface1 {
  public function method1() {
    echo 'Class2';
  }
}
```

```php
class Controller {

  private $repository;

  public function __construct(Interface1 $class) {
    $this->repository = $class;
  }

  public function method() {
    $this->repository->method1();
  }
}
```

```php
$class = new Controller(new Class1);
$class->method();

$class = new Controller(new Class2);
$class->method();
```

В ларавел вместо внедрения в конструкторе, можно привязать в провайдере.

```php
public function register() {
  $this->app->bind(Interface1::class, Class1::class);
  $this->app->bind(Interface1::class, Class2::class);
}
```

## Singleton

Чтобы класс имел 1 объект. Используется для соединения с бд, ведения логов.

```php
class Singleton {

  private static $instance;

  //Запрещаем создание
  private function __construct() {
  }

  //Запрещаем клонирование
  private function __clone() {
  }

  //Запрещаем десериализацию
  private function __wakeup() {
  }

  public static function get() {
    if (empty(self::$instance)) {
      self::$instance = new self();
    }
    return self::$instance;
  }

  public function do() {
    echo 'do';
  }
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
interface Interface1 {
  //Выполнить активность
  public function do();
}
```

**Классы активностей**

```php
class Coding implements interface1 {
  public function do() {
    echo 'кодить';
  }
}

class Eating implements interface1 {
  public function do() {
    echo 'кушать';
  }
}
```

**Разработчик**

```php
class Developer {

  //Активность
  private $activity;

  //Установить активность
  public function set(interface1 $activity) {
    $this->activity = $activity;
  }

  //Выполнить активность
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