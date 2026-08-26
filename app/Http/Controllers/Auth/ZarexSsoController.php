<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

/**
 * Single Sign-On handoff from Zaraex.
 *
 * Zaraex loads https://zarextrade.com/auth/zarex?code=<code> (iframe embed,
 * or a top-level redirect from their "Continue with Zaraex" flow). The code
 * is a short-lived, single-use opaque value — never the user's actual login
 * credential — so we exchange it server-to-server for the user's profile via
 * POST /zarextrade/exchange-code, authenticated with our shared API key.
 * (Previously this verified a signed JWT passed directly in the URL, but
 * that exposed the login credential itself via browser history/access logs;
 * the code+exchange indirection avoids that.)
 */
class ZarexSsoController extends Controller
{
    public function login(Request $request)
    {
        $code = (string) $request->query('code', '');

        if ($code === '') {
            abort(400, 'Missing SSO code.');
        }

        $baseUrl = config('services.zarex.api_base_url');
        $apiKey = config('services.zarex.api_key');

        if (empty($baseUrl) || empty($apiKey)) {
            Log::error('Zaraex SSO exchange is not configured (ZAREXTRADE_API_BASE_URL / ZAREXTRADE_API_KEY).');
            abort(500, 'Sign-in is temporarily unavailable.');
        }

        $response = Http::baseUrl($baseUrl)->withToken($apiKey)->timeout(10)
            ->post('/zarextrade/exchange-code', ['code' => $code]);

        if ($response->status() === 401) {
            abort(401, 'Your sign-in link has expired. Please reopen Zarextrade from Zaraex.');
        }

        if (!$response->successful()) {
            Log::warning('Zaraex SSO code exchange failed: ' . $response->body());
            abort(401, 'Invalid sign-in link.');
        }

        $zarexId = (string) $response->json('sub', '');
        $email   = trim((string) $response->json('email', ''));

        if ($zarexId === '' || $email === '') {
            abort(422, 'Sign-in response is missing required fields.');
        }

        $user = $this->findOrCreateUser(
            $zarexId,
            $email,
            $response->json('name') !== null ? (string) $response->json('name') : null,
            $response->json('kyc_status') !== null ? (string) $response->json('kyc_status') : null
        );

        // Persistent login so the session survives inside the iframe.
        Auth::login($user, true);
        $request->session()->regenerate();

        return redirect()->intended(config('fortify.home', '/dashboard'));
    }

    /**
     * Resolve the Zarextrade account for this Zaraex identity: match on the
     * stored Zaraex id first, then on email (linking a pre-existing account),
     * otherwise provision a new user. Zaraex remains the source of truth.
     */
    private function findOrCreateUser(string $zarexId, string $email, ?string $name, ?string $kycStatus): User
    {
        $user = User::where('zarex_user_id', $zarexId)->first();

        if (!$user) {
            $user = User::where('email', $email)->first();
            if ($user) {
                $user->zarex_user_id = $zarexId;
            }
        }

        if (!$user) {
            $user = new User();
            $user->zarex_user_id = $zarexId;
            $user->email = $email;
            $user->username = $this->uniqueUsername($email);
            // No local password: these users only ever authenticate via Zaraex.
            $user->password = Hash::make(Str::random(40));
            $user->status = 'active';
        }

        if ($name) {
            $user->name = $name;
        } elseif (empty($user->name)) {
            $user->name = strtok($email, '@');
        }

        // Zaraex has already verified the email; mark verified so the user
        // clears the `verified` middleware on the dashboard.
        if (empty($user->email_verified_at)) {
            $user->email_verified_at = now();
        }

        $accountVerify = $this->mapKycStatus($kycStatus);
        if ($accountVerify !== null) {
            $user->account_verify = $accountVerify;
        }

        $user->save();

        return $user;
    }

    /**
     * Map Zaraex kyc_status onto Zarextrade's account_verify values.
     */
    private function mapKycStatus(?string $status): ?string
    {
        switch ($status) {
            case 'approved':
                return 'Verified';
            case 'pending':
                return 'Under review';
            case 'rejected':
                return 'Rejected';
            case 'not_started':
            default:
                return null;
        }
    }

    private function uniqueUsername(string $email): string
    {
        $base = Str::slug(strtok($email, '@'), '_') ?: 'user';
        $username = $base;

        while (User::where('username', $username)->exists()) {
            $username = $base . '_' . Str::lower(Str::random(4));
        }

        return $username;
    }
}
