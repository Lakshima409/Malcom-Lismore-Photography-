/* =====================================================================
   Malcolm Lismore Photography - Front-end interactions
   - Mobile / overlay navigation
   - Testimonials slider
   - Gallery category filters
   - FAQ accordion
   - Client-side form usability enhancements
   ===================================================================== */

(function () {
  "use strict";

  /* -----------------------------------------------------------------
     Overlay navigation
     ----------------------------------------------------------------- */
  var toggle = document.querySelector("[data-nav-toggle]");
  var overlay = document.querySelector("[data-nav-overlay]");
  var closeBtn = document.querySelector("[data-nav-close]");

  function openNav() {
    if (!overlay) return;
    overlay.classList.add("open");
    overlay.setAttribute("aria-hidden", "false");
    if (toggle) toggle.setAttribute("aria-expanded", "true");
    document.body.style.overflow = "hidden";
  }
  function closeNav() {
    if (!overlay) return;
    overlay.classList.remove("open");
    overlay.setAttribute("aria-hidden", "true");
    if (toggle) toggle.setAttribute("aria-expanded", "false");
    document.body.style.overflow = "";
  }

  if (toggle) toggle.addEventListener("click", openNav);
  if (closeBtn) closeBtn.addEventListener("click", closeNav);
  if (overlay) {
    overlay.addEventListener("click", function (e) {
      if (e.target === overlay) closeNav();
    });
  }
  document.addEventListener("keydown", function (e) {
    if (e.key === "Escape") closeNav();
  });

  /* -----------------------------------------------------------------
     Testimonials slider
     ----------------------------------------------------------------- */
  var stage = document.querySelector("[data-slider]");
  if (stage) {
    var track = stage.querySelector("[data-slider-track]");
    var slides = stage.querySelectorAll("[data-slide]");
    var prevBtn = document.querySelector("[data-slider-prev]");
    var nextBtn = document.querySelector("[data-slider-next]");
    var dotsWrap = document.querySelector("[data-slider-dots]");
    var index = 0;
    var total = slides.length;

    var dots = [];
    if (dotsWrap && total > 1) {
      for (var d = 0; d < total; d++) {
        var dot = document.createElement("button");
        dot.type = "button";
        dot.setAttribute("aria-label", "Go to testimonial " + (d + 1));
        (function (di) {
          dot.addEventListener("click", function () { go(di); });
        })(d);
        dotsWrap.appendChild(dot);
        dots.push(dot);
      }
    }

    function go(i) {
      index = (i + total) % total;
      if (track) track.style.transform = "translateX(" + (-index * 100) + "%)";
      dots.forEach(function (dot, di) {
        dot.classList.toggle("active", di === index);
      });
    }

    if (prevBtn) prevBtn.addEventListener("click", function () { go(index - 1); });
    if (nextBtn) nextBtn.addEventListener("click", function () { go(index + 1); });

    var timer = setInterval(function () { go(index + 1); }, 7000);
    stage.addEventListener("mouseenter", function () { clearInterval(timer); });
    stage.addEventListener("mouseleave", function () { timer = setInterval(function () { go(index + 1); }, 7000); });

    go(0);
  }

  /* -----------------------------------------------------------------
     Gallery category filters
     ----------------------------------------------------------------- */
  var filterButtons = document.querySelectorAll("[data-filter]");
  var galleryItems = document.querySelectorAll("[data-category]");
  if (filterButtons.length && galleryItems.length) {
    filterButtons.forEach(function (btn) {
      btn.addEventListener("click", function () {
        var cat = btn.getAttribute("data-filter");
        filterButtons.forEach(function (b) { b.classList.remove("active"); });
        btn.classList.add("active");

        galleryItems.forEach(function (item) {
          var itemCat = item.getAttribute("data-category");
          var show = cat === "all" || itemCat === cat;
          item.style.display = show ? "" : "none";
        });

        var anyVisible = false;
        galleryItems.forEach(function (item) {
          if (item.style.display !== "none") anyVisible = true;
        });
        var empty = document.querySelector("[data-filter-empty]");
        if (empty) empty.style.display = anyVisible ? "none" : "";
      });
    });
  }

  /* -----------------------------------------------------------------
     FAQ accordion
     ----------------------------------------------------------------- */
  document.querySelectorAll("[data-faq-item]").forEach(function (item) {
    var q = item.querySelector("[data-faq-q]");
    var a = item.querySelector("[data-faq-a]");
    if (!q || !a) return;
    q.addEventListener("click", function () {
      var isOpen = item.classList.contains("open");
      document.querySelectorAll("[data-faq-item].open").forEach(function (other) {
        other.classList.remove("open");
        var oa = other.querySelector("[data-faq-a]");
        if (oa) oa.style.maxHeight = null;
      });
      if (!isOpen) {
        item.classList.add("open");
        a.style.maxHeight = a.scrollHeight + "px";
      }
    });
  });

  /* -----------------------------------------------------------------
     Form usability: live validation + clear errors as the user types
     ----------------------------------------------------------------- */
  var forms = document.querySelectorAll("form[data-validate]");
  forms.forEach(function (form) {
    var fields = form.querySelectorAll("input, select, textarea");

    function fieldWrap(el) { return el.closest(".form-field"); }

    function setError(el, message) {
      var wrap = fieldWrap(el);
      if (!wrap) return;
      wrap.classList.add("invalid");
      var err = wrap.querySelector(".error-text");
      if (err) err.textContent = message;
      el.setAttribute("aria-invalid", "true");
    }
    function clearError(el) {
      var wrap = fieldWrap(el);
      if (!wrap) return;
      wrap.classList.remove("invalid");
      var err = wrap.querySelector(".error-text");
      if (err) err.textContent = "";
      el.removeAttribute("aria-invalid");
    }

    function validateField(el) {
      var value = (el.value || "").trim();
      var type = (el.getAttribute("type") || el.tagName.toLowerCase()).toLowerCase();

      if (el.hasAttribute("required") && value === "") {
        setError(el, "This field is required.");
        return false;
      }
      if (type === "email" && value !== "") {
        var re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!re.test(value)) { setError(el, "Please enter a valid email address."); return false; }
      }
      if (el.name === "phone" && value !== "") {
        var pr = /^[0-9+()\-\s]{7,25}$/;
        if (!pr.test(value)) { setError(el, "Please enter a valid phone number."); return false; }
      }
      clearError(el);
      return true;
    }

    fields.forEach(function (el) {
      el.addEventListener("blur", function () { validateField(el); });
      el.addEventListener("input", function () {
        var wrap = fieldWrap(el);
        if (wrap && wrap.classList.contains("invalid")) validateField(el);
      });
    });

    form.addEventListener("submit", function (e) {
      var firstInvalid = null;
      fields.forEach(function (el) {
        if (!validateField(el) && !firstInvalid) firstInvalid = el;
      });
      if (firstInvalid) {
        e.preventDefault();
        firstInvalid.focus();
      }
    });
  });
})();

