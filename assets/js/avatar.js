jQuery(function($){

	console.log('avatar.js loaded!');

});

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

function goBackOrHome() {
    if (document.referrer && document.referrer !== window.location.href) {
        window.history.back();
    } else {
        window.location.href = "/member-home/";
    }
}

const HAT_ASSETS = [
    "Wzard%20Ht.png", // Wizard Hat
    "Party%20Hat%20(3).png", // Party Hat
    "Chefs.png", // Chef's Hat
    "Fire%20Fighter.png" // Fire Fighter Hat
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
modalObserver.observe(document.body, {
    childList: true,
    subtree: true
});