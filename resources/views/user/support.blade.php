<?php
if (Auth::user()->dashboard_style == "light") {
    $bgmenu = "light";
    $bg = "light";
    $text = "dark";
} else {
    $bgmenu = "dark";
    $bg = "dark";
    $text = "light";
}
?>

@extends('layouts.app')

@section('content')
    @include('user.topmenu')
    @include('user.sidebar')

    <div class="main-panel support-page" data-theme="{{ $bg }}">
        <div class="content">
            <div class="page-inner">

                <!-- Page Header -->
                <div class="page-header">
                    <div class="header-content">
                        <h1 class="page-title">Support Center</h1>
                        <p class="page-subtitle">We're here to help you with any questions or concerns</p>
                    </div>
                </div>

                <x-danger-alert/>
                <x-success-alert/>

                <div class="support-layout">
                    <!-- Contact Form -->
                    <div class="support-form-card">
                        <div class="card-header-custom">
                            <div class="header-icon">
                                <i class="fa fa-envelope"></i>
                            </div>
                            <div>
                                <h3>Send us a Message</h3>
                                <p>Fill out the form below and we'll respond within 24 hours</p>
                            </div>
                        </div>

                        <form method="post" action="{{ route('enquiry') }}" id="supportForm">
                            @csrf
                            <input type="hidden" name="name" value="{{ Auth::user()->name }}" />
                            <input type="hidden" name="email" value="{{ Auth::user()->email }}">

                            <!-- User Info Display -->
                            <div class="user-info-bar">
                                <div class="user-info-item">
                                    <i class="fa fa-user"></i>
                                    <span>{{ Auth::user()->name }}</span>
                                </div>
                                <div class="user-info-item">
                                    <i class="fa fa-envelope"></i>
                                    <span>{{ Auth::user()->email }}</span>
                                </div>
                            </div>

                            <!-- Subject (Optional) -->
                            <div class="form-group">
                                <label class="form-label">Subject <span class="optional">(Optional)</span></label>
                                <select name="subject" class="form-select">
                                    <option value="General Inquiry">General Inquiry</option>
                                    <option value="Account Issue">Account Issue</option>
                                    <option value="Deposit Problem">Deposit Problem</option>
                                    <option value="Withdrawal Problem">Withdrawal Problem</option>
                                    <option value="Trading Issue">Trading Issue</option>
                                    <option value="Technical Support">Technical Support</option>
                                    <option value="Feedback">Feedback / Suggestion</option>
                                    <option value="Other">Other</option>
                                </select>
                            </div>

                            <!-- Message -->
                            <div class="form-group">
                                <label class="form-label">Message <span class="required">*</span></label>
                                <textarea
                                    name="message"
                                    id="message"
                                    class="form-textarea"
                                    placeholder="Describe your issue or question in detail..."
                                    required
                                    rows="6"
                                ></textarea>
                                <div class="char-counter">
                                    <span id="charCount">0</span> / 1000 characters
                                </div>
                            </div>

                            <!-- Submit Button -->
                            <button type="submit" class="submit-btn" id="submitBtn">
                                <i class="fa fa-paper-plane"></i>
                                <span id="btnText">Send Message</span>
                            </button>
                        </form>
                    </div>

                    <!-- Contact Info Sidebar -->
                    <div class="support-sidebar">
                        <!-- Quick Help -->
                        <div class="info-card">
                            <div class="info-icon">
                                <i class="fa fa-lightbulb"></i>
                            </div>
                            <h4>Quick Help</h4>
                            <p>Before reaching out, check if your question is answered in our FAQ section.</p>
                            <a href="#" class="info-link">
                                View FAQ
                                <i class="fa fa-arrow-right"></i>
                            </a>
                        </div>

                        <!-- Response Time -->
                        <div class="info-card">
                            <div class="info-icon clock">
                                <i class="fa fa-clock"></i>
                            </div>
                            <h4>Response Time</h4>
                            <p>Our support team typically responds within 24 hours on business days.</p>
                            <div class="response-indicator">
                                <span class="indicator-dot"></span>
                                <span>Currently responding within 2 hours</span>
                            </div>
                        </div>

                        <!-- Alternative Contact -->
                        <div class="info-card">
                            <div class="info-icon email">
                                <i class="fa fa-at"></i>
                            </div>
                            <h4>Email Us Directly</h4>
                            <p>You can also reach us at:</p>
                            <a href="mailto:{{ $settings->site_email ?? 'support@example.com' }}" class="email-link">
                                {{ $settings->site_email ?? 'support@example.com' }}
                            </a>
                        </div>

                        <!-- Live Chat -->
                        @if (!empty($settings->telegram_channel))
                            <div class="info-card telegram">
                                <div class="info-icon tg">
                                    <i class="fab fa-telegram-plane"></i>
                                </div>
                                <h4>Live Chat</h4>
                                <p>Get instant support via Telegram</p>
                                <a href="{{ str_starts_with($settings->telegram_channel, '@') ? 'https://t.me/' . ltrim($settings->telegram_channel, '@') : $settings->telegram_channel }}"
                                   target="_blank"
                                   class="telegram-btn">
                                    <i class="fab fa-telegram-plane"></i>
                                    Open Telegram
                                </a>
                            </div>
                        @endif
                    </div>
                </div>

            </div>
        </div>
    </div>

    <style>
        .support-page {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
        }

        /* Theme Variables */
        .support-page[data-theme="dark"] {
            --bg-primary: #0a0a0f;
            --bg-card: rgba(18, 18, 26, 0.9);
            --text-primary: #f8fafc;
            --text-secondary: #94a3b8;
            --text-muted: #7d8ba3;
            --border-color: rgba(37, 99, 235, 0.15);
            --input-bg: #12121a;
            --hover-bg: rgba(37, 99, 235, 0.08);
        }

        .support-page[data-theme="light"] {
            --bg-primary: #f8fafc;
            --bg-card: #ffffff;
            --text-primary: #0f172a;
            --text-secondary: #64748b;
            --text-muted: #94a3b8;
            --border-color: #e2e8f0;
            --input-bg: #f1f5f9;
            --hover-bg: rgba(37, 99, 235, 0.05);
        }

        .support-page .content {
            background: var(--bg-primary) !important;
        }

        /* Page Header */
        .page-header {
            margin-bottom: 28px;
        }

        .page-title {
            font-size: 1.75rem;
            font-weight: 700;
            color: var(--text-primary);
            margin: 0 0 6px;
        }

        .page-subtitle {
            font-size: 0.95rem;
            color: var(--text-secondary);
            margin: 0;
        }

        /* Support Layout */
        .support-layout {
            display: grid;
            grid-template-columns: 1fr 340px;
            gap: 24px;
            align-items: start;
        }

        @media (max-width: 1024px) {
            .support-layout {
                grid-template-columns: 1fr;
            }
        }

        /* Form Card */
        .support-form-card {
            background: var(--bg-card);
            border: 1px solid var(--border-color);
            border-radius: 16px;
            padding: 28px;
        }

        .card-header-custom {
            display: flex;
            align-items: center;
            gap: 14px;
            margin-bottom: 28px;
            padding-bottom: 20px;
            border-bottom: 1px solid var(--border-color);
        }

        .header-icon {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            background: #2563eb;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 20px;
        }

        .card-header-custom h3 {
            font-size: 1.15rem;
            font-weight: 600;
            color: var(--text-primary);
            margin: 0;
        }

        .card-header-custom p {
            font-size: 0.85rem;
            color: var(--text-secondary);
            margin: 4px 0 0;
        }

        /* User Info Bar */
        .user-info-bar {
            display: flex;
            gap: 20px;
            padding: 14px 18px;
            background: var(--hover-bg);
            border-radius: 10px;
            margin-bottom: 24px;
            flex-wrap: wrap;
        }

        .user-info-item {
            display: flex;
            align-items: center;
            gap: 8px;
            color: var(--text-secondary);
            font-size: 0.9rem;
        }

        .user-info-item i {
            color: #2563eb;
            font-size: 14px;
        }

        /* Form Elements */
        .form-group {
            margin-bottom: 20px;
        }

        .form-label {
            display: block;
            font-size: 0.9rem;
            font-weight: 600;
            color: var(--text-primary);
            margin-bottom: 8px;
        }

        .form-label .required {
            color: #ef4444;
        }

        .form-label .optional {
            color: var(--text-muted);
            font-weight: 400;
            font-size: 0.8rem;
        }

        .form-select,
        .form-textarea {
            width: 100%;
            background: var(--input-bg);
            border: 1px solid var(--border-color);
            border-radius: 10px;
            padding: 14px 16px;
            font-size: 0.95rem;
            color: var(--text-primary);
            font-family: inherit;
            transition: all 0.2s ease;
        }

        .form-select:focus,
        .form-textarea:focus {
            outline: none;
            border-color: #2563eb;
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.15);
        }

        .form-textarea {
            resize: vertical;
            min-height: 160px;
            line-height: 1.6;
        }

        .form-textarea::placeholder {
            color: var(--text-muted);
        }

        .char-counter {
            text-align: right;
            font-size: 0.8rem;
            color: var(--text-muted);
            margin-top: 8px;
        }

        .char-counter.warning {
            color: #f59e0b;
        }

        .char-counter.error {
            color: #ef4444;
        }

        /* Submit Button */
        .submit-btn {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            width: 100%;
            padding: 16px 24px;
            background: #2563eb;
            border: none;
            border-radius: 12px;
            color: white;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s ease;
            margin-top: 8px;
        }

        .submit-btn:hover {
            background: #1d4ed8;
            transform: translateY(-2px);
            box-shadow: 0 8px 20px -8px rgba(37, 99, 235, 0.5);
        }

        .submit-btn:disabled {
            opacity: 0.7;
            cursor: not-allowed;
            transform: none;
        }

        .submit-btn.loading {
            background: var(--text-muted);
        }

        /* Sidebar */
        .support-sidebar {
            display: flex;
            flex-direction: column;
            gap: 16px;
        }

        .info-card {
            background: var(--bg-card);
            border: 1px solid var(--border-color);
            border-radius: 14px;
            padding: 20px;
        }

        .info-icon {
            width: 42px;
            height: 42px;
            border-radius: 10px;
            background: rgba(37, 99, 235, 0.15);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #2563eb;
            font-size: 18px;
            margin-bottom: 14px;
        }

        .info-icon.clock {
            background: rgba(245, 158, 11, 0.15);
            color: #f59e0b;
        }

        .info-icon.email {
            background: rgba(16, 185, 129, 0.15);
            color: #10b981;
        }

        .info-icon.tg {
            background: rgba(0, 136, 204, 0.15);
            color: #0088cc;
        }

        .info-card h4 {
            font-size: 1rem;
            font-weight: 600;
            color: var(--text-primary);
            margin: 0 0 8px;
        }

        .info-card p {
            font-size: 0.875rem;
            color: var(--text-secondary);
            margin: 0 0 14px;
            line-height: 1.5;
        }

        .info-link {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            color: #2563eb;
            font-size: 0.875rem;
            font-weight: 600;
            text-decoration: none;
            transition: gap 0.2s ease;
        }

        .info-link:hover {
            gap: 10px;
            color: #1d4ed8;
        }

        .response-indicator {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 0.8rem;
            color: #10b981;
            font-weight: 500;
        }

        .indicator-dot {
            width: 8px;
            height: 8px;
            background: #10b981;
            border-radius: 50%;
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.5; }
        }

        .email-link {
            display: block;
            color: #2563eb;
            font-size: 0.9rem;
            font-weight: 600;
            text-decoration: none;
            word-break: break-all;
        }

        .email-link:hover {
            color: #1d4ed8;
        }

        .telegram-btn {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            width: 100%;
            padding: 12px;
            background: #0088cc;
            border-radius: 10px;
            color: white;
            font-size: 0.9rem;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.2s ease;
        }

        .telegram-btn:hover {
            background: #006699;
            color: white;
            transform: translateY(-1px);
        }

        /* Mobile Responsive */
        @media (max-width: 576px) {
            .support-form-card {
                padding: 20px;
            }

            .user-info-bar {
                flex-direction: column;
                gap: 10px;
            }

            .card-header-custom {
                flex-direction: column;
                text-align: center;
            }
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('supportForm');
            const submitBtn = document.getElementById('submitBtn');
            const btnText = document.getElementById('btnText');
            const textarea = document.getElementById('message');
            const charCount = document.getElementById('charCount');
            const charCounter = document.querySelector('.char-counter');

            // Character counter
            textarea.addEventListener('input', function() {
                const count = this.value.length;
                charCount.textContent = count;

                charCounter.classList.remove('warning', 'error');
                if (count > 1000) {
                    charCounter.classList.add('error');
                } else if (count > 800) {
                    charCounter.classList.add('warning');
                }
            });

            // Form submission
            form.addEventListener('submit', function(e) {
                const message = textarea.value.trim();

                if (!message) {
                    e.preventDefault();
                    textarea.focus();
                    textarea.style.borderColor = '#ef4444';
                    setTimeout(() => {
                        textarea.style.borderColor = '';
                    }, 3000);
                    return;
                }

                // Show loading state
                submitBtn.disabled = true;
                submitBtn.classList.add('loading');
                btnText.textContent = 'Sending...';
            });
        });
    </script>
@endsection
