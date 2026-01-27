<?php /* Template Name: Avatar Page Template */ ?>

<?php get_header(); ?>

<div class='main-mid'>
	<div class='_maxwrap'>

	<!DOCTYPE html>
<html>
<head>
    <title>Avatar Customization</title>
    <style>
      .rewards-stats-wrapper {
    display: flex;
    justify-content: center;
    gap: 20px;
    width: 100%;
    margin-bottom: 20px;
}

.stat-block {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 8px;
}

.stat-label {
    font-size: 15px;
    display: flex;
    align-items: center;
    gap: 5px;
    text-align: center;
}

.credits-display {
    display: flex;
    align-items: center;
    gap: 10px;
    font-size: 20px;
    font-weight: 600;
    color: #333;
    background-color: white;
    padding: 15px 20px;
    border-radius: 10px;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.08);
    width: fit-content;
    transition: opacity 0.2s ease;
}

.credits-display:hover {
    opacity: 0.85;
}

@media (max-width: 768px) {
    .rewards-stats-wrapper {
        flex-direction: column;
        gap: 15px;
    }
}
         /* Hide footer content during loading */
        footer.x-colophon.top {
            visibility: hidden !important;
            display: none !important;
        }
           /* Show footer only after loading is complete */
        .loading-complete footer.x-colophon.top {
            visibility: visible !important;
            display: block !important;
        }
        .loading-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: white !important;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            z-index: 9999;
        }
        
        .loading-overlay.hide {
            opacity: 0;
            pointer-events: none;
        }
        
        .loading-spinner {
            width: 60px;
            height: 60px;
            border: 6px solid #f3f3f3;
            border-top: 6px solid #ff6b35;
            border-radius: 50%;
            animation: spin 1s linear infinite;
            margin-bottom: 20px;
        }
        
        .loading-text {
            font-size: 18px;
            color: #333;
            font-family: 'Poppins', sans-serif;
            animation: blink 1.2s infinite;
        }

        @keyframes blink {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.4; }
        }
        
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        
        #saveAvatarBtn {
            display: none;
        }
        
        .selected {
            margin-top: 6px !important;
        }
        
        .message-container {
            background-color: white;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.3);
            text-align: center;
            max-width: 400px;
            width: 90%;
            display: none;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }
        
        .message-container.show {
            display: flex;
        }
        
        .modal-title {
            font-size: 31.99px;
            font-weight: 500;
            font-family: 'Poppins', sans-serif;
            font-style: normal;
            margin-bottom: 20px;
            color: #333;
        }
        
        .main-container {
            gap: 30px;
            max-width: 1400px;
            width: 100%;
            background: white;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            padding: 30px;
            margin: 0 auto;
            display: none;
            opacity: 0;
            transition: opacity 0.5s ease;
        }
        
        .main-container.show {
            display: flex;
            opacity: 1;
        }
        
        .avatar-section {
            flex: 0 0 400px;
            padding: 20px;
            background: #f7f7f7;
            border-radius: 15px;
            box-shadow: inset 0 2px 5px rgba(0, 0, 0, 0.05);
            display: flex;
            flex-direction: column;
            align-items: center;
            position: relative;
        }
        
        .avatar-header {
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: flex-start;
            margin-bottom: 20px;
            position: relative;
        }
        
        .avatar-back-button {
            background-color: #f90 !important;
            color: white !important;
            border: none !important;
            border-radius: 8px !important;
            padding: 8px 16px !important;
            font-size: 14px !important;
            font-weight: bold !important;
            font-family: 'Poppins', sans-serif !important;
            cursor: pointer !important;
            transition: all 0.3s ease !important;
            white-space: nowrap !important;
            min-width: 80px !important;
            position: absolute;
            left: 0;
            top: 0;
        }
        
        .avatar-back-button:hover {
            background-color: #e55a2b !important;
            transform: translateY(-1px) !important;
        }
        
        .avatar-title {
            font-size: 31.99px;
            font-weight: 500;
            font-family: 'Poppins', sans-serif;
            font-style: normal;
            color: #333;
            text-align: center;
            width: 100%;
            margin: 40px 0 0 0;
        }
        
        .avatar-display {
            width: 250px;
            height: 380px;
            margin-bottom: 20px;
            position: relative;
            overflow: hidden;
            display: flex;
            justify-content: center;
            align-items: center;
        }
      @media (max-width: 768px) {
    .avatar-display {
        height: 300px !important;
    }
}

        
        .avatar-character {
            width: 100%;
            height: 100%;
            position: absolute;
            top: 10;
            left: 0;
            background: url('https://firebasestorage.googleapis.com/v0/b/lote4kids-gamification.firebasestorage.app/o/default_body.png?alt=media&token=57faff74-435c-43ba-8871-2ae533debcc8') no-repeat center;
            background-size: contain;
            transition: transform 0.3s ease-out;
        }
        
        .avatar-item-layer {
            position: absolute;
            width: 100%;
            height: 100%;
            background-size: contain;
            background-position: center;
            background-repeat: no-repeat;
            pointer-events: none;
        }
        
        .credits-display {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 30px;
            font-size: 20px;
            font-weight: 600;
            color: #333;
            background-color: white;
            padding: 15px 20px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.08);
            width: fit-content;
        }
        
        .credits-display img {
            width: 24px;
            height: 24px;
        }
        
        .category-tabs {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 10px;
            width: 100%;
            margin-bottom: 30px;
        }
        
        .category-tab {
            background: #f90;
            color: white;
            padding: 15px;
            border-radius: 10px;
            text-align: center;
            cursor: pointer;
            font-weight: 600;
            transition: background 0.3s, transform 0.2s;
            box-shadow: 0 4px 10px rgba(255, 153, 0, 0.3);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            border: none;
        }
        
        .category-tab img {
            width: 24px;
            height: 24px;
        }
        
        .category-tab:hover {
            background: #f90;
            transform: translateY(-2px);
        }
        
        .category-tab.active {
            background: #f90;
            box-shadow: 0 2px 5px rgba(255, 153, 0, 0.5);
            transform: translateY(0);
        }
        
        .unlocked-items {
            width: 100%;
        }
        
        .unlocked-title {
            font-family: 'Poppins', sans-serif;
            font-size: 15px; 
            font-style: normal;
            margin-bottom: 15px;
            display: flex;
            align-items: center;
            gap: 10px;
            color: #333;
        }
        
        .unlocked-title span {
            background: #f90;
            color: white;
            padding: 2px 8px;
            border-radius: 15px;
            font-size: 14px;
        }
        
        .unlocked-grid {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }
        
        .unlocked-item {
            width: 70px;
            height: 70px;
            border: 3px solid #ddd;
            border-radius: 12px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: border-color 0.3s, transform 0.2s;
            background-size: contain;
            background-position: center;
            background-repeat: no-repeat;
            position: relative;
        }
        
        .unlocked-item:hover {
            transform: translateY(-2px);
        }
        
        .unlocked-item.active {
            border-color: #28a745;
            box-shadow: 0 0 0 3px rgba(40, 167, 69, 0.3);
        }
        
        .unlocked-item-card {
            margin-top: 10px !important;
        }
        
        .remove-item-btn {
            position: absolute;
            top: -5px;
            right: -5px;
            width: 20px;
            height: 20px;
            background: #ff4757;
            color: white;
            border: none;
            border-radius: 50%;
            cursor: pointer;
            font-size: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            opacity: 0;
            transition: opacity 0.3s;
        }
        
        .unlocked-item:hover .remove-item-btn {
            opacity: 1;
        }
        
        .items-section {
            flex: 1;
            background: white;
            border-radius: 15px;
            padding: 30px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            display: flex;
            flex-direction: column;
        }
        
        .category-header {
            display: flex;
            justify-content: center;
            align-items: center;
            margin-bottom: 30px;
        }
        
        .category-title {
            font-size: 31.99px;
            font-weight: 500;
            font-family: 'Poppins', sans-serif;
            font-style: normal;
            color: #333;
            text-align: center;
            background: none;
            margin: 0;
            flex: 1;
        }
        
        .items-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(120px, 1fr));
            gap: 20px;
            max-height: 800px;
            overflow-y: auto;
            padding-right: 10px;
        }
        
        .items-grid::-webkit-scrollbar {
            width: 8px;
        }
        
        .items-grid::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 10px;
        }
        
        .items-grid::-webkit-scrollbar-thumb {
            background: #888;
            border-radius: 10px;
        }
        
        .items-grid::-webkit-scrollbar-thumb:hover {
            background: #555;
        }
        
        .item-card {
            background: #fcfcfc;
            border-radius: 15px;
            padding: 15px;
            text-align: center;
            cursor: pointer;
            transition: transform 0.2s, box-shadow 0.2s, border-color 0.2s;
            border: 2px solid #eee;
            position: relative;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            align-items: center;
            min-height: 160px;
        }
        
        .item-card:hover {
            margin-top: 5px;
            transform: translateY(-5px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
            border-color: #ff6b35;
        }
        
        .item-card.selected {
            border-color: #ff6b35;
            box-shadow: 0 0 0 4px rgba(255, 107, 53, 0.4);
            transform: translateY(-5px);
        }
        
        .item-card.locked {
            opacity: 0.6;
            position: relative;
        }
        
        .item-card.locked::after {
            content: '🔒';
            position: absolute;
            top: 10px;
            right: 10px;
            font-size: 20px;
            z-index: 3;
        }
        
        .item-preview-container {
            position: absolute;
            top: -40;
            left: 0;
            width: 100%;
            height: 100%;
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 1;
            overflow: hidden;
        }
        
        .item-preview-background {
            position: absolute;
            width: 100px;
            height: 100px;
            background-size: contain;
            filter: blur(3px) brightness(0.8);
            transform: scale(1.2);
        }
        
        .item-image {
            width: 90px;
            height: 90px;
            object-fit: contain;
            margin-bottom: 10px;
            position: relative;
            z-index: 2;
        }
        
        .item-price {
            background: white;
            color: black;
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 13px;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 5px;
            position: relative;
            z-index: 2;
            margin-top: auto;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }
        
        .item-price img {
            width: 16px;
            height: 16px;
        }
        
        .purchase-btn {
            background: #ff6b35;
            color: white;
            border: none;
            border: none;
            padding: 6px 12px;
            border-radius: 6px;
            font-size: 12px;
            font-weight: 400;
            font-family: 'Poppins', sans-serif;
            cursor: pointer;
            margin-top: 8px;
            transition: all 0.3s ease;
            z-index: 2;
            position: relative;
        }
        
        .purchase-btn:hover {
            background: #e55a2b;
            transform: translateY(-1px);
        }
        
        .purchase-btn:disabled {
            background: #6c757d;
            cursor: not-allowed;
            transform: none;
        }
        
        .save-avatar-btn {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            padding: 15px 30px;
            border-radius: 10px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            width: 100%;
            margin-top: 20px;
            transition: all 0.3s ease;
        }
        
        .save-avatar-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }
        
        .loading {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 200px;
        }
        
        .spinner {
            width: 50px;
            height: 50px;
            border: 5px solid #f3f3f3;
            border-top: 5px solid #ff6b35;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }
        
        .notification {
            position: fixed;
            top: 20px;
            right: 20px;
            padding: 15px 20px;
            border-radius: 10px;
            color: white;
            font-weight: bold;
            z-index: 1000;
            transition: all 0.3s ease;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }
        
        .notification.success {
            background: #28a745;
        }
        
        .notification.error {
            background: #dc3545;
        }
        
        .notification.info {
            background: #17a2b8;
        }
        
        .confirmation-popup {
            font-family: 'Poppins', sans-serif;
        }
        
        .confirmation-popup .modal-title {
            font-size: 15px;
            font-weight: 400;
        }
        
        .confirmation-popup .modal-text {
            font-size: 15px;
            font-weight: 400;
            font-family: 'Poppins', sans-serif;
        }
        
        .yes-no-buttons {
            font-weight: 400;
            font-family: 'Poppins', sans-serif;
        }
        
        .ok-button {
            border: none;
            background: #007bff;
            color: white;
            padding: 10px 20px;
            border-radius: 8px;
            font-weight: 400;
            font-family: 'Poppins', sans-serif;
        }
        
        .empty-unlocked-text {
            font-family: 'Poppins', sans-serif;
            font-size: 14px;
            color: #666;
            text-align: center;
            padding: 20px;
        }
        
        .language-modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.7);
            display: none;
            justify-content: center;
            align-items: center;
            z-index: 1001;
            opacity: 0;
            visibility: hidden;
            transition: opacity 0.3s ease, visibility 0.3s ease;
        }
        
        .language-modal-overlay.show {
            opacity: 1;
            visibility: visible;
        }
        
        .language-modal-content {
            background: white;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
            max-width: 600px;
            width: 90%;
            text-align: center;
            position: relative;
            transform: translateY(-20px);
            transition: transform 0.3s ease;
        }
        
        .language-modal-overlay.show .language-modal-content {
            transform: translateY(0);
        }
        
        .language-options-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(120px, 1fr));
            gap: 15px;
            margin-top: 20px;
            margin-bottom: 30px;
            max-height: 350px;
            overflow-y: auto;
            padding-right: 10px;
        }
        
        .language-options-grid::-webkit-scrollbar {
            width: 8px;
        }
        
        .language-options-grid::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 10px;
        }
        
        .language-options-grid::-webkit-scrollbar-thumb {
            background: #888;
            border-radius: 10px;
        }
        
        .language-options-grid::-webkit-scrollbar-thumb:hover {
            background: #555;
        }
        
        .language-option-button {
            background: #f0f0f0;
            border: 2px solid #eee;
            border-radius: 10px;
            padding: 15px 10px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 8px;
            font-size: 16px;
            font-weight: 600;
            color: #333;
            cursor: pointer;
            transition: all 0.2s ease;
        }
        
        .language-option-button img {
            width: 40px;
            height: 40px;
            border-radius: 5px;
            object-fit: cover;
        }
        
        .language-option-button:hover {
            background: #e0e0e0;
            border-color: #ff6b35;
            transform: translateY(-2px);
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }
        
        .language-option-button.selected {
            background: #ff6b35;
            color: white;
            border-color: #d4491f;
            box-shadow: 0 0 0 4px rgba(255, 107, 53, 0.4);
        }
        
        .close-modal-btn {
            background: #6c757d;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.3s ease;
        }
        
        .close-modal-btn:hover {
            background: #5a6268;
        }
        
        @media (max-width: 992px) {
            .main-container {
                flex-direction: column;
                padding: 20px;
            }
            .avatar-section {
                flex: none;
                width: 100%;
                margin-bottom: 20px;
            }
            .items-section {
                width: 100%;
            }
            .category-tabs {
                grid-template-columns: repeat(3, 1fr);
            }
            .category-header {
                flex-direction: column;
                gap: 15px;
                align-items: center;
            }
            .category-title {
                text-align: center;
            }
        }
        
        @media (max-width: 768px) {
            .category-tabs {
                grid-template-columns: repeat(2, 1fr);
            }
            .items-grid {
                grid-template-columns: repeat(auto-fill, minmax(100px, 1fr));
                gap: 15px;
            }
            .language-options-grid {
                grid-template-columns: repeat(auto-fill, minmax(100px, 1fr));
            }
            .category-header {
                margin-bottom: 20px;
            }
            .avatar-back-button {
                font-size: 12px !important;
                padding: 6px 12px !important;
                min-width: 70px !important;
            }
        }
        
        @media (max-width: 480px) {
            .category-tabs {
                grid-template-columns: 1fr;
            }
            .avatar-display {
                width: 200px;
                height: 200px;
            }
            .item-card {
                padding: 10px;
                min-height: 140px;
            }
            .item-image {
                width: 70px;
                height: 70px;
            }
            .main-container {
                padding: 15px;
            }
            .items-section {
                padding: 20px;
            }
            .category-header {
                margin-bottom: 15px;
            }
            .category-title {
                font-size: 24px !important;
            }
            .avatar-back-button {
                font-size: 11px !important;
                padding: 5px 10px !important;
                min-width: 60px !important;
            }
        }
        
        .equip-btn {
            background: #f90;
            color: white;
            border: none;
            padding: 6px 12px;
            border-radius: 6px;
            font-size: 12px;
            font-weight: 400;
            font-family: 'Poppins', sans-serif;
            cursor: pointer;
            margin-top: 8px;
            transition: all 0.3s ease;
            z-index: 2;
            position: relative;
        }
        
        .equip-btn:hover {
            background: #f90;
            transform: translateY(-1px);
        }
        
        .equip-btn.unequip {
            background: #6c757d;
        }
        
        .equip-btn.unequip:hover {
            background: #6c757d
        }
        
        .no-border-btn {
            border: none !important;
            outline: none !important;
            box-shadow: none !important;
        }
    </style>
