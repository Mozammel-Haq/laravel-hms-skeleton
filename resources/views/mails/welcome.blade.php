<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to Our Community</title>
    <style>
        /* Reset styles for email clients */
        body, html {
            margin: 0;
            padding: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333333;
            background-color: #f5f7fa;
        }

        /* Container */
        .container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
        }

        /* Header */
        .header {
            background: linear-gradient(135deg, #6a11cb 0%, #2575fc 100%);
            color: white;
            padding: 30px 20px;
            text-align: center;
        }

        .logo {
            font-size: 28px;
            font-weight: bold;
            margin-bottom: 10px;
        }

        .welcome-text {
            font-size: 24px;
            margin: 0 0 10px;
            font-weight: 600;
        }

        .subtitle {
            font-size: 16px;
            opacity: 0.9;
            margin: 0;
        }

        /* Content */
        .content {
            padding: 40px 30px;
            text-align: center;
        }

        .greeting {
            font-size: 22px;
            color: #2c3e50;
            margin-bottom: 20px;
        }

        .message {
            color: #555555;
            line-height: 1.8;
            margin-bottom: 30px;
            font-size: 16px;
        }

        .cta-button {
            display: inline-block;
            background-color: #3498db;
            color: white !important;
            text-decoration: none;
            padding: 14px 30px;
            border-radius: 30px;
            font-weight: 600;
            font-size: 16px;
            margin: 20px 0;
            transition: all 0.3s ease;
            box-shadow: 0 4px 8px rgba(52, 152, 219, 0.3);
        }

        .cta-button:hover {
            background-color: #2980b9;
            transform: translateY(-2px);
            box-shadow: 0 6px 12px rgba(52, 152, 219, 0.4);
        }

        /* Features */
        .features {
            display: flex;
            justify-content: space-around;
            flex-wrap: wrap;
            margin: 30px 0;
        }

        .feature {
            flex: 1;
            min-width: 150px;
            padding: 15px;
            margin: 10px;
        }

        .feature-icon {
            font-size: 36px;
            margin-bottom: 15px;
            color: #3498db;
        }

        .feature-title {
            font-weight: 600;
            margin: 10px 0;
            color: #2c3e50;
        }

        /* Footer */
        .footer {
            background-color: #f8f9fa;
            padding: 25px 30px;
            text-align: center;
            color: #7f8c8d;
            font-size: 14px;
        }

        .social-links {
            margin: 15px 0;
        }

        .social-icon {
            display: inline-block;
            margin: 0 8px;
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background-color: #e0e7ff;
            color: #3498db;
            line-height: 36px;
            text-align: center;
            text-decoration: none;
        }

        /* Responsive adjustments */
        @media (max-width: 600px) {
            .header {
                padding: 25px 15px;
            }

            .content {
                padding: 30px 20px;
            }

            .features {
                flex-direction: column;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <div class="logo">YourBrand</div>
            <h1 class="welcome-text">Welcome Aboard!</h1>
            <p class="subtitle">We're thrilled to have you join our community</p>
        </div>

        <!-- Content -->
        <div class="content">
            <h2 class="greeting">Hello {{$user->name}},</h2>

            <p class="message">
                Thank you for signing up with us! We're excited to have you as part of our growing community.
                Get ready to experience amazing features and services designed just for you.
            </p>

            <a href="{{ 'mozammel.infy.uk' }}" class="cta-button">Get Started</a>

            <div class="features">
                <div class="feature">
                    <div class="feature-icon">🚀</div>
                    <h3 class="feature-title">Quick Setup</h3>
                    <p>Get started in minutes with our intuitive onboarding process</p>
                </div>

                <div class="feature">
                    <div class="feature-icon">🔒</div>
                    <h3 class="feature-title">Secure</h3>
                    <p>Enterprise-grade security to protect your data</p>
                </div>

                <div class="feature">
                    <div class="feature-icon">💡</div>
                    <h3 class="feature-title">Smart Tools</h3>
                    <p>Powerful features to help you achieve your goals</p>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p>Follow us for updates and tips</p>
            <div class="social-links">
                <a href="#" class="social-icon">f</a>
                <a href="#" class="social-icon">t</a>
                <a href="#" class="social-icon">in</a>
                <a href="#" class="social-icon">ig</a>
            </div>
            <p>© 2025 CityCare Hospital. All rights reserved.</p>
            <p>123 Business Ave, Suite 100, City, Country</p>
        </div>
    </div>
</body>
</html>
