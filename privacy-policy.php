<?php include_once('header.php'); ?>
<div class="container mt-5 animate__animated animate__fadeIn">
    <div class="row">
        <div class="col-12">
            <h1 class="text-center mb-4 animate__animated animate__slideInDown">Privacy Policy</h1>
            
            <div class="privacy-content animate__animated animate__fadeInUp animate__delay-1s">
                <h2>Introduction</h2>
                <p>Welcome to Sabke News's Privacy Policy. This document outlines how we collect, use, and protect your personal information.</p>
                
                <h2 class="mt-4">Information We Collect</h2>
                <p>We collect information that you provide directly to us, including:</p>
                <ul>
                    <li>Name and contact information</li>
                    <li>Account credentials</li>
                    <li>Usage data and preferences</li>
                </ul>
                
                <h2 class="mt-4">How We Use Your Information</h2>
                <p>We use the collected information to:</p>
                <ul>
                    <li>Provide and maintain our services</li>
                    <li>Improve user experience</li>
                    <li>Send important updates and notifications</li>
                </ul>
                
                <h2 class="mt-4">Data Security</h2>
                <p>We implement appropriate security measures to protect your personal information.</p>
                
                <h2 class="mt-4">Contact Us</h2>
                <p>If you have any questions about our Privacy Policy, please contact us.</p>
            </div>
        </div>
    </div>
</div>

<style>
/* Add animate.css CDN in header if not already included */
@import url('https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css');

.privacy-content {
    line-height: 1.8;
    color: #333;
}

.privacy-content h2 {
    color: #2c3e50;
    margin-top: 30px;
    animation: fadeIn 1s ease-in;
}

.privacy-content p, 
.privacy-content ul {
    animation: slideInRight 1s ease-in;
}

.privacy-content ul {
    list-style-type: circle;
    padding-left: 20px;
}

.privacy-content li {
    margin: 10px 0;
    animation: fadeIn 1.5s ease-in;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Add scroll reveal animations
    const elements = document.querySelectorAll('.privacy-content h2, .privacy-content p, .privacy-content ul');
    
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('animate__animated', 'animate__fadeInUp');
            }
        });
    }, { threshold: 0.1 });

    elements.forEach(element => observer.observe(element));
});
</script>

<?php include_once('footer.php'); ?>