</head>
<body>
    <!-- Loading Overlay -->
    <div id="loadingOverlay" class="loading-overlay">
        <div class="loading-spinner"></div>
        <div class="loading-text">Loading Dress your Avatar</div>
    </div>

    <div id="messageContainer" class="message-container">
        <h2 class="modal-title" id="messageTitle"></h2>
        <p id="messageText"></p>
    </div>
    
    <div id="languageModalOverlay" class="language-modal-overlay">
        <div id="languageModalContent" class="language-modal-content">
            <h2 class="modal-title">Select Your Language</h2>
            <div class="language-options-grid">
                <button class="language-option-button" data-lang="af">Afrikaans</button>
                <button class="language-option-button" data-lang="ase">American Sign Language</button>
                <button class="language-option-button" data-lang="am">Amharic</button>
                <button class="language-option-button" data-lang="ar">Arabic</button>
                <button class="language-option-button" data-lang="hy">Armenian</button>
                <button class="language-option-button" data-lang="as">Assamese</button>
                <button class="language-option-button" data-lang="aig">Assyrian</button>
                <button class="language-option-button" data-lang="asl">Australian Sign Language</button>
                <button class="language-option-button" data-lang="bn">Bengali</button>
                <button class="language-option-button" data-lang="bfi">British Sign Language</button>
                <button class="language-option-button" data-lang="my">Burmese</button>
                <button class="language-option-button" data-lang="fr-ca">Canadian French</button>
                <button class="language-option-button" data-lang="yue">Chinese (Cantonese)</button>
                <button class="language-option-button" data-lang="zh-hans">Chinese (Mandarin Simplified)</button>
                <button class="language-option-button" data-lang="zh-hant">Chinese (Mandarin Traditional)</button>
                <button class="language-option-button" data-lang="zh-latn">Chinese (Pinyin)</button>
                <button class="language-option-button" data-lang="mi-ck">Cook Islands Māori</button>
                <button class="language-option-button" data-lang="kw">Cornish</button>
                <button class="language-option-button" data-lang="hr">Croatian</button>
                <button class="language-option-button" data-lang="cs">Czech</button>
                <button class="language-option-button" data-lang="da">Danish</button>
                <button class="language-option-button" data-lang="prs">Dari</button>
                <button class="language-option-button" data-lang="nl">Dutch</button>
                <button class="language-option-button" data-lang="eo">Esperanto</button>
                <button class="language-option-button" data-lang="et">Estonian</button>
                <button class="language-option-button" data-lang="fil">Filipino (Tagalog)</button>
                <button class="language-option-button" data-lang="fi">Finnish</button>
                <button class="language-option-button" data-lang="fr">French</button>
                <button class="language-option-button" data-lang="ka">Georgian</button>
                <button class="language-option-button" data-lang="de">German</button>
                <button class="language-option-button" data-lang="el">Greek</button>
                <button class="language-option-button" data-lang="gu">Gujarati</button>
                <button class="language-option-button" data-lang="ht">Haitian (French) Creole</button>
                <button class="language-option-button" data-lang="ha">Hausa</button>
                <button class="language-option-button" data-lang="haw">Hawaiian</button>
                <button class="language-option-button" data-lang="he">Hebrew</button>
                <button class="language-option-button" data-lang="hi">Hindi</button>
                <button class="language-option-button" data-lang="hmn">Hmong</button>
                <button class="language-option-button" data-lang="hu">Hungarian</button>
                <button class="language-option-button" data-lang="ig">Igbo</button>
                <button class="language-option-button" data-lang="id">Indonesian</button>
                <button class="language-option-button" data-lang="ga">Irish</button>
                <button class="language-option-button" data-lang="isg">Irish Sign Language</button>
                <button class="language-option-button" data-lang="it">Italian</button>
                <button class="language-option-button" data-lang="ja">Japanese</button>
                <button class="language-option-button" data-lang="ja-latn">Japanese (Romaji)</button>
                <button class="language-option-button" data-lang="kn">Kannada</button>
                <button class="language-option-button" data-lang="km">Khmer</button>
                <button class="language-option-button" data-lang="ko">Korean</button>
                <button class="language-option-button" data-lang="ko-latn">Korean (Romanisation)</button>
                <button class="language-option-button" data-lang="ku-latn">Kurmanji Kurdish</button>
                <button class="language-option-button" data-lang="lde">Lidepla</button>
                <button class="language-option-button" data-lang="lt">Lithuanian</button>
                <button class="language-option-button" data-lang="ms">Malay</button>
                <button class="language-option-button" data-lang="ml">Malayalam</button>
                <button class="language-option-button" data-lang="mt">Maltese</button>
                <button class="language-option-button" data-lang="mr">Marathi</button>
                <button class="language-option-button" data-lang="es-mx">Mexican Spanish (Latin America)</button>
                <button class="language-option-button" data-lang="ne">Nepali</button>
                <button class="language-option-button" data-lang="niu">Niue</button>
                <button class="language-option-button" data-lang="nzs">New Zealand Sign Language</button>
                <button class="language-option-button" data-lang="no">Norwegian</button>
                <button class="language-option-button" data-lang="or">Odia</button>
                <button class="language-option-button" data-lang="ps">Pashto</button>
                <button class="language-option-button" data-lang="fa">Persian (Farsi)</button>
                <button class="language-option-button" data-lang="pl">Polish</button>
                <button class="language-option-button" data-lang="pt-br">Portuguese (Brazilian)</button>
                <button class="language-option-button" data-lang="pt-pt">Portuguese (European)</button>
                <button class="language-option-button" data-lang="pa">Punjabi</button>
                <button class="language-option-button" data-lang="ro">Romanian</button>
                <button class="language-option-button" data-lang="ru">Russian</button>
                <button class="language-option-button" data-lang="sm">Samoan</button>
                <button class="language-option-button" data-lang="sa">Sanskrit</button>
                <button class="language-option-button" data-lang="sr-latn">Serbian (Latin)</button>
                <button class="language-option-button" data-lang="sr-cyrl">Serbian (Cyrillic)</button>
                <button class="language-option-button" data-lang="si">Sinhala</button>
                <button class="language-option-button" data-lang="so">Somali</button>
                <button class="language-option-button" data-lang="ckb">Sorani Kurdish</button>
                <button class="language-option-button" data-lang="es">Spanish (European)</button>
                <button class="language-option-button" data-lang="es-419">Spanish (Latin America)</button>
                <button class="language-option-button" data-lang="su">Sundanese</button>
                <button class="language-option-button" data-lang="sw">Swahili</button>
                <button class="language-option-button" data-lang="sv">Swedish</button>
                <button class="language-option-button" data-lang="ta">Tamil</button>
                <button class="language-option-button" data-lang="mi">te reo Maori</button>
                <button class="language-option-button" data-lang="te">Telugu</button>
                <button class="language-option-button" data-lang="th">Thai</button>
                <button class="language-option-button" data-lang="tkl">Tokelauan</button>
                <button class="language-option-button" data-lang="to">Tongan</button>
                <button class="language-option-button" data-lang="tr">Turkish</button>
                <button class="language-option-button" data-lang="uk">Ukrainian</button>
                <button class="language-option-button" data-lang="ur">Urdu</button>
                <button class="language-option-button" data-lang="uz">Uzbek</button>
                <button class="language-option-button" data-lang="vi">Vietnamese</button>
                <button class="language-option-button" data-lang="cy">Welsh</button>
                <button class="language-option-button" data-lang="yi">Yiddish</button>
                <button class="language-option-button" data-lang="yi-latn">Yiddish Transliteration</button>
                <button class="language-option-button" data-lang="yo">Yoruba</button>
                <button class="language-option-button" data-lang="zu">Zulu</button>
            </div>
            <button id="closeLanguageModalBtn" class="close-modal-btn">Close</button>
        </div>
    </div>
    
    <div id="mainContainer" class="main-container">
        <div class="avatar-section">
            <div class="avatar-header">
           <button onclick="goBackOrHome()" class="avatar-back-button">
                Back
            </button>
            </div>
            <h1 class="avatar-title">Dress your Avatar</h1>
     
            <div class="avatar-display">
                <div class="avatar-character" id="avatarCharacter"></div>
            </div>
           <div class="rewards-stats-wrapper">
    <div class="stat-block">
        <span class="stat-label">Your Total Feathers</span>
        <div class="credits-display">
            <span id="userCredits">0</span>
            <img src="https://lote4kids.com/wp-content/uploads/2025/07/IMG_7049.png" alt="Feather Icon">
        </div>
    </div>
    
    <div class="stat-block">
        <span class="stat-label">Your Total Streak Awards</span>
        <a href="/streaks-bookshelf" class="credits-display" style="text-decoration: none; color: inherit; cursor: pointer;">
           <span id="bookCount">0</span>
            <span style="font-size: 24px;">📚</span>
        </a>
    </div>
