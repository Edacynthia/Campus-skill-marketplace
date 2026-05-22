<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\Skill;
use App\Models\Job;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MessageController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $messages = Message::with(['sender', 'receiver', 'skill', 'job'])
            ->where(function ($query) use ($user) {
                $query->where('sender_id', $user->id)
                      ->orWhere('receiver_id', $user->id);
            })
            ->where('is_archived', false)
            ->latest()
            ->paginate(20);

        return view('messages.index', compact('messages'));
    }

    public function archived()
    {
        $messages = Message::with(['sender', 'receiver', 'skill', 'job'])
            ->where(function ($query) {
                $query->where('sender_id', Auth::id())
                      ->orWhere('receiver_id', Auth::id());
            })
            ->where('is_archived', true)
            ->latest()
            ->paginate(20);

        return view('messages.archived', compact('messages'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'message' => 'required|string|max:1000',
            'receiver_id' => 'required|exists:users,id',
            'skill_id' => 'nullable|exists:skills,id',
            'job_id' => 'nullable|exists:jobs,id',
        ]);

        $sender = Auth::user();
        $receiverId = $request->receiver_id;

        if ($sender->id == $receiverId) {
            return back()->with('error', 'You cannot send a message to yourself.');
        }

        if ($request->skill_id) {
            $skill = Skill::findOrFail($request->skill_id);

            if ($skill->user_id != $receiverId) {
                return back()->with('error', 'This skill does not belong to the selected user.');
            }
        }

        if ($request->job_id) {
            $job = Job::findOrFail($request->job_id);

            if ($job->employer_id != $receiverId) {
                return back()->with('error', 'This job does not belong to the selected user.');
            }
        }

        try {
            $message = Message::create([
                'sender_id' => $sender->id,
                'receiver_id' => $receiverId,
                'skill_id' => $request->skill_id,
                'job_id' => $request->job_id,
                'message' => $request->message,
                'status' => 'sent',
                'is_archived' => false,
            ]);

            Notification::createNotification(
                $receiverId,
                'message_received',
                'New Message Received',
                "You have a new message from {$sender->fullName()}",
                route('messages.index')
            );

            return back()->with('success', 'Message sent successfully.');

        } catch (\Exception $e) {
            \Log::error('Failed to create message', [
                'error' => $e->getMessage(),
                'sender_id' => $sender->id,
                'receiver_id' => $receiverId,
            ]);

            return back()->with('error', 'Failed to send message. Please try again.');
        }
    }

    public function markAsRead($id)
    {
        $message = Message::where('id', $id)
            ->where('receiver_id', Auth::id())
            ->firstOrFail();

        if ($message->status === 'sent') {
            $message->update([
                'status' => 'read'
            ]);
        }

        return back()->with('success', 'Message marked as read.');
    }

    public function reply(Request $request, $id)
    {
        $request->validate([
            'reply' => 'required|string|max:1000',
        ]);

        $originalMessage = Message::where('id', $id)
            ->where(function ($query) {
                $query->where('sender_id', Auth::id())
                      ->orWhere('receiver_id', Auth::id());
            })
            ->firstOrFail();

        $receiverId = $originalMessage->sender_id == Auth::id()
            ? $originalMessage->receiver_id
            : $originalMessage->sender_id;

        Message::create([
            'sender_id' => Auth::id(),
            'receiver_id' => $receiverId,
            'skill_id' => $originalMessage->skill_id,
            'job_id' => $originalMessage->job_id,
            'message' => $request->reply,
            'status' => 'sent',
            'is_archived' => false,
        ]);

        $originalMessage->update([
            'status' => 'replied'
        ]);

        Notification::createNotification(
            $receiverId,
            'message_received',
            'New Reply Received',
            Auth::user()->fullName() . ' replied to your message.',
            route('messages.index')
        );

        return back()->with('success', 'Reply sent successfully.');
    }

    public function archive($id)
    {
        $message = Message::where('id', $id)
            ->where(function ($query) {
                $query->where('sender_id', Auth::id())
                      ->orWhere('receiver_id', Auth::id());
            })
            ->firstOrFail();

        $message->update([
            'is_archived' => true
        ]);

        return back()->with('success', 'Message archived successfully.');
    }

    public function unarchive($id)
    {
        $message = Message::where('id', $id)
            ->where(function ($query) {
                $query->where('sender_id', Auth::id())
                      ->orWhere('receiver_id', Auth::id());
            })
            ->firstOrFail();

        $message->update([
            'is_archived' => false
        ]);

        return back()->with('success', 'Message restored successfully.');
    }

    public function destroy($id)
    {
        $message = Message::where('id', $id)
            ->where(function ($query) {
                $query->where('sender_id', Auth::id())
                      ->orWhere('receiver_id', Auth::id());
            })
            ->firstOrFail();

        $message->delete();

        return back()->with('success', 'Message deleted successfully.');
    }
}