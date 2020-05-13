---
title: PHP Reflection
date: 2020-03-24 20:48:21
tags: 
- php
- aspect-oriented programming
img: /medias/featureimages/php_sublime.jpg
categories: php
---
##### 摘要

- Annotation 假註解，真程式碼
- 何謂 Annotation & Reflection
- 實作 PHP Annotation

## Annotation 假註解，真程式碼

從 Doctrine 開始說起， Symfony 是我所接觸的第一個 PHP Framework，使用 Symfony  所整合的 PHP ORM Library Doctrine ，在  Doctrine 當中，曾經有一段程式碼是我當時作為一個新手工程師無法理解的，就是所謂的 Annotation，也就是下方註解當中的 `@ORM\Entity` 部分的程式碼。

```php
use Doctrine\ORM\Mapping as ORM;
    
    /**
     * @ORM\Entity
     * @ORM\Table(name="products")
     */
    class Product
    {
        /** 
         * @ORM\Id
         * @ORM\Column(type="integer")
         * @ORM\GeneratedValue
         */
        protected $id;
        /** 
         * @ORM\Column(type="string") 
         */
        protected $name;
    
        // .. (other code)
    }
```

這是一個定義 `Entity` 的 PHP 程式碼， `Entity` 就如同 Laravel 裡面的 `Model` 一樣，被視為對照到資料庫的一個資料表的物件，然而一直使我不能明白的是，上述的註解為什麼是有 **功能** 的，而並非只是一個單純的註解，包含了 `@ORM\Entity` 、 `@ORM\Table(name="products")` 等等

要是不設定這些參數的話，便無法使用 Doctrine ，換言之，這些註解並非是提供給開發者看的註解而已，而是實際影響程式運作的程式碼。

## 何謂 Annotation & Reflection

> 我的解讀： Annotation 是一種語法設計，用以將註解的 PHPDoc，裡面所設置的 meta-data(通常是 class )自動代理與處理其指定的物件或其 property , method 的一種設計

後來看到 Doctrine 官方說明後，了解到由於 PHP 本身並沒有提供 Annotation 的實作，所以 Doctrine 開發一個 Component(Library) 來實作這一部分，然而這不足以讓我明白 **為什麼註解解變成了程式碼**，到底是用了什麼魔法才使 PHP 可以執行到註解裡面的程式？，然而這個問題後來被擱在一旁，也就忘記繼續了解。

一直到後來接觸 Laravel ，想要了解 Laravel 的 Dependency Injection 機制之後，才發現原來 PHP 有個叫做 `Reflection` 的 API 和相關物件可以使用，這便是 **為什麼註解解變成了程式碼** 的真正原因，這個原因就是：

> 使用 PHP Reflection 便可以讀取到物件中的註解，從而實作 PHP Annotation

這同時也是 Doctrine 和 Laravel 實作依賴注入所使用到的方法。

## 實作 PHP Annotation

那麼，Reflection 又可以做什麼呢？Reflection 是一個可以在執行階段時，取得 以類別名稱為參數，並取得該類別自身的 property 和 Method 、甚至是註解的一種物件。

這裡簡單實作一個例子，我們在一個類別 `Foo` 裡面的 private property 定義一個 Annotation ，並搭配使用 Annotation 將其實體化出來。

```php
class Foo
{
    /**
    * @MyAnnotation(myProperty="value")
    */
    private $bar;
}
    
$reflectionClass = new ReflectionClass(Foo::class);
$property = $reflectionClass->getProperty('bar');
var_dump($property->getDocComment());
```

這裏 `var_dump` 出來所顯示的是一個字串，其值如下：

```php
/**
* @MyAnnotation(myProperty="value")
*/
```

再來是要解析這一段註解，這裏我們為方便，直接使用 Doctrine 的 Library `AnnotationReader` 來協助我們解析。

```php
<?
require __DIR__ . '/vendor/autoload.php';

use Doctrine\Common\Annotations\AnnotationReader;

class Foo
{
    /**
     * @MyAnnotation(myProperty="value")
     */
    private $bar;
}

/**
 * @Annotation
 */
final class MyAnnotation
{
    public $myProperty;
}

$reflectionClass = new ReflectionClass(Foo::class);
$property = $reflectionClass->getProperty('bar');

var_dump($property->getDocComment());
$reader = new AnnotationReader();

$myAnnotation = $reader->getPropertyAnnotation(
    $property,
    MyAnnotation::class
);

var_dump($myAnnotation);
```

這段程式碼的流程主要是這樣

- 建立 ReflectionClass 物件，並指向 `Foo` 這個物件宣告所在的位置。
- 然後利用 ReflectionClass 讀取指定名稱的屬性 `bar` ，還可以取出 `bar` 的 PHP Doc
- 印出 `bar` 的 Doc
- 再搭配 Doctrine 的 `AnnotationReader` ，呼叫 `getPropertyAnnotation` 去讀取屬性並且將此屬性的 PHP Doc 與其指向的類別和指定 property value 設定好之後，實體化出來。
- 印出 `$myAnnotation`

##### 補充：

在 Reflection 的幫助下便可實作到以下幾個設計

- PHP Annotation
- Dependency Injection
- Aspect Oriented Programming(AOP)：[可參考 Hyperf Framework 的 AOP](https://hyperf.wiki/#/zh-cn/aop)

或許有了 Reflection 可能還可以做到更多功能，本篇並非要完全理解 Reflection ，而是理解如何實作 Annotation 以及 Laravel 的 DI 為目的而已

##### 參考文獻

- [Understanding annotations](https://php-annotations.readthedocs.io/en/latest/UsingAnnotations.html)
- [Doctrine Annotation](https://www.doctrine-project.org/projects/doctrine-annotations/en/1.6/index.html)
