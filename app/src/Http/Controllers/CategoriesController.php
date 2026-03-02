<?php

namespace App\Http\Controllers;

use App\Enums\Response;
use App\Http\Actions\CategoriesIndexAction;
use App\Http\Actions\CategoriesShowAction;
use App\Http\Requests\ShowCategoryRequest;

class CategoriesController extends Controller
{
    public function __construct(
        readonly private CategoriesIndexAction $indexAction,
        readonly private CategoriesShowAction $showAction,
        readonly private ShowCategoryRequest $request,
    ) {}
    public function index() {
        $data = $this->indexAction->execute();
        return $this->view('index', $data);
    }

    public function show(int $id) {
        $errors = $this->request->validate();
        if ($errors) {
            return getResponseCodeAndMessage(Response::FAILURE, $errors);
        }
        $data = $this->showAction->execute($id);
        if (!$data) {
            http_response_code(Response::NOT_FOUND);
            return getResponseCodeAndMessage(Response::NOT_FOUND);
        }
        return $this->view('category', $data);
    }
}