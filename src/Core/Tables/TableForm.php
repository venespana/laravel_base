<?php

namespace VD\Core\Tables;

use Nayjest\Grids\Grid;
use VD\Core\Forms\Form;
use Nayjest\Grids\GridConfig;
use Illuminate\Support\Carbon;
use Nayjest\Grids\FieldConfig;
use Nayjest\Grids\FilterConfig;
use Illuminate\Database\Eloquent\Model;
use VD\Core\GridProviders\EloquentDataProvider;

class TableForm extends Form
{
    const LIKE = 'OPERATOR_LIKE';
    const EQ = 'OPERATOR_EQ';
    const NOT_EQ = 'OPERATOR_NOT_EQ';
    const GT = 'OPERATOR_GT';
    const LS = 'OPERATOR_LS';
    const LSE = 'OPERATOR_LSE';
    const GTE = 'OPERATOR_GTE';
    const IN = 'OPERATOR_IN';

    private $model;
    private $columns;
    private $filters;
    private $gridName;
    private $select;
    public $grid;

    public function __construct(Model $model, array $columns, ?callable $filters = null)
    {
        $this->setModel($model);
        $this->setColumns($columns);
        $this->setFilters($filters);
    }

    public function setModel(Model $model)
    {
        $this->model = $model;
        return $this;
    }

    public function getModel()
    {
        return $this->model;
    }

    public function setColumns(array $columns)
    {
        $this->columns = $columns;
        return $this;
    }

    public function getColumns()
    {
        return $this->columns;
    }

    public function setFilters(?callable $filters)
    {
        $this->filters = $filters;
        return $this;
    }

    public function getFilters()
    {
        return $this->filters;
    }

    public function setName($name)
    {
        $this->gridName = $name;
        return $this;
    }

    public function getName()
    {
        return $this->gridName;
    }

    public function generate(?Model $model = null, ?array $columns = null)
    {
        $configs = new GridConfig();

        $query = $this->model->query();

        $table = $this->model->getTable();
        $field = [];
        $relations = [];
        foreach ($this->getColumns() as $key => $data) {
            $label = $data['label'] ?? $key;
            $sortable = $data['sortable'] ?? false;
            $type = $data['macro'] ?? null;
            $view = $data['view'] ?? $type;
            $callback = false;
            $filter = $data['filter'] ?? false;
            $isRelation = starts_with($key, 'with.');

            // RELATIONS
            if ($isRelation) {
                $key = str_replace('with.', '', $key);
                $relation = isset($data['relation']) ? $data['relation'] : false;
                if ($relation === true) {
                    $relation = [
                        'table' => "{$key}s",
                        'pk' => 'id',
                        'fk' => "{$key}_id"
                    ];
                } else if (is_array($relation) && (!isset($data['relation']['ignore']) || $data['relation']['ignore'] === false)) {
                    $rel = $key;
                    if (isset($data['relation']['table'])) {
                        $rel = $data['relation']['table'];
                        $rel = substr($rel, 0, strlen($rel) - 1);
                    }
                    $rel = "{$rel}_id";

                    $relation = [
                        'table' => $data['relation']['table'] ?? "{$key}s",
                        'pk' => $data['relation']['pk'] ?? $rel,
                        'fk' => $data['relation']['fk'] ?? 'id'
                    ];
                } else {
                    $relation = false;
                }

                $this->select[] = $data['relation']['select'] ?? "{$key}_id";
                if (is_array($relation) && !in_array($relation['table'], $relations)) {
                    $query->join($relation['table'], "{$table}.{$relation['fk']}", "{$relation['table']}.{$relation['pk']}");
                    $relations[] = $relation;
                }
                //FIELDS
            } else {
                $name = $data['name'] ?? $key;
                $this->select[] = strpos($name, '.') === false ? "{$this->model->getTable()}.{$name}" : $name;
            }

            if (!is_null($type)) {
                $callback = $type;
            }

            $_field = (new FieldConfig)
                ->setName($key)
                ->setLabel($label);

            if ($filter !== false) {
                $filterOperator = $data['filter_operator'] ?? static::LIKE;
                $view = is_string($view) ? $view : null;
                $this->addFilter($_field, $data['name'] ?? $key, $filterOperator, $view, $filter);
            }

            if ($callback) {
                $this->addCallback($_field, $callback);
            }

            $_field->setSortable($sortable);

            array_push($field, $_field);
        }


        $query->select(array_unique($this->select));

        $configs->setName($this->getName())
            ->setCachingTime(0)
            ->setColumns($field);

        $this->grid = new Grid($configs);

        if ($this->filters != null) {
            $query = call_user_func($this->filters, $query, $this);
        }

        $this->grid->getConfig()->setDataProvider(new EloquentDataProvider($query));
        
        return $this->grid;
    }

