<?php

namespace App\Request;


class Request
{
    public const METHOD_GET = 'GET';
    public const METHOD_POST = 'POST';


    protected static $instance = null;

    public $requestMethod = '';
    public $params = [];
    protected $input = [];

    private function __construct()
    {
        if (isset($_SERVER['REQUEST_METHOD'])) {
            $this->requestMethod = strtoupper($_SERVER['REQUEST_METHOD']);
            $input = $this->getBody();
            $this->input = [];
            if (function_exists('mb_parse_str')) {
                mb_parse_str($input, $this->input);
            } else {
                parse_str($input, $this->input);
            }
        }
    }

    public static function getInstance(): Request
    {
        if (null === self::$instance) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    public function getFile(string $fileIndex, $tempFolder = '/tmp'): ?string
    {
        $files = $_FILES ?: [];
        $file = $files[$fileIndex] ?? null;
        if ($file === null) {
            return null;
        }
        $result = $tempFolder . '/' . $file['name'];
        if (\copy($file['tmp_name'], $result)) {
            return $result;
        }
        return null;
    }

    public function get($key = null)
    {
        $key = (string)$key;

        $value = null;

        if (isset($this->params[$key])) {
            $value = $this->params[$key];
        } else if ($this->isSetPost($key)) {
            $value = $this->getPost($key);
        } else if ($this->isSetGet($key)) {
            $value = $this->getGet($key);
        }

        return $value;
    }

    public function getGet($key = null, $default = null)
    {
        if (null === $key && $this->isGet()) {
            return $_GET;
        }

        $key = (string)$key;

        return $this->isSetGet($key) ? $_GET[$key] : $default;
    }

    public function getPost($key = null, $default = null)
    {
        if (null === $key && $this->isPost()) {
            return (array)$_POST;
        }

        $key = (string)$key;

        return $this->isSetPost($key) ? $_POST[$key] : $default;
    }

    public function isSetGet($key): bool
    {
        if ($key === null) {
            return false;
        }

        if ($this->isGet() && isset($_GET[$key]) && ($_GET[$key] !== null)) {
            return true;
        }

        return false;
    }

    public function isSetPost($key): bool
    {
        if ($key === null) {
            return false;
        }

        if ($this->isPost() && isset($_POST[$key]) && ($_POST[$key] !== null)) {
            return true;
        }

        return false;
    }

    public function isGet(): bool
    {
        if (is_array($_GET) && !empty($_GET)) {
            return true;
        }

        return false;
    }

    public function isPost(): bool
    {
        if (is_array($_POST) && !empty($_POST)) {
            return true;
        }

        return false;
    }


    public function getBody(): string
    {
        return (string)file_get_contents('php://input');
    }


    public function getUploadedFileContent($index = 'file'): array
    {
        $uploadedFile = $_FILES;
        $parsedFile = file_get_contents($_FILES[$index]['tmp_name']);
        if (empty($parsedFile)) {
            return [];
        }
        $data = json_decode($parsedFile, true);
        if (json_last_error()) {
            trigger_error(json_last_error_msg());
            return [];
        }

        return $data;
    }
}