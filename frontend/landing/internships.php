<?php

declare(strict_types=1);

/**
 * View: Browse Internships Page
 */
?>
<div class="bg-gray-50 min-h-screen py-10" x-data="{
    search: '',
    location: '',
    category: '',
    remotePreferences: [],
    commitment: '',
    alertEnabled: false,
    savedStates: {
        'Nexura': false,
        'Airbnb': true,
        'WellSpring Care': false,
        'BrightWave': false
    },
    internships: [
        {
            id: 'software-engineer-intern',
            company: 'Nexura',
            logoBg: 'bg-black text-white',
            logoInitial: 'N',
            category: 'ENGINEERING',
            title: 'Software Engineer Intern',
            location: 'Lagos, NG',
            description: 'Join our payments infrastructure team to build the foundation of global commerce. You\'ll work on high-scale systems.',
            tag: 'REMOTE',
            commitment: 'Full-time'
        },
        {
            id: 'product-design-intern',
            company: 'Airbnb',
            logoBg: 'bg-rose-100 text-rose-600',
            logoInitial: 'A',
            category: 'DESIGN',
            title: 'Product Design Intern',
            location: 'Lagos, NG',
            description: 'Craft beautiful, intuitive experiences for travelers and hosts. You\'ll participate in the full design lifecycle.',
            tag: 'HYBRID',
            commitment: 'Full-time'
        },
        {
            id: 'senior-product-design-intern',
            company: 'WellSpring Care',
            logoBg: 'bg-emerald-100 text-emerald-600',
            logoInitial: 'W',
            category: 'PRODUCT',
            title: 'Senior Product Design Intern',
            location: 'Lagos, NG',
            description: 'Shape the future of audio by defining product requirements and working with cross-functional engineering teams.',
            tag: 'HYBRID',
            commitment: 'Full-time'
        },
        {
            id: 'growth-marketing-intern',
            company: 'BrightWave',
            logoBg: 'bg-amber-100 text-amber-700',
            logoInitial: 'B',
            category: 'MARKETING',
            title: 'Growth Marketing Intern',
            location: 'Lagos, NG',
            description: 'Help us reach more users by running experiments, analyzing data, and crafting compelling narratives.',
            tag: 'ON SITE',
            commitment: 'Part-time'
        }
    ],

    get filteredInternships() {
        return this.internships.filter(item => {
            if (this.search) {
                const kw = this.search.toLowerCase();
                const matchTitle = item.title.toLowerCase().includes(kw);
                const matchCompany = item.company.toLowerCase().includes(kw);
                const matchDesc = item.description.toLowerCase().includes(kw);
                if (!matchTitle && !matchCompany && !matchDesc) return false;
            }
            if (this.location) {
                if (!item.location.toLowerCase().includes(this.location.toLowerCase())) return false;
            }
            if (this.category) {
                if (item.category !== this.category) return false;
            }
            if (this.remotePreferences.length > 0) {
                const mapTag = {
                    'Fully Remote': 'REMOTE',
                    'Hybrid': 'HYBRID'
                };
                const matchingTags = this.remotePreferences.map(pref => mapTag[pref]);
                if (!matchingTags.includes(item.tag)) return false;
            }
            if (this.commitment) {
                if (item.commitment !== this.commitment) return false;
            }
            return true;
        });
    },
    resetFilters() {
        this.search = '';
        this.location = '';
        this.category = '';
        this.remotePreferences = [];
        this.commitment = '';
    }
}">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Header Page Title -->
        <div class="mb-8">
            <h1 class="text-3xl sm:text-4xl font-bold text-gray-900 tracking-tight">Find Your Next Internship</h1>
        </div>

        <!-- Search Bar Section -->
        <div class="bg-white border border-gray-100 rounded-2xl shadow-sm mb-10 p-4 relative z-20" style="overflow: visible;">
            <div class="grid grid-cols-1 md:grid-cols-12 gap-4 items-center">
                
                <!-- Keyword search -->
                <div class="md:col-span-5 relative flex items-center border border-gray-200 rounded-xl bg-gray-50 focus-within:bg-white focus-within:border-primary-hover focus-within:ring-2 focus-within:ring-primary-light transition duration-150">
                    <span class="absolute left-4 text-gray-400">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </span>
                    <input type="text" x-model="search" placeholder="Search roles, companies, or keywords..." 
                        class="w-full pl-11 pr-4 py-3 rounded-xl text-sm border-0 focus:ring-0 focus:outline-none bg-transparent placeholder-gray-400 text-gray-800 font-medium" />
                </div>

                <!-- Location Search -->
                <div class="md:col-span-3 relative flex items-center border border-gray-200 rounded-xl bg-gray-50 focus-within:bg-white focus-within:border-primary-hover focus-within:ring-2 focus-within:ring-primary-light transition duration-150">
                    <span class="absolute left-4 text-gray-400">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                              <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                    </span>
                    <input type="text" x-model="location" placeholder="Location" 
                        class="w-full pl-11 pr-4 py-3 rounded-xl text-sm border-0 focus:ring-0 focus:outline-none bg-transparent placeholder-gray-400 text-gray-800 font-medium" />
                </div>

                <!-- Category Dropdown -->
                <div class="md:col-span-2" 
                     x-data="{ 
                         open: false, 
                         dropY: 0, 
                         dropX: 0, 
                         dropW: 0,
                         toggle(el) {
                             const r = el.getBoundingClientRect();
                             this.dropY = r.bottom + window.scrollY + 6;
                             this.dropX = r.left + window.scrollX;
                             this.dropW = r.width;
                             this.open = !this.open;
                         }
                     }">
                    <button @click="toggle($el)" type="button" 
                        class="w-full flex items-center justify-between pl-11 pr-4 py-3 rounded-xl text-sm text-gray-700 font-medium bg-gray-50 border border-gray-200 hover:bg-gray-100/50 focus:outline-none focus:border-primary-hover focus:ring-2 focus:ring-primary-light transition duration-150 relative">
                        <span class="absolute left-4 text-gray-400 pointer-events-none">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                        </span>
                        <span x-text="category ? (category.charAt(0).toUpperCase() + category.slice(1).toLowerCase()) : 'Category'">Category</span>
                        <svg class="h-4 w-4 text-gray-400 transition-transform duration-200" :class="{'rotate-180': open}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>

                    <!-- Fixed-position dropdown panel: escapes all overflow/grid clipping contexts -->
                    <div 
                        x-show="open" 
                        @click.away="open = false"
                        x-transition:enter="transition ease-out duration-100" 
                        x-transition:enter-start="transform opacity-0 scale-95" 
                        x-transition:enter-end="transform opacity-100 scale-100" 
                        x-transition:leave="transition ease-in duration-75" 
                        x-transition:leave-start="transform opacity-100 scale-100" 
                        x-transition:leave-end="transform opacity-0 scale-95"
                        :style="`position: fixed; top: ${dropY}px; left: ${dropX}px; width: ${dropW}px; z-index: 9999;`"
                        class="bg-white rounded-xl shadow-xl border border-gray-100 py-1.5"
                        style="display: none;">
                        <button type="button" @click="category = ''; open = false" 
                            class="w-full text-left px-4 py-2.5 text-sm text-gray-700 hover:bg-primary-light hover:text-primary transition duration-150 font-medium">All Categories</button>
                        <button type="button" @click="category = 'ENGINEERING'; open = false" 
                            class="w-full text-left px-4 py-2.5 text-sm text-gray-700 hover:bg-primary-light hover:text-primary transition duration-150 font-medium">Engineering</button>
                        <button type="button" @click="category = 'DESIGN'; open = false" 
                            class="w-full text-left px-4 py-2.5 text-sm text-gray-700 hover:bg-primary-light hover:text-primary transition duration-150 font-medium">Design</button>
                        <button type="button" @click="category = 'PRODUCT'; open = false" 
                            class="w-full text-left px-4 py-2.5 text-sm text-gray-700 hover:bg-primary-light hover:text-primary transition duration-150 font-medium">Product</button>
                        <button type="button" @click="category = 'MARKETING'; open = false" 
                            class="w-full text-left px-4 py-2.5 text-sm text-gray-700 hover:bg-primary-light hover:text-primary transition duration-150 font-medium">Marketing</button>
                    </div>
                </div>

                <!-- Search Action Button -->
                <div class="md:col-span-2">
                    <button class="w-full bg-primary hover:bg-primary-hover text-white text-sm font-semibold py-3 px-6 rounded-xl transition shadow-sm">
                        Search
                    </button>
                </div>
            </div>
        </div>

        <!-- Two Column Content Area -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
            
            <!-- Left Sidebar Filter Column -->
            <div class="lg:col-span-3 space-y-8">
                
                <!-- Remote Preference Filter Block -->
                <div class="bg-white border border-gray-200 rounded-2xl p-6 shadow-sm">
                    <h3 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-4">Remote Preference</h3>
                    <div class="space-y-3">
                        <label class="flex items-center text-sm text-gray-700 cursor-pointer">
                            <input type="checkbox" value="Fully Remote" x-model="remotePreferences"
                                class="rounded border-gray-300 text-primary focus:ring-primary-hover h-4 w-4 transition" />
                            <span class="ml-3 font-medium">Fully Remote</span>
                        </label>
                        <label class="flex items-center text-sm text-gray-700 cursor-pointer">
                            <input type="checkbox" value="Hybrid" x-model="remotePreferences"
                                class="rounded border-gray-300 text-primary focus:ring-primary-hover h-4 w-4 transition" />
                            <span class="ml-3 font-medium">Hybrid</span>
                        </label>
                    </div>
                </div>

                <!-- Commitment Filter Block -->
                <div class="bg-white border border-gray-200 rounded-2xl p-6 shadow-sm">
                    <h3 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-4">Commitment</h3>
                    <div class="space-y-3">
                        <label class="flex items-center text-sm text-gray-700 cursor-pointer">
                            <input type="radio" name="commitment" value="" x-model="commitment"
                                class="border-gray-300 text-primary focus:ring-primary-hover h-4 w-4 transition" />
                            <span class="ml-3 font-medium">All Types</span>
                        </label>
                        <label class="flex items-center text-sm text-gray-700 cursor-pointer">
                            <input type="radio" name="commitment" value="Full-time" x-model="commitment"
                                class="border-gray-300 text-primary focus:ring-primary-hover h-4 w-4 transition" />
                            <span class="ml-3 font-medium">Full-time</span>
                        </label>
                        <label class="flex items-center text-sm text-gray-700 cursor-pointer">
                            <input type="radio" name="commitment" value="Part-time" x-model="commitment"
                                class="border-gray-300 text-primary focus:ring-primary-hover h-4 w-4 transition" />
                            <span class="ml-3 font-medium">Part-time</span>
                        </label>
                    </div>
                </div>

                <!-- Reset Filters Button (Optional helper) -->
                <div x-show="search || location || category || remotePreferences.length > 0 || commitment" 
                    x-transition class="text-center">
                    <button @click="resetFilters" class="text-sm font-semibold text-primary hover:text-primary-hover transition">
                        Clear all filters
                    </button>
                </div>

                <!-- Get New Alerts Block -->
                <div class="bg-primary-light border border-primary-light rounded-2xl p-6 shadow-sm">
                    <h4 class="text-sm font-bold text-gray-900 mb-2">Get New Alerts</h4>
                    <p class="text-xs text-gray-500 leading-relaxed mb-4">
                        Receive notifications for new internships matching your profile.
                    </p>
                    <button @click="alertEnabled = !alertEnabled" 
                        class="w-full text-center text-xs font-semibold py-2.5 px-4 rounded-xl border border-primary text-primary bg-white hover:bg-primary-light transition active:scale-95 shadow-sm"
                        x-text="alertEnabled ? 'Alerts Enabled ✓' : 'Enable Alerts'">
                        Enable Alerts
                    </button>
                </div>

            </div>

            <!-- Right Grid List Column -->
            <div class="lg:col-span-9 space-y-8">
                
                <!-- Zero State -->
                <div x-show="filteredInternships.length === 0" x-transition 
                    class="bg-white border border-gray-200 rounded-2xl p-12 text-center shadow-sm">
                    <svg class="mx-auto h-12 w-12 text-gray-400 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <h3 class="text-lg font-bold text-gray-900 mb-1">No internships found</h3>
                    <p class="text-sm text-gray-500 mb-6">We couldn't find any opportunities matching your criteria.</p>
                    <button @click="resetFilters" class="bg-primary hover:bg-primary-hover text-white text-sm font-semibold py-2 px-5 rounded-lg transition shadow-sm">
                        Reset Filters
                    </button>
                </div>

                <!-- Grid wrapper -->
                <div x-show="filteredInternships.length > 0" class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <template x-for="item in filteredInternships" :key="item.company">
                        
                        <!-- Internship Card -->
                        <div class="bg-white border border-gray-200 rounded-2xl p-6 shadow-sm hover:shadow-md transition-shadow relative flex flex-col justify-between h-full">
                            
                            <!-- Card Header Info -->
                            <div>
                                <div class="flex justify-between items-start">
                                    <!-- Brand Logo / Category -->
                                    <div class="flex items-center space-x-3">
                                        <div :class="item.logoBg + ' h-10 w-10 rounded-xl flex items-center justify-center font-bold text-lg shadow-sm'" x-text="item.logoInitial"></div>
                                        <div>
                                            <span class="text-[10px] font-bold text-primary uppercase tracking-widest" x-text="item.category"></span>
                                            <h3 class="font-bold text-gray-900 text-base leading-tight mt-0.5" x-text="item.title"></h3>
                                        </div>
                                    </div>
                                    
                                    <!-- Bookmark Icon -->
                                    <button @click="savedStates[item.company] = !savedStates[item.company]" 
                                        class="text-gray-400 hover:text-primary focus:outline-none transition p-1 hover:bg-gray-50 rounded-lg">
                                        <!-- Saved state active -->
                                        <svg x-show="savedStates[item.company]" class="h-6 w-6 text-primary" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z" />
                                        </svg>
                                        <!-- Saved state inactive -->
                                        <svg x-show="!savedStates[item.company]" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z" />
                                        </svg>
                                    </button>
                                </div>

                                <!-- Company & Location -->
                                <div class="mt-3 text-xs text-gray-500 flex items-center space-x-2">
                                    <span class="font-medium text-gray-700" x-text="item.company"></span>
                                    <span>•</span>
                                    <span x-text="item.location"></span>
                                    <span>•</span>
                                    <span class="italic text-[10px]" x-text="item.commitment"></span>
                                </div>

                                <!-- Description -->
                                <p class="mt-4 text-xs text-gray-600 leading-relaxed" x-text="item.description"></p>
                            </div>

                            <!-- Card Footer Tags & Button -->
                            <div class="mt-6 pt-4 border-t border-gray-100 flex justify-between items-center">
                                <!-- Remote Badge -->
                                <span class="bg-gray-100 text-gray-700 text-[10px] font-bold px-3 py-1.5 rounded-lg tracking-wide uppercase" x-text="item.tag"></span>
                                
                                <!-- Apply Action Button -->
                                <a :href="'<?= url('/internship/') ?>' + item.id"
                                   class="bg-primary hover:bg-primary-hover text-white text-xs font-bold px-4 py-2 rounded-xl transition active:scale-95 shadow-sm">
                                    Apply
                                </a>
                            </div>

                        </div>

                    </template>
                </div>

                <!-- Pagination Section -->
                <div x-show="filteredInternships.length > 0" class="flex justify-center items-center space-x-2 pt-6">
                    <button class="px-4 py-2 text-xs font-semibold text-gray-500 hover:text-primary transition">
                        Previous
                    </button>
                    
                    <button class="w-8 h-8 rounded-full flex items-center justify-center text-xs font-bold bg-primary text-white shadow-sm">
                        1
                    </button>
                    
                    <button class="w-8 h-8 rounded-full flex items-center justify-center text-xs font-bold text-gray-600 hover:text-primary transition">
                        2
                    </button>
                    
                    <button class="w-8 h-8 rounded-full flex items-center justify-center text-xs font-bold text-gray-600 hover:text-primary transition">
                        3
                    </button>
                    
                    <span class="text-gray-400 text-xs px-1">...</span>
                    
                    <button class="w-8 h-8 rounded-full flex items-center justify-center text-xs font-bold text-gray-600 hover:text-primary transition">
                        12
                    </button>

                    <button class="px-4 py-2 text-xs font-semibold text-gray-500 hover:text-primary transition">
                        Next
                    </button>
                </div>

            </div>

        </div>

    </div>
</div>
