// ── Sticky header: add .scrolled class when page scrolls ──────────────────
const header = document.querySelector('header');
window.addEventListener('scroll', () => {
    header.classList.toggle('scrolled', window.scrollY > 50);
});

// ── Mobile hamburger menu toggle ───────────────────────────────────────────
const menuToggle = document.querySelector('#menu-toggle');
const nav = document.querySelector('nav');

if (menuToggle) {
    menuToggle.addEventListener('click', () => {
        nav.classList.toggle('active');
        menuToggle.classList.toggle('open');
    });
}

// Close nav when a link is clicked (mobile)
document.querySelectorAll('nav a').forEach(link => {
    link.addEventListener('click', () => {
        nav.classList.remove('active');
        if (menuToggle) menuToggle.classList.remove('open');
    });
});

// ── Active nav link on scroll ──────────────────────────────────────────────
const sections = document.querySelectorAll('section[id]');
const navLinks = document.querySelectorAll('nav a');

function setActiveLink() {
    let current = 'home';
    sections.forEach(section => {
        const sectionTop = section.offsetTop - 100;
        if (window.scrollY >= sectionTop) {
            current = section.getAttribute('id');
        }
    });

    navLinks.forEach(link => {
        link.classList.remove('active');
        if (link.getAttribute('href') === `#${current}`) {
            link.classList.add('active');
        }
    });
}

window.addEventListener('scroll', setActiveLink);
setActiveLink(); // run on load to highlight Home immediately

// ── Smooth scroll for all anchor links ────────────────────────────────────
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function (e) {
        const target = document.querySelector(this.getAttribute('href'));
        if (target) {
            e.preventDefault();
            target.scrollIntoView({ behavior: 'smooth' });
        }
    });
});

// ── Typing animation ───────────────────────────────────────────────────────
const typingSpan = document.querySelector('.typing-text span');
const words = ['Web Developer', 'UI Designer', 'QA Tester', 'Script Writer'];
let wordIndex = 0;
let charIndex = 0;
let isDeleting = false;

function type() {
    const currentWord = words[wordIndex];

    if (isDeleting) {
        typingSpan.textContent = currentWord.substring(0, charIndex - 1);
        charIndex--;
    } else {
        typingSpan.textContent = currentWord.substring(0, charIndex + 1);
        charIndex++;
    }

    let speed = isDeleting ? 80 : 120;

    if (!isDeleting && charIndex === currentWord.length) {
        speed = 1800; // pause at end
        isDeleting = true;
    } else if (isDeleting && charIndex === 0) {
        isDeleting = false;
        wordIndex = (wordIndex + 1) % words.length;
        speed = 400;
    }

    setTimeout(type, speed);
}

if (typingSpan) {
    // Remove CSS-based typing animation since JS handles it now
    typingSpan.style.animation = 'none';
    type();
}

// ── Hire Me Modal functionality ─────────────────────────────────────────────
const hireMeBtn = document.querySelector('#hireMeBtn');
const modal = document.querySelector('#hireModal');
const closeBtn = document.querySelector('.close-btn');
const hireForm = document.querySelector('#hireForm');

// Open modal when Hire Me button is clicked
if (hireMeBtn) {
    hireMeBtn.addEventListener('click', function(e) {
        e.preventDefault();
        modal.style.display = 'block';
        document.body.style.overflow = 'hidden'; // Prevent scrolling
    });
}

// Close modal when X button is clicked
if (closeBtn) {
    closeBtn.addEventListener('click', function() {
        modal.style.display = 'none';
        document.body.style.overflow = 'auto'; // Enable scrolling
    });
}

// Close modal when clicking outside the modal content
if (modal) {
    modal.addEventListener('click', function(e) {
        if (e.target === modal) {
            modal.style.display = 'none';
            document.body.style.overflow = 'auto';
        }
    });
}

// Handle form submission using Formspree
if (hireForm) {
    hireForm.addEventListener('submit', async function(e) {
        e.preventDefault();
        
        // Replace with your Formspree endpoint
        // Get your free form endpoint at https://formspree.io/
        const formspreeEndpoint = 'https://formspree.io/f/mqeybazy';
        
        const submitBtn = hireForm.querySelector('.submit-btn');
        const originalText = submitBtn.textContent;
        submitBtn.textContent = 'Sending...';
        submitBtn.disabled = true;
        
        try {
            const response = await fetch(formspreeEndpoint, {
                method: 'POST',
                body: new FormData(hireForm),
                headers: {
                    'Accept': 'application/json'
                }
            });
            
            if (response.ok) {
                alert('Thank you for your interest! I will get back to you soon.');
                hireForm.reset();
            } else {
                alert('Sorry, there was an error. Please try again.');
            }
        } catch (error) {
            console.error('Error:', error);
            alert('Sorry, there was an error. Please try again.');
        }
        
        submitBtn.textContent = originalText;
        submitBtn.disabled = false;
        modal.style.display = 'none';
        document.body.style.overflow = 'auto';
    });
}

// ── Design Section Tab Filtering ───────────────────────────────────────────────
const tabBtns = document.querySelectorAll('.tab-btn');
const designCategories = document.querySelectorAll('.design-category');

