### Подключение скриптов

'use strict';
<script defer></script> для скриптов которым требуется доступ ко всему DOM и важен порядок выполнения

<script async></script> для независимых скриптов типа счётчиков и рекламы и неважен порядок выполнения

### События

keydown - для input. первое нажатие отловит пустое
keydup - для input. первое нажатие отловит букву
change - прослушать select и checkbox
paste - вставка с буфера обмена

event.keyCode = 13, 27 ловим Enter, Esc
event.keyCode == 13 && event.shiftKey ловим Shift+Enter
event.preventDefault() не делать действие по умолчанию для события
event.stopPropagation() остановить распространение события
event.target источник события
event.clipboardData.items доступ к буферу обмена

$('#id, #id').on('event event', function (event) {$(this)}) прослушать элементы и события
$(document).delegate('#id, #id', 'event event', function (event) {}) прослушать элементы и события
document.addEventListener('event', function) добавить слушателя для события

### Начальная загрузка

$(document).ready(function () {}) запускается когда DOM готов
$(function() {}) запускается когда DOM готов
$(window).on('load', function () {}) запускается когда готов DOM, картинки, фреймы
window.onload = function () {} запускается когда готов DOM, картинки, фреймы

### Манипуляции с элементами

$('#id').attr('attr', 'attr'); добавить аттрибут
$('#id').removeAttr('attr'); удалить аттрибут
$('#id').addClass('class'); добавить класс
$('#id').removeClass('class'); удалить класс
$('#id').toggleClass('class'); если нет класса то добавит, если есть то удалит
$('#id').prop('checked', 'checked') отметить чекбокс
$('#id').prop('checked', true) отметить чекбокс
$('#id').trigger('click') сделать клик

### Урлы

$.post('url', {'var': 'value'}, function (result) {}); сделать post запрос
window.location.href = 'url' редирект
window.location массив сегментов урла
history.pushState(null, null, window.location.origin + window.location.pathname) изменить урл

### Скрол

$('#id').scrollTop(N) скрол наверх или к элементу
$('html, body').animate({scrollTop: $("id").offset().top - 100, 200) скрол наверх или к элементу

### Разное

if (confirm('?')) {} окно подтверждения
string.length длинна символов
parseInt() приведение к целому числу
regexp = /a-z/i; if (regexp.test(filed)) {} регулярка

### Массивы

.each() обход массива
.map() применение замыкания к каждому элементу массива
.filter() фильтрация значений по критерию
.reduce() сведение массива к одному агрегированному значению

### Таймеры

var timer = setTimeout(function() {$var}, N, $var) ждёт N миллисекунд и запускает функцию
clearTimeout(timer) отменить

var timer = setInterval(function() {$var}, N, $var) запускает функцию каждые N миллисекунд
clearInterval(timer) отменить

### LocalStorage

localStorage.setItem('text', text) сохранить значение
localStorage.getItem('text') получить значение
localStorage.removeItem('text') удалить значение

добавить в массив
var text = JSON.parse(localStorage.getItem('text')).push({id: id});
text.push({id: id});
localStorage.setItem('text', JSON.stringify(text));
for (let i = 0; i < text.length; i++) {imgs[i].id)} перебор массива

### Модуль

```javascript
var module = (function () {
  //приватная переменная
  var prvar = 1;
  //приватный метод
  function prmethod () {}
    //публичный метод
    return {
      pumethod: function (values) {
        return prvar;
		return prmethod;
      },
    }
}());
```

### Самовызывающаяся функция

```javascript
(function name() {
  return {
    var: 1
  };
})();
```

### Объект

```javascript
var obj1 = {
  var: 1,
  method1: function () {
    return this.var;
  }
};
```