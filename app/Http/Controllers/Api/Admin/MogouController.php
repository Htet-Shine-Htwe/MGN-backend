<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MogouActionRequest;
use App\Models\Mogou;
use App\Repo\Admin\Mogou\MogouActionRepo;
use App\Repo\Admin\Mogou\MogouRepo;
use App\Traits\CacheResponse;
use App\Vaildations\MogouValidation;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class MogouController extends Controller
{

    use CacheResponse;
    public function __construct(protected MogouRepo $mogouRepo, protected MogouActionRepo $mogouActionRepo)
    {
    }

    public function index(Request $request)
    {
        $collection =  $this->mogouRepo
            ->withCategories()
            ->get($request);

        if ($request->has('mogou_total_count') == true) {
            $collection->each(function ($mogou) {

                $key = $mogou->rotation_key;

                if(request('mogou_total_count'))
                {
                    $mogou->append('total_view_count');
                }

                $subMogou = $mogou->subMogous($key)->select('title','views')->latest()->limit(3)->get();

                $mogou->setRelation('subMogous', $subMogou);
            });
        }

        return response()->json([
            'mogous' => $collection
        ]);
    }

    public function show(Mogou $mogou)
    {
        $mogou->load('categories');

        return response()->json([
            'mogou' => $mogou
        ]);
    }

    public function create(MogouActionRequest $request)
    {
        $mogou = $this->mogouActionRepo->create($request);

        return response()->json([
            'mogou' => $mogou
        ], Response::HTTP_CREATED);
    }

    public function update(MogouActionRequest $request, Mogou $mogou)
    {
            $mogou = $this->mogouActionRepo->update($request, $mogou);
            return response()->json([
                'mogou' => $mogou
            ]);

    }

    public function updateStatus(Request $request)
    {
        $request->validate([
            'mogou_id' => 'required|exists:mogous,id',
            'status' => 'required|'.MogouValidation::status()
        ]);

        $mogou = Mogou::find($request->input('mogou_id'));

        $this->mogouActionRepo->updateStatus($mogou, $request->input('status'));

        return response()->json([
            'message' => "Mogou status updated to {$mogou->statusName} successfully"
        ]);
    }

    public function bindCategory(Request $request)
    {
        $request->validate([
            'mogou_id' => 'required|exists:mogous,id',
            'category_id' => 'required|exists:categories,id|not_in:'.implode(',', Mogou::find($request->input('mogou_id'))->categories->pluck('id')->toArray())
        ]);

        $mogou = Mogou::find($request->input('mogou_id'));

        $this->mogouActionRepo->addNewCategory($mogou, $request->input('category_id'));

        return response()->json([
            'message' => 'Category Added successfully',
            'mogou' => $mogou->load('categories')
        ]);
    }

    public function unbindCategory(Request $request)
    {
        $mogou = Mogou::findOrFail($request->input('mogou_id'));
        $request->validate([
            'mogou_id' => 'required|exists:mogous,id',
            'category_id' => 'required|exists:categories,id|in:'.implode(',', $mogou->categories->pluck('id')->toArray())
        ]);

        $this->mogouActionRepo->removeCategory($mogou, $request->input('category_id'));

        return response()->json([
            'message' => 'Category removed successfully',
            'mogou' => $mogou->load('categories')
        ]);
    }

    public function delete(Request $request)
    {
        $mogou = Mogou::find($request->input('mogou_id'));

        $this->mogouActionRepo->delete($mogou);

        return response()->json([
            'message' => 'Mogou deleted successfully'
        ]);
    }
}
