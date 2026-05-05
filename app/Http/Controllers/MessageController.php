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
        
        // Get all messages for the user (both sent and received)
        $messages = Message::with(['sender', 'receiver', 'skill', 'job'])
            ->where(function($query) use ($user) {
                $query->where('sender_id', $user->id)
                      ->orWhere('receiver_id', $user->id);
            })
            ->notArchived()
            ->latest()
            ->paginate(20);
        
        return view('messages.index', compact('messages'));
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
        
        // Prevent users from messaging themselves
        if ($sender->id == $receiverId) {
            return back()->with('error', 'You cannot send a message to yourself.');
        }

        // Check if skill or job belongs to the receiver
        if ($request->skill_id) {
            $skill = Skill::find($request->skill_id);
            if ($skill->user_id != $receiverId) {
                return back()->with('error', 'This skill does not belong to the specified user.');
            }
        }

        if ($request->job_id) {
            $job = Job::find($request->job_id);
            if ($job->employer_id != $receiverId) {
                return back()->with('error', 'This job does not belong to the specified user.');
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
            ]);

            // Create notification for message receiver
            $notificationTitle = 'New Message Received';
            $notificationMessage = "You have a new message from {$sender->fullName()}";
            $notificationUrl = route('messages.index');
            
            Notification::createNotification(
                $receiverId,
                'message_received',
                $notificationTitle,
                $notificationMessage,
                $notificationUrl
            );

            // Log the message creation for debugging
            \Log::info('Message created successfully', [
                'message_id' => $message->id,
                'sender_id' => $sender->id,
                'receiver_id' => $receiverId,
                'skill_id' => $request->skill_id,
                'job_id' => $request->job_id,
            ]);

            // Redirect back with success message
            return redirect()->back()->with('success', 'Message sent successfully!');
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
            $message->update(['status' => 'read']);
        }

        return back();
    }

    public function reply(Request $request, $id)
    {
        $request->validate([
            'reply' => 'required|string|max:1000',
        ]);

        $originalMessage = Message::where('id', $id)
            ->where('receiver_id', Auth::id())
            ->firstOrFail();

        // Create reply message
        Message::create([
            'sender_id' => Auth::id(),
            'receiver_id' => $originalMessage->sender_id,
            'skill_id' => $originalMessage->skill_id,
            'job_id' => $originalMessage->job_id,
            'message' => $request->reply,
            'status' => 'sent',
        ]);

        // Mark original message as replied
        $originalMessage->update(['status' => 'replied']);

        return back()->with('success', 'Reply sent successfully!');
    }

    public function archive($id)
    {
        $message = Message::where('id', $id)
            ->where(function($query) {
                $query->where('sender_id', Auth::id())
                      ->orWhere('receiver_id', Auth::id());
            })
            ->firstOrFail();

        $message->update(['is_archived' => true]);

        return back()->with('success', 'Message archived successfully!');
    }

    public function destroy($id)
    {
        $message = Message::where('id', $id)
            ->where('sender_id', Auth::id())
            ->firstOrFail();

        $message->delete();

        return back()->with('success', 'Message deleted successfully!');
    }
}
