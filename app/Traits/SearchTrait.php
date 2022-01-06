<?php

namespace App\Traits;

trait SearchTrait
{

    protected function search($term)
    {
        $columns = (array) $this->searchable;
        $query = $this->newModelQuery();

        $terms = trim($term);
        $terms = rtrim($terms);
        $terms = str_replace([',','+','<', '>', '@', '(', ')', '~'], '',$terms);
        //$terms = (array) explode('+',$terms);

        foreach($columns as $key => $column)
        {
            //info('Current key is '.$key.' Searching for '.$term.' on column '.$column);
            if($key == 0) {
                $query->where("{$column}",'like',"%{$term}%");
            } else {
                $query->orWhere("{$column}",'like',"%{$term}%");
            }

        }
        return $query;
    }
}
