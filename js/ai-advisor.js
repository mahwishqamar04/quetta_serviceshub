document.addEventListener("DOMContentLoaded", function () {

    const form = document.getElementById("advisorForm");
    const input = document.getElementById("userMessage");
    const chatArea = document.getElementById("chatArea");

    if (!form || !input || !chatArea) {
        console.log("AI Advisor elements not found.");
        return;
    }

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


    form.addEventListener("submit", function (event) {

        event.preventDefault();

        const message = input.value.trim();

        if (message === "") {
            alert("Please describe your home service problem.");
            return;
        }

        // Add user's message
        const userMessage = document.createElement("div");

        userMessage.className = "message";

        userMessage.innerHTML = `
            <div class="message-content ms-auto">
                <strong>You</strong>
                <p>${message}</p>
            </div>
        `;

        chatArea.appendChild(userMessage);

        // Clear input
        input.value = "";

        // Convert message to lowercase
        const userProblem = message.toLowerCase();

        let matchedService = null;

        // Natural language keywords for each service
const serviceKeywords = {
    "House Cleaning": [
        "clean",
        "cleaning",
        "dirty",
        "dust",
        "messy",
        "house cleaning",
        "home cleaning",
        "clean my house"
    ],

    "Plumbing": [
        "plumber",
        "plumbing",
        "pipe",
        "leak",
        "leaking",
        "tap",
        "faucet",
        "sink",
        "toilet",
        "water",
        "bathroom"
    ],

    "Electrical Repair": [
        "electric",
        "electrical",
        "electrician",
        "wiring",
        "wire",
        "light",
        "bulb",
        "switch",
        "power",
        "fan",
        "socket",
        "voltage"
    ],

    "AC Repair": [
        "ac",
        "air conditioner",
        "air conditioning",
        "cooling",
        "not cooling",
        "hot air",
        "air conditioner repair"
    ],

    "Painting": [
        "paint",
        "painting",
        "wall",
        "walls",
        "color",
        "colour",
        "house paint"
    ],

    "Carpentry": [
        "carpenter",
        "carpentry",
        "furniture",
        "wood",
        "woodwork",
        "door",
        "doors",
        "chair",
        "table",
        "cupboard",
        "cabinet"
    ]
};
// Find the best matching service from API services
let bestMatchScore = 0;

services.forEach(function (service) {

    const serviceName =
        (service.name || "").toLowerCase();

    const keywords =
        serviceKeywords[service.name] || [];

    let score = 0;

    // Check service name
    if (userProblem.includes(serviceName)) {
        score += 10;
    }

    // Check natural language keywords
    keywords.forEach(function (keyword) {

        if (userProblem.includes(keyword.toLowerCase())) {
            score += 1;
        }

    });

    // Keep the service with the highest score
    if (score > bestMatchScore) {

        bestMatchScore = score;
        matchedService = service;

    }

});

        /*
         * Search database services based on:
         * service name + description
         */


        let response = "";


        // If matching service found
        if (matchedService) {

            const serviceId =
                matchedService.id ||
                matchedService.service_id;

            const serviceName =
                matchedService.name ||
                matchedService.service_name;

            const serviceDescription =
    matchedService.description ||
    "Professional home service is available through Quetta Services Hub.";

const servicePrice =
    matchedService.price || "Price available on request";

response = `
    <strong>${serviceName} Service Recommended 🤖</strong><br><br>

    ${serviceDescription}<br><br>

    <strong>Service Price:</strong>
    Rs. ${servicePrice}<br><br>

    <a href="book.php?service_id=${serviceId}"
       class="btn btn-primary btn-sm">
        <i class="fa-solid fa-calendar-check"></i>
        Book This Service
    </a>
`;
        }

        // If no service found
        else {

            response = `
                Thank you for describing your problem. 🤖<br><br>

                I couldn't identify the exact service yet.
                Please describe your problem using words such as
                <strong>plumbing, electrical, AC, cleaning, painting,
                or carpentry</strong>.
            `;

        }


        // Show AI response
        setTimeout(function () {

            const aiMessage = document.createElement("div");

            aiMessage.className = "message ai-message";

            aiMessage.innerHTML = `
                <div class="message-icon">
                    <i class="fa-solid fa-robot"></i>
                </div>

                <div class="message-content">
                    <strong>AI Advisor</strong>
                    <p>${response}</p>
                </div>
            `;

            chatArea.appendChild(aiMessage);

            chatArea.scrollTop = chatArea.scrollHeight;

        }, 500);

    });

});