tabBtns.forEach(btn => {
    btn.addEventListener('click', () => {
        // Remove active class from all buttons
        tabBtns.forEach(b => {
            b.classList.remove('active');
            b.setAttribute('aria-selected', 'false');
        });
        // Add active class to clicked button
        btn.classList.add('active');
        btn.setAttribute('aria-selected', 'true');
        
        const category = btn.dataset.category;
        
        // Show/hide categories based on selection
        designCategories.forEach(cat => {
            if (category === 'all') {
                // Show all categories
                cat.classList.add('active');
            } else {
                cat.classList.remove('active');
                if (cat.id === `${category}-category`) {
                    cat.classList.add('active');
                }
            }
        });
    });
});

// Initialize: show all categories by default (All Projects)
if (designCategories.length > 0) {
    designCategories.forEach(cat => cat.classList.add('active'));
}

// Store interval IDs for cleanup
const galleryIntervals = [];

// ── Gallery Slider for Project Images ───────────────────────────────────────────
gallerySliders.forEach(slider => {
    const slides = slider.querySelectorAll('.gallery-slide');
    const container = slider.parentElement;
    const prevBtn = container.querySelector('.gallery-btn.prev');
    const nextBtn = container.querySelector('.gallery-btn.next');
    let currentSlide = 0;
    let slideInterval = null;
    
    if (slides.length > 1) {
        // Show navigation buttons
        if (prevBtn) prevBtn.style.display = 'flex';
        if (nextBtn) nextBtn.style.display = 'flex';
        
        // Previous button click
        if (prevBtn) {
            prevBtn.addEventListener('click', (e) => {
                e.stopPropagation();
                if (slideInterval) {
                    clearInterval(slideInterval);
                    slideInterval = setInterval(autoAdvance, 5000);
                }
                slides[currentSlide].classList.remove('active');
                currentSlide = (currentSlide - 1 + slides.length) % slides.length;
                slides[currentSlide].classList.add('active');
            });
        }
        
        // Next button click
        if (nextBtn) {
            nextBtn.addEventListener('click', (e) => {
                e.stopPropagation();
                if (slideInterval) {
                    clearInterval(slideInterval);
                    slideInterval = setInterval(autoAdvance, 5000);
                }
                slides[currentSlide].classList.remove('active');
                currentSlide = (currentSlide + 1) % slides.length;
                slides[currentSlide].classList.add('active');
            });
        }
        
        // Auto-advance slides every 5 seconds
        function autoAdvance() {
            slides[currentSlide].classList.remove('active');
            currentSlide = (currentSlide + 1) % slides.length;
            slides[currentSlide].classList.add('active');
        }
        
        slideInterval = setInterval(autoAdvance, 5000);
        galleryIntervals.push(slideInterval);
    }
});

// Cleanup intervals on page unload to prevent memory leaks
window.addEventListener('beforeunload', () => {
    galleryIntervals.forEach(interval => clearInterval(interval));
});

// ── Lightbox for Project Gallery ───────────────────────────────────────────────
const ilawImages = [
        "ilaw/maindashboard.jpg",
        "ilaw/loginform.png",
        "ilaw/maindashboardmission-vission.jpg",
        "ilaw/dashboard-location.jpg",
        "ilaw/admin-dashboard.png",
        "ilaw/admin-memberlist.png",
        "ilaw/admin-officerlist.png",
        "ilaw/admin-memberrequestacc.png",
        "ilaw/admin-memberstransaction.png",
        "ilaw/admin-documents.png",
        "ilaw/admin-cluster.png",
        "ilaw/admin-barangaycluster.png",
        "ilaw/members-maindashboard.png",
        "ilaw/members-personalinfo.png",
        "ilaw/members-uploadreciept.png",
        "ilaw/members-officerreview.png",
        "ilaw/member-announcement.png",
        "ilaw/announcement.png",
        "ilaw/clients-documents.png"
];

let currentLightboxSlide = 0;

function openGalleryLightbox(event, gallery) {
    event.preventDefault();
    currentLightboxSlide = 0;
    showLightboxSlide();
    document.getElementById('lightbox').classList.add('active');
    document.body.style.overflow = 'hidden';
}

function closeLightbox() {
    document.getElementById('lightbox').classList.remove('active');
    document.body.style.overflow = 'auto';
}

function showLightboxSlide() {
    const img = document.getElementById('lightbox-img');
    const currentSpan = document.getElementById('lightbox-current');
    const totalSpan = document.getElementById('lightbox-total');
    
    img.src = ilawImages[currentLightboxSlide];
    currentSpan.textContent = currentLightboxSlide + 1;
    totalSpan.textContent = ilawImages.length;
}

function changeLightboxSlide(direction) {
    currentLightboxSlide += direction;
    if (currentLightboxSlide < 0) {
        currentLightboxSlide = ilawImages.length - 1;
    } else if (currentLightboxSlide >= ilawImages.length) {
        currentLightboxSlide = 0;
    }
    showLightboxSlide();
}

// Keyboard navigation for lightbox
document.addEventListener('keydown', (e) => {
    const lightbox = document.getElementById('lightbox');
    if (!lightbox.classList.contains('active')) return;
    
    if (e.key === 'ArrowLeft') changeLightboxSlide(-1);
    if (e.key === 'ArrowRight') changeLightboxSlide(1);
    if (e.key === 'Escape') closeLightbox();
});

