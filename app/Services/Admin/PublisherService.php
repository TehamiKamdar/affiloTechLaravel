<?php
namespace App\Services\Admin;

use App\Helper\Methods;
use App\Helper\Vars;
use App\Models\Advertiser;
use App\Models\EmailJob;
use App\Models\FetchDailyData;
use App\Models\Publisher;
use App\Models\User;
use App\Models\Website;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Mail\WebsiteApprovedEmail;
use Yajra\DataTables\DataTables;

class PublisherService
{
    private $statusMap = [
        "pending" => "Pending",
        "hold" => "Hold",
        "active" => "Active",
        "rejected" => "Rejected"
    ];

    public function index(Request $request)
    {
        $status = $request->route('status');
        // Lookup the title in the status map
        $title = $this->statusMap[$status] ?? "";
        return collect(['title' => $title, 'status' => $status]);
    }

    public function ajax(Request $request)
    {
        $publishers = User::with('active_website')->select(['id', 'name', 'status', 'email', 'api_token', 'active_website_id', 'created_at'])
            ->where("type", User::PUBLISHER)
            ->where("status", $request->status);

        return DataTables::of($publishers)
        ->addColumn('id', fn($row) => $row->id)
            ->addColumn('active_website', fn($row) => $row->active_website->name ?? "-")
            ->addColumn('action', fn($row) => $this->getActionButtons($row['id']))
            ->editColumn('created_at', fn($publisher) => $publisher->created_at->format('Y-m-d'))
            ->rawColumns(['action'])
            ->make(true);
    }

    private function getActionButtons($publisherId)
    {
        $editUrl = route("admin.publishers.edit", ['publisher' => $publisherId]);
        $viewUrl = route("admin.publishers.view", ['publisher' => $publisherId]);
        $deleteUrl = route("admin.publishers.delete", ['publisher' => $publisherId]);

        return '
            <a href="javascript:void(0)" onclick="goToLogin('.$publisherId.')" class="btn btn-sm btn-glow-dark btn-dark">Go to Login</a>
            <a href="' . $editUrl . '" class="btn btn-sm btn-glow-primary btn-primary">Edit</a>
            <a href="' . $viewUrl . '" class="btn btn-sm btn-glow-info btn-info">View</a>
            <form action="' . $deleteUrl . '" method="POST" style="display:inline;">
                ' . csrf_field() . '
                ' . method_field('DELETE') . '
                <button type="submit" class="btn btn-sm btn-glow-danger btn-danger">Delete</button>
            </form>';
    }

    public function view(Request $request, User $publisher)
    {
        $publisher->load('company');
        return collect(['publisher' => $publisher, 'company' => $publisher->company]);
    }

    public function edit(Request $request, User $publisher)
    {
        $publisher->load(['company', 'websites']);
        return collect([
            'publisher' => $publisher,
            'company' => $publisher->company,
            'websites' => $publisher->websites
        ]);
    }

    public function viewMediakits(Request $request, User $publisher)
    {
        return collect(['publisher' => $publisher]);
    }

    public function viewWebsites(Request $request, User $publisher)
    {
        $publisher->load('websites');
        return collect(['publisher' => $publisher, 'websites' => $publisher->websites]);
    }

    public function viewLockUnlockNetworkByAdvertiser(Request $request, User $publisher)
    {
        $networks = Vars::OPTION_LIST;
        $websites = Website::select(['id', 'name'])->where('user_id', $publisher->id)->get();

        $perPage = $request->per_page ? $request->per_page : 50; // default to 50 items per page
        $page = $request->page ? $request->page : 1;

        $advertisers = Advertiser::query()
            ->select(['advertisers.id', 'advertisers.name', 'advertiser_publishers.locked_status'])
            ->leftJoin(env('RDS_DB_NAME') . '.advertiser_publishers', function($join) use ($publisher) {
                $join->on('advertiser_publishers.advertiser_id', '=', 'advertisers.id')
                    ->where('advertiser_publishers.publisher_id', '=', $publisher->publisher_id);
            });

        if($request->source) {
            $advertisers = $advertisers->where('advertisers.source', $request->source);
        }

        $advertisers = $advertisers
            ->orderBy('advertisers.name', 'ASC')
            ->paginate($perPage, ['*'], 'page', $page);

        $from = ($advertisers->currentPage() - 1) * $advertisers->perPage() + 1;
        $to = min($advertisers->currentPage() * $advertisers->perPage(), $advertisers->total());

        if($request->ajax()) {
            $view = view('admin.publisher.lock-unlock-network.ajax', compact('advertisers', 'from', 'to', 'perPage'))->render();
            return response()->json(['data' => $view]);
        }

        return view("admin.publisher.view", [
            'publisher' => $publisher,
            'advertisers' => $advertisers,
            'id' => $publisher->id,
            'from' => $from,
            'to' => $to,
            'perPage' => $perPage,
            'networks' => $networks ?? [],
            'websites' => $websites ?? [],
        ]);
    }

