<?php

namespace VD\Controllers\Traits;

use VD\Core\Tables\TableForm;
use VD\Controllers\Controller;

trait TableTrait
{
    protected $name = null;
    protected $title = 'List';
    protected $header = null;
    protected $view = 'adminlte::table';

    protected static $formModel = null;

    /**
     * Set table header text
     *
     * @param string $header
     * @return self
     */
    public function setHeader(string $header) : self
    {
        $this->header = $header;
        return $this;
    }

    /**
     * get Table header text
     *
     * @return string
     */
    public function getHeader() : string
    {
        $header = $this->header;
        if (is_null($header)) {
            $header = class_basename($this);
            $header = str_replace('Controller', '', $header);
        }
        return $header;
    }

    /**
     * Prepare data from model in table view
     *
     * @param mixed $model
     * @param array $columns
     * @param callable|null $filters
     * @return array
     */
    protected function table($model, array $columns = [], ?callable $filters = null) : array
    {
        $message = $this->view;
        $data = null;
        $statusCode = 200;

        try {
            if (is_null($model)) {
                throw new \Exception('$model must be a String or Model, ' . gettype($model) . ' type given;');
            } elseif (is_string($model)) {
                $model = new $model;
            }
            $data = [
                'table' => (new TableForm($model, $columns, $filters))->generate(),
                'title' => $this->title,
                'header' => $this->getHeader()
            ];
        } catch (\Exception $ex) {
            $message = __('VD::http.422', [
                'file' => $ex->getFile(),
                'line' => $ex->getLine(),
                'message' => "With Message: {$ex->getMessage()}"
            ]);
            $data = $ex->getTrace();
            $statusCode = 422;
        }

        return [$message, $data, $statusCode];
    }
}
