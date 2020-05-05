<?php

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
