<!-- Contact Content Section -->
<section class="py-4">
    <div class="container">
        <div class="row g-5">
            <!-- Contact Information -->
            <div class="col-lg-5">
                <h2 class="mb-4">Get In Touch</h2>
                <p class="mb-4">Have questions about our tours or need more information? Contact us and our friendly team will be happy to assist you!</p>
                
                <div class="contact-info-item d-flex align-items-center">
                    <i class="fas fa-map-marker-alt"></i>
                    <div class="ms-3">
                        <h5 class="fw-bold">Our Location</h5>
                        <p class="mb-0"><?= $translator->trans('89/5 Wichit Subdistrict, Mueang District, Phuket Province') ?></p>
                    </div>
                </div>
                
                <div class="contact-info-item d-flex align-items-center">
                    <i class="fas fa-envelope"></i>
                    <div class="ms-3">
                        <h5 class="fw-bold">Email Us</h5>
                        <p class="mb-0">info@cocotravel.com</p>
                    </div>
                </div>
                
                <div class="contact-info-item d-flex align-items-center">
                    <i class="fas fa-phone-alt"></i>
                    <div class="ms-3">
                        <h5 class="fw-bold">Call Us</h5>
                        <p class="mb-0">+66 82 106 5316</p>
                    </div>
                </div>
                
                <div class="contact-info-item d-flex align-items-center">
                    <i class="fab fa-whatsapp"></i>
                    <div class="ms-3">
                        <h5 class="fw-bold">WhatsApp</h5>
                        <p class="mb-0">+66 82 106 5316</p>
                    </div>
                </div>
                
                <div class="contact-info-item d-flex align-items-center">
                    <i class="fab fa-line"></i>
                    <div class="ms-3">
                        <h5 class="fw-bold">Line</h5>
                        <p class="mb-0">+66 82 106 5316</p>
                    </div>
                </div>
                
                <h4 class="mt-5 mb-3">Follow Us</h4>
                <div class="d-flex gap-3">
                    <a href="https://www.facebook.com/profile.php?id=61573956263143" target="_blank" class="btn btn-outline-primary btn-lg btn-social">
                        <i class="fab fa-facebook-f"></i>
                    </a>
                    <a href="https://www.instagram.com/cocotravel.agency/#" target="_blank" class="btn btn-outline-danger btn-lg btn-social">
                        <i class="fab fa-instagram"></i>
                    </a>
                    <a href="https://www.tiktok.com/@cocotravel4?_t=ZS-8uVUjrTgUxm&_r=1" target="_blank" class="btn btn-outline-dark btn-lg btn-social">
                        <i class="fab fa-tiktok"></i>
                    </a>
                </div>
            </div>
            
            <!-- Contact Form -->
            <div class="col-lg-7">
                <div class="contact-form p-4 bg-white shadow-sm rounded">
                    <h2 class="mb-4">Send us a Message</h2>
                    
                    <?php if ($success): ?>
                    <div class="alert alert-success">
                        <h5 class="mb-1"><i class="fas fa-check-circle me-2"></i> Thank You!</h5>
                        <p class="mb-0">Your message has been sent successfully. We'll get back to you as soon as possible.</p>
                    </div>
                    <?php endif; ?>
                    
                    <?php if (isset($errors['general'])): ?>
                    <div class="alert alert-danger">
                        <?= $errors['general'] ?>
                    </div>
                    <?php endif; ?>
                    
                    <form action="<?= \App\Helpers\url('contact/submit') ?>" method="POST" id="contactForm" class="needs-validation" novalidate>
                        <input type="hidden" name="csrf_token" value="<?= \App\Helpers\generateCsrfToken() ?>">
                        
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="form-floating mb-3">
                                    <input type="text" class="form-control <?= isset($errors['name']) ? 'is-invalid' : '' ?>" id="name" name="name" placeholder="Your Name" value="<?= htmlspecialchars($formData['name']) ?>" required data-validation-message="Please enter your name">
                                    <label for="name">Your Name *</label>
                                    <?php if (isset($errors['name'])): ?>
                                    <div class="invalid-feedback">
                                        <?= $errors['name'] ?>
                                    </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-floating mb-3">
                                    <input type="email" class="form-control <?= isset($errors['email']) ? 'is-invalid' : '' ?>" id="email" name="email" placeholder="Your Email" value="<?= htmlspecialchars($formData['email']) ?>" required data-validation-message="Please enter a valid email address">
                                    <label for="email">Your Email *</label>
                                    <?php if (isset($errors['email'])): ?>
                                    <div class="invalid-feedback">
                                        <?= $errors['email'] ?>
                                    </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-floating mb-3">
                                    <input type="tel" class="form-control" id="phone" name="phone" placeholder="Your Phone" value="<?= htmlspecialchars($formData['phone']) ?>">
                                    <label for="phone">Your Phone (optional)</label>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-floating mb-3">
                                    <input type="text" class="form-control" id="subject" name="subject" placeholder="Subject" value="<?= htmlspecialchars($formData['subject']) ?>">
                                    <label for="subject">Subject</label>
                                </div>
                            </div>
                            
                            <div class="col-12">
                                <div class="form-floating mb-3">
                                    <textarea class="form-control <?= isset($errors['message']) ? 'is-invalid' : '' ?>" id="message" name="message" placeholder="Your Message" style="height: 200px" required data-validation-message="Please enter your message"><?= htmlspecialchars($formData['message']) ?></textarea>
                                    <label for="message">Your Message *</label>
                                    <?php if (isset($errors['message'])): ?>
                                    <div class="invalid-feedback">
                                        <?= $errors['message'] ?>
                                    </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                            
                            <div class="col-12">
                                <button type="submit" class="btn btn-primary py-3 px-4">
                                    <i class="fas fa-paper-plane me-2"></i> Send Message
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Map Section -->
<section class="py-3">
    <div class="container">
        <div class="map-container">
            <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3952.2420622347636!2d98.3688930756852!3d7.869719906055685!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x30502e23d1648177%3A0xc263fa8c2b5705c7!2s89%20Soi%206%2C%20Tambon%20Wichit%2C%20Amphoe%20Mueang%20Phuket%2C%20Chang%20Wat%20Phuket%2083000%2C%20Tayland!5e0!3m2!1str!2str!4v1742566779151!5m2!1str!2str" 
                width="100%" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
        </div>
    </div>
</section>