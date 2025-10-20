@extends('layouts.client')

@section('title', 'Liên hệ')

@section('content')
    {{-- Phần tiêu đề --}}
    <section class="bg-gradient-to-r from-purple-50 to-blue-50 rounded-2xl shadow-sm p-8 md:p-12 mt-10 mb-12 text-center"
             data-aos="fade-down">
        <h1 class="text-4xl font-extrabold text-gray-800 mb-3">Liên hệ với chúng tôi</h1>
        <p class="text-gray-600 text-lg">Chúng tôi sẽ phản hồi trong giờ hành chính (8:00 - 18:00)</p>
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
                <form action="{{ route('contact.submit') }}" method="POST" class="space-y-6">
                    @csrf
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Họ và tên</label>
                        <input type="text" name="name" value="{{ old('name') }}"
                               class="w-full rounded-xl border border-gray-300 focus:ring-2 focus:ring-purple-500 focus:border-purple-500 px-3 py-2" required>
                        @error('name')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                            <input type="email" name="email" value="{{ old('email') }}"
                                   class="w-full rounded-xl border border-gray-300 focus:ring-2 focus:ring-purple-500 focus:border-purple-500 px-3 py-2" required>
                            @error('email')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Số điện thoại</label>
                            <input type="text" name="phone" value="{{ old('phone') }}"
                                   class="w-full rounded-xl border border-gray-300 focus:ring-2 focus:ring-purple-500 focus:border-purple-500 px-3 py-2" required>
                            @error('phone')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Chủ đề</label>
                        <input type="text" name="subject" value="{{ old('subject') }}"
                               class="w-full rounded-xl border border-gray-300 focus:ring-2 focus:ring-purple-500 focus:border-purple-500 px-3 py-2" required>
                        @error('subject')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nội dung</label>
                        <textarea name="message" rows="5"
                                  class="w-full rounded-xl border border-gray-300 focus:ring-2 focus:ring-purple-500 focus:border-purple-500 px-3 py-2" required>{{ old('message') }}</textarea>
                        @error('message')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
                    </div>

                    <button type="submit"
                            class="inline-flex items-center bg-purple-600 text-white px-5 py-2.5 rounded-xl hover:bg-purple-700 transition duration-300">
                        <i class="fas fa-paper-plane mr-2"></i>Gửi liên hệ
                    </button>
                </form>
            </div>

            {{-- Thông tin liên hệ --}}
            <aside class="bg-white rounded-2xl shadow-md p-8 flex flex-col justify-between" data-aos="fade-left">
                <div>
                    <h2 class="text-xl font-semibold text-gray-800 mb-4">Thông tin liên hệ</h2>
                    <ul class="space-y-3 text-gray-700">
                        <li><i class="fas fa-map-marker-alt text-purple-600 mr-2"></i>123 Đường ABC, Quận 1, TP.HCM</li>
                        <li><i class="fas fa-phone text-purple-600 mr-2"></i>(+84) 0123 456 789</li>
                        <li><i class="fas fa-envelope text-purple-600 mr-2"></i>support@shoplaravel.vn</li>
                        <li><i class="fas fa-clock text-purple-600 mr-2"></i>08:00 - 18:00 (Thứ 2 - Thứ 7)</li>
                    </ul>

                    <hr class="my-6">

                    <h3 class="text-sm font-medium text-gray-800 mb-3">Kết nối với chúng tôi</h3>
                    <div class="flex items-center gap-4 text-gray-500 mb-6">
                        <a href="#" class="hover:text-blue-600 transition"><i class="fab fa-facebook text-2xl"></i></a>
                        <a href="#" class="hover:text-sky-500 transition"><i class="fab fa-twitter text-2xl"></i></a>
                        <a href="#" class="hover:text-red-600 transition"><i class="fab fa-youtube text-2xl"></i></a>
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

    {{-- AOS Animation --}}
    @push('scripts')
        <link href="https://unpkg.com/aos@2.3.4/dist/aos.css" rel="stylesheet">
        <script src="https://unpkg.com/aos@2.3.4/dist/aos.js"></script>
        <script>
            AOS.init({
                duration: 900,
                once: true,
                offset: 120,
            });
        </script>
    @endpush
@endsection
