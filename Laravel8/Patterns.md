# Паттерны

- **Adapter** - привести неудобный интерфейс класса, в интерфейс совместимый с вашим кодом, предоставляя для этого прослойку, а внутри себя используя оригинальный неудобный интерфейс.
- **Builder** - строитель для создания объектов пошагово по разным сценариям.
- **Command** - заворачивает команды в отдельные классы, команды как отдельные объекты, прослойка между объектами которые вызывают команды, и объектом который обрабатывает команды.


## Adapter

2 библиотеки с разными интерфейсами и методами. Нужно заменить одну на другую, не меняя код по всему проекту.

**Библиотека 1**

```php
interface Interface1 {public function method1()}
class Class1 implements Interface1 {public function method1() {}}
```

**Библиотека 2**

```php
interface Interface2 {public function method2()}
class Class2 implements Interface2 {public function method2() {}}
```

**Адаптер. объект класса Class2 создать в конструкторе**

```php
class Adapter implements Interface1 {
  //подключить библиотеку 2
  public function __construct() {$this->adapter = new Class2}
  
  //названия методов как в библиотеке 1, но внутри вызывают методы библиотеки 2
  public function method1() {$this->adapter->method2()}
}
```

**Адаптер. класс Adapter наследовать от класса Class2**

```php
class Adapter extends Class2 {
  //названия методов как в библиотеке 1, но внутри вызывают методы библиотеки 2
  public function method1() {$this->method2()}
}
```

```php
//Работа с библиотекой 1
$var = app(Class1::class);
$var->method1();
	
//Работа с адаптером. по факту с библиотекой 2
$var = app(Adapter::class);
$var->method1();
	
//Можно улучшить так
$var = app(Interface1::class);
$var->method1();

//в провайдере указать ларавелу, объект какого класса создавать, когда идет обращение к интерфейсу
$bindings = [Interface1::class => Adapter::class]
```

## Builder

Строитель это отдельный класс, который создает объект, мы сами пошагово заполняем его свойства, и он возвращает готовый объект. Логику создания объекта выносим в отдельный класс.

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

**Менеджер** создает сценарии создания объектов. Руководит билдером. Сценарии создают объекты, с по-разному заполненными полями.

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

Заворачивает команды в отдельные классы. Команды как отдельные объекты.
Прослойка между объектами которые вызывают команды, и объектом который обрабатывает.

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
