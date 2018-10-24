<?php

namespace Ximdex\Controllers\Traits;

use Ximdex\Core\Tables\TableForm;
use Ximdex\Controllers\Controller;

trait TableTrait
{
    protected $filters = null;
    protected $name = null;
    protected $title = '';

    protected static $formModel = null;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    protected function table($model, array $columns = [], array $filters = [])
    {
        $data = null;
        $statusCode = 200;

        try {
            if (is_null($model)) {
                throw new \Exception('$model must be a String or Model, ' . gettype($model) . ' type given;');
            } elseif (is_string($model)) {
                $model = new $model;
            }
            $data = (new TableForm($model, $columns, $filters))->generate();
        } catch (\Exception $ex) {
            $message = __('ximdex::http.422', [
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
