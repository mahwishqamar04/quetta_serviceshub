<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>AI Service Advisor | Quetta Services Hub</title>

    <!-- Bootstrap -->
    <link rel="stylesheet" href="assets/bootstrap.css">

    <!-- Font Awesome -->
    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

    <!-- AI Advisor CSS -->
    <link rel="stylesheet" href="assets/ai-advisor.css">
</head>

<body>

<div class="container py-5">

    <div class="ai-advisor-wrapper">

        <!-- Header -->
        <div class="ai-header">
            <div>
                <h2>
                    <i class="fa-solid fa-robot"></i>
                    AI Service Advisor
                </h2>

                <p>
                    Tell us about your home service problem
                    and we'll help you find the right service.
                </p>
            </div>
        </div>

        <!-- Chat Area -->
        <div class="chat-area" id="chatArea">

            <!-- Welcome Message -->
            <div class="message ai-message">

                <div class="message-icon">
                    <i class="fa-solid fa-robot"></i>
                </div>

                <div class="message-content">
                    <strong>AI Advisor</strong>

                    <p>
                        Hello! I'm your Quetta Services Hub
                        Smart Service Advisor.
                    </p>

                    <p>
                        Describe any problem you're facing at home
                        and I'll recommend the right service for you. For example:
                    </p>

                    <div class="example-problems">
                        <button type="button"
                                class="example-btn">
                            My AC is not cooling
                        </button>

                        <button type="button"
                                class="example-btn">
                            My kitchen sink is leaking
                        </button>

                        <button type="button"
                                class="example-btn">
                            I need home cleaning
                        </button>
                    </div>
                </div>

            </div>

        </div>

        <!-- Input Area -->
        <div class="advisor-input-area">

            <form id="advisorForm">

                <div class="input-group">

                    <input
                        type="text"
                        id="userMessage"
                        class="form-control"
                        placeholder="Describe your home service problem..."
                        autocomplete="off"
                        maxlength="500"
                        required
                    >

                    <button
                        type="submit"
                        class="btn btn-primary">

                        <i class="fa-solid fa-paper-plane"></i>
                        Ask Advisor

                    </button>

                </div>

                <div class="input-validation-error" id="inputValidationError">
                    <i class="fa-solid fa-circle-exclamation"></i>
                    <span id="validationErrorText">Please describe your problem (at least 5 characters).</span>
                </div>

            </form>

            <small class="input-help">
                <i class="fa-solid fa-lightbulb"></i>
                Try: "My bathroom pipe is leaking" or "AC not cooling properly"
            </small>

        </div>

    </div>

</div>

<!-- AI Advisor JavaScript -->
<script src="js/ai-advisor.js?v=4"></script>

</body>
</html>