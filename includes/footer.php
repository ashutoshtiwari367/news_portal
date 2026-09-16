<?php

$footerSiteName = getSetting(
    'site_name',
    'News Portal'
);

$footerEmail = getSetting(
    'contact_email',
    'info@newsportal.com'
);

$facebook = getSetting('facebook_url', '#');
$instagram = getSetting('instagram_url', '#');
$twitter = getSetting('twitter_url', '#');
$youtube = getSetting('youtube_url', '#');

?>

</main>


<!-- =========================================
     FOOTER
========================================= -->

<footer class="site-footer">

    <div class="container">

        <div class="footer-main">

            <!-- ABOUT -->

            <div class="footer-column footer-about">

                <div class="footer-logo">

                    <div class="logo-icon">
                        <i class="fa-solid fa-newspaper"></i>
                    </div>

                    <span>
                        <?= clean($footerSiteName); ?>
                    </span>

                </div>

                <p>
                    <?= clean(
                        getSetting(
                            'site_tagline',
                            'Latest News & Breaking Updates'
                        )
                    ); ?>
                </p>

                <p class="footer-description">
                    Stay updated with the latest national,
                    regional, business, technology, sports
                    and entertainment news.
                </p>

            </div>


            <!-- QUICK LINKS -->

            <div class="footer-column">

                <h3>
                    Quick Links
                </h3>

                <ul class="footer-links">

                    <li>
                        <a href="index.php">
                            Home
                        </a>
                    </li>

                    <li>
                        <a href="#">
                            About Us
                        </a>
                    </li>

                    <li>
                        <a href="#">
                            Contact Us
                        </a>
                    </li>

                    <li>
                        <a href="#">
                            Privacy Policy
                        </a>
                    </li>

                    <li>
                        <a href="#">
                            Terms & Conditions
                        </a>
                    </li>

                </ul>

            </div>


            <!-- CATEGORIES -->

            <div class="footer-column">

                <h3>
                    Categories
                </h3>

                <ul class="footer-links">

                    <?php

                    $footerCategories = getCategories();

                    $footerCategories =
                        array_slice(
                            $footerCategories,
                            0,
                            6
                        );

                    ?>

                    <?php foreach (
                        $footerCategories
                        as $category
                    ): ?>

                        <li>

                            <a
                                href="category.php?slug=<?= urlencode($category['slug']); ?>"
                            >

                                <?= clean($category['name']); ?>

                            </a>

                        </li>

                    <?php endforeach; ?>

                </ul>

            </div>


            <!-- CONTACT -->

            <div class="footer-column">

                <h3>
                    Contact
                </h3>

                <ul class="footer-contact">

                    <li>

                        <i class="fa-solid fa-envelope"></i>

                        <a
                            href="mailto:<?= clean($footerEmail); ?>"
                        >

                            <?= clean($footerEmail); ?>

                        </a>

                    </li>

                    <li>

                        <i class="fa-solid fa-globe"></i>

                        <span>
                            <?= clean($footerSiteName); ?>
                        </span>

                    </li>

                </ul>


                <!-- SOCIAL MEDIA -->

                <div class="footer-social">

                    <a
                        href="<?= clean($facebook); ?>"
                        target="_blank"
                        aria-label="Facebook"
                    >

                        <i class="fa-brands fa-facebook-f"></i>

                    </a>


                    <a
                        href="<?= clean($instagram); ?>"
                        target="_blank"
                        aria-label="Instagram"
                    >

                        <i class="fa-brands fa-instagram"></i>

                    </a>


                    <a
                        href="<?= clean($twitter); ?>"
                        target="_blank"
                        aria-label="Twitter"
                    >

                        <i class="fa-brands fa-x-twitter"></i>

                    </a>


                    <a
                        href="<?= clean($youtube); ?>"
                        target="_blank"
                        aria-label="YouTube"
                    >

                        <i class="fa-brands fa-youtube"></i>

                    </a>

                </div>

            </div>

        </div>


        <!-- FOOTER BOTTOM -->

        <div class="footer-bottom">

            <div>

                © <?= date('Y'); ?>

                <strong>
                    <?= clean($footerSiteName); ?>
                </strong>

                . All Rights Reserved.

            </div>


            <div class="footer-bottom-links">

                <a href="#">
                    Privacy
                </a>

                <a href="#">
                    Terms
                </a>

                <a href="#">
                    Disclaimer
                </a>

            </div>

        </div>

    </div>

</footer>


<!-- =========================================
     JAVASCRIPT
========================================= -->

<script
    src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
></script>

<script
    src="<?= $basePath ?? ''; ?>assets/js/script.js"
></script>

</body>

</html>
