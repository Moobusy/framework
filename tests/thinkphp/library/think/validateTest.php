<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

/**
 * Validate类测试
 */

namespace tests\thinkphp\library\think;

use think\Validate;

class validateTest extends \PHPUnit_Framework_TestCase
{

    public function testCheck()
    {
        $rule = [
            'name'  => 'require|max:25',
            'age'   => 'number|between:1,120',
            'email' => 'email',
        ];
        $msg = [
            'name.require' => '名称必须',
            'name.max'     => '名称最多不能超过25个字符',
            'age.number'   => '年龄必须是数字',
            'age.between'  => '年龄只能在1-120之间',
            'email'        => '邮箱格式错误',
        ];
        $data = [
            'name'  => 'thinkphp',
            'age'   => 10,
            'email' => 'thinkphp@qq.com',
        ];
        $validate = new Validate($rule, $msg);
        $result   = $validate->check($data);
        $this->assertEquals(true, $result);
    }

    public function testRule()
    {
        $rule = [
            'name'       => 'require|alphaNum|max:25',
            'account'    => 'alphaDash|min:4|length:4,30',
            'age'        => 'number|between:1,120',
            'email'      => 'email',
            'url'        => 'activeUrl',
            'ip'         => 'ip',
            'score'      => 'float|gt:60',
            'status'     => 'integer|in:0,1,2',
            'begin_time' => 'after:2016-3-18',
            'end_time'   => 'before:2016-10-01',
            'info'       => 'require|array',
            'value'      => 'same:100',
            'bool'       => 'boolean',

        ];
        $data = [
            'name'       => 'thinkphp',
            'account'    => 'liuchen',
            'age'        => 10,
            'email'      => 'thinkphp@qq.com',
            'url'        => 'thinkphp.cn',
            'ip'         => '114.34.54.5',
            'score'      => '89.15',
            'status'     => 1,
            'begin_time' => '2016-3-20',
            'end_time'   => '2016-5-1',
            'info'       => [1, 2, 3],
            'zip'        => '200000',
            'date'       => '16-3-8',
            'ok'         => 'yes',
            'value'      => 100,
            'bool'       => 'true',

        ];
        $validate = new Validate($rule);
        $validate->rule('zip', '/^\d{6}$/');
        $validate->rule([
            'ok'   => 'require|accepted',
            'date' => 'date|dateFormat:y-m-d',
        ]);
        $result = $validate->batch()->check($data);
        $this->assertEquals(true, $result);
    }

    public function testMsg()
    {
        $validate = new Validate();
        $validate->message('name.require', '名称必须');
        $validate->message([
            'name.require' => '名称必须',
            'name.max'     => '名称最多不能超过25个字符',
            'age.number'   => '年龄必须是数字',
            'age.between'  => '年龄只能在1-120之间',
            'email'        => '邮箱格式错误',
        ]);
    }

    public function testConfig()
    {
        $validate = new Validate();
        $validate->config('value_validate', ['name', 'age', 'email']);
        $validate->config([
            'value_validate'  => ['name', 'age', 'email'],
            'exists_validate' => ['zip'],
            'scene'           => [
                'edit' => ['name', 'age', 'email'],
            ],
        ]);
    }

    public function testMake()
    {
        $rule = [
            'name'  => 'require|max:25',
            'age'   => 'number|between:1,120',
            'email' => 'email',
        ];
        $msg = [
            'name.require' => '名称必须',
            'name.max'     => '名称最多不能超过25个字符',
            'age.number'   => '年龄必须是数字',
            'age.between'  => '年龄只能在1-120之间',
            'email'        => '邮箱格式错误',
        ];
        $validate = Validate::make($rule, $msg);
    }

    public function testExtend()
    {
        $validate = new Validate(['name' => 'check:1']);
        $validate->extend('check', function ($value, $rule) {return $rule == $value ? true : false;});
        $data   = ['name' => 1];
        $result = $validate->check($data);
        $this->assertEquals(true, $result);
    }

    public function testScene()
    {
        $rule = [
            'name'  => 'require|max:25',
            'age'   => 'number|between:1,120',
            'email' => 'email',
        ];
        $msg = [
            'name.require' => '名称必须',
            'name.max'     => '名称最多不能超过25个字符',
            'age.number'   => '年龄必须是数字',
            'age.between'  => '年龄只能在1-120之间',
            'email'        => '邮箱格式错误',
        ];
        $data = [
            'name'  => 'thinkphp',
            'age'   => 10,
            'email' => 'thinkphp@qq.com',
        ];
        $validate = new Validate($rule);
        $validate->scene('edit', ['name', 'age']);
        $validate->scene('edit');
        $result = $validate->check($data);
        $this->assertEquals(true, $result);
    }

}