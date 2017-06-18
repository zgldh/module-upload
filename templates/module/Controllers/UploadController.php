<?php namespace WoXuanWang\Upload\Controllers;

use App\Http\Requests\IndexRequest;
use App\Http\Requests\ShowRequest;
use WoXuanWang\ActionLog\Repositories\ActionLogRepository;
use WoXuanWang\Upload\Requests\CreateUploadRequest;
use WoXuanWang\Upload\Requests\UpdateUploadRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use WoXuanWang\Upload\Repositories\UploadRepository;
use Yajra\Datatables\Facades\Datatables;
use zgldh\Scaffold\AppBaseController;

class UploadController extends AppBaseController
{

    public function __construct(UploadRepository $uploadRepo)
    {
        $this->repository = $uploadRepo;
        $this->middleware('auth:admin');
    }

    /**
     * Display a listing of the Upload.
     *
     * @param IndexRequest $request
     * @return Response
     */
    public function index(IndexRequest $request)
    {
        if ($request->ajax()) {
            $with = $request->getWith();
            $data = $this->repository->datatables(null, $with);
            return $data;
        } else {
            ActionLogRepository::log("查看", "上传内容(upload):", \Auth::user());
            return view('WoXuanWang\Upload::index');
        }
    }

    /**
     * Store a newly created Upload in storage.
     *
     * @param CreateUploadRequest $request
     * @return JsonResponse
     */
    public function store(CreateUploadRequest $request)
    {
        $input = $request->all();
        $input['user_id'] = $request->user()->id;

        $upload = $this->repository->create($input);
        ActionLogRepository::log("新增", "上传内容(upload):" . $upload->id, \Auth::user());
        return $this->sendResponse($upload, 'Upload saved successfully.');
    }

    /**
     * Display the specified Upload.
     *
     * @param  int $id
     *
     * @return JsonResponse
     */
    public function show($id, ShowRequest $request)
    {
        $this->repository->with($request->getWith());
        $upload = $this->repository->findWithoutFail($id);

        if (empty($upload)) {
            return $this->sendError('Upload not found');
        }

        return $this->sendResponse($upload, '');
    }

    /**
     * Update the specified Upload in storage.
     *
     * @param  int $id
     * @param UpdateUploadRequest $request
     *
     * @return JsonResponse
     */
    public function update($id, UpdateUploadRequest $request)
    {
        ActionLogRepository::log("编辑", "上传内容(upload):" . $id, \Auth::user());
        $upload = $this->repository->findWithoutFail($id);

        if (empty($upload)) {
            return $this->sendError('Upload not found');
        }
        $all = $request->all();

        $upload = $this->repository->update($all, $id);

        return $this->sendResponse($upload, 'Upload updated successfully.');
    }

    /**
     * Remove the specified Upload from storage.
     *
     * @param  int $id
     *
     * @return JsonResponse
     */
    public function destroy($id)
    {
        ActionLogRepository::log("删除", "上传内容(upload):" . $id, \Auth::user());
        $upload = $this->repository->findWithoutFail($id);

        if (empty($upload)) {
            return $this->sendError('Upload not found');
        }

        $this->repository->delete($id);
        return $this->sendResponse($upload, 'Upload deleted successfully.');
    }
}
