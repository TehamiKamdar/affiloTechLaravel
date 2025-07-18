<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\Admin\Approval\IndexService;
use Illuminate\Http\Request;
use App\Helper\Static\Vars;

class ApprovalController extends Controller
{
    protected $service;

    public function __construct(IndexService $service)
    {
        $this->service = $service;
    }

    public function index(Request $request, $status)
    {
        $data = $this->service->init($request, $status);

        $titleData = $data->get("title");
        $title = $titleData["title"];
        $apiTitle = $titleData["api_title"];
         $list = Vars::OPTION_LIST;

        return view("admin.advertiser.approval.index", compact("title", "apiTitle","list"));
    }

    public function ajax(Request $request)
    {
   
        return $this->service->ajax($request);
    }

    public function updateStatus(Request $request)
    {
        return $this->service->updateStatus($request);
    }
}
