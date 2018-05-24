<?php

class AssignedTasksController extends ControllerCrud
{

    protected $model = 'AssignedTasks';

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
                'name' => 'task_id',
                'validators' => [],
                'filters' => ['int']
            ],
            [
                'name' => 'user_id',
                'validators' => [],
                'filters' => ['int']
            ],
            [
                'name' => 'comment',
                'not_required' => true,
                'allowEmpty' => true,
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

        $task = Tasks::findFirst($data['task_id']);
        if (!$task) {
            $this->response->errors(['Task not found']);
            return false;
        }

        $user = Users::findFirst($data['user_id']);
        if (!$user) {
            $this->response->errors(['User not found']);
            return false;
        }

        if ($user->getRole() != 2) {
            $this->response->errors(['The user is not executor']);
            return false;
        }

        $assigned_task = AssignedTasks::findFirst([
            "task_id = ?1 and user_id = ?2",
            'bind' => [
                1 => $data['task_id'],
                2 => $data['user_id'],
            ],
        ]);

        if ($assigned_task) {
            $this->response->errors(['The task (ID: '.$data['task_id'].') is already assigned to the executor (ID: '.$data['user_id'].')']);
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