</div>
            <div class="category-tabs">
                <button class="category-tab" data-category="headwear">
                    <img src="https://lote4kids.com/wp-content/uploads/2025/08/hats.png" alt="Headwear Icon">
                    Headwear
                </button>
                <button class="category-tab active" data-category="tops">
                    <img src="https://lote4kids.com/wp-content/uploads/2025/08/tops.png" alt="Tops Icon">
                    Tops
                </button>
                <button class="category-tab" data-category="shoes">
                    <img src="https://lote4kids.com/wp-content/uploads/2025/08/shoes.png" alt="Shoes Icon">
                    Shoes
                </button>
                <button class="category-tab" data-category="facewear">
                    <img src="https://lote4kids.com/wp-content/uploads/2025/08/glasses.png" alt="Facewear Icon">
                    Facewear
                </button>
                <button class="category-tab" data-category="bottoms">
                    <img src="https://lote4kids.com/wp-content/uploads/2025/08/bottoms.png" alt="Bottoms Icon">
                    Bottoms
                </button>
                <button class="category-tab" data-category="unlocked">
                    <img src="https://lote4kids.com/wp-content/uploads/2025/08/unlocked.png" alt="Unlocked Items Icon">
                    Unlocked Items
                </button>
            </div>
            <div class="unlocked-items">
                <div class="unlocked-title">
                    Active Items
                    <span id="unlockedCount">0</span>
                </div>
                <div class="unlocked-grid" id="unlockedItemsGrid">
                    <div class="empty-unlocked-text" style="display: none;" id="emptyUnlockedText">
                        You haven't unlocked any items yet. Purchase items to customize your avatar!
                    </div>
                </div>
            </div>
            <button id="saveAvatarBtn" class="save-avatar-btn">Save Avatar</button>
        </div>
        <div class="items-section">
            <div class="category-header">
                <h2 class="category-title" id="categoryTitle">Select Headwear</h2>
            </div>
            <div id="items-container">
                <div class="loading">
                    <div style="text-align: center; padding: 20px;">
                        <div class="spinner"></div>
                        <p style="margin-top: 10px; font-size: 14px; color: #555;">
                            Loading items... please wait.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Show loading overlay initially
        document.addEventListener('DOMContentLoaded', function() {
            const loadingOverlay = document.getElementById('loadingOverlay');
            const mainContainer = document.getElementById('mainContainer');
            
            // Show loading for 1 second
            setTimeout(function() {
                loadingOverlay.classList.add('hide');
                mainContainer.classList.add('show');
                document.body.classList.add('loading-complete');

                // Remove loading overlay from DOM after transition
                setTimeout(function() {
                    loadingOverlay.style.display = 'none';
                }, 500);
            }, 500);
        });

        function showInsufficientFundsMessage() {
            alert("You don't have enough feathers to unlock this item.");
        }
      
      document.addEventListener('DOMContentLoaded', function() {
    const bookCountElement = document.getElementById('bookCount');
    
    if (bookCountElement && window.phpData && typeof window.phpData.rewardCount !== 'undefined') {
        bookCountElement.textContent = window.phpData.rewardCount;
    }
    
    // Listen for book count updates
    document.addEventListener('bookCountUpdated', function(e) {
        if (bookCountElement && e.detail && typeof e.detail.bookCount !== 'undefined') {
            bookCountElement.textContent = e.detail.bookCount;
        }
    });
});
    </script>
            <script>
