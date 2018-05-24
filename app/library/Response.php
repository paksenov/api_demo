<?php
/**
 * Created by PhpStorm.
 * User: aks
 * Date: 24.06.16
 * Time: 15:56
 */

class Response extends Phalcon\Http\Response {

    protected $is_errors = false;

    public function content(array $content = [])
    {
        $this->setJsonContent($content);
    }

    public function code400($send = false, $errors = [])
    {
        if (count($errors)) {
            $this->errors($errors);
        }

        $this->setStatusCode(400, "Bad Request");
        if ($send) {
            parent::send();
        }
    }

    public function code404($send = false, $errors = [])
    {
        if (count($errors)) {
            $this->errors($errors);
        }

        $this->setStatusCode(404, "Not Found");
        if ($send) {
            parent::send();
        }
    }

    public function code500($send = false, $errors = [])
    {
        if (count($errors)) {
            $this->errors($errors);
        }

        $this->setStatusCode(500, "Internal server error");
        if ($send) {
            parent::send();
        }
    }

    public function isErrors()
    {
        return $this->is_errors;
    }

    public function errors(array $errors = [])
    {
        $this->is_errors = true;
        $this->content([
            'errors' => $errors
        ]);
    }

    public function send()
    {
        $this->setContentType('application/json', 'utf-8');
        parent::send();
    }

}