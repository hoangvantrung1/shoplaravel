@extends('layouts.client')

@include('components.toast')

@section('title', 'Liên hệ - Hỗ trợ khách hàng 24/7')

@section('meta')
    <meta name="description" content="Liên hệ với chúng tôi để được hỗ trợ tư vấn sản phẩm, giải đáp thắc mắc và chăm sóc khách hàng tốt nhất. Hotline: (+84) 0123 456 789">
    <meta name="keywords" content="liên hệ, hỗ trợ khách hàng, tư vấn, chăm sóc khách hàng, hotline">
    <meta property="og:title" content="Liên hệ - Hỗ trợ khách hàng">
    <meta property="og:description" content="Liên hệ với chúng tôi để được hỗ trợ tư vấn sản phẩm và chăm sóc khách hàng tốt nhất">
    <meta property="og:type" content="website">
@endsection

@section('content')
    {{-- Phần tiêu đề với decorative elements --}}
    <section class="relative bg-gradient-to-br from-purple-600 via-purple-700 to-purple-800 text-white py-16 md:py-20 overflow-hidden">
        <!-- Decorative background elements -->
        <div class="absolute inset-0 overflow-hidden pointer-events-none">
            <div class="absolute top-0 -right-20 w-96 h-96 bg-purple-500/20 rounded-full blur-3xl animate-pulse-slow"></div>
            <div class="absolute bottom-0 -left-20 w-96 h-96 bg-purple-400/20 rounded-full blur-3xl animate-pulse-slow" style="animation-delay: 1s;"></div>
            <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-64 h-64 bg-white/5 rounded-full blur-2xl"></div>
        </div>

        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 z-10">
            <div class="text-center animate-fadeUp">
                <!-- Icon với glow effect -->
                <div class="inline-flex items-center justify-center w-20 h-20 bg-white/20 backdrop-blur-md rounded-2xl mb-6 shadow-lg border border-white/30 relative group">
                    <i class="fas fa-headset text-3xl"></i>
                    <div class="absolute inset-0 bg-white/10 rounded-2xl opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                </div>
                
                <!-- Title với gradient -->
                <h1 class="text-5xl md:text-6xl lg:text-7xl font-bold mb-6 bg-gradient-to-r from-white via-purple-100 to-white bg-clip-text text-transparent animate-gradient">
                    Liên hệ với chúng tôi
                </h1>
                
                <!-- Description -->
                <p class="text-xl md:text-2xl text-purple-100 max-w-3xl mx-auto leading-relaxed mb-8">
                    Chúng tôi sẽ phản hồi trong giờ hành chính (8:00 - 18:00)
                </p>

                <!-- Features -->
                <div class="flex flex-wrap justify-center gap-6 text-base">
                    <div class="flex items-center bg-white/10 backdrop-blur-sm px-4 py-2 rounded-full border border-white/20">
                        <i class="fas fa-clock text-yellow-300 mr-2"></i>
                        <span>Phản hồi nhanh</span>
                    </div>
                    <div class="flex items-center bg-white/10 backdrop-blur-sm px-4 py-2 rounded-full border border-white/20">
                        <i class="fas fa-shield-alt text-blue-300 mr-2"></i>
                        <span>Bảo mật thông tin</span>
                    </div>
                    <div class="flex items-center bg-white/10 backdrop-blur-sm px-4 py-2 rounded-full border border-white/20">
                        <i class="fas fa-heart text-pink-300 mr-2"></i>
                        <span>Chăm sóc tận tình</span>
                    </div>
                </div>

                <!-- Decorative line -->
                <div class="flex items-center justify-center gap-4 mt-8">
                    <div class="h-px w-16 bg-gradient-to-r from-transparent to-white/50"></div>
                    <div class="w-2 h-2 bg-white rounded-full"></div>
                    <div class="h-px w-16 bg-gradient-to-l from-transparent to-white/50"></div>
                </div>
            </div>
        </div>
    </section>
    {{-- Form liên hệ & thông tin --}}
    <section class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 mb-16">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            {{-- Form --}}
            <div class="md:col-span-2 bg-white rounded-2xl shadow-xl p-8 md:p-10 border border-gray-100" data-aos="fade-right">
                <div class="mb-8">
                    <h2 class="text-3xl font-bold text-gray-800 mb-3 flex items-center">
                        <span class="w-1 h-8 bg-gradient-to-b from-purple-600 to-purple-700 rounded-full mr-3"></span>
                        Gửi tin nhắn cho chúng tôi
                    </h2>
                    <p class="text-gray-600 text-lg">Điền thông tin bên dưới và chúng tôi sẽ liên hệ lại với bạn sớm nhất</p>
                </div>
                
                <form id="contactForm" action="{{ route('contact.submit') }}" method="POST" class="space-y-6">
                    @csrf
                    <div class="relative">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-user text-purple-600 mr-2"></i>
                            Họ và tên <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <input type="text" name="name" id="name" value="{{ old('name') }}"
                                   class="w-full rounded-xl border-2 border-gray-200 focus:ring-2 focus:ring-purple-500 focus:border-purple-500 px-4 py-3 pl-11 transition-all duration-200 bg-gray-50 focus:bg-white"
                                   placeholder="Nhập họ và tên của bạn" required>
                            <i class="fas fa-user absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
                        </div>
                        <div class="error-message text-red-600 text-sm mt-1 hidden" role="alert" aria-live="polite"></div>
                        @error('name')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="relative">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                <i class="fas fa-envelope text-purple-600 mr-2"></i>
                                Email <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <input type="email" name="email" id="email" value="{{ old('email') }}"
                                       class="w-full rounded-xl border-2 border-gray-200 focus:ring-2 focus:ring-purple-500 focus:border-purple-500 px-4 py-3 pl-11 transition-all duration-200 bg-gray-50 focus:bg-white"
                                       placeholder="example@email.com" required>
                                <i class="fas fa-envelope absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
                            </div>
                            <div class="error-message text-red-600 text-sm mt-1 hidden" role="alert" aria-live="polite"></div>
                            @error('email')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
                        </div>
                        <div class="relative">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                <i class="fas fa-phone text-purple-600 mr-2"></i>
                                Số điện thoại <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <input type="tel" name="phone" id="phone" value="{{ old('phone') }}"
                                       class="w-full rounded-xl border-2 border-gray-200 focus:ring-2 focus:ring-purple-500 focus:border-purple-500 px-4 py-3 pl-11 transition-all duration-200 bg-gray-50 focus:bg-white"
                                       placeholder="0123 456 789" required>
                                <i class="fas fa-phone absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
                            </div>
                            <div class="error-message text-red-600 text-sm mt-1 hidden" role="alert" aria-live="polite"></div>
                            @error('phone')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
                        </div>
                    </div>

                    <div class="relative">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-tag text-purple-600 mr-2"></i>
                            Chủ đề <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <select name="subject" id="subject" 
                                    class="w-full rounded-xl border-2 border-gray-200 focus:ring-2 focus:ring-purple-500 focus:border-purple-500 px-4 py-3 pl-11 pr-10 transition-all duration-200 bg-gray-50 focus:bg-white appearance-none cursor-pointer" required>
                            <option value="">Chọn chủ đề liên hệ</option>
                            <option value="Tư vấn sản phẩm" {{ old('subject') == 'Tư vấn sản phẩm' ? 'selected' : '' }}>Tư vấn sản phẩm</option>
                            <option value="Hỗ trợ kỹ thuật" {{ old('subject') == 'Hỗ trợ kỹ thuật' ? 'selected' : '' }}>Hỗ trợ kỹ thuật</option>
                            <option value="Khiếu nại" {{ old('subject') == 'Khiếu nại' ? 'selected' : '' }}>Khiếu nại</option>
                            <option value="Đề xuất" {{ old('subject') == 'Đề xuất' ? 'selected' : '' }}>Đề xuất</option>
                            <option value="Khác" {{ old('subject') == 'Khác' ? 'selected' : '' }}>Khác</option>
                        </select>
                        <i class="fas fa-tag absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none"></i>
                        <i class="fas fa-chevron-down absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none"></i>
                        </div>
                        <div class="error-message text-red-600 text-sm mt-1 hidden" role="alert" aria-live="polite"></div>
                        @error('subject')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div class="relative">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-comment-dots text-purple-600 mr-2"></i>
                            Nội dung <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <textarea name="message" id="message" rows="6"
                                      class="w-full rounded-xl border-2 border-gray-200 focus:ring-2 focus:ring-purple-500 focus:border-purple-500 px-4 py-3 pl-11 transition-all duration-200 resize-none bg-gray-50 focus:bg-white"
                                      placeholder="Mô tả chi tiết vấn đề hoặc câu hỏi của bạn..." required>{{ old('message') }}</textarea>
                            <i class="fas fa-comment-dots absolute left-3 top-4 text-gray-400"></i>
                        </div>
                        <div class="flex justify-between items-center mt-1">
                            <div class="error-message text-red-600 text-sm hidden" role="alert" aria-live="polite"></div>
                            <span class="text-gray-400 text-xs" id="charCount">0/500 ký tự</span>
                        </div>
                        @error('message')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div class="flex items-start p-4 bg-purple-50 rounded-xl border border-purple-100">
                        <input type="checkbox" id="privacy" name="privacy" class="mt-1 mr-3 w-5 h-5 rounded border-gray-300 text-purple-600 focus:ring-purple-500 cursor-pointer" required>
                        <label for="privacy" class="text-sm text-gray-700 flex-1 cursor-pointer">
                            Tôi đồng ý với <a href="#" class="text-purple-600 hover:text-purple-800 font-semibold underline">chính sách bảo mật</a> và 
                            <a href="#" class="text-purple-600 hover:text-purple-800 font-semibold underline">điều khoản sử dụng</a>
                        </label>
                    </div>

                    <!-- Honeypot field (bots will fill this) -->
                    <div class="hidden" aria-hidden="true">
                        <label for="hp_company" class="sr-only">Company</label>
                        <input type="text" id="hp_company" name="company" tabindex="-1" autocomplete="off">
                    </div>

                    <button type="submit" id="submitBtn"
                            class="w-full inline-flex items-center justify-center bg-gradient-to-r from-purple-600 to-purple-700 text-white px-6 py-4 rounded-xl hover:from-purple-700 hover:to-purple-800 transition-all duration-300 disabled:opacity-50 disabled:cursor-not-allowed shadow-lg hover:shadow-xl transform hover:scale-[1.02] font-semibold text-lg group">
                        <span id="submitText" class="flex items-center">
                            <i class="fas fa-paper-plane mr-2 group-hover:translate-x-1 transition-transform"></i>
                            Gửi liên hệ
                        </span>
                        <span id="loadingText" class="hidden flex items-center">
                            <i class="fas fa-spinner fa-spin mr-2"></i>
                            Đang gửi...
                        </span>
                    </button>
                </form>
            </div>

            {{-- Thông tin liên hệ --}}
            <aside class="bg-white rounded-2xl shadow-xl p-8 border border-gray-100 flex flex-col justify-between" data-aos="fade-left">
                <div>
                    <h2 class="text-2xl font-bold text-gray-800 mb-6 flex items-center">
                        <span class="w-1 h-8 bg-gradient-to-b from-purple-600 to-purple-700 rounded-full mr-3"></span>
                        Thông tin liên hệ
                    </h2>
                    <ul class="space-y-5 text-gray-700">
                        <li class="flex items-start group hover:bg-purple-50 p-3 rounded-xl transition-colors duration-200">
                            <div class="flex-shrink-0 w-12 h-12 bg-gradient-to-br from-purple-100 to-purple-200 rounded-xl flex items-center justify-center mr-4 group-hover:scale-110 transition-transform duration-200 shadow-sm">
                                <i class="fas fa-map-marker-alt text-purple-600 text-lg"></i>
                            </div>
                            <div class="flex-1">
                                <p class="font-semibold text-gray-800 mb-1">Địa chỉ</p>
                                <p class="text-sm text-gray-600 leading-relaxed">123 Đường ABC, Quận 1, TP.HCM</p>
                            </div>
                        </li>
                        <li class="flex items-start group hover:bg-green-50 p-3 rounded-xl transition-colors duration-200">
                            <div class="flex-shrink-0 w-12 h-12 bg-gradient-to-br from-green-100 to-green-200 rounded-xl flex items-center justify-center mr-4 group-hover:scale-110 transition-transform duration-200 shadow-sm">
                                <i class="fas fa-phone text-green-600 text-lg"></i>
                            </div>
                            <div class="flex-1">
                                <p class="font-semibold text-gray-800 mb-1">Hotline</p>
                                <a href="tel:+840123456789" class="text-sm text-gray-600 hover:text-green-600 font-medium transition">(+84) 0123 456 789</a>
                            </div>
                        </li>
                        <li class="flex items-start group hover:bg-blue-50 p-3 rounded-xl transition-colors duration-200">
                            <div class="flex-shrink-0 w-12 h-12 bg-gradient-to-br from-blue-100 to-blue-200 rounded-xl flex items-center justify-center mr-4 group-hover:scale-110 transition-transform duration-200 shadow-sm">
                                <i class="fas fa-envelope text-blue-600 text-lg"></i>
                            </div>
                            <div class="flex-1">
                                <p class="font-semibold text-gray-800 mb-1">Email</p>
                                <a href="mailto:support@shoplaravel.vn" class="text-sm text-gray-600 hover:text-blue-600 font-medium transition break-all">support@shoplaravel.vn</a>
                            </div>
                        </li>
                        <li class="flex items-start group hover:bg-orange-50 p-3 rounded-xl transition-colors duration-200">
                            <div class="flex-shrink-0 w-12 h-12 bg-gradient-to-br from-orange-100 to-orange-200 rounded-xl flex items-center justify-center mr-4 group-hover:scale-110 transition-transform duration-200 shadow-sm">
                                <i class="fas fa-clock text-orange-600 text-lg"></i>
                            </div>
                            <div class="flex-1">
                                <p class="font-semibold text-gray-800 mb-1">Giờ làm việc</p>
                                <p class="text-sm text-gray-600 leading-relaxed">08:00 - 18:00 (Thứ 2 - Thứ 7)</p>
                            </div>
                        </li>
                    </ul>

                    <hr class="my-8 border-gray-200">

                    <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center">
                        <i class="fas fa-share-alt text-purple-600 mr-2"></i>
                        Kết nối với chúng tôi
                    </h3>
                    <div class="flex items-center gap-3 mb-8">
                        <a href="#" class="w-12 h-12 bg-blue-50 hover:bg-blue-600 rounded-xl flex items-center justify-center text-blue-600 hover:text-white transition-all duration-300 transform hover:scale-110 hover:shadow-lg" title="Facebook">
                            <i class="fab fa-facebook text-xl"></i>
                        </a>
                        <a href="#" class="w-12 h-12 bg-sky-50 hover:bg-sky-500 rounded-xl flex items-center justify-center text-sky-600 hover:text-white transition-all duration-300 transform hover:scale-110 hover:shadow-lg" title="Twitter">
                            <i class="fab fa-twitter text-xl"></i>
                        </a>
                        <a href="#" class="w-12 h-12 bg-red-50 hover:bg-red-600 rounded-xl flex items-center justify-center text-red-600 hover:text-white transition-all duration-300 transform hover:scale-110 hover:shadow-lg" title="YouTube">
                            <i class="fab fa-youtube text-xl"></i>
                        </a>
                        <a href="#" class="w-12 h-12 bg-pink-50 hover:bg-pink-600 rounded-xl flex items-center justify-center text-pink-600 hover:text-white transition-all duration-300 transform hover:scale-110 hover:shadow-lg" title="Instagram">
                            <i class="fab fa-instagram text-xl"></i>
                        </a>
                    </div>

                    {{-- Chat hỗ trợ --}}
                    <div class="bg-gradient-to-br from-purple-50 via-purple-50/50 to-blue-50 rounded-xl p-5 mb-6 border border-purple-100 shadow-sm hover:shadow-md transition-shadow duration-300">
                        <div class="flex items-center mb-3">
                            <div class="w-10 h-10 bg-gradient-to-br from-purple-600 to-purple-700 rounded-lg flex items-center justify-center mr-3 shadow-md">
                                <i class="fas fa-comments text-white"></i>
                            </div>
                            <span class="font-bold text-gray-800">Chat hỗ trợ trực tuyến</span>
                        </div>
                        <p class="text-sm text-gray-600 mb-4">Có câu hỏi? Chat ngay với chúng tôi!</p>
                        <button onclick="document.getElementById('openChat')?.click()" class="w-full bg-gradient-to-r from-purple-600 to-purple-700 text-white py-3 px-4 rounded-xl hover:from-purple-700 hover:to-purple-800 transition-all duration-300 text-sm font-semibold shadow-lg hover:shadow-xl transform hover:scale-[1.02]">
                            <i class="fas fa-comment-dots mr-2"></i>Bắt đầu chat
                        </button>
                    </div>
                </div>

                {{-- Google Map nằm trong khung --}}
                <div class="rounded-xl overflow-hidden shadow-lg border-2 border-gray-200 hover:border-purple-300 transition-colors duration-300" data-aos="zoom-in" data-aos-delay="200">
                    <div class="relative">
                        <iframe
                            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3919.492933997431!2d106.70042327451744!3d10.773374759293327!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x31752f43a92b3563%3A0x8eaa2896cf814567!2zMTIzIEPhuqd1IFRow6FpIEJcdTkgMSwgUXXhuq1uIDEuIFRQLiBIQ00!5e0!3m2!1svi!2s!4v1717161234567!5m2!1svi!2s"
                            width="100%" height="220" style="border:0;" allowfullscreen="" loading="lazy"
                            referrerpolicy="no-referrer-when-downgrade" class="rounded-xl"></iframe>
                        <div class="absolute top-2 right-2 bg-white/90 backdrop-blur-sm px-3 py-1 rounded-lg shadow-md text-xs font-semibold text-gray-700">
                            <i class="fas fa-map-marker-alt text-purple-600 mr-1"></i>
                            Xem bản đồ
                        </div>
                    </div>
                </div>
            </aside>
        </div>
    </section>

    {{-- FAQ Section --}}
    <section class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 mb-16" data-aos="fade-up">
        <div class="text-center mb-12">
            <div class="inline-flex items-center justify-center w-16 h-16 bg-purple-100 rounded-2xl mb-4">
                <i class="fas fa-question-circle text-purple-600 text-2xl"></i>
            </div>
            <h2 class="text-4xl font-bold text-gray-800 mb-4">Câu hỏi thường gặp</h2>
            <p class="text-gray-600 text-lg">Tìm câu trả lời cho những thắc mắc phổ biến</p>
        </div>
        
        <div class="space-y-4">
            <div class="bg-white rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 overflow-hidden border border-gray-100 group">
                <button class="faq-toggle w-full px-6 py-5 text-left flex items-center justify-between hover:bg-purple-50 transition duration-200 group-hover:border-purple-200" aria-expanded="false" aria-controls="faq-panel-1">
                    <span class="font-semibold text-gray-800 text-lg flex items-center">
                        <i class="fas fa-shopping-cart text-purple-600 mr-3"></i>
                        Làm thế nào để đặt hàng?
                    </span>
                    <i class="fas fa-chevron-down transition-transform duration-200 text-purple-600"></i>
                </button>
                <div id="faq-panel-1" class="faq-content hidden px-6 pb-5 border-t border-gray-100">
                    <p class="text-gray-600 leading-relaxed pt-4">Bạn có thể đặt hàng trực tiếp trên website bằng cách thêm sản phẩm vào giỏ hàng và tiến hành thanh toán. Chúng tôi hỗ trợ nhiều phương thức thanh toán an toàn.</p>
                </div>
            </div>
            
            <div class="bg-white rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 overflow-hidden border border-gray-100 group">
                <button class="faq-toggle w-full px-6 py-5 text-left flex items-center justify-between hover:bg-purple-50 transition duration-200 group-hover:border-purple-200" aria-expanded="false" aria-controls="faq-panel-2">
                    <span class="font-semibold text-gray-800 text-lg flex items-center">
                        <i class="fas fa-truck text-green-600 mr-3"></i>
                        Thời gian giao hàng là bao lâu?
                    </span>
                    <i class="fas fa-chevron-down transition-transform duration-200 text-purple-600"></i>
                </button>
                <div id="faq-panel-2" class="faq-content hidden px-6 pb-5 border-t border-gray-100">
                    <p class="text-gray-600 leading-relaxed pt-4">Thời gian giao hàng từ 1-3 ngày làm việc đối với nội thành TP.HCM và 3-7 ngày đối với các tỉnh thành khác. Chúng tôi sẽ thông báo cụ thể khi đơn hàng được xác nhận.</p>
                </div>
            </div>
            
            <div class="bg-white rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 overflow-hidden border border-gray-100 group">
                <button class="faq-toggle w-full px-6 py-5 text-left flex items-center justify-between hover:bg-purple-50 transition duration-200 group-hover:border-purple-200" aria-expanded="false" aria-controls="faq-panel-3">
                    <span class="font-semibold text-gray-800 text-lg flex items-center">
                        <i class="fas fa-exchange-alt text-blue-600 mr-3"></i>
                        Có thể đổi trả sản phẩm không?
                    </span>
                    <i class="fas fa-chevron-down transition-transform duration-200 text-purple-600"></i>
                </button>
                <div id="faq-panel-3" class="faq-content hidden px-6 pb-5 border-t border-gray-100">
                    <p class="text-gray-600 leading-relaxed pt-4">Chúng tôi hỗ trợ đổi trả sản phẩm trong vòng 7 ngày kể từ ngày nhận hàng với điều kiện sản phẩm còn nguyên vẹn và có hóa đơn mua hàng.</p>
                </div>
            </div>
            
            <div class="bg-white rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 overflow-hidden border border-gray-100 group">
                <button class="faq-toggle w-full px-6 py-5 text-left flex items-center justify-between hover:bg-purple-50 transition duration-200 group-hover:border-purple-200" aria-expanded="false" aria-controls="faq-panel-4">
                    <span class="font-semibold text-gray-800 text-lg flex items-center">
                        <i class="fas fa-search-location text-orange-600 mr-3"></i>
                        Làm sao để theo dõi đơn hàng?
                    </span>
                    <i class="fas fa-chevron-down transition-transform duration-200 text-purple-600"></i>
                </button>
                <div id="faq-panel-4" class="faq-content hidden px-6 pb-5 border-t border-gray-100">
                    <p class="text-gray-600 leading-relaxed pt-4">Bạn có thể theo dõi đơn hàng bằng mã đơn hàng hoặc đăng nhập vào tài khoản để xem lịch sử đơn hàng. Chúng tôi cũng sẽ gửi SMS/Email cập nhật trạng thái đơn hàng.</p>
                </div>
            </div>
        </div>
    </section>

    {{-- Custom Styles --}}
    <style>
        /* Animations */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes gradient {
            0%, 100% {
                background-position: 0% 50%;
            }
            50% {
                background-position: 100% 50%;
            }
        }

        @keyframes pulse-slow {
            0%, 100% {
                opacity: 0.3;
            }
            50% {
                opacity: 0.6;
            }
        }

        .animate-fadeUp {
            animation: fadeInUp 0.8s ease-out forwards;
        }

        .animate-gradient {
            background-size: 200% auto;
            animation: gradient 3s ease infinite;
        }

        .animate-pulse-slow {
            animation: pulse-slow 4s ease-in-out infinite;
        }

        /* FAQ Animation - Sử dụng Tailwind hidden class */
        .faq-content {
            transition: all 0.3s ease-out;
        }

        .faq-content:not(.hidden) {
            animation: fadeInDown 0.3s ease-out;
        }

        @keyframes fadeInDown {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .faq-toggle[aria-expanded="true"] .fa-chevron-down {
            transform: rotate(180deg);
        }
    </style>

    {{-- AOS Animation --}}
    @push('scripts')
        <link href="https://unpkg.com/aos@2.3.4/dist/aos.css" rel="stylesheet">
        <script src="https://unpkg.com/aos@2.3.4/dist/aos.js"></script>
        <script>
            // Initialize AOS with reduced-motion support
            const prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
            AOS.init({
                duration: prefersReducedMotion ? 0 : 900,
                once: true,
                offset: prefersReducedMotion ? 0 : 120,
                disable: prefersReducedMotion
            });

            // Use global toast manager

            // Form validation and interactions
            document.addEventListener('DOMContentLoaded', function() {
                const form = document.getElementById('contactForm');
                const submitBtn = document.getElementById('submitBtn');
                const submitText = document.getElementById('submitText');
                const loadingText = document.getElementById('loadingText');
                const messageTextarea = document.getElementById('message');
                const charCount = document.getElementById('charCount');
                const honeypot = document.getElementById('hp_company');

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
                    
                    // Honeypot check
                    if (honeypot && honeypot.value.trim() !== '') {
                        // silently drop or show generic error
                        toastManager.error('Có lỗi xảy ra. Vui lòng thử lại.');
                        return;
                    }

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

                // FAQ Toggle functionality - Sử dụng Tailwind hidden class
                console.log('Initializing FAQ toggles...');
                const faqToggles = document.querySelectorAll('.faq-toggle');
                console.log('Found FAQ toggles:', faqToggles.length);
                
                if (faqToggles.length === 0) {
                    console.error('No FAQ toggles found!');
                }
                
                faqToggles.forEach((toggle, index) => {
                    console.log(`Setting up FAQ toggle ${index + 1}`);
                    toggle.addEventListener('click', function(e) {
                        console.log('FAQ clicked!', this);
                        e.preventDefault();
                        e.stopPropagation();
                        
                        const content = this.nextElementSibling;
                        console.log('Content element:', content);
                        
                        if (!content) {
                            console.error('FAQ content not found - nextElementSibling is null');
                            return;
                        }
                        
                        if (!content.classList.contains('faq-content')) {
                            console.error('Element is not a faq-content:', content);
                            return;
                        }
                        
                        const icon = this.querySelector('i.fa-chevron-down');
                        const isExpanded = this.getAttribute('aria-expanded') === 'true';
                        console.log('Is expanded:', isExpanded);
                        
                        if (isExpanded) {
                            // Đóng
                            console.log('Closing FAQ');
                            content.classList.add('hidden');
                            this.setAttribute('aria-expanded', 'false');
                            if (icon) {
                                icon.style.transform = 'rotate(0deg)';
                            }
                        } else {
                            // Mở - đóng các FAQ khác trước
                            console.log('Opening FAQ, closing others');
                            faqToggles.forEach(otherToggle => {
                                if (otherToggle !== this) {
                                    const otherContent = otherToggle.nextElementSibling;
                                    if (otherContent && otherContent.classList.contains('faq-content')) {
                                        otherContent.classList.add('hidden');
                                        otherToggle.setAttribute('aria-expanded', 'false');
                                        const otherIcon = otherToggle.querySelector('i.fa-chevron-down');
                                        if (otherIcon) {
                                            otherIcon.style.transform = 'rotate(0deg)';
                                        }
                                    }
                                }
                            });
                            
                            // Mở FAQ hiện tại
                            console.log('Removing hidden class from content');
                            content.classList.remove('hidden');
                            console.log('Content classes after remove:', content.className);
                            this.setAttribute('aria-expanded', 'true');
                            if (icon) {
                                icon.style.transform = 'rotate(180deg)';
                            }
                        }
                    });
                });
                
                console.log('FAQ toggles initialized successfully');

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

                // Convert server flash success into toast (if exists in DOM)
                const serverSuccess = {!! json_encode(session('success')) !!};
                if (serverSuccess) {
                    toastManager.success(serverSuccess);
                }
            });
        </script>
    @endpush
@endsection
