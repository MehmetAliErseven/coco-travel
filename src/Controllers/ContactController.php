<?php
namespace App\Controllers;

use App\Core\Database;

class ContactController extends BaseController
{
    /**
     * Display contact page
     */
    public function indexAction()
    {
        // Get selected tour from query string if any
        $selectedTour = isset($_GET['tour']) ? $_GET['tour'] : '';
        
        $this->render('contact/index', [
            'pageTitle' => 'Contact Us',
            'selectedTour' => $selectedTour,
            'errors' => [],
            'success' => false,
            'formData' => [
                'name' => '',
                'email' => '',
                'phone' => '',
                'subject' => $selectedTour ? 'Inquiry about ' . $selectedTour : '',
                'message' => ''
            ]
        ]);
    }
    
    /**
     * Process contact form submission
     */
    public function submitAction()
    {
        $db = Database::getInstance();
        $errors = [];
        $formData = [
            'name' => $_POST['name'] ?? '',
            'email' => $_POST['email'] ?? '',
            'phone' => $_POST['phone'] ?? '',
            'subject' => $_POST['subject'] ?? '',
            'message' => $_POST['message'] ?? ''
        ];
        
        // Validate form data
        if (empty($formData['name'])) {
            $errors['name'] = 'Name is required';
        }
        
        if (empty($formData['email'])) {
            $errors['email'] = 'Email is required';
        } elseif (!filter_var($formData['email'], FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Please enter a valid email address';
        }
        
        if (empty($formData['message'])) {
            $errors['message'] = 'Message is required';
        }
        
        // If there are no validation errors, save the message
        if (empty($errors)) {
            try {
                // Insert message into database
                $db->insert('messages', [
                    'name' => $formData['name'],
                    'email' => $formData['email'],
                    'phone' => $formData['phone'],
                    'subject' => $formData['subject'],
                    'message' => $formData['message']
                ]);
                
                // Send email notification if configured
                if ($this->sendEmailNotification($formData)) {
                    // Email sent successfully
                }
                
                // Redirect to thank you page or display success message
                $this->render('contact/index', [
                    'pageTitle' => 'Contact Us',
                    'selectedTour' => '',
                    'errors' => [],
                    'success' => true,
                    'formData' => [
                        'name' => '',
                        'email' => '',
                        'phone' => '',
                        'subject' => '',
                        'message' => ''
                    ]
                ]);
                return;
            } catch (\Exception $e) {
                $errors['general'] = 'An error occurred while sending your message. Please try again later.';
            }
        }
        
        // If there are errors or exception occurred, re-render the form with errors
        $this->render('contact/index', [
            'pageTitle' => 'Contact Us',
            'selectedTour' => '',
            'errors' => $errors,
            'success' => false,
            'formData' => $formData
        ]);
    }
    
    /**
     * Send email notification to admin
     */
    private function sendEmailNotification($formData)
    {
        // Check if email settings are configured
        if (
            empty($_ENV['MAIL_HOST']) || 
            empty($_ENV['MAIL_USERNAME']) || 
            empty($_ENV['MAIL_PASSWORD']) || 
            empty($_ENV['MAIL_FROM_ADDRESS'])
        ) {
            return false;
        }
        
        try {
            // Create PHPMailer instance
            $mail = new \PHPMailer\PHPMailer\PHPMailer(true);
            
            // Server settings
            $mail->isSMTP();
            $mail->Host = $_ENV['MAIL_HOST'];
            $mail->SMTPAuth = true;
            $mail->Username = $_ENV['MAIL_USERNAME'];
            $mail->Password = $_ENV['MAIL_PASSWORD'];
            $mail->SMTPSecure = $_ENV['MAIL_ENCRYPTION'] ?? 'tls';
            $mail->Port = $_ENV['MAIL_PORT'] ?? 587;
            
            // Recipients
            $mail->setFrom($_ENV['MAIL_FROM_ADDRESS'], $_ENV['MAIL_FROM_NAME'] ?? 'Coco Travel');
            $mail->addAddress($_ENV['MAIL_FROM_ADDRESS'], $_ENV['MAIL_FROM_NAME'] ?? 'Coco Travel');
            $mail->addReplyTo($formData['email'], $formData['name']);
            
            // Content
            $mail->isHTML(true);
            $mail->Subject = 'New Contact Form Message: ' . $formData['subject'];
            
            // Email body
            $body = '<h2>New Contact Form Message</h2>';
            $body .= '<p><strong>Name:</strong> ' . htmlspecialchars($formData['name']) . '</p>';
            $body .= '<p><strong>Email:</strong> ' . htmlspecialchars($formData['email']) . '</p>';
            if (!empty($formData['phone'])) {
                $body .= '<p><strong>Phone:</strong> ' . htmlspecialchars($formData['phone']) . '</p>';
            }
            $body .= '<p><strong>Subject:</strong> ' . htmlspecialchars($formData['subject']) . '</p>';
            $body .= '<p><strong>Message:</strong></p>';
            $body .= '<div style="padding: 15px; border: 1px solid #ddd; background-color: #f8f9fa; margin-top: 10px;">';
            $body .= nl2br(htmlspecialchars($formData['message']));
            $body .= '</div>';
            
            $mail->Body = $body;
            $mail->AltBody = "Name: {$formData['name']}\nEmail: {$formData['email']}\nPhone: {$formData['phone']}\n\nMessage:\n{$formData['message']}";
            
            $mail->send();
            return true;
        } catch (\Exception $e) {
            // Silently fail but log the error
            error_log('Error sending mail: ' . $e->getMessage());
            return false;
        }
    }
}