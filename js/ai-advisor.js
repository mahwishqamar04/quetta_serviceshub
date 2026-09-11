document.addEventListener("DOMContentLoaded", function () {

    const form = document.getElementById("advisorForm");
    const input = document.getElementById("userMessage");
    const chatArea = document.getElementById("chatArea");

    if (!form || !input || !chatArea) {
        console.log("AI Advisor elements not found.");
        return;
    }

    // Validation error elements
    var validationError = document.getElementById("inputValidationError");
    var validationErrorText = document.getElementById("validationErrorText");
    var MIN_LENGTH = 5;
    var MAX_LENGTH = 500;

    // API URL
    const API_URL = "api/services.php";

    // Store services loaded from database
    let services = [];

    // Load services from API
    fetch(API_URL)
        .then(response => response.json())
        .then(data => {

            if (data.success && Array.isArray(data.services)) {

                services = data.services;

                console.log("Services loaded successfully:", services);

            } else {

                console.error("Unable to load services from API.");

            }

        })
        .catch(error => {

            console.error("API Error:", error);

        });


    // -------------------------------------------------------
    // Keyword map: each service maps to an array of phrases
    // that a customer might type.  Multi-word phrases are
    // checked first so they score higher than single words.
    // -------------------------------------------------------
    const serviceKeywords = {
        "House Cleaning": [
            "house cleaning", "home cleaning", "clean my house",
            "clean the house", "cleaning required", "cleaning service",
            "deep cleaning", "room cleaning", "office cleaning",
            "floor cleaning", "carpet cleaning",
            "clean", "cleaning", "dirty", "dust", "messy", "mess"
        ],

        "Plumbing": [
            "leaking pipe", "water leakage", "broken pipe", "tap leaking",
            "pipe leak", "pipe burst", "water leak", "pipe broken",
            "sink leaking", "toilet leaking", "bathroom leak",
            "drain blocked", "drain clogged", "blocked drain",
            "no water", "low water pressure", "water problem",
            "plumber", "plumbing", "pipe", "leak", "leaking",
            "tap", "faucet", "sink", "toilet", "water",
            "bathroom", "drain", "blocked"
        ],

        "Electrical Repair": [
            "electricity problem", "electrical problem", "power problem",
            "wiring issue", "wiring problem", "lights not working",
            "light not working", "power outage", "power cut",
            "circuit breaker", "short circuit", "electric shock",
            "fan not working", "switch not working", "socket problem",
            "electric", "electrical", "electrician", "wiring",
            "wire", "light", "bulb", "switch", "power",
            "fan", "socket", "voltage", "current"
        ],

        "AC Repair": [
            "ac not cooling", "ac not working", "ac problem",
            "ac making noise", "ac leaking water", "ac gas refill",
            "ac service", "ac installation", "ac repair",
            "air conditioner not cooling", "air conditioner problem",
            "air conditioner making noise", "air conditioner repair",
            "ac", "air conditioner", "air conditioning",
            "cooling", "not cooling", "hot air"
        ],

        "Painting": [
            "wall paint", "wall painting", "room painting",
            "house painting", "home painting", "paint my wall",
            "paint my house", "paint my room", "paint the wall",
            "exterior paint", "interior paint", "fresh coat",
            "paint", "painting", "wall", "walls",
            "color", "colour", "painter"
        ],

        "Carpentry": [
            "furniture repair", "wooden work", "cupboard repair",
            "door repair", "window repair", "wood work",
            "cabinet repair", "shelf repair", "table repair",
            "chair repair", "wooden furniture", "custom furniture",
            "carpenter", "carpentry", "furniture", "wood",
            "woodwork", "door", "doors", "chair", "table",
            "cupboard", "cabinet", "shelf"
        ]
    };


    // -------------------------------------------------------
    // Match user input against loaded services
    // -------------------------------------------------------
    function findBestMatch(userProblem) {

        const problem = userProblem.toLowerCase();
        let bestMatch = null;
        let bestScore = 0;

        services.forEach(function (service) {

            const serviceName = (service.name || "").toLowerCase();
            const keywords = serviceKeywords[service.name] || [];
            let score = 0;

            // Direct service-name mention (high weight)
            if (problem.includes(serviceName)) {
                score += 15;
            }

            // Check each keyword / phrase
            keywords.forEach(function (keyword) {

                const kw = keyword.toLowerCase();

                if (problem.includes(kw)) {
                    // Multi-word phrases score higher
                    if (kw.indexOf(" ") !== -1) {
                        score += 5;
                    } else {
                        score += 1;
                    }
                }
            });

            if (score > bestScore) {
                bestScore = score;
                bestMatch = service;
            }
        });

        // Require at least a minimal score to count as a match
        return bestScore >= 2 ? bestMatch : null;
    }


    // -------------------------------------------------------
    // Build a professional recommendation card (HTML)
    // -------------------------------------------------------
    function buildRecommendationCard(service) {

        const serviceId   = service.id || service.service_id;
        const serviceName = service.name || service.service_name || "Service";
        const serviceDesc = service.description || "Professional home service available through Quetta Services Hub.";
        const servicePrice = service.price || "";
        const serviceImage = service.image || "";

        let imageHtml = "";
        if (serviceImage) {
            let imgSrc = serviceImage;
            if (!imgSrc.startsWith("http")) {
                imgSrc = "images/" + imgSrc.split("/").pop();
            }
            imageHtml = `
                <div class="advisor-card-image">
                    <img src="${imgSrc}" alt="${serviceName}">
                </div>`;
        }

        let priceHtml = "";
        if (servicePrice) {
            priceHtml = `
                <div class="advisor-card-price">
                    <i class="fa-solid fa-tag"></i>
                    Starting from <strong>Rs. ${servicePrice}</strong>
                </div>`;
        }

        return `
            <div class="advisor-recommend-card">
                ${imageHtml}
                <div class="advisor-card-body">
                    <div class="advisor-card-badge">
                        <i class="fa-solid fa-check-circle"></i> Recommended Service
                    </div>
                    <h4 class="advisor-card-title">${serviceName}</h4>
                    <p class="advisor-card-desc">${serviceDesc}</p>
                    ${priceHtml}
                    <a href="book.php?service_id=${serviceId}"
                       class="btn btn-primary advisor-book-btn">
                        <i class="fa-solid fa-calendar-check"></i>
                        Book This Service
                    </a>
                </div>
            </div>`;
    }


    // -------------------------------------------------------
    // Build a helpful "no match" response
    // -------------------------------------------------------
    function buildNoMatchResponse() {

        return `
            <div class="advisor-no-match">
                <p>
                    I couldn't identify the exact service you need.
                    Please describe your problem in a little more detail.
                </p>
                <p class="advisor-no-match-hint">Here are some things you can ask:</p>
                <div class="example-problems">
                    <button type="button" class="example-btn advisor-suggestion-btn">My AC is not cooling</button>
                    <button type="button" class="example-btn advisor-suggestion-btn">Leaking pipe in kitchen</button>
                    <button type="button" class="example-btn advisor-suggestion-btn">Need electrician for wiring issue</button>
                    <button type="button" class="example-btn advisor-suggestion-btn">I need house cleaning</button>
                    <button type="button" class="example-btn advisor-suggestion-btn">Wall painting required</button>
                    <button type="button" class="example-btn advisor-suggestion-btn">Furniture repair needed</button>
                </div>
            </div>`;
    }


    // -------------------------------------------------------
    // Show / hide validation error
    // -------------------------------------------------------
    function showValidationError(msg) {
        if (validationError && validationErrorText) {
            validationErrorText.textContent = msg;
            validationError.classList.add("visible");
        }
        input.classList.add("is-invalid-advisor");
        // Shake the input area
        var area = input.closest(".advisor-input-area");
        if (area) {
            area.classList.remove("advisor-shake");
            void area.offsetWidth; // force reflow to restart animation
            area.classList.add("advisor-shake");
        }
    }

    function clearValidationError() {
        if (validationError) {
            validationError.classList.remove("visible");
        }
        input.classList.remove("is-invalid-advisor");
    }

    // Clear error as user types
    input.addEventListener("input", function () {
        if (input.classList.contains("is-invalid-advisor")) {
            clearValidationError();
        }
    });

    // -------------------------------------------------------
    // Handle form submit
    // -------------------------------------------------------
    form.addEventListener("submit", function (event) {

        event.preventDefault();

        var message = input.value.trim();

        // Validate: empty check
        if (message === "") {
            showValidationError("Please describe your home service problem.");
            input.focus();
            return;
        }

        // Validate: minimum length
        if (message.length < MIN_LENGTH) {
            showValidationError("Please describe your problem in more detail (at least " + MIN_LENGTH + " characters).");
            input.focus();
            return;
        }

        // Validate: maximum length
        if (message.length > MAX_LENGTH) {
            showValidationError("Message is too long (maximum " + MAX_LENGTH + " characters).");
            input.focus();
            return;
        }

        // Validation passed — clear any error and proceed
        clearValidationError();

        addMessage(message, "user");

        input.value = "";

        const matchedService = findBestMatch(message);

        let responseHtml = "";

        if (matchedService) {

            const greeting = getSmartGreeting(message);

            responseHtml = `
                <p>${greeting}</p>
                ${buildRecommendationCard(matchedService)}`;

        } else {

            responseHtml = buildNoMatchResponse();
        }

        // Small delay to feel natural
        setTimeout(function () {

            addMessage(responseHtml, "ai", true);

        }, 400);
    });


    // -------------------------------------------------------
    // Smart greeting based on what the user typed
    // -------------------------------------------------------
    function getSmartGreeting(problem) {

        const p = problem.toLowerCase();

        if (p.includes("not working") || p.includes("not cooling") || p.includes("broken") || p.includes("leaking") || p.includes("problem")) {
            return "Based on your problem, I recommend our service:";
        }

        if (p.includes("need") || p.includes("want") || p.includes("looking for") || p.includes("require")) {
            return "I found the perfect service for your needs:";
        }

        if (p.includes("clean") || p.includes("paint") || p.includes("repair") || p.includes("fix")) {
            return "Great choice! Here is what I recommend:";
        }

        return "Based on what you described, I recommend this service:";
    }


    // -------------------------------------------------------
    // Add a chat message bubble
    //   type: "user" | "ai"
    //   isRaw: if true, html is inserted directly (for cards)
    // -------------------------------------------------------
    function addMessage(text, type, isRaw) {

        const wrapper = document.createElement("div");
        wrapper.className = "message " + (type === "user" ? "" : "ai-message");

        if (type === "user") {

            wrapper.innerHTML = `
                <div class="message-content ms-auto">
                    <strong>You</strong>
                    <p>${escapeHtml(text)}</p>
                </div>`;

        } else {

            wrapper.innerHTML = `
                <div class="message-icon">
                    <i class="fa-solid fa-robot"></i>
                </div>
                <div class="message-content">
                    <strong>AI Advisor</strong>
                    ${isRaw ? text : "<p>" + escapeHtml(text) + "</p>"}
                </div>`;
        }

        chatArea.appendChild(wrapper);
        chatArea.scrollTop = chatArea.scrollHeight;
    }


    // -------------------------------------------------------
    // Simple HTML-escape for user-supplied text
    // -------------------------------------------------------
    function escapeHtml(str) {

        const div = document.createElement("div");
        div.appendChild(document.createTextNode(str));
        return div.innerHTML;
    }


    // -------------------------------------------------------
    // Clickable example / suggestion buttons
    // -------------------------------------------------------
    document.addEventListener("click", function (e) {

        if (e.target.classList.contains("example-btn") ||
            e.target.classList.contains("advisor-suggestion-btn")) {

            const text = e.target.textContent.trim();

            if (text) {
                input.value = text;
                form.dispatchEvent(new Event("submit"));
            }
        }
    });

});
