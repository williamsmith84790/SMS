</main>

<style>
    /* Footer Styles */
    .footer-area { background-color: #1a1a1a; color: #b0b0b0; padding: 70px 0 30px; font-size: 0.95rem; border-top: 5px solid var(--secondary-color); }
    .footer-area h3 { color: #fff; font-size: 1.3rem; margin-bottom: 30px; position: relative; padding-bottom: 15px; font-family: 'Merriweather', serif; letter-spacing: 0.5px; }
    .footer-area h3::after { content: ''; position: absolute; left: 0; bottom: 0; width: 40px; height: 3px; background: var(--secondary-color); }
    .footer-area p { line-height: 1.7; margin-bottom: 20px; }
    .footer-area a { color: #b0b0b0; text-decoration: none; transition: 0.3s; font-weight: 500; }
    .footer-area a:hover { color: var(--secondary-color); padding-left: 5px; }

    .footer-links ul { padding: 0; list-style: none; }
    .footer-links li { margin-bottom: 12px; border-bottom: 1px solid rgba(255,255,255,0.05); padding-bottom: 8px; }
    .footer-links li:last-child { border-bottom: none; }

    .footer-contact li { display: flex; margin-bottom: 18px; align-items: flex-start; }
    .footer-contact i { color: var(--secondary-color); margin-right: 15px; font-size: 1.2rem; margin-top: 5px; }

    .footer-bottom { background-color: #111; padding: 25px 0; margin-top: 50px; border-top: 1px solid rgba(255,255,255,0.1); }
    .copyright-text { margin: 0; font-size: 0.85rem; color: #888; }
    .copyright-text strong { color: #ddd; }

    .footer-social a {
        color: #fff; margin-right: 15px; font-size: 1.1rem; transition: 0.3s;
        background: rgba(255,255,255,0.1); width: 40px; height: 40px; display: inline-flex;
        align-items: center; justify-content: center; border-radius: 50%;
    }
    .footer-social a:hover { background: var(--secondary-color); color: var(--primary-color); transform: translateY(-3px); }

    /* Newsletter Form */
    .newsletter-form .input-group { background: rgba(255,255,255,0.05); padding: 5px; border-radius: 4px; }
    .newsletter-form .form-control { background: transparent; border: none; color: #fff; padding: 10px 15px; }
    .newsletter-form .form-control:focus { box-shadow: none; }
    .newsletter-form .btn { border-radius: 4px; background: var(--secondary-color); color: var(--primary-color); font-weight: bold; padding: 0 20px; border: none; }
    .newsletter-form .btn:hover { background: #fff; }
</style>

<footer class="footer-area">
    <div class="container">
        <div class="row">
            <!-- Col 1: About -->
            <div class="col-lg-3 col-md-6 mb-5">
                <h3>About Us</h3>
                <p><?php echo isset($settings['footer_about_text']) ? htmlspecialchars($settings['footer_about_text']) : 'We are dedicated to providing quality education.'; ?></p>
            </div>

            <!-- Col 2: Useful Links (Dynamic) -->
            <div class="col-lg-3 col-md-6 mb-5 footer-links">
                <h3>Useful Links</h3>
                <ul>
                    <?php
                    // Fetch Footer Menu Items
                    $footer_menu_sql = "SELECT * FROM menu_items WHERE location = 'footer' ORDER BY sort_order ASC";
                    $footer_menu_result = $conn->query($footer_menu_sql);
                    if ($footer_menu_result && $footer_menu_result->num_rows > 0) {
                        while($item = $footer_menu_result->fetch_assoc()) {
                            echo '<li><a href="' . htmlspecialchars($item['link']) . '"><i class="fas fa-angle-right text-muted me-2 small"></i>' . htmlspecialchars($item['label']) . '</a></li>';
                        }
                    } else {
                        echo '<li><a href="index.php">Home</a></li>';
                    }
                    ?>
                </ul>
            </div>

            <!-- Col 3: Contact Info -->
            <div class="col-lg-3 col-md-6 mb-5 footer-contact">
                <h3>Contact Us</h3>
                <ul class="list-unstyled">
                    <li>
                        <i class="fas fa-map-marker-alt"></i>
                        <span><?php echo nl2br(htmlspecialchars($settings['contact_address'] ?? '123 Education Street, Knowledge City')); ?></span>
                    </li>
                    <li>
                        <i class="fas fa-phone-alt"></i>
                        <span><?php echo htmlspecialchars($settings['contact_phone'] ?? '(123) 456-7890'); ?></span>
                    </li>
                    <li>
                        <i class="fas fa-envelope"></i>
                        <span><?php echo htmlspecialchars($settings['contact_email'] ?? 'info@eduportal.com'); ?></span>
                    </li>
                </ul>
            </div>

            <!-- Col 4: Newsletter -->
            <div class="col-lg-3 col-md-6 mb-5">
                <h3>Newsletter</h3>
                <p>Subscribe to our newsletter to get the latest updates and news delivered to your inbox.</p>
                <form class="newsletter-form mt-4" action="#" method="POST">
                    <div class="input-group">
                        <input type="email" class="form-control" placeholder="Your Email Address">
                        <button class="btn" type="submit">GO</button>
                    </div>
                </form>
                <div class="mt-4 pt-2">
                    <h3 style="font-size: 1.1rem; border: none; margin-bottom: 15px;">Follow Us</h3>
                    <div class="footer-social">
                        <?php if(isset($settings['social_facebook']) && $settings['social_facebook'] != '#'): ?><a href="<?php echo htmlspecialchars($settings['social_facebook']); ?>" target="_blank"><i class="fab fa-facebook-f"></i></a><?php endif; ?>
                        <?php if(isset($settings['social_twitter']) && $settings['social_twitter'] != '#'): ?><a href="<?php echo htmlspecialchars($settings['social_twitter']); ?>" target="_blank"><i class="fab fa-twitter"></i></a><?php endif; ?>
                        <?php if(isset($settings['social_instagram']) && $settings['social_instagram'] != '#'): ?><a href="<?php echo htmlspecialchars($settings['social_instagram']); ?>" target="_blank"><i class="fab fa-instagram"></i></a><?php endif; ?>
                        <?php if(isset($settings['social_linkedin']) && $settings['social_linkedin'] != '#'): ?><a href="<?php echo htmlspecialchars($settings['social_linkedin']); ?>" target="_blank"><i class="fab fa-linkedin-in"></i></a><?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bottom Bar -->
    <div class="footer-bottom">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-6 text-center text-md-start">
                    <p class="copyright-text">
                        &copy; <?php echo date('Y'); ?> <strong><?php echo htmlspecialchars($settings['site_name'] ?? 'EduPortal'); ?></strong>.
                        <?php echo isset($settings['footer_copyright_text']) ? htmlspecialchars($settings['footer_copyright_text']) : 'All Rights Reserved'; ?>
                    </p>
                </div>
                <div class="col-md-6 text-center text-md-end">
                    <div class="footer-links d-inline-block">
                        <ul class="d-flex list-unstyled mb-0" style="font-size: 0.8rem;">
                            <li style="border:none; margin:0 10px;"><a href="#">Privacy Policy</a></li>
                            <li style="border:none; margin:0 10px;"><a href="#">Terms of Use</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</footer>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
    // Enable nested dropdowns
    document.addEventListener("DOMContentLoaded", function(){
        // make it as accordion for smaller screens
        if (window.innerWidth < 992) {
            document.querySelectorAll('.dropdown-menu a').forEach(function(element){
                element.addEventListener('click', function (e) {
                    let nextEl = this.nextElementSibling;
                    if(nextEl && nextEl.classList.contains('dropdown-menu')) {
                        // prevent opening link if link needs to open dropdown
                        e.preventDefault();
                        if(nextEl.style.display == 'block'){
                            nextEl.style.display = 'none';
                        } else {
                            nextEl.style.display = 'block';
                        }
                    }
                });
            })
        }
    });
</script>
</body>
</html>
<?php if(isset($conn)) $conn->close(); ?>
