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

