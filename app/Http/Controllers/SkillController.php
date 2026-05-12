<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Skill;
use App\Models\User;

class SkillController extends Controller
{
    public function index(Request $request)
    {
        // Debug: Log all request parameters
        \Log::info('SkillController index called with params: ' . json_encode($request->all()));
        
        $query = Skill::with('user')->where('status', 'active');
        
        // Search functionality
        if ($request->has('search') && !empty($request->search)) {
            $searchTerm = $request->search;
            // Split search term into individual words for better matching
            $searchWords = explode(' ', $searchTerm);
            
            $query->where(function($q) use ($searchWords) {
                foreach ($searchWords as $word) {
                    if (!empty(trim($word))) {
                        $q->orWhere('title', 'LIKE', '%' . trim($word) . '%')
                          ->orWhere('description', 'LIKE', '%' . trim($word) . '%')
                          ->orWhere('category', 'LIKE', '%' . trim($word) . '%');
                    }
                }
            });
        }
        
        // Filter by category
        if ($request->has('category') && !empty($request->category)) {
            $query->where('category', $request->category);
        }
        
        // Filter by price range
        if ($request->has('min_price') && $request->min_price) {
            $query->where('price', '>=', $request->min_price);
        }
        
        if ($request->has('max_price') && $request->max_price) {
            $query->where('price', '<=', $request->max_price);
        }
        
        // Sort functionality
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        
        if ($sortBy === 'price_low') {
            $query->orderBy('price', 'asc');
        } elseif ($sortBy === 'price_high') {
            $query->orderBy('price', 'desc');
        } elseif ($sortBy === 'rating') {
            $query->orderBy('rating', 'desc');
        } else {
            $query->orderBy($sortBy, $sortOrder);
        }
        
        $skills = $query->paginate(12);
        
        // Get categories for filter
        $categories = Skill::where('status', 'active')
            ->distinct()
            ->pluck('category')
            ->filter()
            ->sort();
        
        return view('skills.index', compact('skills', 'categories'));
    }
    
    public function show($id)
    {
        $skill = Skill::with(['user', 'reviews.user'])->findOrFail($id);
        
        // Increment view count
        $skill->increment('views_count');
        
        // Get related skills
        $relatedSkills = Skill::where('category', $skill->category)
            ->where('id', '!=', $skill->id)
            ->where('status', 'active')
            ->take(4)
            ->get();
        
        // If user is not authenticated, show limited view
        if (!auth()->check()) {
            session()->flash('info', 'Sign in to contact providers and save services');
        }
        
        return view('skills.show', compact('skill', 'relatedSkills'));
    }
    
    public function create()
    {
        return view('skills.create');
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'category' => 'required|string',
            'price' => 'required|numeric|min:0',
            'price_type' => 'required|in:fixed,negotiable',
        ]);
        
        $skill = Skill::create([
            'user_id' => auth()->id(),
            'title' => $request->title,
            'description' => $request->description,
            'category' => $request->category,
            'price' => $request->price,
            'price_type' => $request->price_type,
            'status' => 'active',
        ]);
        
        return redirect()->route('dashboard')
            ->with('success', 'Skill posted successfully!');
    }
    
    public function edit($id)
    {
        $skill = Skill::where('user_id', auth()->id())->findOrFail($id);
        return view('skills.edit', compact('skill'));
    }
    
    public function update(Request $request, $id)
    {
        $skill = Skill::where('user_id', auth()->id())->findOrFail($id);
        
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'category' => 'required|string',
            'price' => 'required|numeric|min:0',
            'price_type' => 'required|in:fixed,negotiable',
        ]);
        
        $skill->update([
            'title' => $request->title,
            'description' => $request->description,
            'category' => $request->category,
            'price' => $request->price,
            'price_type' => $request->price_type,
        ]);
        
        return redirect()->route('dashboard')
            ->with('success', 'Skill updated successfully!');
    }
    
    public function destroy($id)
    {
        $skill = Skill::where('user_id', auth()->id())->findOrFail($id);
        $skill->delete();
        
        return redirect()->route('dashboard')
            ->with('success', 'Skill deleted successfully!');
    }

    /**
     * Activate a skill
     */
    public function activate($id)
    {
        $skill = Skill::where('user_id', auth()->id())->findOrFail($id);
        $skill->update(['status' => 'active']);
        
        return redirect()->back()
            ->with('success', 'Skill activated successfully!');
    }

    /**
     * Deactivate a skill
     */
    public function deactivate($id)
    {
        $skill = Skill::where('user_id', auth()->id())->findOrFail($id);
        $skill->update(['status' => 'inactive']);
        
        return redirect()->back()
            ->with('success', 'Skill deactivated successfully!');
    }

    /**
     * Display user's skills
     */
    public function mySkills()
    {
        $user = auth()->user();
        
        $skills = $user->skills()
            ->withCount(['reviews', 'bookings', 'ratings'])
            ->with(['bookings' => function($query) {
                $query->latest()->take(3);
            }])
            ->latest()
            ->paginate(10);

        return view('skills.mine', compact('skills'));
    }
}
