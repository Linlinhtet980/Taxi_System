<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Personal Profile - Luxury Driver</title>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary: #D4AF37;
            --primary-light: rgba(212, 175, 55, 0.15);
            --bg-dark: #0a0a0a;
            --card-bg: rgba(20, 20, 20, 0.8);
            --text-dim: #94a3b8;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Outfit', sans-serif; }

        body {
            background-color: var(--bg-dark);
            min-height: 100vh;
            overflow-x: hidden;
            color: white;
            margin: 0;
        }

        .split-container {
            display: flex;
            min-height: 100vh;
            width: 100%;
        }

        /* Illustration Side */
        .illustration-side {
            flex: 1;
            background: linear-gradient(135deg, #111 0%, #1a1a1a 100%);
            display: flex;
            flex-direction: column;
            padding: 40px;
            position: sticky;
            top: 0;
            height: 100vh;
            border-right: 1px solid rgba(212, 175, 55, 0.1);
        }

        .brand-badge {
            display: flex;
            align-items: center;
            gap: 12px;
            font-weight: 900;
            letter-spacing: 2px;
            color: var(--primary);
        }

        .illustration-content {
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
        }

        .illustration-box {
            width: 80%;
            max-width: 400px;
            margin-bottom: 40px;
            filter: drop-shadow(0 20px 50px rgba(0,0,0,0.5));
        }

        .illustration-box img {
            width: 100%;
            border-radius: 30px;
        }

        .illustration-text h2 {
            font-size: 2rem;
            font-weight: 800;
            margin-bottom: 15px;
            background: linear-gradient(to right, #fff, var(--primary));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .illustration-text p { color: var(--text-dim); max-width: 400px; line-height: 1.6; font-size: 0.9rem; }

        /* Form Side */
        .form-side {
            flex: 1.5;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 60px 40px;
            background: radial-gradient(circle at top right, var(--primary-light), transparent);
        }

        .step-progress-wrapper {
            display: flex;
            align-items: center;
            gap: 40px;
            width: 100%;
            max-width: 750px;
        }

        .auth-card {
            flex: 1;
            background: var(--card-bg);
            backdrop-filter: blur(20px);
            padding: 40px;
            border-radius: 35px;
            border: 1px solid rgba(255,255,255,0.05);
            box-shadow: 0 25px 50px rgba(0,0,0,0.4);
        }

        .avatar-upload-wrapper {
            width: 100px;
            height: 100px;
            margin: 0 auto 20px;
            position: relative;
            cursor: pointer;
        }

        .avatar-preview {
            width: 100%;
            height: 100%;
            border-radius: 50%;
            background: rgba(255,255,255,0.05);
            border: 2px dashed var(--primary);
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            transition: 0.3s;
        }

        .avatar-preview i { font-size: 30px; color: var(--primary); }
        .avatar-preview img { width: 100%; height: 100%; object-fit: cover; }
        .avatar-upload-wrapper:hover .avatar-preview { background: rgba(212, 175, 55, 0.1); }

        .avatar-upload-wrapper input {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            opacity: 0;
            cursor: pointer;
        }

        .auth-header h1 { font-size: 1.8rem; margin-bottom: 5px; font-weight: 800; }
        .auth-header p { color: var(--primary); font-size: 0.8rem; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; }

        .grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }
        @media (max-width: 600px) { .grid-2 { grid-template-columns: 1fr; } }

        .section-divider {
            margin: 30px 0 20px;
            font-size: 0.75rem;
            font-weight: 800;
            color: var(--text-dim);
            text-transform: uppercase;
            letter-spacing: 2px;
            display: flex;
            align-items: center;
            gap: 15px;
        }
        .section-divider::after { content: ''; flex: 1; height: 1px; background: rgba(255,255,255,0.05); }

        .form-group { margin-bottom: 20px; text-align: left; }
        .form-group label { display: block; font-size: 0.75rem; font-weight: 700; color: var(--text-dim); margin-bottom: 8px; text-transform: uppercase; }

        .input-wrapper { position: relative; display: flex; align-items: center; }
        .input-wrapper i { position: absolute; left: 15px; color: var(--primary); font-size: 1rem; }
        .input-wrapper input {
            width: 100%;
            background: rgba(255,255,255,0.03);
            border: 1px solid rgba(255,255,255,0.1);
            padding: 14px 15px 14px 45px;
            border-radius: 14px;
            color: white;
            font-size: 0.9rem;
            outline: none;
            transition: 0.3s;
        }
        .input-wrapper input:focus { border-color: var(--primary); background: rgba(255,255,255,0.06); }

        .file-drop-area {
            position: relative;
            background: rgba(255,255,255,0.03);
            border: 1px dashed rgba(212, 175, 55, 0.4);
            border-radius: 14px;
            padding: 15px;
            text-align: center;
            transition: 0.3s;
            cursor: pointer;
            overflow: hidden;
            min-height: 90px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }
        .file-drop-area:hover { background: rgba(212, 175, 55, 0.05); border-color: var(--primary); }
        .file-drop-area i { font-size: 20px; color: var(--primary); margin-bottom: 5px; display: block; }
        .file-drop-area span { font-size: 10px; color: var(--text-dim); font-weight: 700; text-transform: uppercase; z-index: 2; position: relative; }
        .file-drop-area input { position: absolute; top: 0; left: 0; width: 100%; height: 100%; opacity: 0; cursor: pointer; z-index: 3; }
        .preview-img {
            position: absolute;
            top: 0; left: 0; width: 100%; height: 100%;
            object-fit: cover;
            z-index: 1;
            border-radius: 12px;
        }

        .btn-continue {
            width: 100%;
            background: var(--primary);
            color: #000;
            border: none;
            padding: 18px;
            border-radius: 15px;
            font-weight: 900;
            font-size: 1rem;
            cursor: pointer;
            transition: 0.3s;
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 12px;
            margin-top: 20px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .btn-continue:hover { transform: translateY(-3px); box-shadow: 0 15px 30px rgba(212, 175, 55, 0.3); }

        /* Vertical Progress */
        .vertical-progress { height: 300px; width: 2px; position: relative; }
        .progress-line { height: 100%; width: 100%; background: rgba(255,255,255,0.1); position: relative; border-radius: 2px; }
        .line-fill { position: absolute; top: 0; left: 0; width: 100%; background: var(--primary); box-shadow: 0 0 10px var(--primary); transition: 0.5s; }
        .progress-step { position: absolute; left: 50%; transform: translate(-50%, -50%); width: 16px; height: 16px; background: #222; border: 2px solid rgba(255,255,255,0.2); border-radius: 50%; z-index: 2; display: flex; align-items: center; justify-content: center; }
        .progress-step.active { background: var(--primary); border-color: var(--primary); box-shadow: 0 0 15px var(--primary); }
        .step-label { position: absolute; right: 30px; text-align: right; white-space: nowrap; color: var(--text-dim); font-size: 0.75rem; font-weight: 800; text-transform: uppercase; letter-spacing: 1px; }
        .progress-step.active .step-label { color: white; }

        @media (max-width: 1100px) {
            .illustration-side { display: none; }
            .vertical-progress { display: none; }
            .form-side { background: var(--bg-dark); }
        }

        .animate-fade { animation: fadeIn 0.8s ease-out; }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
    </style>
</head>
<body>

    <div class="split-container">
        <!-- Left Side: Illustration Area -->
        <div class="illustration-side">
            <div class="brand-badge">
                <i class="fa-solid fa-taxi"></i>
                <span>TAXI SERVICE</span>
            </div>
            <div class="illustration-content">
                <div class="illustration-box">
                    <img src="https://img.freepik.com/free-vector/yellow-taxi-car-cab-with-driver-character_107791-16834.jpg" alt="Luxury Taxi">
                </div>
                <div class="illustration-text">
                    <h2>Identity & Verification</h2>
                    <p>We need a few more details to verify your identity and ensure a safe experience for everyone.</p>
                </div>
            </div>
        </div>

        <!-- Right Side: Form Area -->
        <div class="form-side">
            <div class="step-progress-wrapper">
                <div class="auth-card animate-fade">
                    <div class="auth-header" style="text-align: center; margin-bottom: 30px;">
                        <div class="avatar-upload-wrapper">
                            <div class="avatar-preview" id="imagePreview">
                                <i class="fa-solid fa-camera"></i>
                            </div>
                            <input type="file" name="profile_picture" id="profile_picture" accept="image/*" form="personalForm">
                        </div>
                        <h1>Personal Profile</h1>
                        <p>Step 2: Identity Documents</p>
                    </div>

                    @if($errors->any())
                        <div class="error-badge">
                            <i class="fa-solid fa-circle-exclamation"></i> {{ $errors->first() }}
                        </div>
                    @endif

                    <form action="{{ route('driver.onboarding.personal.store') }}" method="POST" enctype="multipart/form-data" id="personalForm">
                        @csrf
                        
                        <div class="grid-2">
                            <div class="form-group">
                                <label>Phone Number</label>
                                <div class="input-wrapper">
                                    <i class="fa-solid fa-phone"></i>
                                    <input type="tel" name="phone_no" id="phone_no" placeholder="09xxxxxxxxx" value="{{ old('phone_no', $driver->phone_no) }}" required minlength="7" maxlength="11" pattern="[0-9]+" title="Phone number should contain only digits (7 to 11 digits)." oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Emergency Contact Number</label>
                                <div class="input-wrapper">
                                    <i class="fa-solid fa-phone-flip"></i>
                                    <input type="tel" name="emergency_contact_no" id="emergency_contact_no" placeholder="09xxxxxxxxx" value="{{ old('emergency_contact_no') }}" minlength="7" maxlength="11" pattern="[0-9]+" title="Emergency contact should contain only digits (7 to 11 digits)." oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                                </div>
                            </div>
                        </div>

                        <div class="grid-2">
                            <div class="form-group">
                                <label>NRIC Number</label>
                                <div class="input-wrapper">
                                    <i class="fa-solid fa-id-card"></i>
                                    <input type="text" name="identity_card_no" id="identity_card_no" placeholder="e.g. 12/LATHANA(N)123456" required pattern="\d{1,2}/[a-zA-Z]+\([Nn]\)\d{6}" title="Please match Myanmar NRIC format (e.g. 12/LATHANA(N)123456).">
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Driver License Number</label>
                                <div class="input-wrapper">
                                    <i class="fa-solid fa-address-card"></i>
                                    <input type="text" name="license_no" id="license_no" placeholder="e.g. B/12345/21" required pattern="[a-zA-Z0-9/]+" title="Please enter a valid driver license number (letters, numbers and slashes only).">
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label>Current Address</label>
                            <div class="input-wrapper">
                                <i class="fa-solid fa-location-dot"></i>
                                <input type="text" name="address" id="address" placeholder="Full Address" required value="{{ old('address') }}">
                            </div>
                        </div>

                        <div class="section-divider">Document Uploads</div>

                        <div class="grid-2">
                            <div class="form-group">
                                <label>License (Front)</label>
                                <div class="file-drop-area">
                                    <i class="fa-solid fa-cloud-arrow-up"></i>
                                    <span>Upload</span>
                                    <input type="file" name="license_photo" accept="image/*" required>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>NRIC (Front)</label>
                                <div class="file-drop-area">
                                    <i class="fa-solid fa-cloud-arrow-up"></i>
                                    <span>Upload</span>
                                    <input type="file" name="nric_photo" accept="image/*" required>
                                </div>
                            </div>
                        </div>

                        <button type="submit" class="btn-continue">Save and Continue <i class="fa-solid fa-arrow-right"></i></button>
                    </form>
                </div>

                <!-- Vertical Progress Indicator (Minimalist on Right) -->
                <div class="vertical-progress">
                    <div class="progress-line">
                        <div class="line-fill" style="height: 66%;"></div>
                        <div class="progress-step active" style="top: 0;">
                            <i class="fa-solid fa-check" style="font-size: 8px; color: #000;"></i>
                        </div>
                        <div class="progress-step active" style="top: 50%;"></div>
                        <div class="progress-step" style="top: 100%;"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('profile_picture').onchange = function (evt) {
            const [file] = this.files;
            if (file) {
                const preview = document.getElementById('imagePreview');
                preview.innerHTML = `<img src="${URL.createObjectURL(file)}" alt="Preview">`;
            }
        };

        // Live Preview for all document drop areas
        document.querySelectorAll('.file-drop-area input[type="file"]').forEach(input => {
            input.addEventListener('change', function() {
                const file = this.files[0];
                if (file) {
                    let dropArea = this.closest('.file-drop-area');
                    let existingImg = dropArea.querySelector('.preview-img');
                    if (!existingImg) {
                        existingImg = document.createElement('img');
                        existingImg.className = 'preview-img';
                        dropArea.appendChild(existingImg);
                    }
                    existingImg.src = URL.createObjectURL(file);
                    let span = dropArea.querySelector('span');
                    if (span) span.style.opacity = '0'; // Hide 'Upload' text neatly to show image fully
                    let icon = dropArea.querySelector('i');
                    if (icon) icon.style.opacity = '0';
                }
            });
        });

        // Live Validation for Form Fields
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('personalForm');
            const phoneNo = document.getElementById('phone_no');
            const emergencyNo = document.getElementById('emergency_contact_no');
            const nric = document.getElementById('identity_card_no');
            const licenseNo = document.getElementById('license_no');

            // Help elements
            const phoneError = createErrorElement(phoneNo);
            const emergencyError = createErrorElement(emergencyNo);
            const nricError = createErrorElement(nric);
            const licenseError = createErrorElement(licenseNo);

            function createErrorElement(target) {
                const p = document.createElement('p');
                p.style.color = '#dc3545';
                p.style.fontSize = '0.75rem';
                p.style.marginTop = '5px';
                p.style.display = 'none';
                target.parentNode.insertAdjacentElement('afterend', p);
                return p;
            }

            function validatePhone() {
                const val = phoneNo.value;
                if (val && (val.length < 7 || val.length > 11)) {
                    phoneError.textContent = 'Phone number must be between 7 and 11 digits.';
                    phoneError.style.display = 'block';
                    return false;
                } else {
                    phoneError.style.display = 'none';
                    return true;
                }
            }

            function validateEmergency() {
                const val = emergencyNo.value;
                if (val && (val.length < 7 || val.length > 11)) {
                    emergencyError.textContent = 'Emergency phone number must be between 7 and 11 digits.';
                    emergencyError.style.display = 'block';
                    return false;
                } else {
                    emergencyError.style.display = 'none';
                    return true;
                }
            }

            function validateNRIC() {
                const val = nric.value.trim();
                const regex = /^\d{1,2}\/[a-zA-Z]+\([Nn]\)\d{6}$/;
                if (val && !regex.test(val)) {
                    nricError.textContent = 'Must match Myanmar NRIC (e.g. 12/LATHANA(N)123456).';
                    nricError.style.display = 'block';
                    return false;
                } else {
                    nricError.style.display = 'none';
                    return true;
                }
            }

            function validateLicense() {
                const val = licenseNo.value.trim();
                const regex = /^[a-zA-Z0-9\/]+$/;
                if (val && !regex.test(val)) {
                    licenseError.textContent = 'License number must only contain letters, numbers, and slashes.';
                    licenseError.style.display = 'block';
                    return false;
                } else {
                    licenseError.style.display = 'none';
                    return true;
                }
            }

            phoneNo.addEventListener('input', validatePhone);
            emergencyNo.addEventListener('input', validateEmergency);
            nric.addEventListener('input', validateNRIC);
            licenseNo.addEventListener('input', validateLicense);

            form.addEventListener('submit', function(e) {
                const isPhoneValid = validatePhone();
                const isEmergValid = validateEmergency();
                const isNRICValid = validateNRIC();
                const isLicenseValid = validateLicense();

                if (!isPhoneValid || !isEmergValid || !isNRICValid || !isLicenseValid) {
                    e.preventDefault();
                    if (!isPhoneValid) phoneNo.focus();
                    else if (!isEmergValid) emergencyNo.focus();
                    else if (!isNRICValid) nric.focus();
                    else if (!isLicenseValid) licenseNo.focus();
                }
            });
        });
    </script>
</body>
</html>
