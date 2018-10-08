<?php

namespace App\Admin\Controllers;

use App\Models\Department;
use App\Models\User;
use Qiaweicom\Admin\Form;
use Qiaweicom\Admin\Grid;
use Qiaweicom\Admin\Facades\Admin;
use Qiaweicom\Admin\Layout\Content;
use App\Http\Controllers\Controller;
use Qiaweicom\Admin\Controllers\ModelForm;
use Qiaweicom\Admin\Grid\Column;

class UsersController extends Controller
{
    use ModelForm;

    protected $title = '员工管理';

    /**
     * Index interface.
     *
     * @return Content
     */
    public function index(Content $content)
    {
        return $content
            ->header($this->title)
            ->description('')
            ->body($this->grid());
    }

    /**
     * Edit interface.
     *
     * @param $id
     * @return Content
     */
    public function edit($id, Content $content)
    {
        return $content
            ->header($this->title)
            ->description('修改员工信息')
            ->body($this->form()->edit($id));
    }

    /**
     * Create interface.
     *
     * @return Content
     */
    public function create(Content $content)
    {
        return $content
            ->header($this->title)
            ->description('新增员工')
            ->body($this->form());
    }

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        return Admin::grid(User::class, function (Grid $grid) {
            $grid->model()->orderBy('id', 'desc');
            $grid->id('ID')->sortable();
            $grid->name('员工姓名');
            $grid->column('department.name', '部门');
            $grid->sex('性别')->display(function ($sex) {
                if ($sex == 1) return '男';
                if ($sex == 2) return '女';
                return '未知';
            });
            $grid->mobile('手机号');
            $grid->email('电子邮箱')->prependIcon('envelope');
            $grid->id_number('身份证号码');
            $grid->back_card_number('银行卡号');
            $grid->basic_wage('基本薪资');
            $grid->created_at('入职时间');
        });
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        return Admin::form(User::class, function (Form $form) {

            $form->text('name', '员工姓名');
            $form->select('d_id', '部门')
                ->options(Department::where('pid', 0)->pluck('name', 'id')->toArray())
                ->rules('required');
            $form->select('sex', '性别')->options([1 => '男', 2 => '女']);
            $form->mobile('mobile', '手机号')->rules('required');
            $form->email('email', '电子邮箱')->rules('required');
            $form->text('id_number', '银行卡号')->rules('required');
            $form->text('back_card_number', '身份证号码')->rules('required|regex:/^\d{18}$/');
            $form->number('basic_wage', '基本薪资')->rules('required');
        });
    }
}