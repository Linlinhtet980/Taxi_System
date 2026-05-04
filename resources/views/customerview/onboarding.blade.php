<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to Taxi - Onboarding</title>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary: #6366f1;
            --primary-dark: #4f46e5;
            --bg: #f8fafc;
            --text: #1e293b;
            --text-light: #64748b;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Outfit', sans-serif;
        }

        body {
            background-color: #c9d6ff;
            background: linear-gradient(to right, #e2e2e2, #c9d6ff);
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            overflow: hidden;
        }

        .onboarding-container {
            width: 90%;
            max-width: 400px;
            height: 550px;
            background: white;
            position: relative;
            display: flex;
            flex-direction: column;
            box-shadow: 0 20px 50px rgba(0,0,0,0.1);
            border-radius: 40px;
            overflow: hidden;
        }

        .slider-wrapper {
            flex: 1;
            overflow: hidden;
            width: 100%;
        }

        .slider {
            display: flex;
            transition: transform 0.6s cubic-bezier(0.65, 0, 0.35, 1);
            width: 300%; /* 3 slides */
            height: 100%;
        }

        .slide {
            width: 33.333%;
            padding: 20px 30px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
        }

        .slide-image {
            width: 150px;
            height: 150px;
            background: #f1f5f9;
            border-radius: 50%;
            display: flex;
            justify-content: center;
            align-items: center;
            margin-bottom: 20px;
            color: var(--primary);
            font-size: 50px;
            position: relative;
        }

        .slide-image::after {
            content: '';
            position: absolute;
            width: 110%;
            height: 110%;
            border: 2px dashed #e2e8f0;
            border-radius: 50%;
            animation: rotate 10s linear infinite;
        }

        @keyframes rotate {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }

        .slide h2 {
            font-size: 24px;
            font-weight: 700;
            color: var(--text);
            margin-bottom: 12px;
        }

        .slide p {
            font-size: 15px;
            color: var(--text-light);
            line-height: 1.5;
        }

        .footer {
            padding: 20px 30px 30px;
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .dots {
            display: flex;
            justify-content: center;
            gap: 8px;
            margin-bottom: 25px;
        }

        .dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: #e2e8f0;
            transition: all 0.3s;
        }

        .dot.active {
            width: 24px;
            border-radius: 4px;
            background: var(--primary);
        }

        .btn-next {
            width: 100%;
            padding: 16px;
            background: var(--primary);
            color: white;
            border: none;
            border-radius: 16px;
            font-size: 18px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 10px;
        }

        .btn-next:hover {
            background: var(--primary-dark);
            transform: translateY(-2px);
        }

        .btn-skip {
            background: none;
            border: none;
            color: var(--text-light);
            font-size: 16px;
            font-weight: 500;
            cursor: pointer;
            align-self: center;
        }
    </style>
</head>
<body>
    <div class="onboarding-container">
        <div class="slider-wrapper">
            <div class="slider" id="slider">
                <!-- Slide 1: Location -->
                <div class="slide">
                    <div class="slide-image">
                        <i class="fa-solid fa-map-location-dot"></i>
                    </div>
                    <h2>တည်နေရာရွေးချယ်ပါ</h2>
                    <p>မြေပုံပေါ်မှာဖြစ်စေ၊ ရှာဖွေပြီးဖြစ်စေ သင်သွားလိုတဲ့နေရာနဲ့ ကြိုဆိုရမယ့်နေရာကို လွယ်ကူစွာ ရွေးချယ်နိုင်ပါတယ်။</p>
                </div>
                <!-- Slide 2: Vehicle Selection -->
                <div class="slide">
                    <div class="slide-image" style="color: #10b981;">
                        <i class="fa-solid fa-car-side"></i>
                    </div>
                    <h2>ကားအမျိုးအစားရွေးချယ်ပါ</h2>
                    <p>သင့်စိတ်ကြိုက် ကားအမျိုးအစားနဲ့ ယာဉ်မောင်းကို ရွေးချယ်နိုင်ပြီး ခရီးစဉ်စရိတ်ကိုလည်း ကြိုတင်သိရှိနိုင်ပါတယ်။</p>
                </div>
                <!-- Slide 3: Confirm & Ride -->
                <div class="slide">
                    <div class="slide-image" style="color: #f59e0b;">
                        <i class="fa-solid fa-circle-check"></i>
                    </div>
                    <h2>ခရီးစဉ်စတင်ပါ</h2>
                    <p>အတည်ပြုလိုက်တာနဲ့ အနီးဆုံးယာဉ်မောင်းက သင့်ဆီကို လာရောက်ကြိုဆိုမှာ ဖြစ်ပါတယ်။</p>
                </div>
            </div>
        </div>

        <div class="footer">
            <div class="dots">
                <div class="dot active"></div>
                <div class="dot"></div>
                <div class="dot"></div>
            </div>
            <button class="btn-next" id="nextBtn">
                <span>ဆက်သွားမည်</span>
                <i class="fa-solid fa-arrow-right"></i>
            </button>
            <button class="btn-skip" onclick="window.location.href='{{ route('customer.payment.setup') }}'">ကျော်မည်</button>
        </div>
    </div>

    <script>
        const slider = document.getElementById('slider');
        const nextBtn = document.getElementById('nextBtn');
        const dots = document.querySelectorAll('.dot');
        let currentSlide = 0;

        nextBtn.addEventListener('click', () => {
            if (currentSlide < 2) {
                currentSlide++;
                updateSlider();
            } else {
                window.location.href = "{{ route('customer.payment.setup') }}";
            }
        });

        function updateSlider() {
            slider.style.transform = `translateX(-${currentSlide * 33.333}%)`;
            dots.forEach((dot, index) => {
                dot.classList.toggle('active', index === currentSlide);
            });

            if (currentSlide === 2) {
                nextBtn.querySelector('span').innerText = "စတင်အသုံးပြုမည်";
            } else {
                nextBtn.querySelector('span').innerText = "ဆက်သွားမည်";
            }
        }
    </script>
</body>
</html>
