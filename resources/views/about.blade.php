<x-app-layout>
    <div class="py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="lg:grid lg:grid-cols-2 lg:gap-16 items-center">
                
                {{-- Image Section --}}
                <div class="mb-12 lg:mb-0 relative">
                    <div class="absolute -inset-4 bg-orange-100 rounded-[3rem] transform -rotate-3 scale-105 opacity-50 z-0"></div>
                    <div class="absolute -inset-4 bg-amber-50 rounded-[3rem] transform rotate-2 scale-105 opacity-50 z-0"></div>
                    
                    <div class="relative z-10 bg-white rounded-3xl p-4 shadow-2xl border border-gray-100 overflow-hidden group">
                        {{-- The user's uploaded image --}}
                        <img src="{{ asset('about.png') }}" alt="Our Chef Cooking" class="w-full h-auto rounded-2xl object-cover transform transition duration-700 group-hover:scale-105">
                        
                        <div class="absolute bottom-6 right-6 bg-white/90 backdrop-blur-md px-6 py-3 rounded-2xl shadow-lg border border-white/50">
                            <p class="font-bold text-gray-900 display-font text-lg">Est. 2024</p>
                            <p class="text-xs text-orange-600 font-bold uppercase tracking-wider">Crafted with Love</p>
                        </div>
                    </div>
                </div>

                {{-- Content Section --}}
                <div class="lg:pl-8">
                    <div class="inline-block px-4 py-1.5 rounded-full bg-orange-50 border border-orange-100 text-orange-600 font-bold text-xs uppercase tracking-widest mb-6">
                        Our Story
                    </div>
                    
                    <h2 class="text-4xl lg:text-5xl font-black text-gray-900 display-font mb-6 leading-tight">
                        We don't just cook food. <br>
                        <span class="text-transparent bg-clip-text bg-gradient-to-r from-orange-600 to-amber-500">We create memories.</span>
                    </h2>
                    
                    <div class="space-y-6 text-gray-600 text-lg leading-relaxed">
                        <p>
                            Welcome to <strong class="text-gray-900">Love Bite</strong>, where passion meets the plate. It all started with a simple idea: that the best food is made exactly the way you'd make it at home—with fresh ingredients, bold flavors, and a whole lot of heart.
                        </p>
                        <p>
                            Whether you're grabbing a quick takeaway, sitting down for a cozy dine-in experience, or ordering delivery to your doorstep, our commitment remains the same. Every dish is a labor of love, crafted by chefs who believe that a great meal can turn a good day into a perfect one.
                        </p>
                        <p>
                            From our signature sauces to our hand-picked spices, everything is prepared from scratch daily. We invite you to take a bite, feel the love, and become part of our growing family.
                        </p>
                    </div>

                    <div class="mt-10 flex gap-4">
                        <a href="{{ route('menu.index') }}" class="px-8 py-4 bg-gray-900 text-white font-bold rounded-xl hover:bg-black transition transform active:scale-95 shadow-lg shadow-gray-900/20">
                            Explore Menu
                        </a>
                        <a href="{{ route('dine-in.menu') }}" class="px-8 py-4 bg-orange-50 text-orange-600 font-bold rounded-xl hover:bg-orange-100 transition transform active:scale-95 border border-orange-200">
                            Dine With Us
                        </a>
                    </div>
                </div>

            </div>
            
            {{-- Stats / Highlights --}}
            <div class="mt-24 grid grid-cols-2 md:grid-cols-4 gap-8 border-t border-gray-200 pt-16">
                <div class="text-center">
                    <p class="text-4xl font-black text-orange-600 display-font mb-2">100%</p>
                    <p class="text-sm font-bold text-gray-500 uppercase tracking-wider">Fresh Ingredients</p>
                </div>
                <div class="text-center">
                    <p class="text-4xl font-black text-orange-600 display-font mb-2">50+</p>
                    <p class="text-sm font-bold text-gray-500 uppercase tracking-wider">Unique Dishes</p>
                </div>
                <div class="text-center">
                    <p class="text-4xl font-black text-orange-600 display-font mb-2">3</p>
                    <p class="text-sm font-bold text-gray-500 uppercase tracking-wider">Ways to Order</p>
                </div>
                <div class="text-center">
                    <p class="text-4xl font-black text-orange-600 display-font mb-2">∞ </p>
                    <p class="text-sm font-bold text-gray-500 uppercase tracking-wider">Love Poured In</p>
                </div>
            </div>
            
        </div>
    </div>
</x-app-layout>
