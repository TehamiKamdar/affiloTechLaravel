<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Website;
use App\Services\Admin\PublisherService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PublisherController extends Controller
{
    protected $publisherService;

    public function __construct(PublisherService $publisherService)
    {
        $this->publisherService = $publisherService;
    }

    public function index(Request $request)
    {
        $data = $this->publisherService->index($request);
        $title = $data->get('title');
        $heading = "Publisher";
        $status = $data->get('status');

        return view('admin.publisher.index', compact('title', 'status', 'heading'));
    }

    public function ajax(Request $request)
    {
        return $this->publisherService->ajax($request);
    }

    protected function loadPublisherData(User $publisher, Request $request, $viewName, array $additionalData = [])
    {
        $data = $this->publisherService->view($request, $publisher);
        $publisher = $data->get('publisher');
        $viewData = array_merge(
            ['publisher' => $publisher, 'id' => $publisher->id],
            $additionalData
        );

        return view($viewName, $viewData);
    }

    public function view(Request $request, User $publisher)
    {
        return $this->loadPublisherData($publisher, $request, 'admin.publisher.view', [
            'company' => $this->publisherService->view($request, $publisher)->get('company')
        ]);
    }

    public function edit(Request $request, User $publisher)
    {
        $data = $this->publisherService->edit($request, $publisher);

        return view('admin.publisher.edit', [
            'publisher' => $data->get('publisher'),
            'company' => $data->get('company'),
            'id' => $data->get('publisher')->id
        ]);
    }

    public function viewMediakits(Request $request, User $publisher)
    {
        return $this->loadPublisherData($publisher, $request, 'admin.publisher.media-kits');
    }

    public function viewWebsites(Request $request, User $publisher)
    {
        $data = $this->publisherService->viewWebsites($request, $publisher);

        return view('admin.publisher.website', [
            'publisher' => $data->get('publisher'),
            'id' => $data->get('publisher')->id,
            'websites' => $data->get('websites') ?? []
        ]);
    }

    public function viewBillingInfo(Request $request, User $publisher)
    {
        return $this->loadPublisherData($publisher, $request, 'admin.publisher.billing-information');
    }

    public function viewPaymentInfo(Request $request, User $publisher)
    {
        return $this->loadPublisherData($publisher, $request, 'admin.publisher.payment-information');
    }

    public function viewLockUnlockNetworkByAdvertiser(Request $request, User $publisher)
    {
        return $this->publisherService->viewLockUnlockNetworkByAdvertiser($request, $publisher);
    }

    public function storeLockUnlockNetworkByAdvertiser(Request $request, User $publisher)
    {
        return $this->publisherService->storeLockUnlockNetworkByAdvertiser($request, $publisher);
    }

    public function update(Request $request, User $publisher)
    {
        return $this->publisherService->update($request, $publisher);
    }

    public function publisherStatusUpdate(User $publisher, $status)
    {
        $response = $this->publisherService->publisherStatusUpdate($publisher, $status);

        return redirect()->back()->with($response['type'], $response['message']);
    }

    public function websiteStatusUpdate(Website $website, $status)
    {
        $response = $this->publisherService->websiteStatusUpdate($website, $status);

        return redirect()->back()->with($response['type'], $response['message']);
    }

    public function delete(Request $request, User $publisher)
    {
        return $this->publisherService->delete($request, $publisher);
    }

    public function deleteAllUsers(Request $request)
    {
       $ids = $request->ids;
       foreach($ids as $id){
           $user = User::find($id);
           if($user){
              $user->delete();
           }
       }

      return response()->json(['status' => true, 'message' => 'Users deleted successfully!']);
    }

    public function accessLogin(Request $request, $id)
    {
        if ($id) {
            $user = User::find($id);
            Auth::login($user);
            $login = new LoginController();
            return $login->authenticated($request, $user);
        } else {
            $user = User::where('type', User::SUPER_ADMIN)->first();
            Auth::login($user);
            $route = 'admin.dashboard';
        }

        return redirect(route($route));
    }
}
