<?php

namespace App\Http\Controllers;

use App\Traits\ApiResponse;
use App\Traits\QueryParameters;

abstract class Controller
{
    use ApiResponse, QueryParameters;

    /**
     * Except params from request for paginate.
     *
     * @var array
     */
    protected $requestExcept = [
        'limit',
        'order_by',
        'order',
        'page',
        'count',
        'current_page',
        'last_page',
        'next_page_url',
        'per_page',
        'previous_page_url',
        'total',
        'url',
        'from',
        'to',
        'registerActive',
    ];

    /**  Array to Export in Excel, set columns names */
    public $column_names = [];

    public $Paginated = false;

    public $Export = false;

    /**
     * Array of params to filter queries
     */
    public array $queryStrings;

    protected $limit;
    protected $order_by;
    protected $order;
    protected $hasRegisterActive;


    public function __construct()
    {
        if (request()->header('Paginated'))
            $this->Paginated = true;

        if (request()->header('Export'))
            $this->Export = true;

        if (request()->isMethod('get'))
            $this->getQueriesparameters();
    }

    private function getQueriesparameters()
    {
        $this->queryStrings = request()->except($this->requestExcept);

        $this->limit                = request()->input('limit')         ?? '100';
        $this->order_by             = request()->input('order_by')      ?? 'id';
        $this->order                = request()->input('order')         ?? 'desc';

        if (strpos($this->order_by, '.') === false)
            $this->order_by = $this->thisTable . $this->order_by;

        if ($this->limit >= 100)
            $this->limit = 100;
    }
}
