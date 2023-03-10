<?php

namespace App\Http\Controllers\Web\Admin;

use App\Base\Filters\Master\CommonMasterFilter;
use App\Base\Libraries\QueryFilter\QueryFilterContract;
use App\Base\Services\ImageUploader\ImageUploaderContract;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Driver\CreateDriverRequest;
use App\Http\Requests\Admin\SoS\CreateSosRequest;
use App\Http\Requests\Admin\SoS\UpdateSosRequest;
use App\Models\Admin\Driver;
use App\Models\Admin\ServiceLocation;
use App\Models\Admin\Sos;
use App\Models\Admin\TripRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;


class TripRequestController extends Controller
{
    /**
     * The Driver model instance.
     *
     * @var Driver
     */
    protected $driver;

    /**
     * The User model instance.
     *
     * @var User
     */
    protected $user;

    /**
     * The
     *
     * @var App\Base\Services\ImageUploader\ImageUploaderContract
     */
    protected $imageUploader;


    /**
     * DriverController constructor.
     *
     * @param Driver $driver
     */
    public function __construct(Sos $sos, ImageUploaderContract $imageUploader, User $user)
    {
        $this->sos = $sos;
        $this->imageUploader = $imageUploader;
        $this->user = $user;
    }

    /**
     * Get all drivers
     * @return JsonResponse
     */
    public function index()
    {
        $page = trans('pages_names.emergency_number');

        $main_menu = 'emergency_number';
        $sub_menu = '';

        return view('admin.sos.index', compact('page', 'main_menu', 'sub_menu'));
    }

    public function getAllSos(QueryFilterContract $queryFilter)
    {
        $url = request()->fullUrl(); //get full url

        $query = TripRequest::companyKey()->where('user_type', 'admin');
        $results = $queryFilter->builder($query)->customFilter(new CommonMasterFilter)->paginate();

        return view('admin.sos._sos', compact('results'));
    }

    /**
     * Create Driver View
     *
     */
    public function create()
    {
        $page = trans('pages_names.add_sos');
        $cities = ServiceLocation::companyKey()->whereActive(true)->get();
        $main_menu = 'emergency_number';
        $sub_menu = '';

        return view('admin.sos.create', compact('cities', 'page', 'main_menu', 'sub_menu'));
    }

    /**
     * Create Driver.
     *
     * @param CreateDriverRequest $request
     * @return JsonResponse
     */
    public function store(CreateSosRequest $request)
    {
        $created_params = $request->only(['service_location_id', 'name','number']);
        $created_params['active'] = 1;
        $created_params['created_by']=auth()->user()->id;
        $created_params['company_key'] = auth()->user()->company_key;
        $created_params['user_type'] = 'admin';
        Sos::create($created_params);

        $message = trans('success_messages.sos_added_succesfully');
        return redirect('sos')->with('success', $message);
    }

    public function getById(Sos $sos)
    {
        $page = trans('pages_names.edit_sos');
        $cities = ServiceLocation::whereActive(true)->get();
        $main_menu = 'emergency_number';
        $sub_menu = '';

        return view('admin.sos.update', compact('cities', 'sos', 'page', 'main_menu', 'sub_menu'));
    }

    public function update(UpdateSosRequest $request, Sos $sos)
    {
        $updated_params = $request->all();
        $sos->update($updated_params);
        $message = trans('succes_messages.sos_updated_succesfully');
        return redirect('sos')->with('success', $message);
    }

    public function toggleStatus(Sos $sos)
    {
        $status = $sos->isActive() ? false: true;
        $sos->update(['active' => $status]);

        $message = trans('succes_messages.sos_status_changed_succesfully');
        return redirect('sos')->with('success', $message);
    }

    public function delete(Sos $sos)
    {
        $sos->delete();

        $message = trans('succes_messages.sos_deleted_succesfully');
        return $message;
    }
}
