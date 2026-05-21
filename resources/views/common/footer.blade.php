<footer class="bg-zendo-navy text-gray-300 pt-16 pb-8"> 
    <div class="max-w-8xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-12 mb-12">
            
            <!-- Column 1: About -->
            <div>
                 <img src="{{ asset('main/images/zendo.png') }}" alt="ZENDO India Logo" class="h-10 w-auto mb-4">
                <p class="text-gray-400 font-body text-sm leading-relaxed mb-6">
                    Redefining excellence in every aspect of real estate operations. Your trusted partner in finding the perfect property in India.
                </p>
                <div class="flex space-x-4">
                    <a href="#" class="text-gray-400 hover:text-zendo-gold transition-colors" aria-label="Facebook"> 
                         <img src="{{ asset('main/icons/social/facebook.svg') }}" alt="Facebook" class="w-5 h-5">
                    </a>
                    <a href="#" class="text-gray-400 hover:text-zendo-gold transition-colors" aria-label="Twitter"> 
                       <img src="{{ asset('main/icons/social/twitter.svg') }}" alt="Twitter" class="w-5 h-5">
                    </a>
                     <a href="#" class="text-gray-400 hover:text-zendo-gold transition-colors" aria-label="LinkedIn"> 
                       <img src="{{ asset('main/icons/social/linkedin.svg') }}" alt="LinkedIn" class="w-5 h-5">
                    </a>
                     <a href="#" class="text-gray-400 hover:text-zendo-gold transition-colors" aria-label="Instagram"> 
                      <img src="{{ asset('main/icons/social/instagram.svg') }}" alt="Instagram" class="w-5 h-5">
                    </a>
                </div>
            </div>

            <!-- Column 2: Contact Info -->
            <div>
                 <h4 class="text-lg font-medium font-heading text-white mb-4 uppercase tracking-wider">Contact Info</h4> 
                <ul class="space-y-3">
                    <li class="flex items-start">
                         <img src="{{ asset('main/icons/location.svg') }}" alt="Location" class="w-5 h-5 mr-3 mt-1 flex-shrink-0 text-zendo-gold"> 
                        <span class="text-gray-400 font-body text-sm">Registered Office: Room No 1, Plot No 1, Ground Floor, Vatika Primrose Avenue, Sector-82, Gurugram,Haryana - 122012</span>
                    </li>
                     <li class="flex items-center">
                       <img src="{{ asset('main/icons/phone.svg') }}" alt="Phone" class="w-5 h-5 mr-3 flex-shrink-0 text-zendo-gold"> 
                        <a href="tel:+919990186086" class="text-gray-400 font-body text-sm footer-link transition-colors">+91-9990186086</a>
                    </li>
                     <li class="flex items-center">
                         <img src="{{ asset('main/icons/email.svg') }}" alt="Email" class="w-5 h-5 mr-3 flex-shrink-0 text-zendo-gold"> 
                        <a href="mailto:info@zendoindia.com" class="text-gray-400 font-body text-sm footer-link transition-colors">info@zendoindia.com</a>
                    </li>
                </ul>
            </div>

            <!-- Column 3: Quick Links -->
            <div>
                 <h4 class="text-lg font-medium font-heading text-white mb-4 uppercase tracking-wider">Quick Links</h4> 
                 <ul class="space-y-2">
                     <li><a href="{{ route('about') }}" class="text-gray-400 font-body text-sm footer-link transition-colors">About Us</a></li>
                     <li><a href="{{ route('contact') }}" class="text-gray-400 font-body text-sm footer-link transition-colors">Contact Us</a></li>
                     <li><a href="{{ route('properties.index') }}" class="text-gray-400 font-body text-sm footer-link transition-colors">Services</a></li>
                     <li><a href="{{ route('blogs.index') }}" class="text-gray-400 font-body text-sm footer-link transition-colors">Blog/News</a></li>
                 </ul>
            </div>

            <!-- Column 4: Appointment -->
            <div>
                 <h4 class="text-lg font-medium font-heading text-white mb-4 uppercase tracking-wider">Appointment</h4> 
                 <p class="text-gray-400 font-body text-sm leading-relaxed mb-6">
                    Schedule appointments easily via phone, email, or our online booking system. Let's find your perfect property.
                 </p>
            </div>

        </div>

        <!-- Bottom Bar -->
        <div class="border-t border-gray-700 pt-8 mt-8 flex flex-col md:flex-row justify-between items-center text-sm">
            <p class="text-gray-500 font-body mb-4 md:mb-0">&copy; {{ date('Y') }} ZENDO . All rights reserved.</p>
            <div class="flex space-x-6">
                <a href="{{ route('terms-and-conditions') }}" class="text-gray-500 hover:text-zendo-gold font-body transition-colors footer-link">Terms & Conditions</a> 
                <a href="{{ route('privacy-policy') }}" class="text-gray-500 hover:text-zendo-gold font-body transition-colors footer-link">Privacy & Policy</a> 
                <a href="https://apwebworld.com/" target="_blank" class="text-gray-500 hover:text-zendo-gold font-body transition-colors footer-link">Design And Develop By Ap Web World</a> 
            </div>
        </div>

    </div>
</footer>