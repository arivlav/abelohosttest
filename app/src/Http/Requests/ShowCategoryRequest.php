<?php

namespace App\Http\Requests;

use App\Common\Request;
use App\Enums\OrderDirection;

class ShowCategoryRequest extends Request
{
    public function validate(): array
    {
        $errors = [];
        $params = $this->getParams();
        if (isset($params['page']) && !is_numeric($params['page'])) {
            $errors[] = "Page must be a number";
        }
        if (isset($params['perPage']) && !is_numeric($params['perPage'])) {
            $errors[] = "PerPage must be a number";
        }
        $sortMap = ['views', 'published_at'];
        if (isset($params['sort']) && !in_array(strtolower($params['sort']), $sortMap)) {
            $errors[] = "Sort must have value from [" . implode(', ', $sortMap) . "]";
        }
        if (isset($params['direction']) && !in_array(strtoupper($params['direction']), [OrderDirection::ASC->value, OrderDirection::DESC->value])) {
            $errors[] = "Direction must have ASC or DESC value";
        }
        return $errors;
    }


}