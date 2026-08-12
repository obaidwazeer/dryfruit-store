<footer class="storefront-footer">

    {{-- =========================================================
        Newsletter / Promotional Section
    ========================================================== --}}
    <section class="footer-newsletter">

        <div class="container">

            <div class="row align-items-center g-4">

                <div class="col-lg-6">

                    <span class="footer-eyebrow">
                        Stay Updated
                    </span>

                    <h3 class="footer-newsletter-title mb-2">
                        Get fresh deals & offers
                    </h3>

                    <p class="mb-0">
                        Subscribe to receive our latest products,
                        special offers and dry fruit deals.
                    </p>

                </div>

                <div class="col-lg-6">

                    <form method="POST" action="#" class="footer-newsletter-form">

                        @csrf

                        <div class="input-group">

                            <input type="email" name="email" class="form-control"
                                placeholder="Enter your email address" aria-label="Email address" required>

                            <button type="submit" class="btn">
                                Subscribe
                            </button>

                        </div>

                    </form>

                </div>

            </div>

        </div>

    </section>


    {{-- =========================================================
        Main Footer
    ========================================================== --}}
    <section class="footer-main">

        <div class="container">

            <div class="row g-4">


                {{-- =================================================
                    Store Information
                ================================================== --}}
                <div class="col-lg-4 col-md-6">

                    <h5 class="footer-title">
                        Dry Fruit Store
                    </h5>

                    <p class="footer-description">
                        Premium quality dry fruits carefully selected
                        for freshness, taste and quality. Shop your
                        favourite dry fruits and have them delivered
                        to your doorstep.
                    </p>


                    {{-- Social Links --}}
                    <div class="footer-socials">

                        <a href="#" aria-label="Facebook" title="Facebook">
                            <i class="bi bi-facebook"></i>
                        </a>

                        <a href="#" aria-label="Instagram" title="Instagram">
                            <i class="bi bi-instagram"></i>
                        </a>

                        <a href="#" aria-label="WhatsApp" title="WhatsApp">
                            <i class="bi bi-whatsapp"></i>
                        </a>

                    </div>

                </div>


                {{-- =================================================
                    Shop Links
                ================================================== --}}
                <div class="col-lg-2 col-md-6">

                    <h5 class="footer-title">
                        Shop
                    </h5>

                    <ul class="footer-links">

                        <li>
                            <a href="#">
                                All Products
                            </a>
                        </li>

                        <li>
                            <a href="#">
                                Almonds
                            </a>
                        </li>

                        <li>
                            <a href="#">
                                Cashews
                            </a>
                        </li>

                        <li>
                            <a href="#">
                                Pistachios
                            </a>
                        </li>

                        <li>
                            <a href="#">
                                Walnuts
                            </a>
                        </li>

                        <li>
                            <a href="#">
                                Dates
                            </a>
                        </li>

                    </ul>

                </div>


                {{-- =================================================
                    Customer Service
                ================================================== --}}
                <div class="col-lg-3 col-md-6">

                    <h5 class="footer-title">
                        Customer Service
                    </h5>

                    <ul class="footer-links">

                        <li>
                            <a href="#">
                                Contact Us
                            </a>
                        </li>

                        <li>
                            <a href="#">
                                Track Your Order
                            </a>
                        </li>

                        <li>
                            <a href="#">
                                Shipping Information
                            </a>
                        </li>

                        <li>
                            <a href="#">
                                Return & Refund Policy
                            </a>
                        </li>

                        <li>
                            <a href="#">
                                Frequently Asked Questions
                            </a>
                        </li>

                    </ul>

                </div>


                {{-- =================================================
                    Contact Information
                ================================================== --}}
                <div class="col-lg-3 col-md-6">

                    <h5 class="footer-title">
                        Contact Us
                    </h5>

                    <ul class="footer-contact">

                        <li>

                            <span class="footer-contact-icon">
                                <i class="bi bi-geo-alt"></i>
                            </span>

                            <span>
                                Rawalpindi, Punjab, Pakistan
                            </span>

                        </li>

                        <li>

                            <span class="footer-contact-icon">
                                <i class="bi bi-telephone"></i>
                            </span>

                            <a href="tel:+923000000000">
                                +92 300 0000000
                            </a>

                        </li>

                        <li>

                            <span class="footer-contact-icon">
                                <i class="bi bi-envelope"></i>
                            </span>

                            <a href="mailto:info@example.com">
                                info@example.com
                            </a>

                        </li>

                    </ul>

                </div>

            </div>

        </div>

    </section>


    {{-- =========================================================
        Bottom Footer
    ========================================================== --}}
    <section class="footer-bottom">

        <div class="container">

            <div class="row align-items-center g-3">

                <div class="col-md-6">

                    <p class="mb-0">
                        © {{ date('Y') }} Dry Fruit Store.
                        All rights reserved.
                    </p>

                </div>

                <div class="col-md-6">

                    <div class="footer-bottom-links">

                        <a href="#">
                            Privacy Policy
                        </a>

                        <a href="#">
                            Terms & Conditions
                        </a>

                    </div>

                </div>

            </div>

        </div>

    </section>

</footer>