function goBackOrHome() {
    if (document.referrer && document.referrer !== window.location.href) {
        window.history.back();
    } else {
        window.location.href = "/member-home/";
    }
}
</script>     
<script>
  const HAT_ASSETS = [
    "Wzard%20Ht.png",        // Wizard Hat
    "Party%20Hat%20(3).png", // Party Hat
    "Chefs.png",             // Chef's Hat
    "Fire%20Fighter.png"     // Fire Fighter Hat
  ]

  function applyHatStyling() {
    const avatarLayers = document.querySelectorAll(".avatar-item-layer")

    avatarLayers.forEach((layer) => {
      const backgroundImage = layer.style.backgroundImage

      const matchedHat = HAT_ASSETS.find(hat => backgroundImage.includes(hat))

      if (matchedHat) {
        if (matchedHat.includes("Wzard%20Ht.png")) {
          layer.style.marginTop = "-40px"
        } else {
          layer.style.marginTop = "-20px"
        }

        console.log("[v0] Hat styling applied:", matchedHat)
      }
    })
  }

  if (document.readyState === "loading") {
    document.addEventListener("DOMContentLoaded", applyHatStyling)
  } else {
    applyHatStyling()
  }

  const observer = new MutationObserver(() => {
    applyHatStyling()
  })

  const avatarCharacter = document.getElementById("avatarCharacter")
  if (avatarCharacter) {
    observer.observe(avatarCharacter, {
      childList: true,
      attributes: true,
      subtree: true,
    })
  }
