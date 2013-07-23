<?php
namespace Ackintosh;

class Y
{
    const WIDTH  = 500;
    const HEIGHT = 500;

    private $im;
    private $black;
    private $operationStack = array();

    public function __construct()
    {
        // Initializing image
        $this->im = imagecreate(self::WIDTH, self::HEIGHT);
        imagecolorallocate($this->im, 255, 255, 255);

        $this->black = imagecolorallocate($this->im, 0, 0, 0);

        // X line
        imageline($this->im, self::WIDTH / 2, 0, self::WIDTH / 2, self::HEIGHT, $this->black);
        // Y line
        imageline($this->im, 0, self::HEIGHT / 2, self::WIDTH, self::HEIGHT / 2, $this->black);
    }

    public static function equals()
    {
        return new self();
    }

    public function __call($name, $args)
    {
        list($method, $argument) = $this->extractMethodName($name);
        if ($method === null) throw new \Exception();

        return $this->$method($argument);
    }

    private function extractMethodName($name)
    {
        if (preg_match('/^_[0-9]+X$/', $name)) {
            $argument = str_replace(array('_', 'X'), array('', ''), $name);
            return array('XmultipliedBy', (int)$argument);
        } elseif (preg_match('/^_[0-9]+X_squared$/', $name)) {
            $argument = str_replace(array('_', 'X_squared'), array('', ''), $name);
            return array('X_squaredMultipliedBy', (int)$argument);
        }
        return array(null, null);
    }

    private function XmultipliedBy($num = 1)
    {
        $this->operationStack[] = function ($x) use ($num) {
            return $x * $num;
        };
        return $this;
    }

    private function X_squaredMultipliedBy($num = 1)
    {
        $this->operationStack[] = function ($x) use ($num) {
            return $x * $x * $num;
        };
        return $this;
    }

    public function _X()
    {
        $this->operationStack[] = function ($x) {
            return $x;
        };
        return $this;
    }

    public function _X_squared()
    {
        $this->operationStack[] = function ($x) {
            return $x * $x;
        };
        return $this;
    }

    public function _plus($num)
    {
        if ($num < 0) throw new InvalidArgumentException();
        $this->operationStack[] = function ($x) use ($num) {
            return $num;
        };
        return $this;
    }

    public function _minus($num)
    {
        if ($num < 0) throw new InvalidArgumentException();
        $this->operationStack[] = function ($x) use ($num) {
            return $num - $num - $num;
        };
        return $this;
    }

    public function draw()
    {
        $prev_x = null;
        $prev_y = null;

        for ($x = $this->conv2negative(self::WIDTH / 2); $x <= (self::WIDTH / 2); $x++) {
            $y = 0;
            foreach ($this->operationStack as $c) {
                $y += $c($x);
            }

            if (self::HEIGHT >= $y && $y >= $this->conv2negative(self::HEIGHT))
                $this->drawLine($x, $y, $prev_x, $prev_y);
            $prev_x = $x;
            $prev_y = $y;
        }
        return $this;
    }

    public function drawLine($x, $y, $prev_x, $prev_y)
    {
        $prev_x = $prev_x ? : $x;
        $prev_y = $prev_y ? : $y;

        list($x, $y) = $this->value2point($x, $y, self::WIDTH, self::HEIGHT);
        list($prev_x, $prev_y) = $this->value2point($prev_x, $prev_y, self::WIDTH, self::HEIGHT);

        imageline($this->im, $prev_x, $prev_y, $x, $y, $this->black);
    }


    public function output()
    {
        $this->draw();
        header('Content-Type: image/png');
        imagepng($this->im);
        imagedestroy();
    }

    /* Utility functions */

    /**
     * Convert values to coordinates for image.
     *
     * @param int $x X coordinate
     * @param int $y Y coordinate
     * @return array
     */
    public function value2point($x, $y)
    {
        $x += self::WIDTH / 2;
        $y = ($y > 0) ? (self::HEIGHT / 2) - $y : (self::HEIGHT / 2) + abs($y);
        return array($x, $y);
    }

    /**
     * Convert a negative.
     *
     * @param int $num
     * @param int
     */
    public function conv2negative($num)
    {
        $num = abs($num);
        return $num - $num - $num;
    }

}

