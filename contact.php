<?php include_once 'header.php'; ?>

<style>
    @keyframes fadeIn {
        from {
            opacity: 0;
        }

        to {
            opacity: 1;
        }
    }

    @keyframes slideIn {
        from {
            transform: translateX(-100%);
        }

        to {
            transform: translateX(0);
        }
    }

    @keyframes pulse {
        0% {
            transform: scale(1);
        }

        50% {
            transform: scale(1.05);
        }

        100% {
            transform: scale(1);
        }
    }

    .contact-container {
        max-width: 1200px;
        margin: 2rem auto;
        padding: 0 1rem;
        animation: fadeIn 1s ease-out;
    }

    .contact-grid {
        display: grid;
        grid-template-columns: 1fr;
        gap: 2rem;
    }

    .contact-form {
        animation: slideIn 1s ease-out;
    }

    .contact-form h2 {
        font-size: 2rem;
        margin-bottom: 1.5rem;
        color: #333;
    }

    .input-group {
        margin-bottom: 1.5rem;
    }

    .input-group input,
    .input-group textarea {
        width: 100%;
        padding: 0.8rem;
        border: 2px solid #ddd;
        border-radius: 8px;
        font-size: 1rem;
        transition: all 0.3s ease;
    }

    .input-group input:focus,
    .input-group textarea:focus {
        border-color: #4a90e2;
        outline: none;
    }

    .submit-btn {
        background: #4a90e2;
        color: white;
        border: none;
        padding: 1rem 2rem;
        border-radius: 8px;
        cursor: pointer;
        font-size: 1rem;
        transition: all 0.3s ease;
    }

    .submit-btn:hover {
        background: #357abd;
        animation: pulse 1s infinite;
    }

    .contact-info {
        background: #f8f9fa;
        padding: 2rem;
        border-radius: 12px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }

    .contact-info h3 {
        font-size: 1.5rem;
        margin-bottom: 1.5rem;
        color: #333;
    }

    .info-item {
        display: flex;
        align-items: center;
        margin-bottom: 1.2rem;
    }

    .info-item i {
        font-size: 1.5rem;
        color: #4a90e2;
        margin-right: 1rem;
    }

    @media (min-width: 768px) {
        .contact-grid {
            grid-template-columns: 1fr 1fr;
        }
    }
</style>

<div class="contact-container">
    <div class="contact-grid">
        <div class="contact-form">
            <h2>Contact Us</h2>
            <form id="contactForm">
                <div class="input-group">
                    <input type="text" placeholder="Your Name" required>
                </div>
                <div class="input-group">
                    <input type="email" placeholder="Email Address" required>
                </div>
                <div class="input-group">
                    <textarea rows="5" placeholder="Your Message" required></textarea>
                </div>
                <button type="submit" class="submit-btn">Send Message</button>
            </form>
        </div>

        <div class="contact-info">
            <h3>Get in Touch</h3>
            <div class="info-item">
                <i class="fas fa-map-marker-alt"></i>
                <p>123 News Street, City, Country</p>
            </div>
            <div class="info-item">
                <i class="fas fa-phone"></i>
                <p>+1 234 567 8900</p>
            </div>
            <div class="info-item">
                <i class="fas fa-envelope"></i>
                <p>contact@sabkenews.in</p>
            </div>
        </div>
    </div>
</div>

<script src="https://kit.fontawesome.com/your-font-awesome-kit.js"></script>

<script>
    document.getElementById('contactForm').addEventListener('submit', function(e) {
        e.preventDefault();

        const button = this.querySelector('button');
        button.style.animation = 'fadeIn 0.5s';

        setTimeout(() => {
            button.textContent = 'Message Sent!';
            button.style.background = '#28a745';
        }, 500);
    });
</script>

<?php include_once 'footer.php'; ?>