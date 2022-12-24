<?php

abstract class Shape
{
    abstract public function area();
}

class Circle extends Shape
{
    private $radius;

    public function __construct($radius)
    {
        $this->radius = $radius;
    }

    public function area()
    {
        return pi() * pow($this->radius, 2);
    }
}

class Rectangle extends Shape
{
    private $width;
    private $height;

    public function __construct($width, $height)
    {
        $this->width = $width;
        $this->height = $height;
    }

    public function area()
    {
        return $this->width * $this->height;
    }
}

class AreaCalculator
{
    protected $shapes;

    public function __construct($shapes = array())
    {
        $this->shapes = $shapes;
    }

    public function sum()
    {
        $area = array();
        foreach ($this->shapes as $shape) {
            $area[] = $shape->area();
        }
        return array_sum($area);
    }
}

$shapes = array(
    new Circle(2),
    new Rectangle(5, 10)
);

$calculator = new AreaCalculator($shapes);
echo $calculator->sum(); // 57.28
