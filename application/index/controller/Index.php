<?php
namespace app\index\controller;
use think\Controller;

class Index extends Controller
{
    public function index()
    {
        echo  "hello world";
        //return $this->fetch();
    }

    public function friend()
    {
        return $this->fetch();
    }

    public function hello($name = 'ThinkPHP5')
    {
        return 'hello,' . $name;
    }

    public function all()
    {
        return $this->fetch();
    }

    public function all2()
    {
        return $this->fetch();
    }
}
