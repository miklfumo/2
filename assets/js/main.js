/**
 * Conference Site — Main JavaScript
 * Progressive enhancement: site works without JS, JS adds interactivity
 */

document.addEventListener('DOMContentLoaded', function () {
  'use strict';

  /* ==========================================================================
     Navigation
     ========================================================================== */
  const nav = document.querySelector('.nav');
  const navToggle = document.querySelector('.nav__toggle');
  const navMobile = document.querySelector('.nav__mobile');
  const mobileLinks = navMobile ? navMobile.querySelectorAll('a') : [];

  // Scroll-aware nav background
  function updateNav() {
    if (!nav) return;
    if (window.scrollY > 50) {
      nav.classList.add('nav--scrolled');
    } else {
      nav.classList.remove('nav--scrolled');
    }
  }
  window.addEventListener('scroll', updateNav, { passive: true });
  updateNav();

  // Mobile menu toggle
  if (navToggle && navMobile) {
    navToggle.addEventListener('click', function () {
      const isOpen = navMobile.classList.toggle('is-open');
      navToggle.setAttribute('aria-expanded', isOpen);
      // Toggle icon
      const icons = navToggle.querySelectorAll('svg');
      if (icons.length === 2) {
        icons[0].style.display = isOpen ? 'none' : 'block';
        icons[1].style.display = isOpen ? 'block' : 'none';
      }
    });

    mobileLinks.forEach(function (link) {
      link.addEventListener('click', function () {
        navMobile.classList.remove('is-open');
        navToggle.setAttribute('aria-expanded', 'false');
        const icons = navToggle.querySelectorAll('svg');
        if (icons.length === 2) {
          icons[0].style.display = 'block';
          icons[1].style.display = 'none';
        }
      });
    });
  }

  /* ==========================================================================
     Speakers Carousel
     ========================================================================== */
  const speakersTrack = document.querySelector('.speakers__track');
  const speakerCards = speakersTrack ? Array.from(speakersTrack.children) : [];
  const speakerPrev = document.querySelector('.speakers__arrow--prev');
  const speakerNext = document.querySelector('.speakers__arrow--next');
  const speakerDots = document.querySelectorAll('.speakers__dot');
  let speakerIndex = 0;

  function getVisibleCount() {
    if (window.innerWidth >= 1024) return 4;
    if (window.innerWidth >= 640) return 2;
    return 1;
  }

  function updateSpeakers() {
    var visibleCount = getVisibleCount();
    var total = speakerCards.length;

    speakerCards.forEach(function (card, i) {
      var show = false;
      for (var j = 0; j < visibleCount; j++) {
        if (i === (speakerIndex + j) % total) show = true;
      }
      card.style.display = show ? '' : 'none';
    });

    speakerDots.forEach(function (dot, i) {
      dot.classList.toggle('is-active', i === speakerIndex);
    });
  }

  if (speakerPrev) {
    speakerPrev.addEventListener('click', function () {
      speakerIndex = (speakerIndex - 1 + speakerCards.length) % speakerCards.length;
      updateSpeakers();
    });
  }

  if (speakerNext) {
    speakerNext.addEventListener('click', function () {
      speakerIndex = (speakerIndex + 1) % speakerCards.length;
      updateSpeakers();
    });
  }

  speakerDots.forEach(function (dot, i) {
    dot.addEventListener('click', function () {
      speakerIndex = i;
      updateSpeakers();
    });
  });

  if (speakerCards.length > 0) {
    updateSpeakers();
    window.addEventListener('resize', updateSpeakers);
  }

  /* ==========================================================================
     Schedule Tabs
     ========================================================================== */
  const scheduleTabs = document.querySelectorAll('.schedule__tab');
  const schedulePanels = document.querySelectorAll('.schedule__panel');

  scheduleTabs.forEach(function (tab, i) {
    tab.addEventListener('click', function () {
      scheduleTabs.forEach(function (t) { t.classList.remove('is-active'); });
      schedulePanels.forEach(function (p) { p.classList.remove('is-active'); });
      tab.classList.add('is-active');
      if (schedulePanels[i]) schedulePanels[i].classList.add('is-active');
    });
  });

  /* ==========================================================================
     Gallery Accordion + Lightbox
     ========================================================================== */
  const galleryBtns = document.querySelectorAll('.gallery__year-btn');
  const galleryPanels = document.querySelectorAll('.gallery__images');
  const lightbox = document.querySelector('.lightbox');
  const lightboxImg = lightbox ? lightbox.querySelector('.lightbox__img') : null;
  const lightboxCounter = lightbox ? lightbox.querySelector('.lightbox__counter') : null;
  const lightboxClose = lightbox ? lightbox.querySelector('.lightbox__close') : null;
  const lightboxPrev = lightbox ? lightbox.querySelector('.lightbox__arrow--prev') : null;
  const lightboxNext = lightbox ? lightbox.querySelector('.lightbox__arrow--next') : null;

  // Collect all gallery images for lightbox navigation
  var allGalleryImages = [];
  document.querySelectorAll('.gallery__thumb img').forEach(function (img) {
    allGalleryImages.push({ src: img.getAttribute('src'), alt: img.getAttribute('alt') || '' });
  });
  var lightboxIndex = 0;

  // Accordion
  galleryBtns.forEach(function (btn, i) {
    btn.addEventListener('click', function () {
      var isOpen = btn.classList.toggle('is-open');
      if (galleryPanels[i]) galleryPanels[i].classList.toggle('is-open', isOpen);
      // Close others
      galleryBtns.forEach(function (otherBtn, j) {
        if (j !== i) {
          otherBtn.classList.remove('is-open');
          if (galleryPanels[j]) galleryPanels[j].classList.remove('is-open');
        }
      });
    });
  });

  // Open lightbox
  document.querySelectorAll('.gallery__thumb').forEach(function (thumb, i) {
    thumb.addEventListener('click', function () {
      lightboxIndex = i;
      showLightbox();
    });
  });

  function showLightbox() {
    if (!lightbox || !lightboxImg) return;
    var img = allGalleryImages[lightboxIndex];
    if (!img) return;
    lightboxImg.src = img.src;
    lightboxImg.alt = img.alt;
    if (lightboxCounter) {
      lightboxCounter.textContent = (lightboxIndex + 1) + ' / ' + allGalleryImages.length;
    }
    lightbox.classList.add('is-open');
    document.body.style.overflow = 'hidden';
  }

  function closeLightbox() {
    if (!lightbox) return;
    lightbox.classList.remove('is-open');
    document.body.style.overflow = '';
  }

  if (lightboxClose) lightboxClose.addEventListener('click', closeLightbox);
  if (lightboxPrev) {
    lightboxPrev.addEventListener('click', function () {
      lightboxIndex = (lightboxIndex - 1 + allGalleryImages.length) % allGalleryImages.length;
      showLightbox();
    });
  }
  if (lightboxNext) {
    lightboxNext.addEventListener('click', function () {
      lightboxIndex = (lightboxIndex + 1) % allGalleryImages.length;
      showLightbox();
    });
  }

  // Close on Escape
  document.addEventListener('keydown', function (e) {
    if (e.key === 'Escape') closeLightbox();
    if (lightbox && lightbox.classList.contains('is-open')) {
      if (e.key === 'ArrowLeft' && lightboxPrev) lightboxPrev.click();
      if (e.key === 'ArrowRight' && lightboxNext) lightboxNext.click();
    }
  });

  // Close on backdrop click
  if (lightbox) {
    lightbox.addEventListener('click', function (e) {
      if (e.target === lightbox) closeLightbox();
    });
  }

  /* ==========================================================================
     Registration Form
     ========================================================================== */
  const regForm = document.querySelector('.reg__form');
  const participantBtns = document.querySelectorAll('.reg__selector-btn');
  const otherFields = document.getElementById('other-fields');
  const paymentBtns = document.querySelectorAll('.payment-toggle__btn');
  const innInput = document.getElementById('reg-inn');
  const innHint = document.getElementById('inn-hint');

  var participantType = 'education';
  var paymentType = 'organization';

  // Participant type toggle
  participantBtns.forEach(function (btn) {
    btn.addEventListener('click', function () {
      participantType = btn.dataset.type;
      participantBtns.forEach(function (b) { b.classList.remove('is-active'); });
      btn.classList.add('is-active');
      if (otherFields) {
        otherFields.style.display = participantType === 'other' ? '' : 'none';
      }
      // Update hidden field
      var hiddenType = document.getElementById('participant-type-input');
      if (hiddenType) hiddenType.value = participantType;
    });
  });

  // Payment type toggle
  paymentBtns.forEach(function (btn) {
    btn.addEventListener('click', function () {
      paymentType = btn.dataset.payment;
      paymentBtns.forEach(function (b) { b.classList.remove('is-active'); });
      btn.classList.add('is-active');
      updateInnHint();
      // Update hidden field
      var hiddenPayment = document.getElementById('payment-type-input');
      if (hiddenPayment) hiddenPayment.value = paymentType;
    });
  });

  function updateInnHint() {
    if (innInput && innHint) {
      var isOrg = paymentType === 'organization';
      innInput.maxLength = isOrg ? 10 : 12;
      innInput.placeholder = isOrg ? '1234567890' : '123456789012';
      innHint.textContent = isOrg ? '10 цифр' : '12 цифр';
    }
  }

  // Client-side INN filter (digits only)
  if (innInput) {
    innInput.addEventListener('input', function () {
      this.value = this.value.replace(/\D/g, '');
    });
  }

  // Client-side validation (progressive enhancement, server validates too)
  if (regForm) {
    regForm.addEventListener('submit', function (e) {
      var errors = [];
      var fullname = document.getElementById('reg-fullname');
      var email = document.getElementById('reg-email');
      var org = document.getElementById('reg-organization');
      var position = document.getElementById('reg-position');
      var phone = document.getElementById('reg-phone');
      var captcha = document.getElementById('reg-captcha');
      var pdConsent = document.getElementById('reg-personal-data');
      var offerConsent = document.getElementById('reg-offer');

      // Remove previous error states
      regForm.querySelectorAll('.form-input--error').forEach(function (el) {
        el.classList.remove('form-input--error');
      });

      if (fullname && fullname.value.trim().length < 3) {
        errors.push('Укажите ФИО');
        fullname.classList.add('form-input--error');
      }
      if (email && !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email.value.trim())) {
        errors.push('Укажите корректный email');
        email.classList.add('form-input--error');
      }
      if (org && org.value.trim().length < 2) {
        errors.push('Укажите организацию');
        org.classList.add('form-input--error');
      }
      if (position && position.value.trim().length < 2) {
        errors.push('Укажите должность');
        position.classList.add('form-input--error');
      }
      if (phone && phone.value.replace(/\D/g, '').length < 10) {
        errors.push('Укажите корректный телефон');
        phone.classList.add('form-input--error');
      }

      // INN validation for "other" type
      if (participantType === 'other' && innInput) {
        var digits = innInput.value.replace(/\D/g, '');
        var requiredLen = paymentType === 'organization' ? 10 : 12;
        if (digits.length !== requiredLen) {
          errors.push('ИНН должен содержать ' + requiredLen + ' цифр');
          innInput.classList.add('form-input--error');
        }
      }

      if (captcha && captcha.value.trim() === '') {
        errors.push('Решите проверочный пример');
        captcha.classList.add('form-input--error');
      }

      if (pdConsent && !pdConsent.checked) {
        errors.push('Необходимо согласие на обработку персональных данных');
      }
      if (offerConsent && !offerConsent.checked) {
        errors.push('Необходимо принять условия оферты');
      }

      if (errors.length > 0) {
        e.preventDefault();
        var errBox = document.querySelector('.reg__errors');
        if (errBox) {
          errBox.innerHTML = '<ul>' + errors.map(function (err) { return '<li>' + err + '</li>'; }).join('') + '</ul>';
          errBox.style.display = '';
        }
        return false;
      }
    });
  }

  /* ==========================================================================
     Smooth scroll for anchor links (progressive enhancement)
     ========================================================================== */
  document.querySelectorAll('a[href^="#"]').forEach(function (link) {
    link.addEventListener('click', function (e) {
      var target = document.querySelector(this.getAttribute('href'));
      if (target) {
        e.preventDefault();
        var offset = nav ? nav.offsetHeight : 0;
        var top = target.getBoundingClientRect().top + window.pageYOffset - offset;
        window.scrollTo({ top: top, behavior: 'smooth' });
      }
    });
  });
});
