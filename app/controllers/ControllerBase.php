<?php
/**
 * Created by PhpStorm.
 * User: aks
 * Date: 29.06.16
 * Time: 14:01
 */

class ControllerBase extends \Phalcon\Mvc\Controller {

    protected function getPage()
    {
        $page = $this->request->getQuery('p', 'int', 0);
        $limit = $this->request->getQuery('l', 'int', 10);

        if ($page > 0) {
            $offset = ($page-1)*$limit;
        } else {
            $offset = 0;
        }

        return [
            'p' => $page,
            'l' => $limit,
            'o' => $offset
        ];
    }

    protected function getOrder($allow = [])
    {
        $order = 'id ASC';

        $orderby = $this->request->getQuery('orderby', 'string', 0);

        //print_r($allow);

        if (!$orderby) {
            return $order;
        } else {
            $order_prams = explode(',', $orderby);
            if (count($order_prams) != 2) {
                return $order;
            }

            if (!in_array($order_prams[0], $allow)) {
                return $order;
            }

            if (!in_array($order_prams[1], ['asc', 'desc'])) {
                return $order;
            }

            $order = "{$order_prams[0]} $order_prams[1]";
        }

        return $order;
    }

    protected function getErrorValidation(ValidationBase $validation)
    {
        $errors = [];
        foreach($validation->getMessages() as $message) {
            $errors[] = $message->getMessage();
        }
        return $errors;
    }

    protected function is_base64($str){
        if ( base64_encode(base64_decode($str, true)) === $str){
            return true;
        } else {
            return false;
        }
    }

    protected function getInputData($input = [])
    {
        if ($this->request->isPost() && $this->request->hasPost('data')) {
            $input = json_decode($this->request->getPost('data'), true);
        }

        if ($this->request->isPut() && $this->request->hasPut('data')) {
            $input = json_decode($this->request->getPut('data'), true);
        }

        if (!count($input)) {
            $input = $this->request->getJsonRawBody(true);
        }

        if (!count($input)) {
            $this->response->code400();
            return false;
        }

        return $input;
    }

}