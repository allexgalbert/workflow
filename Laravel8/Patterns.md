# Паттерны

- **Adapter** - привести неудобный интерфейс класса, в интерфейс совместимый с вашим кодом, предоставляя для этого прослойку, а внутри себя используя оригинальный неудобный интерфейс.


## Adapter

2 библиотеки с разными интерфейсами и методами. Нужно заменить одну на другую, не меняя код по всему проекту.

Библиотека 1

```php
interface Interface1 {public function method1()}
class Class1 implements Interface1 {public function method1() {}}
```

Библиотека 2

```php
interface Interface2 {public function method2()}
class Class2 implements Interface2 {public function method2() {}}
```

Адаптер. Объект класса Class2 создать в конструкторе

```php
class Adapter implements Interface1 {

  //подключить библиотеку 2
  public function __construct() {$this->adapter = new Class2}
  
  //названия методов как в библиотеке 1, но внутри вызывают методы библиотеки 2
  public function method1() {$this->adapter->method2()}
}
```

Адаптер. класс Adapter наследовать от класса Class2

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
```
	
В провайдере указать ларавелу, объект какого класса создавать, когда идет обращение к интерфейсу

```php
$bindings = [Interface1::class => Adapter::class]
```

