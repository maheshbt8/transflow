<?php

namespace App\Http\Controllers\Admin;
use App\User;
use Auth;
use App\marketing_campaign;
use Spatie\Permission\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreUsersRequest;
use App\Http\Requests\Admin\UpdateUsersRequest;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;



class MarketingCampaignController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (! checkpermission('marketing_campaign')) {
            return abort(401);
        }

        $marketing_campaign = marketing_campaign::orderBy('created_on', 'desc')->get();
        return view('admin.marketing_campaign.index', compact('marketing_campaign'));
    }
}