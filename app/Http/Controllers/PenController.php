<?php

namespace App\Http\Controllers;

use App\Models\Pen;
use App\Models\Pig;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PenController extends Controller
{
    /**
     * Display a listing of the pens.
     */
    public function index()
    {
        $pens = Pen::with(['assignedPersonnel', 'pigs' => function($q) {
            $q->whereNotIn('status', ['Sold', 'Disposed'])
              ->orderByRaw("FIELD(health_status, 'Critical', 'Sick', 'Recovering', 'Healthy')");
        }, 'pigs.pen'])->get();

        foreach($pens as $pen) {
            // Calculate revenue from sold pigs in this pen
            $soldPigsIds = \App\Models\Pig::where('pen_id', $pen->id)->where('status', 'Sold')->pluck('id');
            $pen->revenue = \App\Models\PigSale::whereIn('pig_id', $soldPigsIds)->sum('amount');
            
            // Basic Income = Revenue - Batch Cost (assuming batch_cost is numeric-ish)
            $investment = (float) filter_var($pen->batch_cost, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
            $pen->income = $pen->revenue - $investment;
        }
        
        $allPigs = \App\Models\Pig::with('pen')->whereNotIn('status', ['Sold', 'Disposed'])->orderBy('tag')->get();
        $workers = \App\Models\User::where('role', 'farm_worker')->get();
        
        return view('pens.index', compact('pens', 'workers', 'allPigs'));
    }

    /**
     * Store a newly created pen in storage.
     */
    /**
     * Generate a unique sequential tag for a pen, avoiding DB collisions.
     */
    public static function generateUniqueTag(string $penName, int $sequence): string
    {
        $base = strtoupper(preg_replace('/[^A-Za-z0-9]/', '', $penName));
        if (empty($base)) $base = 'PIG';
        $base = substr($base, 0, 6); // Max 6 chars prefix

        $candidate = $base . '-' . sprintf('%03d', $sequence);
        // Avoid duplicates by bumping sequence if already used
        while (Pig::where('tag', $candidate)->exists()) {
            $sequence++;
            $candidate = $base . '-' . sprintf('%03d', $sequence);
        }
        return $candidate;
    }

    /**
     * Return what the next auto-generated ear tag would be for a given pen name.
     */
    public function nextTag(Request $request)
    {
        $penName = $request->input('pen_name', 'PEN');
        $existingCount = (int) $request->input('existing_count', 0);
        $tag = self::generateUniqueTag($penName, $existingCount + 1);
        return response()->json(['tag' => $tag]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'section' => 'nullable|string|max:255',
            'status' => 'nullable|string|max:255',
            'healthy_pigs' => 'nullable|integer',
            'sick_pigs' => 'nullable|integer',
            'avg_weight' => 'nullable|string|max:255',
            'target_weight' => 'nullable|string|max:255',
            'batch_cost' => 'nullable|string|max:255',
            'feed_cons' => 'nullable|string|max:255',
            'profit_margin' => 'nullable|string|max:255',
            'progress' => 'nullable|integer',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date',
            'pig_count' => 'nullable|integer|min:0|max:200',
            'assigned_to' => 'nullable|exists:users,id',
        ]);

        return \DB::transaction(function () use ($validated) {
            $pen = Pen::create($validated);

            $pigCount = (int) ($validated['pig_count'] ?? 0);
            $sequence = 1;
            for ($i = 0; $i < $pigCount; $i++) {
                $tag = self::generateUniqueTag($pen->name, $sequence);
                $sequence++; // advance past what was just used
                // Also advance past the actual tag's sequence to avoid re-checking
                $tagSeq = (int) substr($tag, strrpos($tag, '-') + 1);
                if ($tagSeq >= $sequence) $sequence = $tagSeq + 1;

                Pig::create([
                    'tag'          => $tag,
                    'pen_id'       => $pen->id,
                    'birth_date'   => $pen->start_date,
                    'health_status'=> 'Healthy',
                    'status'       => 'Active',
                ]);
            }

            if ($pigCount > 0) {
                $pen->update(['healthy_pigs' => $pigCount, 'sick_pigs' => 0]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Pen "' . $pen->name . '" created with ' . $pigCount . ' pig(s)!',
                'pen'     => $pen->load('pigs'),
            ]);
        });
    }

    /**
     * Update the specified pen in storage.
     */
    public function update(Request $request, Pen $pen)
    {
        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'section' => 'nullable|string|max:255',
            'status' => 'nullable|string|max:255',
            'healthy_pigs' => 'nullable|integer',
            'sick_pigs' => 'nullable|integer',
            'avg_weight' => 'nullable|string|max:255',
            'target_weight' => 'nullable|string|max:255',
            'batch_cost' => 'nullable|string|max:255',
            'feed_cons' => 'nullable|string|max:255',
            'profit_margin' => 'nullable|string|max:255',
            'progress' => 'nullable|integer',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date',
            'assigned_to' => 'nullable|exists:users,id',
        ]);

        $pen->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Pen updated successfully!',
            'pen' => $pen
        ]);
    }

    /**
     * Remove the specified pen from storage.
     */
    public function destroy(Pen $pen)
    {
        $pen->delete();

        return response()->json([
            'success' => true,
            'message' => 'Pen deleted successfully!'
        ]);
    }

    public function show($id)
    {
        // Kukunin ang pen at isasama lahat ng pigs na 'Healthy' o 'Active'
        $pen = Pen::with(['pigs' => function($query) {
            $query->whereNotIn('status', ['Sold', 'Disposed'])
                  ->orderByRaw("FIELD(health_status, 'Critical', 'Sick', 'Recovering', 'Healthy')");
        }])->findOrFail($id);

        return view('pens.show', compact('pen'));
    }
}
