@extends('layouts.customer')

@section('title', 'Terms and Conditions - Kibo Auto')

@section('content')
<div class="min-h-screen bg-white py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
            <div class="flex items-center gap-4 mb-4">
                <div class="w-12 h-12 bg-emerald-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Terms and Conditions</h1>
                    <p class="text-gray-600 mt-1">Last updated: {{ date('F j, Y') }}</p>
                </div>
            </div>
        </div>

        <!-- Content -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 sm:p-8 lg:p-10">
            <div class="prose prose-lg max-w-none">
                <div class="space-y-6">
                    <!-- Introduction -->
                    <section>
                        <h2 class="text-2xl font-bold text-gray-900 mb-4">1. Introduction</h2>
                        <p class="text-gray-700 leading-relaxed">
                            Welcome to Kibo Auto. These Terms and Conditions ("Terms") govern your access to and use of our website, services, and platform. By registering an account, accessing, or using our services, you agree to be bound by these Terms. If you do not agree to these Terms, please do not use our services.
                        </p>
                    </section>

                    <!-- Acceptance of Terms -->
                    <section>
                        <h2 class="text-2xl font-bold text-gray-900 mb-4">2. Acceptance of Terms</h2>
                        <p class="text-gray-700 leading-relaxed mb-3">
                            By creating an account, you acknowledge that you have read, understood, and agree to be bound by these Terms and our Privacy Policy. You represent that you are at least 18 years old and have the legal capacity to enter into these Terms.
                        </p>
                    </section>

                    <!-- Account Registration -->
                    <section>
                        <h2 class="text-2xl font-bold text-gray-900 mb-4">3. Account Registration</h2>
                        <div class="space-y-3">
                            <p class="text-gray-700 leading-relaxed">
                                When you register for an account, you agree to:
                            </p>
                            <ul class="list-disc list-inside space-y-2 text-gray-700 ml-4">
                                <li>Provide accurate, current, and complete information</li>
                                <li>Maintain and promptly update your account information</li>
                                <li>Maintain the security of your password and account</li>
                                <li>Accept responsibility for all activities that occur under your account</li>
                                <li>Notify us immediately of any unauthorized use of your account</li>
                            </ul>
                        </div>
                    </section>

                    <!-- Use of Services -->
                    <section>
                        <h2 class="text-2xl font-bold text-gray-900 mb-4">4. Use of Services</h2>
                        <div class="space-y-3">
                            <p class="text-gray-700 leading-relaxed">
                                You agree to use our services only for lawful purposes and in accordance with these Terms. You agree not to:
                            </p>
                            <ul class="list-disc list-inside space-y-2 text-gray-700 ml-4">
                                <li>Violate any applicable laws or regulations</li>
                                <li>Infringe upon the rights of others</li>
                                <li>Transmit any harmful, offensive, or illegal content</li>
                                <li>Attempt to gain unauthorized access to our systems</li>
                                <li>Interfere with or disrupt our services or servers</li>
                                <li>Use automated systems to access our services without permission</li>
                            </ul>
                        </div>
                    </section>

                    <!-- Vehicle Listings and Transactions -->
                    <section>
                        <h2 class="text-2xl font-bold text-gray-900 mb-4">5. Vehicle Listings and Transactions</h2>
                        <div class="space-y-3">
                            <p class="text-gray-700 leading-relaxed">
                                When listing or purchasing vehicles through our platform:
                            </p>
                            <ul class="list-disc list-inside space-y-2 text-gray-700 ml-4">
                                <li>All vehicle information must be accurate and truthful</li>
                                <li>You are responsible for the condition and description of listed vehicles</li>
                                <li>Transactions are subject to verification and approval</li>
                                <li>We reserve the right to remove any listing that violates our policies</li>
                                <li>Payment terms and conditions apply as specified in individual transactions</li>
                            </ul>
                        </div>
                    </section>

                    <!-- Financing Services -->
                    <section>
                        <h2 class="text-2xl font-bold text-gray-900 mb-4">6. Financing Services</h2>
                        <p class="text-gray-700 leading-relaxed">
                            Our financing services are provided through partner lenders. All financing applications are subject to approval by the respective lender. We do not guarantee loan approval, and terms and conditions are determined by the lender. You agree to provide accurate financial information and understand that false information may result in application denial or legal action.
                        </p>
                    </section>

                    <!-- Fees and Payments -->
                    <section>
                        <h2 class="text-2xl font-bold text-gray-900 mb-4">7. Fees and Payments</h2>
                        <p class="text-gray-700 leading-relaxed">
                            Some services on our platform may require payment of fees. All fees are clearly disclosed before you commit to a transaction. You agree to pay all applicable fees and charges. We reserve the right to change our fee structure with reasonable notice.
                        </p>
                    </section>

                    <!-- Intellectual Property -->
                    <section>
                        <h2 class="text-2xl font-bold text-gray-900 mb-4">8. Intellectual Property</h2>
                        <p class="text-gray-700 leading-relaxed">
                            All content on our platform, including text, graphics, logos, images, and software, is the property of Kibo Auto or its licensors and is protected by copyright and other intellectual property laws. You may not reproduce, distribute, or create derivative works without our express written permission.
                        </p>
                    </section>

                    <!-- Limitation of Liability -->
                    <section>
                        <h2 class="text-2xl font-bold text-gray-900 mb-4">9. Limitation of Liability</h2>
                        <p class="text-gray-700 leading-relaxed">
                            To the maximum extent permitted by law, Kibo Auto and its affiliates shall not be liable for any indirect, incidental, special, consequential, or punitive damages, or any loss of profits or revenues, whether incurred directly or indirectly, or any loss of data, use, goodwill, or other intangible losses resulting from your use of our services.
                        </p>
                    </section>

                    <!-- Indemnification -->
                    <section>
                        <h2 class="text-2xl font-bold text-gray-900 mb-4">10. Indemnification</h2>
                        <p class="text-gray-700 leading-relaxed">
                            You agree to indemnify, defend, and hold harmless Kibo Auto, its officers, directors, employees, and agents from and against any claims, liabilities, damages, losses, and expenses, including reasonable attorneys' fees, arising out of or in any way connected with your use of our services or violation of these Terms.
                        </p>
                    </section>

                    <!-- Termination -->
                    <section>
                        <h2 class="text-2xl font-bold text-gray-900 mb-4">11. Termination</h2>
                        <p class="text-gray-700 leading-relaxed">
                            We reserve the right to suspend or terminate your account and access to our services at any time, with or without cause or notice, for any reason including, but not limited to, violation of these Terms. You may also terminate your account at any time by contacting us.
                        </p>
                    </section>

                    <!-- Changes to Terms -->
                    <section>
                        <h2 class="text-2xl font-bold text-gray-900 mb-4">12. Changes to Terms</h2>
                        <p class="text-gray-700 leading-relaxed">
                            We reserve the right to modify these Terms at any time. We will notify users of any material changes by posting the updated Terms on our website and updating the "Last updated" date. Your continued use of our services after such changes constitutes acceptance of the modified Terms.
                        </p>
                    </section>

                    <!-- Governing Law -->
                    <section>
                        <h2 class="text-2xl font-bold text-gray-900 mb-4">13. Governing Law</h2>
                        <p class="text-gray-700 leading-relaxed">
                            These Terms shall be governed by and construed in accordance with the laws of Tanzania, without regard to its conflict of law provisions. Any disputes arising from these Terms shall be subject to the exclusive jurisdiction of the courts of Tanzania.
                        </p>
                    </section>

                    <!-- Contact Information -->
                    <section>
                        <h2 class="text-2xl font-bold text-gray-900 mb-4">14. Contact Us</h2>
                        <p class="text-gray-700 leading-relaxed">
                            If you have any questions about these Terms, please contact us through our website's contact form or by email at support@kiboauto.com.
                        </p>
                    </section>
                </div>
            </div>

            <!-- Back Button -->
            <div class="mt-8 pt-6 border-t border-gray-200">
                <a href="{{ route('cars.index') }}" class="inline-flex items-center gap-2 text-emerald-600 hover:text-emerald-700 font-medium">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Back to Home
                </a>
            </div>
        </div>
    </div>
</div>
@endsection