    public function storeLockUnlockNetworkByAdvertiser(Request $request, User $publisher)
    {
        $websiteID = $request->website;

        foreach ($request->advertisers as $advertiser) {
            $source = $request->source;
            if (empty($request->source)) {
                $source = Advertiser::select('source')->where('id', $advertiser['advertiser_id'])->first();
                $source = $source->source;
            }

            FetchDailyData::updateOrCreate(
                [
                    'path' => 'DataAdvertiserLockUnloadJob',
                    'process_date' => now()->format(Vars::CUSTOM_DATE_FORMAT_3),
                    'key' => $advertiser['advertiser_id'],
                    'publisher_id' => $publisher->publisher_id,
                    'website_id' => $websiteID,
                    'queue' => Vars::ADMIN_WORK,
                    'source' => $source,
                    'type' => Vars::ADVERTISER,
                    'date' => now()->toDateString()
                ],
                [
                    'name' => "Data Advertiser Lock Unload Job",
                    'payload' => json_encode([
                        'source' => $source,
                        'advertiser_id' => $advertiser['advertiser_id'],
                        'publisher_id' => $publisher->publisher_id,
                        'website_id' => $websiteID,
                        'status' => $advertiser['status']
                    ]),
                    'date' => now()->format(Vars::CUSTOM_DATE_FORMAT_2)
                ]
            );
        }

        return response()->json([
            'success' => true,
            'message' => 'You have successfully submitted a request to change the lock and unlock status of the advertiser\'s data for the publisher.'
        ]);
    }

    public function update(Request $request, User $publisher)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:191',
            'email' => 'required|string|email|max:191|unique:users,email,' . $publisher->id,
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        $isChangeEmail = $publisher->email !== $validatedData['email'];
        if ($isChangeEmail) {
            $validatedData['status'] = User::STATUS_PENDING;
            $validatedData['email_verified_at'] = null;
        }

        if ($request->password) {
            $validatedData['password'] = Hash::make($request->password);
        } else {
            unset($validatedData['password']);
        }

        $publisher->update($validatedData);

        if ($isChangeEmail) {
            $publisher->sendEmailVerificationNotification();
        }

        return redirect()->route("admin.publishers.edit", $publisher->id)
            ->with('success', 'You have successfully updated the publisher\'s data.');
    }

    public function publisherStatusUpdate(User $publisher, $status)
    {
        $response = ['type' => 'success', 'message' => 'Publisher Status Successfully Updated.'];

        try {
            $publisher->load('company');
            $publisher->update(['status' => $status, 'force_logout' => 1]);
            $this->createEmailJob($publisher, $status);
        } catch (\Exception $exception) {
            $response = ['type' => 'error', 'message' => $exception->getMessage()];
        }

        return $response;
    }

    private function createEmailJob(User $publisher, $status)
    {
        $jobMap = [
            User::STATUS_HOLD => ['Hold Job', 'HoldJob'],
            User::STATUS_REJECT => ['Reject Job', 'RejectJob'],
            User::STATUS_ACTIVE => ['Approve Job', 'ApproveJob']
        ];

        if (isset($jobMap[$status]) && $publisher->is_publisher) {
            [$name, $path] = $jobMap[$status];
            EmailJob::create([
                'name' => $name,
                'path' => $path,
                'payload' => $publisher->toJson(),
                'date' => now()->format(Vars::CUSTOM_DATE_FORMAT_2)
            ]);
        }
    }

    public function websiteStatusUpdate(Website $website, $status)
    {
        $response = ['type' => 'success', 'message' => 'Website Status Successfully Updated.'];

        try {
            if ($website) {
                $website->load('users');
                $user = $website->users()->first();
                $website->update(['status' => $status]);

                if ($status == Website::ACTIVE && empty($user->active_website_id)) {
                    $user->update(['active_website_id' => $website->id, 'active_website_status' => $status, 'force_logout' => 1]);
                    $user->save();
                    $user->website = $website;
                     Mail::to($user->email)->send(new WebsiteApprovedEmail($user));
                      $response = ['type' => 'success', 'message' => 'Website Status Active Successfully Updated.'];
                } else {
                    $user->update(['force_logout' => 1]);
                    $user->save();
                }
                
            } else {
                $response['message'] = 'Website Status Not Updated.';
            }
        } catch (\Exception $exception) {
            $response = ['type' => 'error', 'message' => $exception->getMessage()];
        }

        return $response;
    }

    public function delete(Request $request, User $publisher)
    {
        $publisherName = $publisher->name;
        $status = $publisher->status;

        $publisher->delete();

        return redirect()->route("admin.publishers.status", ['status' => $status])
        ->with("success", "{$publisherName} Publisher Successfully Deleted.");
    }
}
