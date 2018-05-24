<?php

class TasksController extends ControllerCrud
{

    protected $model = 'Tasks';

    protected function validateFields()
    {
        return [
            [
                'name' => 'id',
                'not_required' => $this->request->isPost() ? true : false,
                'validators' => [],
                'filters' => ['int']
            ],
            [
                'name' => 'user_id',
                'validators' => [],
                'filters' => ['int']
            ],
            [
                'name' => 'name',
                'validators' => [
                    'StringLength' => [
                        'max' => 150,
                        'min' => 5,
                        'messageMaximum' => 'We don\'t like really long :field ',
                        'messageMinimum' => 'We want more than just their initials :field'
                    ]
                ],
                'filters' => ['striptags', 'string', 'trim']
            ],
        ];
    }

    protected function beforeSave($item, $data)
    {

        $user = Users::findFirst($data['user_id']);
        if (!$user) {
            $this->response->errors(['User not found']);
            return false;
        }

        if ($user->getRole() != 1) {
            $this->response->errors(['The user is not client']);
            return false;
        }

        return true;
    }

    protected function beforeDelete($item)
    {

        $assigned_task = AssignedTasks::findFirst([
            "task_id = ?1",
            'bind' => [
                1 => $item->getId()
            ],
        ]);

        if ($assigned_task) {
            $this->response->errors(['The task (ID: '.$item->getId().') can not be deleted because it is assigned to the executor']);
            return false;
        }

        return true;
    }


    public function user($user_id = '')
    {
        $user_id = $this->filter->sanitize($user_id, 'int', 0);
        $this->search('', [
            "user_id = ?1",
            'bind' => [
                1 => $user_id,
            ],
        ]);
    }

}

