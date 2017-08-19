<?php namespace $NAME$\Upload\Controllers;

use $NAME$\Upload\Requests\CreateUploadRequest;
use $NAME$\Upload\Requests\UpdateUploadRequest;
use $NAME$\Upload\Repositories\UploadRepository;
use App\Http\Requests\IndexRequest;
use App\Http\Requests\ShowRequest;
use Illuminate\Http\JsonResponse;
use zgldh\Scaffold\AppBaseController;

class UploadController extends AppBaseController
{

    public function __construct(UploadRepository $uploadRepo)
    {
        $this->repository = $uploadRepo;
        $this->middleware('auth');
    }

    /**
     * Display a listing of the Upload.
     *
     * @param IndexRequest $request
     * @return Response
     */
    public function index(IndexRequest $request)
    {
        $with = $request->getWith();
        $data = $this->repository->datatables(null, $with);

        return $data;
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
        $upload->load($request->getWith());

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
        $upload = $this->repository->findWithoutFail($id);
        $upload->load($request->getWith());

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
        $upload = $this->repository->findWithoutFail($id);

        if (empty($upload)) {
            return $this->sendError('Upload not found');
        }
        $all = $request->all();

        $upload = $this->repository->update($all, $id);
        $upload->load($request->getWith());

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
        $upload = $this->repository->findWithoutFail($id);

        if (empty($upload)) {
            return $this->sendError('Upload not found');
        }

        $this->repository->delete($id);

        return $this->sendResponse($upload, 'Upload deleted successfully.');
    }
}
