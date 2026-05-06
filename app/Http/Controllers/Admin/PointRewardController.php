<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Core\PointReward;
use Illuminate\Http\Request;

class PointRewardController extends Controller
{
    public function index()
    {
        $rewards = PointReward::query()->latest('created_at')->paginate(10);
        return view('dashboardview.point_rewards.index', compact('rewards'));
    }

    public function create()
    {
        return view('dashboardview.point_rewards.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'points_required' => 'required|integer|min:1',
            'reward_amount' => 'required|numeric|min:0',
            'description' => 'nullable|string',
        ]);

        $data = $request->except(['_token']);
        $data['is_active'] = $request->has('is_active');
        
        PointReward::query()->create($data);

        return redirect()->route('point-rewards.index')->with('success', 'Point Reward created successfully.');
    }

    public function edit(int $id)
    {
        $point_reward = PointReward::query()->findOrFail($id);
        return view('dashboardview.point_rewards.edit', compact('point_reward'));
    }

    public function update(Request $request, int $id)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'points_required' => 'required|integer|min:1',
            'reward_amount' => 'required|numeric|min:0',
            'description' => 'nullable|string',
        ]);

        $reward = PointReward::query()->findOrFail($id);
        $data = $request->except(['_token', '_method']);
        $data['is_active'] = $request->has('is_active');
        
        $reward->update($data);

        return redirect()->route('point-rewards.index')->with('success', 'Point Reward updated successfully.');
    }

    public function destroy(int $id)
    {
        PointReward::destroy($id);
        return redirect()->route('point-rewards.index')->with('success', 'Point Reward deleted successfully.');
    }
}
