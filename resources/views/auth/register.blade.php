<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Register - HR Management System</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-50 min-h-screen">
    <div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-5xl w-full">
            <!-- Header -->
            <div class="text-center mb-8">
                <a href="/" class="inline-flex items-center gap-2 mb-4 text-blue-600 hover:text-blue-700 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Back to Home
                </a>
                <h1 class="text-4xl font-bold text-slate-900 mb-2">Create Your Account</h1>
                <p class="text-slate-600">Join our HR Management System platform</p>
            </div>

            <!-- Multi-step Form -->
            <div x-data="registerForm()" class="bg-white rounded-2xl shadow-xl overflow-hidden">
                <!-- Progress Bar -->
                <div class="bg-gradient-to-r from-blue-600 to-indigo-600 px-8 py-6">
                    <div class="flex items-center justify-between mb-4">
                        <template x-for="(step, index) in steps" :key="index">
                            <div class="flex items-center" :class="index < steps.length - 1 ? 'flex-1' : ''">
                                <div class="flex items-center gap-3">
                                    <div 
                                        class="w-10 h-10 rounded-full flex items-center justify-center font-semibold transition-all duration-300"
                                        :class="currentStep > index ? 'bg-white text-blue-600' : currentStep === index ? 'bg-blue-500 text-white ring-4 ring-blue-300' : 'bg-blue-700 text-blue-300'"
                                    >
                                        <span x-show="currentStep <= index" x-text="index + 1"></span>
                                        <svg x-show="currentStep > index" class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                        </svg>
                                    </div>
                                    <span 
                                        class="hidden md:block font-medium transition-all duration-300"
                                        :class="currentStep >= index ? 'text-white' : 'text-blue-300'"
                                        x-text="step.title"
                                    ></span>
                                </div>
                                <div 
                                    x-show="index < steps.length - 1" 
                                    class="flex-1 h-1 mx-4 rounded-full transition-all duration-300"
                                    :class="currentStep > index ? 'bg-white' : 'bg-blue-700'"
                                ></div>
                            </div>
                        </template>
                    </div>
                </div>

                <!-- Form Content -->
                <form @submit.prevent="submitForm" class="p-8">
                    <!-- Step 1: Personal Information -->
                    <div x-show="currentStep === 0" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform translate-x-8" x-transition:enter-end="opacity-100 transform translate-x-0">
                        <h2 class="text-2xl font-bold text-slate-900 mb-6">Personal Information</h2>
                        
                        <div class="grid md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-2">First Name *</label>
                                <input 
                                    type="text" 
                                    x-model="formData.first_name"
                                    @input="clearError('first_name')"
                                    class="w-full px-4 py-3 rounded-lg border border-slate-300 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
                                    :class="errors.first_name ? 'border-red-500' : ''"
                                    placeholder="John"
                                >
                                <p x-show="errors.first_name" x-text="errors.first_name" class="text-red-500 text-sm mt-1"></p>
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-2">Last Name *</label>
                                <input 
                                    type="text" 
                                    x-model="formData.last_name"
                                    @input="clearError('last_name')"
                                    class="w-full px-4 py-3 rounded-lg border border-slate-300 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
                                    :class="errors.last_name ? 'border-red-500' : ''"
                                    placeholder="Doe"
                                >
                                <p x-show="errors.last_name" x-text="errors.last_name" class="text-red-500 text-sm mt-1"></p>
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-2">Email Address *</label>
                                <input 
                                    type="email" 
                                    x-model="formData.email"
                                    @input="clearError('email')"
                                    class="w-full px-4 py-3 rounded-lg border border-slate-300 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
                                    :class="errors.email ? 'border-red-500' : ''"
                                    placeholder="john.doe@example.com"
                                >
                                <p x-show="errors.email" x-text="errors.email" class="text-red-500 text-sm mt-1"></p>
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-2">Phone Number *</label>
                                <input 
                                    type="tel" 
                                    x-model="formData.phone"
                                    @input="clearError('phone')"
                                    class="w-full px-4 py-3 rounded-lg border border-slate-300 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
                                    :class="errors.phone ? 'border-red-500' : ''"
                                    placeholder="+1 (555) 123-4567"
                                >
                                <p x-show="errors.phone" x-text="errors.phone" class="text-red-500 text-sm mt-1"></p>
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-2">Date of Birth *</label>
                                <input 
                                    type="date" 
                                    x-model="formData.date_of_birth"
                                    @input="clearError('date_of_birth')"
                                    class="w-full px-4 py-3 rounded-lg border border-slate-300 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
                                    :class="errors.date_of_birth ? 'border-red-500' : ''"
                                >
                                <p x-show="errors.date_of_birth" x-text="errors.date_of_birth" class="text-red-500 text-sm mt-1"></p>
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-2">Gender *</label>
                                <select 
                                    x-model="formData.gender"
                                    @change="clearError('gender')"
                                    class="w-full px-4 py-3 rounded-lg border border-slate-300 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
                                    :class="errors.gender ? 'border-red-500' : ''"
                                >
                                    <option value="">Select Gender</option>
                                    <option value="male">Male</option>
                                    <option value="female">Female</option>
                                    <option value="other">Other</option>
                                </select>
                                <p x-show="errors.gender" x-text="errors.gender" class="text-red-500 text-sm mt-1"></p>
                            </div>
                        </div>

                        <div class="mt-6">
                            <label class="block text-sm font-semibold text-slate-700 mb-2">Address *</label>
                            <textarea 
                                x-model="formData.address"
                                @input="clearError('address')"
                                rows="3"
                                class="w-full px-4 py-3 rounded-lg border border-slate-300 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
                                :class="errors.address ? 'border-red-500' : ''"
                                placeholder="123 Main Street, City, State, ZIP"
                            ></textarea>
                            <p x-show="errors.address" x-text="errors.address" class="text-red-500 text-sm mt-1"></p>
                        </div>
                    </div>

                    <!-- Step 2: Professional Information -->
                    <div x-show="currentStep === 1" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform translate-x-8" x-transition:enter-end="opacity-100 transform translate-x-0">
                        <h2 class="text-2xl font-bold text-slate-900 mb-6">Professional Information</h2>
                        
                        <div class="grid md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-2">Position/Job Title *</label>
                                <input 
                                    type="text" 
                                    x-model="formData.position"
                                    @input="clearError('position')"
                                    class="w-full px-4 py-3 rounded-lg border border-slate-300 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
                                    :class="errors.position ? 'border-red-500' : ''"
                                    placeholder="Software Engineer"
                                >
                                <p x-show="errors.position" x-text="errors.position" class="text-red-500 text-sm mt-1"></p>
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-2">Department *</label>
                                <select 
                                    x-model="formData.department"
                                    @change="clearError('department')"
                                    class="w-full px-4 py-3 rounded-lg border border-slate-300 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
                                    :class="errors.department ? 'border-red-500' : ''"
                                >
                                    <option value="">Select Department</option>
                                    <option value="IT">IT</option>
                                    <option value="HR">Human Resources</option>
                                    <option value="Finance">Finance</option>
                                    <option value="Sales">Sales</option>
                                    <option value="Marketing">Marketing</option>
                                    <option value="Operations">Operations</option>
                                    <option value="Customer Service">Customer Service</option>
                                </select>
                                <p x-show="errors.department" x-text="errors.department" class="text-red-500 text-sm mt-1"></p>
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-2">Employment Type *</label>
                                <select 
                                    x-model="formData.employment_type"
                                    @change="clearError('employment_type')"
                                    class="w-full px-4 py-3 rounded-lg border border-slate-300 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
                                    :class="errors.employment_type ? 'border-red-500' : ''"
                                >
                                    <option value="">Select Type</option>
                                    <option value="full_time">Full Time</option>
                                    <option value="part_time">Part Time</option>
                                    <option value="contract">Contract</option>
                                    <option value="intern">Intern</option>
                                </select>
                                <p x-show="errors.employment_type" x-text="errors.employment_type" class="text-red-500 text-sm mt-1"></p>
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-2">Expected Salary *</label>
                                <div class="relative">
                                    <span class="absolute left-4 top-1/2 transform -translate-y-1/2 text-slate-500">$</span>
                                    <input 
                                        type="number" 
                                        x-model="formData.salary"
                                        @input="clearError('salary')"
                                        class="w-full pl-8 pr-4 py-3 rounded-lg border border-slate-300 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
                                        :class="errors.salary ? 'border-red-500' : ''"
                                        placeholder="50000"
                                        step="1000"
                                    >
                                </div>
                                <p x-show="errors.salary" x-text="errors.salary" class="text-red-500 text-sm mt-1"></p>
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-2">Start Date *</label>
                                <input 
                                    type="date" 
                                    x-model="formData.hire_date"
                                    @input="clearError('hire_date')"
                                    class="w-full px-4 py-3 rounded-lg border border-slate-300 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
                                    :class="errors.hire_date ? 'border-red-500' : ''"
                                >
                                <p x-show="errors.hire_date" x-text="errors.hire_date" class="text-red-500 text-sm mt-1"></p>
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-2">Years of Experience *</label>
                                <input 
                                    type="number" 
                                    x-model="formData.experience_years"
                                    @input="clearError('experience_years')"
                                    class="w-full px-4 py-3 rounded-lg border border-slate-300 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
                                    :class="errors.experience_years ? 'border-red-500' : ''"
                                    placeholder="5"
                                    min="0"
                                >
                                <p x-show="errors.experience_years" x-text="errors.experience_years" class="text-red-500 text-sm mt-1"></p>
                            </div>
                        </div>

                        <div class="mt-6">
                            <label class="block text-sm font-semibold text-slate-700 mb-2">Skills (comma-separated)</label>
                            <input 
                                type="text" 
                                x-model="formData.skills"
                                class="w-full px-4 py-3 rounded-lg border border-slate-300 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
                                placeholder="JavaScript, React, Node.js, Python"
                            >
                        </div>
                    </div>

                    <!-- Step 3: Account Security -->
                    <div x-show="currentStep === 2" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform translate-x-8" x-transition:enter-end="opacity-100 transform translate-x-0">
                        <h2 class="text-2xl font-bold text-slate-900 mb-6">Account Security</h2>
                        
                        <div class="grid md:grid-cols-2 gap-6">
                            <div class="md:col-span-2">
                                <label class="block text-sm font-semibold text-slate-700 mb-2">Username *</label>
                                <input 
                                    type="text" 
                                    x-model="formData.username"
                                    @input="clearError('username')"
                                    class="w-full px-4 py-3 rounded-lg border border-slate-300 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
                                    :class="errors.username ? 'border-red-500' : ''"
                                    placeholder="johndoe123"
                                >
                                <p x-show="errors.username" x-text="errors.username" class="text-red-500 text-sm mt-1"></p>
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-2">Password *</label>
                                <div class="relative">
                                    <input 
                                        :type="showPassword ? 'text' : 'password'"
                                        x-model="formData.password"
                                        @input="clearError('password'); checkPasswordStrength()"
                                        class="w-full px-4 py-3 rounded-lg border border-slate-300 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all pr-12"
                                        :class="errors.password ? 'border-red-500' : ''"
                                        placeholder="••••••••"
                                    >
                                    <button 
                                        type="button"
                                        @click="showPassword = !showPassword"
                                        class="absolute right-3 top-1/2 transform -translate-y-1/2 text-slate-400 hover:text-slate-600"
                                    >
                                        <svg x-show="!showPassword" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                        </svg>
                                        <svg x-show="showPassword" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>
                                        </svg>
                                    </button>
                                </div>
                                <p x-show="errors.password" x-text="errors.password" class="text-red-500 text-sm mt-1"></p>
                                
                                <!-- Password Strength Indicator -->
                                <div x-show="formData.password" class="mt-2">
                                    <div class="flex gap-1 mb-1">
                                        <div class="h-1 flex-1 rounded-full transition-all" :class="passwordStrength >= 1 ? (passwordStrength === 1 ? 'bg-red-500' : passwordStrength === 2 ? 'bg-orange-500' : passwordStrength === 3 ? 'bg-yellow-500' : 'bg-green-500') : 'bg-slate-200'"></div>
                                        <div class="h-1 flex-1 rounded-full transition-all" :class="passwordStrength >= 2 ? (passwordStrength === 2 ? 'bg-orange-500' : passwordStrength === 3 ? 'bg-yellow-500' : 'bg-green-500') : 'bg-slate-200'"></div>
                                        <div class="h-1 flex-1 rounded-full transition-all" :class="passwordStrength >= 3 ? (passwordStrength === 3 ? 'bg-yellow-500' : 'bg-green-500') : 'bg-slate-200'"></div>
                                        <div class="h-1 flex-1 rounded-full transition-all" :class="passwordStrength >= 4 ? 'bg-green-500' : 'bg-slate-200'"></div>
                                    </div>
                                    <p class="text-xs" :class="passwordStrength === 1 ? 'text-red-500' : passwordStrength === 2 ? 'text-orange-500' : passwordStrength === 3 ? 'text-yellow-600' : 'text-green-600'">
                                        <span x-text="passwordStrength === 1 ? 'Weak' : passwordStrength === 2 ? 'Fair' : passwordStrength === 3 ? 'Good' : 'Strong'"></span>
                                    </p>
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-2">Confirm Password *</label>
                                <input 
                                    :type="showPassword ? 'text' : 'password'"
                                    x-model="formData.password_confirmation"
                                    @input="clearError('password_confirmation')"
                                    class="w-full px-4 py-3 rounded-lg border border-slate-300 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
                                    :class="errors.password_confirmation ? 'border-red-500' : ''"
                                    placeholder="••••••••"
                                >
                                <p x-show="errors.password_confirmation" x-text="errors.password_confirmation" class="text-red-500 text-sm mt-1"></p>
                            </div>
                        </div>

                        <div class="mt-6 p-4 bg-blue-50 border border-blue-200 rounded-lg">
                            <h3 class="font-semibold text-slate-900 mb-2">Password Requirements:</h3>
                            <ul class="text-sm text-slate-600 space-y-1">
                                <li class="flex items-center gap-2">
                                    <svg class="w-4 h-4" :class="formData.password.length >= 8 ? 'text-green-500' : 'text-slate-400'" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                    </svg>
                                    At least 8 characters
                                </li>
                                <li class="flex items-center gap-2">
                                    <svg class="w-4 h-4" :class="/[A-Z]/.test(formData.password) ? 'text-green-500' : 'text-slate-400'" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                    </svg>
                                    One uppercase letter
                                </li>
                                <li class="flex items-center gap-2">
                                    <svg class="w-4 h-4" :class="/[a-z]/.test(formData.password) ? 'text-green-500' : 'text-slate-400'" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                    </svg>
                                    One lowercase letter
                                </li>
                                <li class="flex items-center gap-2">
                                    <svg class="w-4 h-4" :class="/\d/.test(formData.password) ? 'text-green-500' : 'text-slate-400'" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                    </svg>
                                    One number
                                </li>
                            </ul>
                        </div>

                        <div class="mt-6">
                            <label class="flex items-start gap-3 cursor-pointer">
                                <input 
                                    type="checkbox" 
                                    x-model="formData.terms"
                                    @change="clearError('terms')"
                                    class="mt-1 w-5 h-5 rounded border-slate-300 text-blue-600 focus:ring-2 focus:ring-blue-500"
                                >
                                <span class="text-sm text-slate-600">
                                    I agree to the <a href="#" class="text-blue-600 hover:underline">Terms of Service</a> and <a href="#" class="text-blue-600 hover:underline">Privacy Policy</a> *
                                </span>
                            </label>
                            <p x-show="errors.terms" x-text="errors.terms" class="text-red-500 text-sm mt-1"></p>
                        </div>
                    </div>

                    <!-- Navigation Buttons -->
                    <div class="mt-8 flex items-center justify-between">
                        <button 
                            type="button"
                            @click="previousStep"
                            x-show="currentStep > 0"
                            class="px-6 py-3 text-slate-700 font-semibold rounded-lg hover:bg-slate-100 transition-colors"
                        >
                            ← Previous
                        </button>
                        
                        <div class="flex gap-3 ml-auto">
                            <button 
                                type="button"
                                @click="nextStep"
                                x-show="currentStep < steps.length - 1"
                                class="px-8 py-3 bg-gradient-to-r from-blue-600 to-indigo-600 text-white font-semibold rounded-lg hover:from-blue-700 hover:to-indigo-700 transition-all shadow-lg hover:shadow-xl"
                            >
                                Next →
                            </button>

                            <button 
                                type="submit"
                                x-show="currentStep === steps.length - 1"
                                :disabled="isSubmitting"
                                class="px-8 py-3 bg-gradient-to-r from-green-600 to-emerald-600 text-white font-semibold rounded-lg hover:from-green-700 hover:to-emerald-700 transition-all shadow-lg hover:shadow-xl disabled:opacity-50 disabled:cursor-not-allowed"
                            >
                                <span x-show="!isSubmitting">Create Account</span>
                                <span x-show="isSubmitting" class="flex items-center gap-2">
                                    <svg class="animate-spin h-5 w-5" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                    Processing...
                                </span>
                            </button>
                        </div>
                    </div>

                    <!-- Already have an account -->
                    <div class="mt-6 text-center text-sm text-slate-600">
                        Already have an account? 
                        <a href="/admin/login" class="text-blue-600 hover:underline font-semibold">Sign In</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function registerForm() {
            return {
                currentStep: 0,
                isSubmitting: false,
                showPassword: false,
                passwordStrength: 0,
                steps: [
                    { title: 'Personal Info', fields: ['first_name', 'last_name', 'email', 'phone', 'date_of_birth', 'gender', 'address'] },
                    { title: 'Professional', fields: ['position', 'department', 'employment_type', 'salary', 'hire_date', 'experience_years'] },
                    { title: 'Security', fields: ['username', 'password', 'password_confirmation', 'terms'] }
                ],
                formData: {
                    first_name: '',
                    last_name: '',
                    email: '',
                    phone: '',
                    date_of_birth: '',
                    gender: '',
                    address: '',
                    position: '',
                    department: '',
                    employment_type: '',
                    salary: '',
                    hire_date: '',
                    experience_years: '',
                    skills: '',
                    username: '',
                    password: '',
                    password_confirmation: '',
                    terms: false
                },
                errors: {},

                nextStep() {
                    if (this.validateStep(this.currentStep)) {
                        this.currentStep++;
                    }
                },

                previousStep() {
                    this.currentStep--;
                    this.errors = {};
                },

                validateStep(stepIndex) {
                    this.errors = {};
                    const step = this.steps[stepIndex];
                    let isValid = true;

                    step.fields.forEach(field => {
                        if (field === 'terms') {
                            if (!this.formData[field]) {
                                this.errors[field] = 'You must agree to the terms and conditions';
                                isValid = false;
                            }
                        } else if (field === 'skills') {
                            // Optional field
                        } else if (!this.formData[field]) {
                            this.errors[field] = 'This field is required';
                            isValid = false;
                        } else if (field === 'email' && !this.isValidEmail(this.formData[field])) {
                            this.errors[field] = 'Please enter a valid email address';
                            isValid = false;
                        } else if (field === 'phone' && !this.isValidPhone(this.formData[field])) {
                            this.errors[field] = 'Please enter a valid phone number';
                            isValid = false;
                        } else if (field === 'password' && !this.isValidPassword(this.formData[field])) {
                            this.errors[field] = 'Password must be at least 8 characters with uppercase, lowercase, and number';
                            isValid = false;
                        } else if (field === 'password_confirmation' && this.formData.password !== this.formData.password_confirmation) {
                            this.errors[field] = 'Passwords do not match';
                            isValid = false;
                        }
                    });

                    return isValid;
                },

                isValidEmail(email) {
                    return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
                },

                isValidPhone(phone) {
                    return /^[\d\s\-\+\(\)]+$/.test(phone) && phone.replace(/\D/g, '').length >= 10;
                },

                isValidPassword(password) {
                    return password.length >= 8 && 
                           /[A-Z]/.test(password) && 
                           /[a-z]/.test(password) && 
                           /\d/.test(password);
                },

                checkPasswordStrength() {
                    const password = this.formData.password;
                    let strength = 0;

                    if (password.length >= 8) strength++;
                    if (/[a-z]/.test(password) && /[A-Z]/.test(password)) strength++;
                    if (/\d/.test(password)) strength++;
                    if (/[^A-Za-z0-9]/.test(password)) strength++;

                    this.passwordStrength = strength;
                },

                clearError(field) {
                    delete this.errors[field];
                },

                async submitForm() {
                    if (!this.validateStep(this.currentStep)) {
                        return;
                    }

                    this.isSubmitting = true;

                    try {
                        const response = await fetch('/register', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                            },
                            body: JSON.stringify(this.formData)
                        });

                        const data = await response.json();

                        if (response.ok) {
                            // Success - redirect to login or dashboard
                            window.location.href = data.redirect || '/admin/login';
                        } else {
                            // Handle validation errors
                            if (data.errors) {
                                this.errors = data.errors;
                                // Go to first step with errors
                                for (let i = 0; i < this.steps.length; i++) {
                                    const hasError = this.steps[i].fields.some(field => this.errors[field]);
                                    if (hasError) {
                                        this.currentStep = i;
                                        break;
                                    }
                                }
                            }
                        }
                    } catch (error) {
                        console.error('Registration error:', error);
                        alert('An error occurred during registration. Please try again.');
                    } finally {
                        this.isSubmitting = false;
                    }
                }
            };
        }
    </script>
</body>
</html>
