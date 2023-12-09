<?php
namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Models\Farm;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use App\Http\Resources\FarmCollection;

class UserFarmsController extends Controller
{
    public function index(Request $request, User $user): FarmCollection
    {
        $this->authorize('view', $user);

        $search = $request->get('search', '');

        $farms = $user
            ->farms()
            ->search($search)
            ->latest()
            ->paginate();

        return new FarmCollection($farms);
    }

    public function store(Request $request, User $user, Farm $farm): Response
    {
        $this->authorize('update', $user);

        $user->farms()->syncWithoutDetaching([$farm->id]);

        return response()->noContent();
    }

    public function destroy(Request $request, User $user, Farm $farm): Response
    {
        $this->authorize('update', $user);

        $user->farms()->detach($farm);

        return response()->noContent();
    }
}