</script>
<script>
const MODAL_HAT_ASSETS = [
  "Wzard%20Ht.png",
  "Party%20Hat%20(3).png",
  "Chefs.png",
  "Fire%20Fighter.png"
];

function applyModalHatStyling() {
  const exactModalBox = document.querySelector(
    'div[style*="width: 200px"][style*="height: 200px"][style*="overflow: hidden"][style*="position: relative"]'
  );
  if (exactModalBox) {
    exactModalBox.style.height = "300px";
    exactModalBox.style.marginBottom = "-10px";

    const modalLayers = exactModalBox.querySelectorAll('div[style*="background-image"]');
    modalLayers.forEach(layer => {
      const style = layer.getAttribute("style");
      const matchedHat = MODAL_HAT_ASSETS.find(hat => style.includes(hat));
      if (matchedHat) {
        const newMargin = matchedHat.includes("Wzard%20Ht.png") ? "-40px" : "-15px";
        layer.style.marginTop = newMargin;
      }
    });
  }

  const buttonDivs = document.querySelectorAll(
    'div[style*="display: flex"][style*="justify-content: center"]'
  );
  buttonDivs.forEach(div => {
    const buttons = div.querySelectorAll("button");
    if (
      buttons.length === 2 &&
      buttons[0].textContent.trim() === "Use it" &&
      buttons[1].textContent.trim() === "Store"
    ) {
      div.style.marginTop = "30px";
    }
  });
}

if (document.readyState === "loading") {
  document.addEventListener("DOMContentLoaded", applyModalHatStyling);
} else {
  applyModalHatStyling();
}

const modalObserver = new MutationObserver(applyModalHatStyling);
modalObserver.observe(document.body, { childList: true, subtree: true });
</script>




</body>
</html>

	</div>
</div>

<?php get_footer(); ?>