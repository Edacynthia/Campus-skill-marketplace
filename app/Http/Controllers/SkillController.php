<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Skill;
use App\Models\User;
use App\Models\Notification;

class SkillController extends Controller
{
   public function index(Request $request)
    {
        $query = Skill::with('user')
            ->where('status', 'active');

        // Search functionality
        if ($request->filled('search')) {
            $searchTerm = trim($request->search);
            $searchWords = explode(' ', $searchTerm);

            $query->where(function ($q) use ($searchWords) {
                foreach ($searchWords as $word) {
                    $word = trim($word);

                    if ($word !== '') {
                        $q->orWhere('title', 'LIKE', "%{$word}%")
                        ->orWhere('description', 'LIKE', "%{$word}%")
                        ->orWhere('category', 'LIKE', "%{$word}%");
                    }
                }
            });
        }

        // Category filter
        if ($request->filled('category')) {
            $category = trim($request->category);

            $query->where(function ($q) use ($category) {
                $q->where('category', 'LIKE', "%{$category}%")
                ->orWhere('title', 'LIKE', "%{$category}%")
                ->orWhere('description', 'LIKE', "%{$category}%");
            });
        }

        // Filter by price range
        if ($request->filled('min_price')) {
            $query->where('price', '>=', $request->min_price);
        }

        if ($request->filled('max_price')) {
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

        $skills = $query->paginate(12)->withQueryString();

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
            'description' => strip_tags($request->description),
            'category' => $request->category,
            'price' => $request->price,
            'price_type' => $request->price_type,
            'status' => 'active',
        ]);

        $users = User::where('id', '!=', auth()->id())->get();

        foreach ($users as $user) {
            Notification::createNotification(
                $user->id,
                'new_skill',
                'New Skill Posted',
                auth()->user()->first_name . ' posted a new skill: ' . $skill->title,
                '/skills/' . $skill->id
            );
        }
        
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
