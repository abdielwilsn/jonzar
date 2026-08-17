<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Firebase\JWT\ExpiredException;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

/**
 * Single Sign-On handoff from Zaraex.
 *
 * Zaraex loads https://zarextrade.com/auth/zarex?token=<jwt> inside an iframe.
 * The JWT is signed HS256 with a shared secret. We verify it, then find (or
 * create) the matching Zarextrade user and start their session.
 */
class ZarexSsoController extends Controller
{
    public function login(Request $request)
    {
        $token = (string) $request->query('token', '');

        if ($token === '') {
            abort(400, 'Missing SSO token.');
        }

        $secret = config('services.zarex.sso_secret');

        if (empty($secret)) {
            Log::error('Zaraex SSO secret is not configured (ZAREXTRADE_SSO_SECRET).');
            abort(500, 'Sign-in is temporarily unavailable.');
        }

        // Tolerate a little clock drift between the two servers.
        JWT::$leeway = (int) config('services.zarex.sso_leeway', 30);

        try {
            $claims = JWT::decode($token, new Key($secret, 'HS256'));
        } catch (ExpiredException $e) {
            abort(401, 'Your sign-in link has expired. Please reopen Zarextrade from Zaraex.');
        } catch (\Throwable $e) {
            Log::warning('Zaraex SSO token rejected: ' . $e->getMessage());
            abort(401, 'Invalid sign-in link.');
        }

        $zarexId = isset($claims->sub) ? (string) $claims->sub : '';
        $email   = isset($claims->email) ? trim((string) $claims->email) : '';

        if ($zarexId === '' || $email === '') {
            abort(422, 'Sign-in token is missing required fields.');
        }

        $user = $this->findOrCreateUser(
            $zarexId,
            $email,
            isset($claims->name) ? (string) $claims->name : null,
            isset($claims->kyc_status) ? (string) $claims->kyc_status : null
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
