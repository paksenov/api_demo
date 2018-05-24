<?php
/**
 * Created by PhpStorm.
 * User: aks
 * Date: 07.07.16
 * Time: 17:09
 */

use Phalcon\Mvc\Model\Transaction\Failed as TxFailed;
use Phalcon\Mvc\Model\Transaction\Manager as TxManager;

class ControllerCrud extends ControllerBase {

    protected $model;
    protected $page;
    protected $order = 'id';
    protected $allow_for_ordering;

    private $is_transaction = false;
    private $transaction;

    public function index()
    {
        $obj = $this->model;

        $page = $this->getPage();
        $this->order = $this->getOrder($this->allow_for_ordering);

        $items = $obj::find([
            'limit' => $page['l'],
            'offset' => $page['o'],
            'order' => $this->order,
        ]);

        if (!count($items)) {
            $this->response->code404(false, [$this->model . ' not found']);
            return;
        }

        $data = [];
        foreach ($items as $k => $item) {
            $data[$k] = $item->toArray();

            /**
             * postData
             */
            if (method_exists($this, 'postData')) {
                $data[$k] = $this->postData($data[$k]);
            }
        }

        $this->response->content(['data' => $data]);
    }

    public function get($id)
    {
        $id = $this->filter->sanitize($id, 'int', 0);

        if ($id <= 0) {
            $this->response->code404(false, [$this->model . ' not found']);
            return;
        }

        $obj = $this->model;

        $item = $obj::findFirst($id);
        if (!$item) {
            $this->response->code404(false, [$this->model . ' not found']);
            return;
        }

        $data = $item->toArray();

        /**
         * postData
         */
        if (method_exists($this, 'postData')) {
            $data = $this->postData($data);
        }

        $this->response->content($data);
    }

    public function search($text = '', $params = [])
    {

        if (!count($params)) {

            /**
             * searchParams
             */
            if (method_exists($this, 'searchParams')) {
                $params = $this->searchParams($text);
            } else {
                $this->response->code404(false, [$this->model . ' not found']);
                return;
            }

        }

        $obj = $this->model;

        $page = $this->getPage();
        $this->order = $this->getOrder($this->allow_for_ordering);

        $items = $obj::find(array_merge($params,[
            'limit' => $page['l'],
            'offset' => $page['o'],
            'order' => $this->order,
        ]));

        if (!count($items)) {
            $this->response->code404(false, [$this->model . ' not found']);
            return;
        }

        $data = [];
        foreach ($items as $k => $item) {
            $data[$k] = $item->toArray();

            /**
             * postData
             */
            if (method_exists($this, 'postData')) {
                $data[$k] = $this->postData($data[$k]);
            }

        }

        $this->response->content(['data' => $data]);
    }

    public function post($input = [])
    {

        $input = $this->getInputData($input);

        if ($input === false) {
            return;
        }

        /**
         * validation fields
         */
        if (method_exists($this, 'validateFields')) {

            $fields = $this->validateFields();

            $obj = new stdClass();
            $validation = new ValidationBase($obj, ['fields' => $fields]);
            if (!$validation->isValid($input)) {
                $this->response->errors($this->getErrorValidation($validation));
                return;
            }
            $data = array_filter((array) $obj);

        } else {
            $data = $input;
        }

        if (!count($data)) {
            $this->response->code400();
            return;
        }

        /**
         * afterValidateBeforeAssign
         */
        if (method_exists($this, 'afterValidateBeforeAssign')) {
            $data = $this->afterValidateBeforeAssign($data, $input);

            if ($data === false) {
                return;
            }
        }

        $obj = $this->model;

        if ($this->request->isPut()) {
            $item = $obj::findFirst($data['id']);
            if (!$item) {
                $this->response->code404(false, [$this->model . ' not found']);
                return;
            }
        } else {
            $item = new $obj();
            if (isset($data['id'])) {
                unset($data['id']);
            }
        }

        $item->assign($data);

        /**
         * beforeSave
         */
        if (method_exists($this, 'beforeSave')) {

            $next = $this->beforeSave($item, $data);

            if ($next !== true) {
                return;
            }

        }

        /**
         * Save
         */

        $is_saved = $item->save();
        if ($is_saved === false) {

            if ($this->is_transaction) {
                $this->rollback();
            }

            //todo: logger
            $errors = [];
            foreach($item->getMessages() as $message) {
                $errors[] = $message->getMessage();
            }
            $this->response->errors($errors);
            return;
        }

        /**
         * afterSave
         */
        if (method_exists($this, 'afterSave')) {

            $next = $this->afterSave($item);

            if ($next !== true) {
                return;
            }

        }

        /**
         * postData
         */
        $item = $item->toArray();
        if (method_exists($this, 'postData')) {
            $item = $this->postData($item);
        }

        $this->response->content(['status' => 'OK', 'item' => $item]);
    }

    public function put()
    {
        $this->post();
    }

    public function softDelete($id, $un_delete = false)
    {

        $id = $this->filter->sanitize($id, 'int', 0);

        $obj = $this->model;

        $item = $obj::findFirst($id);

        if (!$item) {
            $this->response->code404(false, [$this->model . ' not found']);
            return;
        }

        /**
         * beforeDelete
         */
        if (method_exists($this, 'beforeDelete')) {

            /*$item['api_method'] = 'softDelete';*/

            $next = $this->beforeDelete($item);

            if ($next !== true) {
                return;
            }

        }



        if (!$un_delete) {
            $item->setDeleted(1);
        } else {
            $item->setDeleted(0);
        }

        $is_deleted = $item->save();
        if ($is_deleted === false) {
            //todo: logger
            $errors = [];
            foreach($item->getMessages() as $message) {
                $errors[] = $message->getMessage();
            }
            $this->response->errors($errors);
            return;
        }

        /**
         * afterDelete
         */
        if (method_exists($this, 'afterDelete')) {

            $this->afterDelete($item);

        }

        $this->response->content(['status' => 'OK']);
        return;

    }

    public function unDelete($id)
    {
        $this->softDelete($id, true);
    }

    public function delete($id)
    {
        $id = $this->filter->sanitize($id, 'int', 0);

        $obj = $this->model;

        $item = $obj::findFirst($id);
        if (!$item) {
            $this->response->code404(false, [$this->model . ' not found']);
            return;
        }

        /**
         * beforeDelete
         */
        if (method_exists($this, 'beforeDelete')) {

            $next = $this->beforeDelete($item);

            if ($next !== true) {
                return;
            }

        }

        $is_deleted = $item->delete();

        if ($is_deleted === false) {
            //todo: logger
            $errors = [];
            foreach($item->getMessages() as $message) {
                $errors[] = $message->getMessage();
            }
            $this->response->errors($errors);
            return;
        }

        /**
         * afterDelete
         */
        if (method_exists($this, 'afterDelete')) {

            $this->afterDelete($item);

        }

        $this->response->content(['status' => 'OK']);
        return;
    }

    protected function begin()
    {
        $this->is_transaction = true;

        $manager = new TxManager();
        $this->transaction = $manager->get();
    }
    protected function rollback($mess = 'Rollback transaction')
    {
        $this->transaction->rollback($mess);
    }
    protected function commit()
    {
        $this->transaction->commit();
    }

}