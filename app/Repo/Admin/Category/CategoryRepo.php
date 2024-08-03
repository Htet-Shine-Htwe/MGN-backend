<?php
namespace App\Repo\Admin\Category;


use App\Http\Requests\CategoryActionRequest;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryRepo  implements \App\Contracts\ModelRepoInterface
{
    protected Request $request;

    public function get(Request $request) : mixed
    {
        $this->request = $request;
        return $this->collection();
    }

    public function collection() : mixed
    {
        return Category::search($this->request->search)
        ->orderByMogousCount()
        ->paginate($this->request->limit ?? 10)
        ->withQueryString();
    }

    public function create(CategoryActionRequest $request) : Category
    {
        $request->validate([
            'title' => 'unique:categories,title'
        ]);

        return Category::create($request->validated());
    }

    public function update(CategoryActionRequest $request, Category $category) : Category
    {
        $request->validate([
            'title' => 'unique:categories,title,'.$category->id
        ]);
        $category->update($request->validated());
        return $category;
    }

    public function delete(Category $category) : bool
    {
        return $category->delete();
    }

}
