<?php

namespace App\Http\Controllers;

use App\Mail\NewMessageReceived;
use App\Services\RecaptchaService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Inertia\Inertia;
use Modules\Platform\Models\Message;

class ContactController extends Controller
{
    /**
     * Display the contact form.
     */
    public function index()
    {
        return Inertia::render('Contact', [
            'recaptchaSiteKey' => config('recaptcha.site_key'),
            'recaptchaEnabled' => config('recaptcha.enabled'),
        ]);
    }

    /**
     * Store a new message.
     */
    public function store(Request $request, RecaptchaService $recaptcha)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'subject' => 'required|string|in:new_company,feature_request,bug_report,general_inquiry,other',
            'message' => 'required|string|max:5000',
            'company_name' => 'nullable|string|max:255',
            'company_url' => 'nullable|url|max:500',
            'recaptcha_token' => 'required|string',
        ]);

        // Verify reCAPTCHA
        if (!$recaptcha->verify($validated['recaptcha_token'], 'contact')) {
            return back()->withErrors([
                'recaptcha' => 'reCAPTCHA verification failed. Please try again.'
            ])->withInput();
        }

        $message = Message::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'subject' => $validated['subject'],
            'message' => $validated['message'],
            'company_name' => $validated['company_name'] ?? null,
            'company_url' => $validated['company_url'] ?? null,
            'ip_address' => $request->ip(),
            'responded_to' => false,
        ]);

        // Send email notification to admin
        $adminEmail = config('mail.admin_email');
        if ($adminEmail) {
            Mail::to($adminEmail)->send(new NewMessageReceived($message));
        }

        return redirect()->route('contact.index')->with('success', 'Thank you for your message! We\'ll get back to you soon.');
    }
}
