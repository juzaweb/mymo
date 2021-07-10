<?php

namespace Mymo\Backend\Http\Controllers\Backend;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Mymo\Backend\Http\Controllers\BackendController;
use Mymo\PostType\Models\Page;

class PageController extends BackendController
{
    public function index()
    {
        return view('mymo::backend.pages.index', [
            'title' => trans('mymo::app.pages')
        ]);
    }
    
    public function getData(Request $request)
    {
        $search = $request->get('search');
        $status = $request->get('status');
        
        $sort = $request->get('sort', 'id');
        $order = $request->get('order', 'desc');
        $offset = $request->get('offset', 0);
        $limit = $request->get('limit', 20);
        
        $query = Page::query();
        
        if ($search) {
            $query->where(function ($subquery) use ($search) {
                $subquery->orWhere('name', 'like', '%'. $search .'%');
                $subquery->orWhere('description', 'like', '%'. $search .'%');
            });
        }
        
        if (!is_null($status)) {
            $query->where('status', '=', $status);
        }
        
        $count = $query->count();
        $query->orderBy($sort, $order);
        $query->offset($offset);
        $query->limit($limit);
        $rows = $query->get();
        
        foreach ($rows as $row) {
            $row->thumb_url = $row->getThumbnail();
            $row->edit_url = route('admin.page.edit', [$row->id]);
        }
        
        return response()->json([
            'total' => $count,
            'rows' => $rows
        ]);
    }
    
    public function form($id = null)
    {
        $this->addBreadcrumb([
            'title' => trans('mymo::app.pages'),
            'url' => route('admin.page.index')
        ]);

        $model = Page::firstOrNew(['id' => $id]);
        return view('mymo::backend.pages.form', [
            'model' => $model,
            'title' => $model->name ?: trans('mymo::app.add_new')
        ]);
    }
    
    public function save(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:250',
            'status' => 'required|in:0,1',
            'thumbnail' => 'nullable|string|max:250',
        ], [], [
            'name' => trans('mymo::app.name'),
            'status' => trans('mymo::app.status'),
            'thumbnail' => trans('mymo::app.thumbnail'),
        ]);
        
        $model = Page::firstOrNew(['id' => $request->id]);
        $model->fill($request->all());
        $model->save();

        return $this->success([
            'message' => trans('mymo::app.successfully'),
            'redirect' => route('admin.page.index')
        ]);
    }
    
    public function bulkActions(Request $request)
    {
        $request->validate([
            'ids' => 'required',
            'action' => 'required',
        ], [], [
            'ids' => trans('mymo::app.pages')
        ]);

        $ids = $request->post('ids');
        $action = $request->post('action');

        try {
            DB::beginTransaction();
            switch ($action) {
                case 'delete':
                    Page::destroy($ids);
                    break;
            }
            DB::commit();
        } catch (\Exception $exception) {
            DB::rollBack();
            return $this->error([
                'message' => $exception->getMessage()
            ]);
        }

        return $this->success([
            'message' => trans('mymo::app.successfully')
        ]);
    }
}
