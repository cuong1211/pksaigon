@extends('frontend.layouts.index')
@section('content')
    <!-- Hero Section Start -->
    <div class="hero">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <!-- Hero Content Start -->
                    <div class="hero-content">
                        <!-- Section Title Start -->
                        <div class="section-title">
                            <h1 class="text-anime-style-2" data-cursor="-opaque">Chăm sóc <span>Sức khỏe Phụ nữ</span> với Tâm
                                huyết và Chuyên nghiệp</h1>
                            <p class="wow fadeInUp" data-wow-delay="0.25s">Phòng khám Sản phụ khoa Sài Gòn tự hào mang đến
                                dịch vụ chăm sóc sức khỏe toàn diện cho phụ nữ với đội ngũ bác sĩ giàu kinh nghiệm, trang
                                thiết bị hiện đại và không gian thân thiện.</p>
                        </div>
                        <!-- Section Title End -->

                        <!-- Hero Content Body Start -->
                        <div class="hero-content-body wow fadeInUp" data-wow-delay="0.5s">
                            <a href="{{ route('frontend.appointment') }}" class="btn-default">đặt lịch khám</a>
                        </div>
                        <!-- Hero Content Body End -->

                    </div>
                    <!-- Hero Content End -->
                </div>

                <div class="col-lg-6">
                    <!-- Hero Image Start -->
                    <div class="hero-image">
                        <!-- Hero Img Start -->
                        <div class="hero-img">
                            <figure>
                                <img src="images/bs-3-1.png" alt="">
                            </figure>
                        </div>
                        <!-- Hero Img End -->

                        <!-- Hero Image Tag Start -->
                        <div class="export-dantist-box">
                            <div class="icon-box">
                                <figure class="image-anime">
                                    <img src="images/bs-3-2.png" alt="">
                                </figure>
                            </div>
                            <div class="export-dantist-content">
                                <h3>BS. Nguyễn Thị Thu Hiền</h3>
                                <p>bác sĩ sản phụ khoa</p>
                            </div>
                        </div>
                        <!-- Hero Image Tag End -->


                        <!-- Hero Icon List End -->

                        <!-- Icon Start Image Start -->

                        <!-- Icon Start Image End -->
                    </div>
                    <!-- Hero Image End -->
                </div>
            </div>
        </div>
    </div>
    <!-- Hero Section End -->

    <!-- Call To Action Start -->
    <div class="cta-box">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-4 col-md-6 col-12">
                    <!-- Cta Box Item Start -->
                    <div class="cta-box-item wow fadeInUp">
                        <div class="icon-box">
                            <img src="images/icon-cta-phone.svg" alt="">
                        </div>
                        <div class="cta-box-content">
                            <h3>cần tư vấn khám bệnh?</h3>
                            <p>Gọi ngay: <a href="tel:0384518881" class="contact-link">0384518881</a>
                                <br>hoặc: <a href="tel:0988669292" class="contact-link">0988669292</a>
                            </p>
                            <p>hoặc gửi email: <a href="mailto:info@pksaigon.com" class="contact-link">info@pksaigon.com</a>
                            </p>
                        </div>
                    </div>
                </div>
                <!-- Cta Box Item End -->

                <!-- Cta Box Item Start -->
                <div class="col-lg-4 col-md-6 col-12">
                    <div class="cta-box-item wow fadeInUp" data-wow-delay="0.25s">
                        <div class="icon-box">
                            <img src="images/icon-cta-time.svg" alt="">
                        </div>
                        <div class="cta-box-content">
                            <h3>giờ làm việc</h3>
                            <p>Thứ 2 - Chủ nhật: 7:00 - 19:00</p>
                        </div>
                    </div>
                </div>
                <!-- Cta Box Item End -->

                <div class="col-lg-4 col-md-12 col-12">
                    <!-- Cta Box Btn Start -->
                    <div class="cta-box-btn wow fadeInUp" data-wow-delay="0.5s">
                        <a href="{{ route('frontend.appointment') }}" class="btn-default btn-highlighted">đặt lịch khám</a>
                    </div>
                    <!-- Cta Box Btn End -->
                </div>
            </div>
        </div>
    </div>
    <!-- Call To Action End -->

    <!-- About Us Section Start -->
    <div class="about-us">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <!-- About Image Start -->
                    <div class="about-image">
                        <div class="about-img-1">
                            <figure class="image-anime reveal">
                                <img src="images/sc-2-1.jpg" alt="">
                            </figure>
                        </div>

                        <div class="about-img-2">
                            <figure class="image-anime reveal">
                                <img src="images/sc-2-2.jpg" alt="">
                            </figure>
                        </div>

                        <!-- About Experience Circle Start -->
                        <div class="about-experience">
                            <figure>
                                <img src="images/about-experience-circle.png" alt="">
                            </figure>
                        </div>
                        <!-- About Experience Circle End -->
                    </div>
                    <!-- About Image End -->
                </div>

                <div class="col-lg-6">
                    <!-- About Content Start -->
                    <div class="about-content">
                        <!-- Section Title Start -->
                        <div class="section-title">
                            <h3 class="wow fadeInUp">về chúng tôi</h3>
                            <h2 class="text-anime-style-2" data-cursor="-opaque"><span>Đồng hành</span> cùng sức khỏe phụ nữ
                                mọi giai đoạn</h2>
                            <p class="wow fadeInUp" data-wow-delay="0.25s">Phòng khám phụ sản Thu Hiền với
                                đội ngũ bác sĩ giàu kinh nghiệm, trang thiết bị hiện đại, cam kết mang đến dịch vụ chăm sóc
                                sức khỏe chất lượng cao, tận tâm và an toàn cho phụ nữ ở mọi độ tuổi.</p>
                        </div>
                        <!-- Section Title End -->

                        <!-- About Us Body Start -->
                        <div class="about-us-body wow fadeInUp" data-wow-delay="0.5s">
                            <ul>
                                <li>đội ngũ bác sĩ giàu kinh nghiệm</li>
                                <li>dịch vụ chăm sóc toàn diện</li>
                                <li>trang thiết bị y tế hiện đại</li>
                                <li>dịch vụ cấp cứu 24/7</li>
                            </ul>
                        </div>
                        <!-- About Us Body End -->

                        <!-- About Us Footer Start -->
                        <div class="about-us-footer wow fadeInUp" data-wow-delay="0.75s">
                            <a href="{{ route('about') }}" class="btn-default">tìm hiểu thêm</a>
                        </div>
                        <!-- About Us Footer End -->
                    </div>
                    <!-- About Content End -->
                </div>
            </div>
        </div>
    </div>
    <!-- About Us Section End -->

    <!-- Our Serviceds Section Start -->
    <div class="our-services">
        <div class="container">
            <div class="row section-row">
                <div class="col-lg-12">
                    <!-- Section Title Start -->
                    <div class="section-title">
                        <h3 class="wow fadeInUp">dịch vụ của chúng tôi</h3>
                        <h2 class="text-anime-style-2" data-cursor="-opaque"><span>Dịch vụ</span> chất lượng cao dành cho
                            bạn</h2>
                        <p class="wow fadeInUp" data-wow-delay="0.25s">Chúng tôi cam kết mang đến những dịch vụ chăm sóc sức
                            khỏe tốt nhất.</p>
                    </div>
                    <!-- Section Title End -->
                </div>
            </div>

            <div class="row">
                <div class="col-lg-3 col-md-6">
                    <!-- Service Item Start -->
                    <div class="service-item wow fadeInUp">
                        <div class="icon-box">
                            <div class="img">
                                <img src="icon/icon_1.png" alt="">
                            </div>
                        </div>
                        <div class="service-body">
                            <h3>khám phụ khoa tổng quát</h3>
                            <p>Chúng tôi cung cấp dịch vụ khám phụ khoa định kỳ và điều trị các bệnh lý phụ khoa.</p>
                        </div>
                        <div class="read-more-btn">
                            <a href="{{ route('frontend.services.show', 'kham-phu-khoa') }}">xem thêm</a>
                        </div>
                    </div>
                    <!-- Service Item End -->
                </div>

                <div class="col-lg-3 col-md-6">
                    <!-- Service Item Start -->
                    <div class="service-item wow fadeInUp" data-wow-delay="0.25s">
                        <div class="icon-box">
                            <div class="img">
                                <img src="icon/icon_2.png" alt="">
                            </div>
                        </div>
                        <div class="service-body">
                            <h3>theo dõi thai kỳ</h3>
                            <p>Dịch vụ theo dõi thai kỳ toàn diện từ giai đoạn đầu đến khi sinh với trang thiết bị hiện đại.
                            </p>
                        </div>
                        <div class="read-more-btn">
                            <a href="{{ route('frontend.services.show', 'kham-thai') }}">xem thêm</a>
                        </div>
                    </div>
                    <!-- Service Item End -->
                </div>

                <div class="col-lg-3 col-md-6">
                    <!-- Service Item Start -->
                    <div class="service-item wow fadeInUp" data-wow-delay="0.5s">
                        <div class="icon-box">
                            <div class="img">
                                <img src="icon/icon_3.png" alt="">
                            </div>
                        </div>
                        <div class="service-body">
                            <h3>thủ thuật chuyên khoa</h3>
                            <p>Các thủ thuật chuyên khoa sản phụ khoa như điều trị tổn thương cổ tử cung, áp xe, và các can
                                thiệp khác.</p>
                        </div>
                        <div class="read-more-btn">
                            <a href="{{ route('frontend.services.type', 'procedure') }}">xem thêm</a>
                        </div>
                    </div>
                    <!-- Service Item End -->
                </div>

                <div class="col-lg-3 col-md-6">
                    <!-- Service Item Start -->
                    <div class="service-item wow fadeInUp" data-wow-delay="0.75s">
                        <div class="icon-box">
                            <div class="img">
                                <img src="icon/icon_4.png" alt="">
                            </div>
                        </div>
                        <div class="service-body">
                            <h3>xét nghiệm chẩn đoán</h3>
                            <p>Xét nghiệm và chẩn đoán hình ảnh toàn diện với máy móc hiện đại, kết quả nhanh chóng và chính
                                xác.</p>
                        </div>
                        <div class="read-more-btn">
                            <a href="{{ route('frontend.services.type', 'laboratory') }}">xem thêm</a>
                        </div>
                    </div>
                    <!-- Service Item End -->
                </div>

                <div class="col-lg-12">
                    <!-- Service Box Footer Start -->
                    <div class="services-box-footer wow fadeInUp" data-wow-delay="1s">
                        <p>Chúng tôi tin tưởng vào việc sử dụng công nghệ và kỹ thuật tiên tiến nhất để đảm bảo kết quả tốt
                            nhất cho bệnh nhân.</p>
                        <a href="{{ route('frontend.services') }}" class="btn-default">xem tất cả dịch vụ</a>
                    </div>
                    <!-- Service Box Footer End -->
                </div>
            </div>
        </div>
    </div>
    <!-- Our Serviceds Section End -->

    <!-- Why Choose Us Section Start -->
    <div class="why-choose-us">
        <div class="container">
            <div class="row section-row">
                <div class="col-lg-12">
                    <!-- Section Title Start -->
                    <div class="section-title">
                        <h3 class="wow fadeInUp">tại sao chọn chúng tôi</h3>
                        <h2 class="text-anime-style-2" data-cursor="-opaque"><span>Phòng khám Thu Hiền</span> - Địa chỉ
                            tin cậy cho phụ nữ</h2>
                        <p class="wow fadeInUp" data-wow-delay="0.25s">Phòng khám phụ sản Thu Hiền cam kết mang đến dịch
                            vụ chăm sóc sức khỏe
                            chuyên khoa sản phụ khoa chất lượng cao, an toàn và tận tâm nhất.</p>
                    </div>
                    <!-- Section Title End -->
                </div>
            </div>

            <div class="row">
                <div class="col-lg-4 col-md-6 order-1">
                    <!-- Why Choose Box Start -->
                    <div class="why-choose-box-1">
                        <!-- Why Choose Item Start -->
                        <div class="why-choose-item wow fadeInUp">
                            <!-- Icon Box Start -->
                            <div class="icon-box">
                                <img src="images/icon-why-us-1.svg" alt="">
                            </div>
                            <!-- Icon Box End -->

                            <!-- Why Choose Content Start -->
                            <div class="why-choose-content">
                                <h3>đội ngũ bác sĩ chuyên khoa tại Thu Hiền</h3>
                                <p>Bác sĩ chuyên khoa sản phụ khoa với nhiều năm kinh nghiệm, được đào tạo bài bản và cập
                                    nhật kiến thức thường xuyên.</p>
                            </div>
                            <!-- Why Choose Content End -->
                        </div>
                        <!-- Why Choose Item End -->

                        <!-- Why Choose Item Start -->
                        <div class="why-choose-item wow fadeInUp" data-wow-delay="0.25s">
                            <!-- Icon Box Start -->
                            <div class="icon-box">
                                <img src="icon/icon_1.png" alt="">
                            </div>
                            <!-- Icon Box End -->

                            <!-- Why Choose Content Start -->
                            <div class="why-choose-content">
                                <h3>phương pháp điều trị cá nhân hóa</h3>
                                <p>Phòng khám phụ sản Thu Hiền áp dụng phương án điều trị riêng biệt cho từng bệnh nhân, phù
                                    hợp với tình trạng
                                    sức khỏe và nhu cầu cá nhân.</p>
                            </div>
                            <!-- Why Choose Content End -->
                        </div>
                        <!-- Why Choose Item End -->

                        <!-- Why Choose Item Start -->
                        <div class="why-choose-item wow fadeInUp" data-wow-delay="0.5s">
                            <!-- Icon Box Start -->
                            <div class="icon-box">
                                <img src="images/icon-why-us-3.svg" alt="">
                            </div>
                            <!-- Icon Box End -->

                            <!-- Why Choose Content Start -->
                            <div class="why-choose-content">
                                <h3>dịch vụ thanh toán tiện lợi</h3>
                                <p>Phòng khám phụ sản Thu Hiền hỗ trợ đa dạng hình thức thanh toán bao gồm tiền mặt, chuyển
                                    khoản, QR code.</p>
                            </div>
                            <!-- Why Choose Content End -->
                        </div>
                        <!-- Why Choose Item End -->
                    </div>
                    <!-- Why Choose Box Start -->
                </div>

                <div class="col-lg-4 order-lg-1 order-md-2 order-1">
                    <!-- Why Choose Image Start -->
                    <div class="why-choose-image wow fadeInUp">
                        <figure>
                            <img src="images/123.png" alt="">
                        </figure>
                    </div>
                    <!-- Why Choose Image End -->
                </div>

                <div class="col-lg-4 col-md-6 order-lg-2 order-md-1 order-2">
                    <!-- Why Choose Box Start -->
                    <div class="why-choose-box-2">
                        <!-- Why Choose Item Start -->
                        <div class="why-choose-item wow fadeInUp">
                            <!-- Icon Box Start -->
                            <div class="icon-box">
                                <img src="images/icon-why-us-4.svg" alt="">
                            </div>
                            <!-- Icon Box End -->

                            <!-- Why Choose Content Start -->
                            <div class="why-choose-content">
                                <h3>thời gian làm việc linh hoạt</h3>
                                <p>Phòng khám phụ sản Thu Hiền phục vụ từ 7:00 - 19:00 hàng ngày, sẵn sàng hỗ trợ khám và tư
                                    vấn khi bạn cần.
                                </p>
                            </div>
                            <!-- Why Choose Content End -->
                        </div>
                        <!-- Why Choose Item End -->

                        <!-- Why Choose Item Start -->
                        <div class="why-choose-item wow fadeInUp" data-wow-delay="0.25s">
                            <!-- Icon Box Start -->
                            <div class="icon-box">
                                <img src="images/icon-why-us-5.svg" alt="">
                            </div>
                            <!-- Icon Box End -->

                            <!-- Why Choose Content Start -->
                            <div class="why-choose-content">
                                <h3>niềm tin từ hàng nghìn bệnh nhân</h3>
                                <p>Phòng khám phụ sản Thu Hiền vinh dự nhận được sự tin tưởng và phản hồi tích cực từ nhiều
                                    bệnh nhân đã được
                                    chăm sóc.</p>
                            </div>
                            <!-- Why Choose Content End -->
                        </div>
                        <!-- Why Choose Item End -->

                        <!-- Why Choose Item Start -->
                        <div class="why-choose-item wow fadeInUp" data-wow-delay="0.5s">
                            <!-- Icon Box Start -->
                            <div class="icon-box">
                                <img src="images/icon-why-us-6.svg" alt="">
                            </div>
                            <!-- Icon Box End -->

                            <!-- Why Choose Content Start -->
                            <div class="why-choose-content">
                                <h3>trang thiết bị y tế hiện đại</h3>
                                <p>Phòng khám phụ sản Thu Hiền đầu tư trang bị máy móc y tế tiên tiến để đảm bảo chẩn đoán
                                    chính xác và điều
                                    trị hiệu quả nhất.</p>
                            </div>
                            <!-- Why Choose Content End -->
                        </div>
                        <!-- Why Choose Item End -->
                    </div>
                    <!-- Why Choose Box Start -->
                </div>
            </div>
        </div>

        <!-- Icon Start Image End -->
    </div>
    <!-- Why Choose Us Section End -->

    <!-- How It Work Start -->
    <div class="how-it-work">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <!-- How It Work Image Start -->
                    <div class="how-it-work-img">
                        <figure class="reveal image-anime">
                            <img src="images/team.jpg" alt="">
                        </figure>
                    </div>
                    <!-- How It Work Image End -->
                </div>

                <div class="col-lg-6">
                    <div class="how-it-work-content">
                        <!-- Section Title Start -->
                        <div class="section-title">
                            <h3 class="wow fadeInUp">quy trình làm việc</h3>
                            <h2 class="text-anime-style-2" data-cursor="-opaque"><span>Quy trình khám</span> chuyên nghiệp
                                và tận tâm</h2>
                            <p class="wow fadeInUp" data-wow-delay="0.25s">Từ đặt lịch hẹn, thăm khám sơ bộ, thực hiện
                                dịch vụ đến theo dõi sau điều trị. Mỗi bước được thực hiện theo quy trình chuẩn y khoa với
                                sự chăm sóc tận tình nhất.</p>
                        </div>
                        <!-- Section Title End -->

                        <!-- How Work Accordion Start -->

                        <!-- FAQ Accordion Start -->
                        <div class="faq-accordion how-work-accordion" id="accordion">
                            <!-- FAQ Item Start -->
                            <div class="accordion-item wow fadeInUp">
                                <div class="icon-box">
                                    <img src="images/icon-how-it-work-1.svg" alt="">
                                </div>
                                <h2 class="accordion-header" id="heading1">
                                    <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#collapse1" aria-expanded="true" aria-controls="collapse1">
                                        đặt lịch khám
                                    </button>
                                </h2>
                                <div id="collapse1" class="accordion-collapse collapse show" aria-labelledby="heading1"
                                    data-bs-parent="#accordion">
                                    <div class="accordion-body">
                                        <p>Mục tiêu của phòng khám là cung cấp dịch vụ chăm sóc sức khỏe thân thiện và mức
                                            độ cao nhất trong các dịch vụ sản khoa, phụ khoa tổng quát và chuyên khoa.</p>
                                    </div>
                                </div>
                            </div>
                            <!-- FAQ Item End -->

                            <!-- FAQ Item Start -->
                            <div class="accordion-item wow fadeInUp" data-wow-delay="0.25s">
                                <div class="icon-box">
                                    <img src="images/icon-how-it-work-2.svg" alt="">
                                </div>
                                <h2 class="accordion-header" id="heading2">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#collapse2" aria-expanded="false" aria-controls="collapse2">
                                        khám và chẩn đoán
                                    </button>
                                </h2>
                                <div id="collapse2" class="accordion-collapse collapse" aria-labelledby="heading2"
                                    data-bs-parent="#accordion">
                                    <div class="accordion-body">
                                        <p>Bác sĩ sẽ thực hiện khám lâm sàng và các xét nghiệm cần thiết để đưa ra chẩn đoán
                                            chính xác nhất.</p>
                                    </div>
                                </div>
                            </div>
                            <!-- FAQ Item End -->

                            <!-- FAQ Item Start -->
                            <div class="accordion-item wow fadeInUp" data-wow-delay="0.5s">
                                <div class="icon-box">
                                    <img src="images/icon-how-it-work-3.svg" alt="">
                                </div>
                                <h2 class="accordion-header" id="heading3">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#collapse3" aria-expanded="false" aria-controls="collapse3">
                                        điều trị chuyên nghiệp
                                    </button>
                                </h2>
                                <div id="collapse3" class="accordion-collapse collapse" aria-labelledby="heading3"
                                    data-bs-parent="#accordion">
                                    <div class="accordion-body">
                                        <p>Thực hiện phương án điều trị phù hợp với tình trạng bệnh và theo dõi sát sao quá
                                            trình hồi phục.</p>
                                    </div>
                                </div>
                            </div>
                            <!-- FAQ Item End -->
                        </div>
                        <!-- FAQ Accordion End -->
                        <!-- How Work Accordion End -->
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- How It Work End -->

    <!-- Our Team Start -->
    <div class="our-team">
        <div class="container">
            <div class="row section-row">
                <div class="col-lg-12">
                    <!-- Section Title Start -->
                    <div class="section-title">
                        <h3 class="wow fadeInUp">đội ngũ của chúng tôi</h3>
                        <h2 class="text-anime-style-2" data-cursor="-opaque"><span>Đội ngũ bác sĩ</span> chuyên khoa uy
                            tín</h2>
                        <p class="wow fadeInUp" data-wow-delay="0.25s">Đội ngũ bác sĩ giàu kinh nghiệm và tận tâm trong
                            lĩnh vực sản phụ khoa.</p>
                    </div>
                    <!-- Section Title End -->
                </div>
            </div>

            <div class="row">
                <div class="col-lg-3 col-md-6">
                    <!-- Team Member Item Start -->
                    <div class="team-member-item wow fadeInUp">
                        <!-- Team Image Start -->
                        <div class="team-image">
                            <figure class="image-anime">
                                <img src="images/bs-3.png" alt="">
                            </figure>

                            <!-- Team Social Icon Start -->

                            <!-- Team Social Icon End -->
                        </div>
                        <!-- Team Image End -->

                        <!-- Team Content Start -->
                        <div class="team-content">
                            <h3>BS. Nguyễn Thị Thu Hiền</h3>
                        </div>
                        <!-- Team Content End -->
                    </div>
                    <!-- Team Member Item End -->
                </div>
                <div class="col-lg-3 col-md-6">
                    <!-- Team Member Item Start -->
                    <div class="team-member-item wow fadeInUp" data-wow-delay="0.5s">
                        <!-- Team Image Start -->
                        <div class="team-image">
                            <figure class="image-anime">
                                <img src="images/bs_2.png" alt="">
                            </figure>

                            <!-- Team Social Icon Start -->

                            <!-- Team Social Icon End -->
                        </div>
                        <!-- Team Image End -->

                        <!-- Team Content Start -->
                        <div class="team-content">
                            <h3>BS. Nguyễn Thị Rỳ</h3>
                        </div>
                        <!-- Team Content End -->
                    </div>
                    <!-- Team Member Item End -->
                </div>
                <div class="col-lg-3 col-md-6">
                    <!-- Team Member Item Start -->
                    <div class="team-member-item wow fadeInUp" data-wow-delay="0.5s">
                        <!-- Team Image Start -->
                        <div class="team-image">
                            <figure class="image-anime">
                                <img src="images/bs_4.png" alt="">
                            </figure>

                            <!-- Team Social Icon Start -->

                            <!-- Team Social Icon End -->
                        </div>
                        <!-- Team Image End -->

                        <!-- Team Content Start -->
                        <div class="team-content">
                            <h3>Th.S Cao Thị Thu Cúc</h3>
                        </div>
                        <!-- Team Content End -->
                    </div>
                    <!-- Team Member Item End -->
                </div>
                <div class="col-lg-3 col-md-6">
                    <!-- Team Member Item Start -->
                    <div class="team-member-item wow fadeInUp" data-wow-delay="0.5s">
                        <!-- Team Image Start -->
                        <div class="team-image">
                            <figure class="image-anime">
                                <img src="images/bs_5.png" alt="">
                            </figure>

                            <!-- Team Social Icon Start -->

                            <!-- Team Social Icon End -->
                        </div>
                        <!-- Team Image End -->

                        <!-- Team Content Start -->
                        <div class="team-content">
                            <h3>DS. Lê Thị Trà Hương</h3>
                        </div>
                        <!-- Team Content End -->
                    </div>
                    <!-- Team Member Item End -->
                </div>
            </div>
        </div>
        <!-- Icon Start Image Start -->

        <!-- Icon Start Image End -->
    </div>
    <!-- Our Team End -->

    <!-- Our Testiminial Start -->
    {{-- <div class="our-testimonials">
        <div class="container">
            <div class="row section-row">
                <div class="col-lg-12">
                    <!-- Section Title Start -->
                    <div class="section-title">
                        <h3 class="wow fadeInUp">phản hồi từ bệnh nhân</h3>
                        <h2 class="text-anime-style-2" data-cursor="-opaque"><span>Bệnh nhân</span> nói gì về chúng tôi</h2>
                        <p class="wow fadeInUp" data-wow-delay="0.25s">Những chia sẻ chân thực từ các bệnh nhân đã tin tưởng chọn chúng tôi.</p>
                    </div>
                    <!-- Section Title End -->
                </div>
            </div>

            <div class="row align-items-center">
                <div class="col-lg-5">
                    <!-- Testiminial Image Start -->
                    <div class="testimonial-image">
                        <div class="testimonial-img">
                            <figure class="reveal image-anime">
                                <img src="images/testimonials-img.jpg" alt="">
                            </figure>
                        </div>

                        <!-- Terstimonial Rating Box Start -->
                        <div class="testimonial-rating-box">
                            <!-- Counter Item Start -->
                            <div class="rating-counter-item">
                                <div class="rating-counter-number">
                                    <h3><span class="counter">4.8</span>/5</h3>
                                </div>

                                <div class="rating-counter-content">
                                    <p>Đây là đánh giá từ các bệnh nhân sau khi khám tại phòng khám</p>
                                </div>
                            </div>
                            <!-- Counter Item End -->

                            <!-- Service Rating Start -->
                            <div class="service-rating">
                                <ul>
                                    <li>
                                        <i class="fa-solid fa-star"></i>
                                        <i class="fa-solid fa-star"></i>
                                        <i class="fa-solid fa-star"></i>
                                        <i class="fa-solid fa-star"></i>
                                        <i class="fa-solid fa-star"></i>
                                    </li>
                                    <li>cho dịch vụ xuất sắc</li>
                                </ul>
                            </div>
                            <!-- Service Rating End -->
                        </div>
                        <!-- Terstimonial Rating Box End -->
                    </div>
                    <!-- Testiminial Image End -->
                </div>

                <div class="col-lg-7">
                    <!-- Testimonial Slider Start -->
                    <div class="testimonial-slider">
                        <div class="swiper">
                            <div class="swiper-wrapper" data-cursor-text="Drag">
                                <!-- Testimonial Slide Start -->
                                <div class="swiper-slide">
                                    <div class="testimonial-item">
                                        <div class="testimonial-header">
                                            <div class="testimonial-quote-image">
                                                <img src="images/icon-testimonial-quote.svg" alt="">
                                            </div>
                                            <div class="testimonial-content">
                                                <p>"Tôi muốn cảm ơn bác sĩ Minh! Bác sĩ rất tận tâm và chu đáo trong quá trình theo dõi thai kỳ của tôi. Cảm ơn bác sĩ đã giúp tôi vượt qua những lo lắng trong thời gian mang thai."</p>
                                            </div>
                                        </div>
                                        <div class="testimonial-body">
                                            <div class="author-image">
                                                <figure class="image-anime">
                                                    <img src="images/author-1.jpg" alt="">
                                                </figure>
                                            </div>
                                            <div class="author-content">
                                                <h3>chị Nguyễn Lan</h3>
                                                <p>nhân viên văn phòng</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- Testimonial Slide End -->

                                <!-- Testimonial Slide Start -->
                                <div class="swiper-slide">
                                    <div class="testimonial-item">
                                        <div class="testimonial-header">
                                            <div class="testimonial-quote-image">
                                                <img src="images/icon-testimonial-quote.svg" alt="">
                                            </div>
                                            <div class="testimonial-content">
                                                <p>"Trải nghiệm khám bệnh tuyệt vời nhất mà tôi từng có! Đội ngũ y tế chuyên nghiệp và thân thiện, kết quả điều trị rất tốt. Tôi rất khuyến khích mọi người đến đây! Bác sĩ và nhân viên đều tuyệt vời!"</p>
                                            </div>
                                        </div>
                                        <div class="testimonial-body">
                                            <div class="author-image">
                                                <figure class="image-anime">
                                                    <img src="images/author-2.jpg" alt="">
                                                </figure>
                                            </div>
                                            <div class="author-content">
                                                <h3>chị Trần Hoa</h3>
                                                <p>giáo viên</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- Testimonial Slide End -->

                                <!-- Testimonial Slide Start -->
                                <div class="swiper-slide">
                                    <div class="testimonial-item">
                                        <div class="testimonial-header">
                                            <div class="testimonial-quote-image">
                                                <img src="images/icon-testimonial-quote.svg" alt="">
                                            </div>
                                            <div class="testimonial-content">
                                                <p>"Dịch vụ và chăm sóc tuyệt vời. Nhân viên am hiểu chuyên môn và luôn sẵn sàng giải đáp thắc mắc. Tôi sẽ không đi đâu khác cho các nhu cầu chăm sóc sức khỏe phụ khoa. Ngay từ lúc bước vào, tôi cảm thấy được chào đón và chăm sóc tận tình."</p>
                                            </div>
                                        </div>
                                        <div class="testimonial-body">
                                            <div class="author-image">
                                                <figure class="image-anime">
                                                    <img src="images/author-3.jpg" alt="">
                                                </figure>
                                            </div>
                                            <div class="author-content">
                                                <h3>chị Lê Linh</h3>
                                                <p>thiết kế đồ họa</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- Testimonial Slide End -->
                            </div>
                            <div class="testimonial-btn">
                                <div class="testimonial-button-prev"></div>
                                <div class="testimonial-button-next"></div>
                            </div>
                        </div>
                    </div>
                    <!-- Testimonial Slider End -->
                </div>
            </div>
        </div>
    </div> --}}
    <!-- Our Testiminial End -->

    <!-- Our Blog Start -->
    <div class="our-blog">
        <div class="container">
            <div class="row section-row">
                <div class="col-lg-12">
                    <!-- Section Title Start -->
                    <div class="section-title">
                        <a href="{{ route('frontend.posts') }}">
                            <h3 class="wow fadeInUp">tin tức</h3>
                        </a>
                        <h2 class="text-anime-style-2" data-cursor="-opaque"><span>Các bài viết</span> mới nhất</h2>
                        <p class="wow fadeInUp" data-wow-delay="0.25s">Cập nhật những thông tin y khoa và sức khỏe mới
                            nhất.</p>
                    </div>
                    <!-- Section Title End -->
                </div>
            </div>

            @if ($featuredPosts && $featuredPosts->count() > 0)
                <div class="row">
                    @foreach ($featuredPosts->take(3) as $index => $post)
                        <div class="col-lg-4 col-md-6">
                            <!-- Blog Item Start -->
                            <div class="blog-item wow fadeInUp" data-wow-delay="{{ $index * 0.25 }}s">
                                <!-- Post Featured Image Start-->
                                <div class="post-featured-image" data-cursor-text="View">
                                    <figure>
                                        <a href="{{ route('frontend.posts.show', $post->slug) }}" class="image-anime">
                                            @if ($post->featured_image && file_exists(public_path('storage/' . $post->featured_image)))
                                                <img src="{{ $post->featured_image_url }}" alt="{{ $post->title }}">
                                            @else
                                                <img src="{{ asset('frontend/images/favicon_1.png') }}"
                                                    alt="Default Image">
                                            @endif
                                        </a>
                                    </figure>
                                </div>
                                <!-- Post Featured Image End -->

                                <!-- post Item Body Start -->
                                <div class="post-item-body">
                                    <h2><a href="{{ route('frontend.posts.show', $post->slug) }}">{{ $post->title }}</a>
                                    </h2>
                                    <p>{{ $post->excerpt }}</p>
                                </div>
                                <!-- Post Item Body End-->

                                <!-- Post Item Footer Start-->
                                <div class="post-item-footer">
                                    <a href="{{ route('frontend.posts.show', $post->slug) }}" class="read-more-btn">đọc
                                        thêm</a>
                                </div>
                                <!-- Post Item Footer End-->
                            </div>
                            <!-- Blog Item End -->
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
        <!-- Icon Start Image Start -->

        <!-- Icon Start Image End -->
    </div>
    <!-- Our Blog End -->

    <!-- Footer Contact Us Start -->
    <div class="contact-now">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <!-- Google Map Start -->
                    <div class="contact-google-map">
                        <!-- Google Map Iframe Start -->

                        <div class="google-map-iframe">
                            <iframe
                                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d560.7132917885511!2d106.67725972899653!3d10.762753230234482!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x31752f1dee235cc9%3A0x844785fe99581167!2zNjUgxJAuIEjDuW5nIFbGsMahbmcsIFBoxrDhu51uZyA0LCBRdeG6rW4gNSwgSOG7kyBDaMOtIE1pbmg!5e1!3m2!1sen!2s!4v1748595644802!5m2!1sen!2s"
                                allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                        </div>
                        <!-- Google Map Iframe End -->
                    </div>
                    <!-- Google Map End -->
                </div>

                <div class="col-lg-6">

                    <!-- Footer Contact Content Start -->
                    <div class="contact-now-content">
                        <!-- Section Title Start -->
                        <div class="section-title">
                            <h3 class="wow fadeInUp">liên hệ ngay</h3>
                            <h2 class="text-anime-style-2" data-cursor="-opaque"><span>Nhận tư vấn</span> chuyên nghiệp
                                miễn phí</h2>
                        </div>
                        <!-- Section Title End -->

                        <!-- Contact Info Start -->
                        <div class="contact-now-info">
                            <!-- Contact Info Item Start -->
                            <div class="contact-info-list wow fadeInUp" data-wow-delay="0.2s">
                                <!-- Icon Box Start -->
                                <div class="icon-box">
                                    <img src="images/icon-location.svg" alt="">
                                </div>
                                <!-- Icon Box End -->

                                <!-- Contact Info Content Start -->
                                <div class="contact-info-content">
                                    <p>65 Hùng vương, Phường 4, Quận 5, TP. Hồ Chí Minh</p>
                                </div>
                                <!-- Contact Info Content End -->
                            </div>
                            <!-- Contact Info Item End -->

                            <!-- Contact Info Item Start -->
                            <div class="contact-info-list wow fadeInUp" data-wow-delay="0.4s">
                                <!-- Icon Box Start -->
                                <div class="icon-box">
                                    <img src="images/icon-phone.svg" alt="">
                                </div>
                                <!-- Icon Box End -->

                                <!-- Contact Info Content Start -->
                                <div class="contact-info-content">
                                    <p>0384518881</p>
                                    <p>028 3837 8888</p>
                                </div>
                                <!-- Contact Info Content End -->
                            </div>
                            <!-- Contact Info Item End -->

                            <!-- Contact Info Item Start -->
                            <div class="contact-info-list wow fadeInUp" data-wow-delay="0.6s">
                                <!-- Icon Box Start -->
                                <div class="icon-box">
                                    <img src="images/icon-mail.svg" alt="">
                                </div>
                                <!-- Icon Box End -->

                                <!-- Contact Info Content Start -->
                                <div class="contact-info-content">
                                    <p>pksg@gmail.com</p>
                                </div>
                                <!-- Contact Info Content End -->
                            </div>
                            <!-- Contact Info Item End -->

                            <!-- Contact Info Item Start -->
                            <div class="contact-info-list wow fadeInUp" data-wow-delay="0.8s">
                                <!-- Icon Box Start -->
                                <div class="icon-box">
                                    <img src="images/icon-clock.svg" alt="">
                                </div>
                                <!-- Icon Box End -->

                                <!-- Contact Info Content Start -->
                                <div class="contact-info-content">
                                    <p>Thứ 2 - Chủ nhật: 7:00 - 19:00</p>
                                </div>
                                <!-- Contact Info Content End -->
                            </div>
                            <!-- Contact Info Item End -->
                        </div>

                        <!-- Footer Appointment Button Start  -->
                        <div class="contact-appointment-btn wow fadeInUp" data-wow-delay="1s">
                            <a href="{{ route('frontend.appointment') }}" class="btn-default">đặt lịch khám</a>
                        </div>
                        <!-- Footer Appointment Button End  -->
                    </div>
                    <!-- Footer Contact Content End -->
                </div>
            </div>
        </div>
    </div>
    <!-- Footer Contact Us End -->
@endsection