    private
    function addFilter(FieldConfig &$_field, string $name, string $operator, string $view = null, $_filter = false)
    {
        if (!defined(FilterConfig::class . "::{$operator}")) {
            $operator = static::LIKE;
        }
        $filter = (new FilterConfig)->setName($name)
            ->setOperator(constant(FilterConfig::class . "::{$operator}"));

        if (!is_null($view) && view()->exists("vendor.components.{$view}")) {
            $filter->setTemplate("vendor.components.{$view}");
        }

        if (!is_null($_filter) && !is_bool($_filter)) {
            $filter = $_filter;
        }

        $_field->addFilter($filter);
    }

    private function addCallback(FieldConfig &$_field, $callback)
    {
        if (is_string($callback) && method_exists(TableForm::class, $callback)) {
            $model = $this->model;
            $callback = function ($val) use ($callback, $model) {
                return static::$callback($val, $model);
            };
        }
        $_field->setCallback($callback);
    }

    protected static function date(Carbon $value)
    {
        $format = config('app.date_format');
        $date = $value->format($format);
        return $date;
    }

    protected static function color(string $value)
    {
        $text_color = color_is_ligth($value) ? '#000000' : '#FFFFFF';
        $style = 'background-color: #' . $value . '; color: ' . $text_color . '; padding: 5px; font-weigth: bold; text-align: center;';
        $tag = '<aside  class="color-block" style="' . $style . '" >#' . $value . '</aside>';
        return $tag;
    }

    protected static function actions(string $value, Model $model)
    {
        $route = route_name();
        $links = [];

        $data = $model::where('id', $value)->first();
        $title = $data->title ?? $data->name ?? $value;

        if (\Route::has("{$route}.show")) {
            $links[] = [
                'href' => route("{$route}.show", ['id' => $value]),
                'target' => '_self',
                'class' => 'actions view fas fa-eye'
            ];
        }

        if (\Route::has("{$route}.edit")) {
            $links[] = [
                'href' => route("{$route}.edit", ['id' => $value]),
                'target' => '_self',
                'class' => 'actions edit fas fa-edit'
            ];
        }

        if (\Route::has("{$route}.duplicate")) {
            $links[] = [
                'href' => route("{$route}.duplicate", ['id' => $value]),
                'target' => '_self',
                'class' => 'actions view fas fa-copy'
            ];
        }

        if (\Route::has("{$route}.delete")) {
            $links[] = [
                'target' => '_self',
                'class' => 'actions delete fas fa-trash',
                'modal' => [
                    'label' => __('globals.delete_modal_title', ['type' => __("globals.{$route}"), 'value' => $title]),
                    'body' => __('globals.delete_modal_body'),
                    'target' => "#delete-{$value}",
                    'toggle' => 'modal',
                    'cancel' => __('globals.cancel'),
                    'success' => __('globals.success'),
                    'link' => route("{$route}.delete", ['id' => $value]),
                ]
            ];
        }

        $html = view('tables.actions', compact('links'))->render();
        return $html;
    }
}
