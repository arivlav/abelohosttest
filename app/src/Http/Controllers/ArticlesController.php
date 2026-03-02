<?php

namespace App\Http\Controllers;

use App\Enums\Response;
use App\Http\Actions\ArticlesShowAction;

class ArticlesController extends Controller
{
    public function __construct(
        readonly private ArticlesShowAction   $articleAction
    ) {}

    public function show(int $id): string
    {
        $data = $this->articleAction->execute($id);
        if (!$data) {
            getResponseCodeAndMessage(Response::NOT_FOUND);
        }

        return $this->view('article', $data);
    }
}
