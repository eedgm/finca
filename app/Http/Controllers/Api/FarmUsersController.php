<?php
namespace App\Http\Controllers\Api;

use App\Models\Farm;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserCollection;

class FarmUsersController extends Controller
{
    public function index(Request $request, Farm $farm): UserCollection
    {
        $this->authorize('view', $farm);

        $search = $request->get('search', '');

        $users = $farm
            ->users()
            ->search($search)
            ->latest()
            ->paginate();

        return new UserCollection($users);
    }

    public function store(Request $request, Farm $farm, User $user): Response
    {
        $this->authorize('update', $farm);

        $farm->users()->syncWithoutDetaching([$user->id]);

        return response()->noContent();
    }

    public function destroy(Request $request, Farm $farm, User $user): Response
    {
        $this->authorize('update', $farm);

        $farm->users()->detach($user);

        return response()->noContent();
    }
}
