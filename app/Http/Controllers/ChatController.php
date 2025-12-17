<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\ChatSession;
use App\Models\ChatMessage;
use App\Models\Document;
use App\Services\GeminiService;

class ChatController extends Controller
{
    protected $gemini;

    public function __construct(GeminiService $gemini)
    {
        $this->gemini = $gemini;
    }

    /* ============================================================
     * CHAT INDEX — LIST SESSIONS
     * ============================================================ */
    public function index()
    {
        $user = Auth::user();

        // Gatekeeper: pastikan profil lengkap
        if (
            !$user->userProfile?->is_completed ||
            !$user->companyProfile?->is_completed
        ) {
            return redirect()
                ->route('user.dashboard')
                ->with('error', 'Lengkapi profil untuk akses Chatbot.');
        }

        // Semua sesi user
        $sessions = ChatSession::where('user_id', $user->id)
            ->orderByDesc('updated_at')
            ->get();

        // Dokumen valid user (approved)
        $documents = Document::where('user_id', $user->id)
            ->where('status', 'approved')
            ->with('subfield')
            ->get();

        return view('user.chat.index', compact('sessions', 'documents'));
    }

    /* ============================================================
     * CREATE NEW CHAT SESSION
     * ============================================================ */
    public function store()
    {
        $session = ChatSession::create([
            'user_id' => Auth::id(),
            'title'   => 'Percakapan Baru ' . now()->format('d/m H:i'),
        ]);

        return redirect()->route('user.chat.show', $session->id);
    }

    /* ============================================================
     * SHOW A SESSION
     * ============================================================ */
    public function show($id)
    {
        $user = Auth::user();

        $session = ChatSession::where('user_id', $user->id)
            ->findOrFail($id);

        // Load relasi messages + attached documents (pivot)
        $session->load(['messages', 'documents']);

        // Sidebar session list
        $sessions = ChatSession::where('user_id', $user->id)
            ->orderByDesc('updated_at')
            ->get();

        // Dokumen yang mampu dipakai di sesi
        $allDocuments = Document::where('user_id', $user->id)
            ->where('status', 'approved')
            ->get();

        return view('user.chat.show', compact('session', 'sessions', 'allDocuments'));
    }

    /* ============================================================
     * SEND USER MESSAGE + CALL GEMINI
     * ============================================================ */
    public function sendMessage(Request $request, $id)
    {
        $request->validate([
            'message' => 'required|string'
        ]);

        $session = ChatSession::where('user_id', Auth::id())
            ->findOrFail($id);

        // 1. Simpan pesan user
        ChatMessage::create([
            'session_id' => $session->id,
            'role'       => 'user',
            'content'    => $request->message,
        ]);

        // 2. Ambil history terakhir (max 10 pesan)
        $history = $session->messages()
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get()
            ->reverse() // jadi kronologis
            ->map(fn($m) => [
                'role'    => $m->role,
                'content' => $m->content,
            ])
            ->toArray();

        // 3. Ambil dokumen terlampir
        $docPaths = $session->documents()->pluck('storage_path')->toArray();

        // 4. Panggil Gemini Service
        $aiResponseText = $this->gemini->chat(
            $request->message,
            $history,
            $docPaths
        );

        // 5. Simpan response AI
        ChatMessage::create([
            'session_id' => $session->id,
            'role'       => 'assistant',
            'content'    => $aiResponseText,
        ]);

        // Update session timestamp
        $session->touch();

        return back();
    }

    /* ============================================================
     * UPDATE ATTACHED DOCUMENTS (SYNC)
     * ============================================================ */
    public function updateDocuments(Request $request, $id)
    {
        $request->validate([
            'document_ids'   => 'array|max:10',
            'document_ids.*' => 'exists:documents,id',
        ]);

        $session = ChatSession::where('user_id', Auth::id())
            ->findOrFail($id);

        // Sync pivot (replace old attachments)
        $session->documents()->sync($request->document_ids ?? []);

        return back()->with('success', 'Dokumen konteks diperbarui.');
    }

    /* ============================================================
     * CLEAR MESSAGES IN SESSION
     * ============================================================ */
    public function clearMessages($id)
    {
        $session = ChatSession::where('user_id', Auth::id())
            ->findOrFail($id);

        $session->messages()->delete();

        return back()->with('success', 'Riwayat pesan dibersihkan.');
    }

    /* ============================================================
     * DELETE SESSION
     * ============================================================ */
    public function destroy($id)
    {
        $session = ChatSession::where('user_id', Auth::id())
            ->findOrFail($id);

        $session->delete();

        return redirect()
            ->route('user.chat.index')
            ->with('success', 'Sesi obrolan dihapus.');
    }
}
