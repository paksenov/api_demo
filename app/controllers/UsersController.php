<?php

class UsersController extends ControllerCrud
{

    protected $model = 'Users';

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
                'name' => 'email',
                'validators' => [
                    'Email' => [
                        'message' => 'The :field is not email',
                    ],
                    'StringLength' => [
                        'max' => 150,
                        'min' => 8,
                        'messageMaximum' => 'We don\'t like really long :field ',
                        'messageMinimum' => 'We want more than just their initials :field',
                    ]
                ],
                'filters' => ['striptags', 'email', 'trim']
            ],
            [
                'name' => 'fio',
                'validators' => [
                    'StringLength' => [
                        'max' => 50,
                        'min' => 5,
                        'messageMaximum' => 'We don\'t like really long :field ',
                        'messageMinimum' => 'We want more than just their initials :field'
                    ]
                ],
                'filters' => ['striptags', 'string', 'trim']
            ],
            [
                'name' => 'role',
                'validators' => [
                    'InclusionIn' => [
                        'message' => 'The role must be 1 or 2 (1 - client, 2 - executor)',
                        'domain' => [1, 2]
                    ],
                ],
                'filters' => ['int']
            ],
        ];
    }

    protected function beforeSave($item, $data)
    {

        $user = false;

        if ($this->request->isPost()) {
            $user = Users::findFirst([
                "email = ?1",
                'bind' => [
                    1 => $data['email'],
                ],
            ]);
        }

        if ($this->request->isPut()) {
            $user = Users::findFirst([
                "email = ?1 and id != ?2",
                'bind' => [
                    1 => $data['email'],
                    2 => $data['id'],
                ],
            ]);
        }

        if ($user) {
            $this->response->errors(['Email already exists']);
            return false;
        }

        return true;
    }

    public function clients()
    {

        parent::search('', [
            "role = 1"
        ]);
    }

    public function executors()
    {
        parent::search('', [
            "role = 2"
        ]);
    }

    public function search($text = '', $params = [])
    {
        $email = $this->request->getQuery('email', 'email', '');
        parent::search('', [
            "email = ?1",
            'bind' => [
                1 => $email
            ],
        ]);
    }


}

