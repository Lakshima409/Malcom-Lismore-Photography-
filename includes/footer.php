<?php
/**
 * Shared site footer and closing scripts (public + admin pages).
 *
 * Malcolm Lismore Photography
 */

declare(strict_types=1);

$scriptDir = rtrim(str_replace('\\', '/', dirname((string) ($_SERVER['SCRIPT_NAME'] ?? '/'))), '/');
$inAdmin   = str_contains($scriptDir, '/admin') || basename($scriptDir) === 'admin';
$prefix    = $inAdmin ? '../' : '';
?>
  </main><!-- /#main -->

  <?php if (!$inAdmin): ?>
  <footer class="site-footer">
    <div class="container">
      <div class="footer-grid">
        <div class="footer-col">
          <h4>Contact Us</h4>
          <ul class="contact-list">
            <li><span class="ic" aria-hidden="true">&#128205;</span><span>Ullapool, North West Highlands, Scotland</span></li>
            <li><span class="ic" aria-hidden="true">&#9993;</span><a href="mailto:hello@malcolmlismore.co.uk">hello@malcolmlismore.co.uk</a></li>
            <li><span class="ic" aria-hidden="true">&#9742;</span><a href="tel:+441234567890">+44 1234 567890</a></li>
          </ul>
          <div class="social-row" aria-label="Social media">
            <a href="#" aria-label="Facebook">f</a>
            <a href="#" aria-label="Twitter">&#120143;</a>
            <a href="#" aria-label="LinkedIn">in</a>
            <a href="#" aria-label="Instagram">&#9901;</a>
            <a href="#" aria-label="YouTube">&#9658;</a>
          </div>
        </div>

        <div class="footer-col">
          <h4>Explore</h4>
          <ul class="footer-links">
            <li><a href="<?= e($prefix) ?>index.php">Home</a></li>
            <li><a href="<?= e($prefix) ?>about.php">About</a></li>
            <li><a href="<?= e($prefix) ?>gallery.php">Gallery</a></li>
            <li><a href="<?= e($prefix) ?>blog.php">Blog</a></li>
            <li><a href="<?= e($prefix) ?>prices.php">Pricing</a></li>
          </ul>
        </div>

        <div class="footer-col">
          <h4>Information</h4>
          <ul class="footer-links">
            <li><a href="<?= e($prefix) ?>enquiry.php">Contact</a></li>
            <li><a href="<?= e($prefix) ?>enquiry.php">Inquiry</a></li>
            <li><a href="<?= e($prefix) ?>faq.php">FAQ</a></li>
            <li><a href="<?= e($prefix) ?>admin/login.php">Admin Login</a></li>
          </ul>
        </div>
      </div>

      <div class="footer-bottom">
        &copy; <?= date('Y') ?> Malcolm Lismore Photography. All rights reserved.
      </div>
    </div>
  </footer>
  <?php endif; ?>

  <script src="<?= e($prefix) ?>assets/js/script.js" defer></script>
</body>
</html>
