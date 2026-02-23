# NutriSense 🌿

NutriSense is a modern, full-stack health and nutrition tracking application. It helps users manage their daily intake, analyze food metrics, and provides personalized advice through an integrated AI Advisor.

![NutriSense Dashboard](https://raw.githubusercontent.com/Craggy-sense/Nutrisense/main/frontend/assets/preview.png) *(Note: Add a real preview image link if available)*

## 🚀 Features

- **Personalized Dashboard**: Track your daily calorie and protein goals with dynamic progress bars.
- **Smart Meal Logging**: Log meals with ease, categorized by type (Breakfast, Lunch, Dinner, Snack).
- **Food Analyzer**: Search the global **Open Food Facts API** for instant nutrition data.
- **AI Advisor**: A built-in health assistant powered by **Llama 3.3 (via Groq)** to provide evidence-based nutrition advice.
- **Water Tracking**: Stay hydrated by tracking your daily water intake.
- **Recipe of the Day**: Discover new healthy meals powered by the **MealDB API**.

## 🛠 Tech Stack

### Frontend
- **HTML5 & CSS3**: Custom vanilla CSS with a premium, responsive design.
- **JavaScript**: Core logic and API integration.
- **Open Food Facts API**: For real-time food nutrition data.
- **MealDB API**: For daily recipe discovery.

### Backend
- **Laravel 12 (PHP)**: A robust, secure API layer.
- **SQLite**: Local, lightweight database for personal data persistence.
- **Laravel Sanctum**: Secure, token-based authentication.
- **Groq API (Llama 3.3)**: Powering the AI chat functionality.

## 📦 Installation & Setup

### Prerequisites
- PHP 8.2+
- Composer
- A [Groq Cloud](https://console.groq.com/) API Key (for the AI Chat)

### Backend Setup
1. Navigate to the `backend` folder.
2. Install dependencies:
   ```bash
   composer install
   ```
3. Prepare the environment file:
   ```bash
   cp .env.example .env
   ```
4. Generate the application key:
   ```bash
   php artisan key:generate
   ```
5. Set your AI Key in the `.env` file:
   ```env
   GROQ_API_KEY=your_key_here
   ```
6. Run database migrations:
   ```bash
   php artisan migrate
   ```
7. Start the server:
   ```bash
   php artisan serve
   ```

### Frontend Setup
1. Open the `frontend/index.html` file in your favorite modern browser.
2. (Wait for the backend to be running so the APIs can connect!)

## 👨‍💻 Educational Documentation
This project was built with learning in mind. You will find **detailed comments** in all major files:
- `backend/app/Http/Controllers/`: Explains the logic for meals, water, and AI.
- `frontend/api.js`: Explains the "Bridge" connecting the frontend and backend.
- `frontend/dashboard.html`: Explains the JavaScript state and storage logic.

## 📄 License
MIT License. Feel free to use and modify for your own health tracking journey!
