<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;
use App\Exports\GenericExport;
use Illuminate\Database\Eloquent\Collection;
use Maatwebsite\Excel\Facades\Excel;
trait QueryParameters
{
    protected string $thisTable = '';

    /**
     * 
     * @param Builder $query
     * return Void
     */
    public function setParameters(Builder $query): void
    {
        $this->setScopes($query);

        $this->setingQueryStrings($query);

        $this->setOrder($query);

        $this->setLimit($query);
    }

    public function getResults(Builder|Collection $query)
    {

        if($this->Export){
            return $this->export($query);
        }elseif($this->Paginated){
            return $query->paginate($this->limit);
        }

        return $query->get();
    }

    public function export(Builder|Collection $query)
    {
        $exp = new GenericExport();
        $exp->query = $query;
        $exp->column_names = $this->column_names;

        $title = $this->title ?? 'export';
        return Excel::download($exp, "{$title}.xlsx");
    }

    public function handle(Builder|Collection $query)
    {
        $this->setParameters($query);
        return $this->getResults($query);
    }

    public function setScopes(Builder $query)
    {
        foreach($this->queryStrings as $key => $value){
            if(method_exists($query->getModel(), "scope{$key}")){
                $query->$key($value);
                unset($this->queryStrings[$key]);
            }
                
        }
    }

    private function setingQueryStrings(Builder $query): void
    {
        foreach ($this->queryStrings as $key => $value) {
            if (is_array($value)) {
                if ($key === 'betweenDates') {
                    $this->setBetween($query, $value);
                } else {
                    $query->whereIn($key, $value);
                }
            } else {
                $this->setWhereLike($query, $key, $value);
            }
        }
    }

    private function setOrder(Builder $query): void
    {
        request('order_by') && $query->orderBy($this->order_by, $this->order);
    }

    private function setLimit(Builder $query): void
    {
        $this->limit && $query->limit($this->limit);
    }

    protected function setBetween(Builder $query, array $array):void
    {
        foreach ($array as $field => $dates) {
            $query->whereBetween($this->thisTable . $field, [$dates['start'], $dates['end']]);
        }
    }

    private function setWhereLike(Builder $query, string $field, $value):void
    {
        $field !== 'status' && $value = "%{$value}%";
        $query->where($this->thisTable . $field, 'like', $value);
    }

}