@extends('layouts.client')

@section('title', 'Liên hệ - Hỗ trợ khách hàng 24/7')

@section('meta')
    <meta name="description" content="Liên hệ với chúng tôi để được hỗ trợ tư vấn sản phẩm, giải đáp thắc mắc và chăm sóc khách hàng tốt nhất. Hotline: (+84) 0123 456 789">
    <meta name="keywords" content="liên hệ, hỗ trợ khách hàng, tư vấn, chăm sóc khách hàng, hotline">
    <meta property="og:title" content="Liên hệ - Hỗ trợ khách hàng">
    <meta property="og:description" content="Liên hệ với chúng tôi để được hỗ trợ tư vấn sản phẩm và chăm sóc khách hàng tốt nhất">
    <meta property="og:type" content="website">
@endsection

@section('content')
    {{-- Phần tiêu đề --}}
    <section class="bg-gradient-to-r from-purple-50 to-blue-50 rounded-2xl shadow-sm p-8 md:p-12 mt-10 mb-12 text-center"
             data-aos="fade-down">
        <div class="inline-flex items-center justify-center w-16 h-16 bg-purple-100 rounded-full mb-4">
            <i class="fas fa-headset text-purple-600 text-2xl"></i>
        </div>
        <h1 class="text-4xl font-extrabold text-gray-800 mb-3">Liên hệ với chúng tôi</h1>
        <p class="text-gray-600 text-lg mb-4">Chúng tôi sẽ phản hồi trong giờ hành chính (8:00 - 18:00)</p>
        <div class="flex flex-wrap justify-center gap-4 text-sm text-gray-500">
            <span class="flex items-center"><i class="fas fa-clock text-green-500 mr-1"></i>Phản hồi nhanh</span>
            <span class="flex items-center"><i class="fas fa-shield-alt text-blue-500 mr-1"></i>Bảo mật thông tin</span>
            <span class="flex items-center"><i class="fas fa-heart text-red-500 mr-1"></i>Chăm sóc tận tình</span>
        </div>
    </section>

    {{-- Thông báo --}}
    @if(session('success'))
        <div class="max-w-3xl mx-auto bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg mb-6 text-center" data-aos="fade-down">
            {{ session('success') }}
        </div>
    @endif

    {{-- Form liên hệ & thông tin --}}
    <section class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 mb-16">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            {{-- Form --}}
            <div class="md:col-span-2 bg-white rounded-2xl shadow-md p-8" data-aos="fade-right">
                <div class="mb-6">
                    <h2 class="text-2xl font-bold text-gray-800 mb-2">Gửi tin nhắn cho chúng tôi</h2>
                    <p class="text-gray-600">Điền thông tin bên dưới và chúng tôi sẽ liên hệ lại với bạn sớm nhất</p>
                </div>
                
                <form id="contactForm" action="{{ route('contact.submit') }}" method="POST" class="space-y-6">
                    @csrf
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Họ và tên <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="name" id="name" value="{{ old('name') }}"
                               class="w-full rounded-xl border border-gray-300 focus:ring-2 focus:ring-purple-500 focus:border-purple-500 px-3 py-2 transition-all duration-200"
                               placeholder="Nhập họ và tên của bạn" required>
                        <div class="error-message text-red-600 text-sm mt-1 hidden"></div>
                        @error('name')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Email <span class="text-red-500">*</span>
                            </label>
                            <input type="email" name="email" id="email" value="{{ old('email') }}"
                                   class="w-full rounded-xl border border-gray-300 focus:ring-2 focus:ring-purple-500 focus:border-purple-500 px-3 py-2 transition-all duration-200"
                                   placeholder="example@email.com" required>
                            <div class="error-message text-red-600 text-sm mt-1 hidden"></div>
                            @error('email')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Số điện thoại <span class="text-red-500">*</span>
                            </label>
                            <input type="tel" name="phone" id="phone" value="{{ old('phone') }}"
                                   class="w-full rounded-xl border border-gray-300 focus:ring-2 focus:ring-purple-500 focus:border-purple-500 px-3 py-2 transition-all duration-200"
                                   placeholder="0123 456 789" required>
                            <div class="error-message text-red-600 text-sm mt-1 hidden"></div>
                            @error('phone')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Chủ đề <span class="text-red-500">*</span>
                        </label>
                        <select name="subject" id="subject" 
                                class="w-full rounded-xl border border-gray-300 focus:ring-2 focus:ring-purple-500 focus:border-purple-500 px-3 py-2 transition-all duration-200" required>
                            <option value="">Chọn chủ đề liên hệ</option>
                            <option value="Tư vấn sản phẩm" {{ old('subject') == 'Tư vấn sản phẩm' ? 'selected' : '' }}>Tư vấn sản phẩm</option>
                            <option value="Hỗ trợ kỹ thuật" {{ old('subject') == 'Hỗ trợ kỹ thuật' ? 'selected' : '' }}>Hỗ trợ kỹ thuật</option>
                            <option value="Khiếu nại" {{ old('subject') == 'Khiếu nại' ? 'selected' : '' }}>Khiếu nại</option>
                            <option value="Đề xuất" {{ old('subject') == 'Đề xuất' ? 'selected' : '' }}>Đề xuất</option>
                            <option value="Khác" {{ old('subject') == 'Khác' ? 'selected' : '' }}>Khác</option>
                        </select>
                        <div class="error-message text-red-600 text-sm mt-1 hidden"></div>
                        @error('subject')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Nội dung <span class="text-red-500">*</span>
                        </label>
                        <textarea name="message" id="message" rows="5"
                                  class="w-full rounded-xl border border-gray-300 focus:ring-2 focus:ring-purple-500 focus:border-purple-500 px-3 py-2 transition-all duration-200 resize-none"
                                  placeholder="Mô tả chi tiết vấn đề hoặc câu hỏi của bạn..." required>{{ old('message') }}</textarea>
                        <div class="flex justify-between items-center mt-1">
                            <div class="error-message text-red-600 text-sm hidden"></div>
                            <span class="text-gray-400 text-xs" id="charCount">0/500 ký tự</span>
                        </div>
                        @error('message')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div class="flex items-center">
                        <input type="checkbox" id="privacy" name="privacy" class="mr-2" required>
                        <label for="privacy" class="text-sm text-gray-600">
                            Tôi đồng ý với <a href="#" class="text-purple-600 hover:underline">chính sách bảo mật</a> và 
                            <a href="#" class="text-purple-600 hover:underline">điều khoản sử dụng</a>
                        </label>
                    </div>

                    <button type="submit" id="submitBtn"
                            class="w-full inline-flex items-center justify-center bg-purple-600 text-white px-5 py-3 rounded-xl hover:bg-purple-700 transition duration-300 disabled:opacity-50 disabled:cursor-not-allowed">
                        <span id="submitText">
                            <i class="fas fa-paper-plane mr-2"></i>Gửi liên hệ
                        </span>
                        <span id="loadingText" class="hidden">
                            <i class="fas fa-spinner fa-spin mr-2"></i>Đang gửi...
                        </span>
                    </button>
                </form>
            </div>

            {{-- Thông tin liên hệ --}}
            <aside class="bg-white rounded-2xl shadow-md p-8 flex flex-col justify-between" data-aos="fade-left">
                <div>
                    <h2 class="text-xl font-semibold text-gray-800 mb-4">Thông tin liên hệ</h2>
                    <ul class="space-y-4 text-gray-700">
                        <li class="flex items-start">
                            <div class="flex-shrink-0 w-8 h-8 bg-purple-100 rounded-full flex items-center justify-center mr-3">
                                <i class="fas fa-map-marker-alt text-purple-600 text-sm"></i>
                            </div>
                            <div>
                                <p class="font-medium">Địa chỉ</p>
                                <p class="text-sm text-gray-600">123 Đường ABC, Quận 1, TP.HCM</p>
                            </div>
                        </li>
                        <li class="flex items-start">
                            <div class="flex-shrink-0 w-8 h-8 bg-green-100 rounded-full flex items-center justify-center mr-3">
                                <i class="fas fa-phone text-green-600 text-sm"></i>
                            </div>
                            <div>
                                <p class="font-medium">Hotline</p>
                                <a href="tel:+840123456789" class="text-sm text-gray-600 hover:text-green-600">(+84) 0123 456 789</a>
                            </div>
                        </li>
                        <li class="flex items-start">
                            <div class="flex-shrink-0 w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center mr-3">
                                <i class="fas fa-envelope text-blue-600 text-sm"></i>
                            </div>
                            <div>
                                <p class="font-medium">Email</p>
                                <a href="mailto:support@shoplaravel.vn" class="text-sm text-gray-600 hover:text-blue-600">support@shoplaravel.vn</a>
                            </div>
                        </li>
                        <li class="flex items-start">
                            <div class="flex-shrink-0 w-8 h-8 bg-orange-100 rounded-full flex items-center justify-center mr-3">
                                <i class="fas fa-clock text-orange-600 text-sm"></i>
                            </div>
                            <div>
                                <p class="font-medium">Giờ làm việc</p>
                                <p class="text-sm text-gray-600">08:00 - 18:00 (Thứ 2 - Thứ 7)</p>
                            </div>
                        </li>
                    </ul>

                    <hr class="my-6">

                    <h3 class="text-sm font-medium text-gray-800 mb-3">Kết nối với chúng tôi</h3>
                    <div class="flex items-center gap-4 text-gray-500 mb-6">
                        <a href="#" class="hover:text-blue-600 transition transform hover:scale-110" title="Facebook">
                            <i class="fab fa-facebook text-2xl"></i>
                        </a>
                        <a href="#" class="hover:text-sky-500 transition transform hover:scale-110" title="Twitter">
                            <i class="fab fa-twitter text-2xl"></i>
                        </a>
                        <a href="#" class="hover:text-red-600 transition transform hover:scale-110" title="YouTube">
                            <i class="fab fa-youtube text-2xl"></i>
                        </a>
                        <a href="#" class="hover:text-pink-600 transition transform hover:scale-110" title="Instagram">
                            <i class="fab fa-instagram text-2xl"></i>
                        </a>
                    </div>

                    {{-- Chat hỗ trợ --}}
                    <div class="bg-gradient-to-r from-purple-50 to-blue-50 rounded-xl p-4 mb-6">
                        <div class="flex items-center mb-2">
                            <i class="fas fa-comments text-purple-600 mr-2"></i>
                            <span class="font-medium text-gray-800">Chat hỗ trợ trực tuyến</span>
                        </div>
                        <p class="text-sm text-gray-600 mb-3">Có câu hỏi? Chat ngay với chúng tôi!</p>
                        <button class="w-full bg-purple-600 text-white py-2 px-4 rounded-lg hover:bg-purple-700 transition duration-200 text-sm">
                            <i class="fas fa-comment-dots mr-1"></i>Bắt đầu chat
                        </button>
                    </div>
                </div>

                {{-- Google Map nằm trong khung --}}
                <div class="rounded-xl overflow-hidden shadow-sm border border-gray-200" data-aos="zoom-in" data-aos-delay="200">
                    <iframe
                        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3919.492933997431!2d106.70042327451744!3d10.773374759293327!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x31752f43a92b3563%3A0x8eaa2896cf814567!2zMTIzIEPhuqd1IFRow6FpIEJcdTkgMSwgUXXhuq1uIDEuIFRQLiBIQ00!5e0!3m2!1svi!2s!4v1717161234567!5m2!1svi!2s"
                        width="100%" height="200" style="border:0;" allowfullscreen="" loading="lazy"
                        referrerpolicy="no-referrer-when-downgrade"></iframe>
                </div>
            </aside>
        </div>
    </section>

    {{-- FAQ Section --}}
    <section class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 mb-16" data-aos="fade-up">
        <div class="text-center mb-12">
            <h2 class="text-3xl font-bold text-gray-800 mb-4">Câu hỏi thường gặp</h2>
            <p class="text-gray-600">Tìm câu trả lời cho những thắc mắc phổ biến</p>
        </div>
        
        <div class="space-y-4">
            <div class="bg-white rounded-xl shadow-md overflow-hidden">
                <button class="faq-toggle w-full px-6 py-4 text-left flex items-center justify-between hover:bg-gray-50 transition duration-200">
                    <span class="font-medium text-gray-800">Làm thế nào để đặt hàng?</span>
                    <i class="fas fa-chevron-down transition-transform duration-200"></i>
                </button>
                <div class="faq-content hidden px-6 pb-4">
                    <p class="text-gray-600">Bạn có thể đặt hàng trực tiếp trên website bằng cách thêm sản phẩm vào giỏ hàng và tiến hành thanh toán. Chúng tôi hỗ trợ nhiều phương thức thanh toán an toàn.</p>
                </div>
            </div>
            
            <div class="bg-white rounded-xl shadow-md overflow-hidden">
                <button class="faq-toggle w-full px-6 py-4 text-left flex items-center justify-between hover:bg-gray-50 transition duration-200">
                    <span class="font-medium text-gray-800">Thời gian giao hàng là bao lâu?</span>
                    <i class="fas fa-chevron-down transition-transform duration-200"></i>
                </button>
                <div class="faq-content hidden px-6 pb-4">
                    <p class="text-gray-600">Thời gian giao hàng từ 1-3 ngày làm việc đối với nội thành TP.HCM và 3-7 ngày đối với các tỉnh thành khác. Chúng tôi sẽ thông báo cụ thể khi đơn hàng được xác nhận.</p>
                </div>
            </div>
            
            <div class="bg-white rounded-xl shadow-md overflow-hidden">
                <button class="faq-toggle w-full px-6 py-4 text-left flex items-center justify-between hover:bg-gray-50 transition duration-200">
                    <span class="font-medium text-gray-800">Có thể đổi trả sản phẩm không?</span>
                    <i class="fas fa-chevron-down transition-transform duration-200"></i>
                </button>
                <div class="faq-content hidden px-6 pb-4">
                    <p class="text-gray-600">Chúng tôi hỗ trợ đổi trả sản phẩm trong vòng 7 ngày kể từ ngày nhận hàng với điều kiện sản phẩm còn nguyên vẹn và có hóa đơn mua hàng.</p>
                </div>
            </div>
            
            <div class="bg-white rounded-xl shadow-md overflow-hidden">
                <button class="faq-toggle w-full px-6 py-4 text-left flex items-center justify-between hover:bg-gray-50 transition duration-200">
                    <span class="font-medium text-gray-800">Làm sao để theo dõi đơn hàng?</span>
                    <i class="fas fa-chevron-down transition-transform duration-200"></i>
                </button>
                <div class="faq-content hidden px-6 pb-4">
                    <p class="text-gray-600">Bạn có thể theo dõi đơn hàng bằng mã đơn hàng hoặc đăng nhập vào tài khoản để xem lịch sử đơn hàng. Chúng tôi cũng sẽ gửi SMS/Email cập nhật trạng thái đơn hàng.</p>
                </div>
            </div>
        </div>
    </section>

    {{-- AOS Animation --}}
    @push('scripts')
        <link href="https://unpkg.com/aos@2.3.4/dist/aos.css" rel="stylesheet">
        <script src="https://unpkg.com/aos@2.3.4/dist/aos.js"></script>
        <script>
            // Initialize AOS
            AOS.init({
                duration: 900,
                once: true,
                offset: 120,
            });

            // Form validation and interactions
            document.addEventListener('DOMContentLoaded', function() {
                const form = document.getElementById('contactForm');
                const submitBtn = document.getElementById('submitBtn');
                const submitText = document.getElementById('submitText');
                const loadingText = document.getElementById('loadingText');
                const messageTextarea = document.getElementById('message');
                const charCount = document.getElementById('charCount');

                // Character counter for message
                messageTextarea.addEventListener('input', function() {
                    const length = this.value.length;
                    charCount.textContent = `${length}/500 ký tự`;
                    
                    if (length > 500) {
                        charCount.classList.add('text-red-500');
                        charCount.classList.remove('text-gray-400');
                    } else {
                        charCount.classList.remove('text-red-500');
                        charCount.classList.add('text-gray-400');
                    }
                });

                // Phone number formatting
                const phoneInput = document.getElementById('phone');
                phoneInput.addEventListener('input', function() {
                    let value = this.value.replace(/\D/g, '');
                    if (value.length > 0) {
                        if (value.startsWith('84')) {
                            value = value.substring(2);
                        }
                        if (value.length <= 3) {
                            this.value = value;
                        } else if (value.length <= 6) {
                            this.value = value.substring(0, 3) + ' ' + value.substring(3);
                        } else if (value.length <= 9) {
                            this.value = value.substring(0, 3) + ' ' + value.substring(3, 6) + ' ' + value.substring(6);
                        } else {
                            this.value = value.substring(0, 3) + ' ' + value.substring(3, 6) + ' ' + value.substring(6, 9);
                        }
                    }
                });

                // Client-side validation
                function validateForm() {
                    let isValid = true;
                    const fields = ['name', 'email', 'phone', 'subject', 'message'];
                    
                    fields.forEach(fieldId => {
                        const field = document.getElementById(fieldId);
                        const errorDiv = field.parentElement.querySelector('.error-message');
                        
                        if (!field.value.trim()) {
                            showError(field, errorDiv, 'Trường này không được để trống');
                            isValid = false;
                        } else {
                            hideError(field, errorDiv);
                            
                            // Specific validations
                            if (fieldId === 'email' && !isValidEmail(field.value)) {
                                showError(field, errorDiv, 'Email không hợp lệ');
                                isValid = false;
                            } else if (fieldId === 'phone' && !isValidPhone(field.value)) {
                                showError(field, errorDiv, 'Số điện thoại không hợp lệ');
                                isValid = false;
                            } else if (fieldId === 'message' && field.value.length > 500) {
                                showError(field, errorDiv, 'Nội dung không được vượt quá 500 ký tự');
                                isValid = false;
                            }
                        }
                    });

                    // Check privacy checkbox
                    const privacyCheckbox = document.getElementById('privacy');
                    if (!privacyCheckbox.checked) {
                        alert('Vui lòng đồng ý với chính sách bảo mật và điều khoản sử dụng');
                        isValid = false;
                    }

                    return isValid;
                }

                function isValidEmail(email) {
                    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                    return emailRegex.test(email);
                }

                function isValidPhone(phone) {
                    const phoneRegex = /^[0-9\s]{10,}$/;
                    return phoneRegex.test(phone.replace(/\s/g, ''));
                }

                function showError(field, errorDiv, message) {
                    field.classList.add('border-red-500');
                    field.classList.remove('border-gray-300');
                    errorDiv.textContent = message;
                    errorDiv.classList.remove('hidden');
                }

                function hideError(field, errorDiv) {
                    field.classList.remove('border-red-500');
                    field.classList.add('border-gray-300');
                    errorDiv.classList.add('hidden');
                }

                // Form submission
                form.addEventListener('submit', function(e) {
                    e.preventDefault();
                    
                    if (validateForm()) {
                        // Show loading state
                        submitBtn.disabled = true;
                        submitText.classList.add('hidden');
                        loadingText.classList.remove('hidden');
                        
                        // Submit form
                        setTimeout(() => {
                            form.submit();
                        }, 1000);
                    }
                });

                // FAQ Toggle functionality
                const faqToggles = document.querySelectorAll('.faq-toggle');
                faqToggles.forEach(toggle => {
                    toggle.addEventListener('click', function() {
                        const content = this.nextElementSibling;
                        const icon = this.querySelector('i');
                        
                        if (content.classList.contains('hidden')) {
                            content.classList.remove('hidden');
                            icon.style.transform = 'rotate(180deg)';
                        } else {
                            content.classList.add('hidden');
                            icon.style.transform = 'rotate(0deg)';
                        }
                    });
                });

                // Smooth scroll for anchor links
                document.querySelectorAll('a[href^="#"]').forEach(anchor => {
                    anchor.addEventListener('click', function (e) {
                        e.preventDefault();
                        const target = document.querySelector(this.getAttribute('href'));
                        if (target) {
                            target.scrollIntoView({
                                behavior: 'smooth',
                                block: 'start'
                            });
                        }
                    });
                });
            });
        </script>
    @endpush
@endsection
