# 🌆 Cybernews Intelligence Aggregator (M-CORNER AI)

![n8n](https://img.shields.io/badge/n8n-Automated-FF6F61?style=for-the-badge&logo=n8n)
![WordPress](https://img.shields.io/badge/WordPress-Core_CMS-21759B?style=for-the-badge&logo=wordpress)
![Google Gemini](https://img.shields.io/badge/Google_Gemini-AI_Rewrite-8E75FF?style=for-the-badge&logo=googlegemini)
![Docker](https://img.shields.io/badge/Docker-Infrastructure-2496ED?style=for-the-badge&logo=docker)

Повністю автономний, подієво-орієнтований (Event-Driven) конвеєр автоматизації новинного порталу. Система автоматично збирає технічні інсайди, обходить обмеження сирих RSS-фідів через dynamic web scraping, генерує унікальний рерайт за допомогою LLM та публікує оптимізований контент на фронтенд WordPress.

---

## 🏗️ Системна архітектура & Дата-стрім

Конвеєр побудований на базі **n8n** у локальній інфраструктурі **Docker**. Вся логіка працює лінійно без зайвого навантаження на систему завдяки розумному мапінгу потоків даних:

[RSS Feed Trigger] ──> [WP Duplicate Check] ──> [IF: Unique?] ──(True)──> [HTTP Request: Scraper]
│ │
│ (Forward Meta) │ (Raw HTML)
└─────────────────────────────────> [Merge Node] <──────────────────────┘
│
▼
[Google Gemini API] (Structured JSON)
│
▼
[JavaScript Code Node] (DOM Parser & RegEx)
│
▼
[WP REST API + FIFU] ──> [Neon Frontend Grid]

## 🔥 Ключові фічі (Key Features)

- **Smart Duplicate Shield:** Інтелектуальний фільтр дублікатів. Перед кожним запуском система стукає до бази WordPress через REST API за допомогою магічних Docker-містків (`host.docker.internal`). Якщо новина вже існує — конвеєр моментально тушить процес, економлячи токени нейромережі.
- **Advanced Web Scraping & RegEx HTML Parsing:** Оскільки стандартні RSS-фіди часто не віддають зображення постів, n8n викачує сирий HTML-код оригінальної сторінки, а кастомна нода на JavaScript за допомогою регулярних виразів витягує метатеги Open Graph (`og:image`).
- **AI-Powered Structured Rewrite:** Інтеграція з **Google Gemini API**. Модель налаштована видавати строго валідований JSON-об'єкт із готовим унікальним тайтлом, ексцептом та очищеним текстом статті.
- **Production Error Handling:** До ноди Gemini додано механізм автоповтору запитів (Retry on Fail). При отриманні помилок перевантаження серверів (наприклад, HTTP 503), система робить до 3 спроб із паузою в 5 секунд.
- **Custom Cyberpunk Frontend Grid:** Адаптивна CSS-сітка карток новин із неоновими ефектами, зумом зображень та динамічним підтягуванням Featured Images через мета-поля бази даних (`_fifu_image_url`) без фізичного завантаження картинок на сервер.

---

## 🛠️ Технологічний стек (Tech Stack)

- **Automation & Core Integration:** n8n (Self-Hosted), JavaScript (Node.js)
- **AI & LLM:** Google Gemini API (Model: Message a model v4)
- **Backend & CMS:** WordPress Core, PHP (Кастомні шорткоди у `functions.php`), WP REST API
- **Infrastructure:** Docker, Docker Compose, Windows WSL/Ubuntu, Git

---

## 🚀 Як запустити локально (Quick Start)

### 1. Клонування репозиторію

```bash
git clone [https://github.com/твоє-імя-в-гіті/назва-репозиторію.git](https://github.com/твоє-імя-в-гіті/назва-репозиторію.git)
cd назва-репозиторію
```

### 2. Запуск інфраструктури

```bash
docker-compose up -d
```

### 3. Імпорт воркфлоу в n8n

<ul>
<li>Відкрий http://localhost:5678</li>

<li>Перейди в розділі Workflows ➔ Import from File.</li>

<li>Обери файл з папки n8n-workflows/wp-ai-news.json.</li>

<li>Вкажи свій Gemini API Key та Application Password від WordPress.</li>
</ul>

### 4. Налаштування теми WordPress
<ul>
<li>Перенеси папку wp-content/themes/m-corner-core у свій локальний WordPress.</li>

<li>Активуй кастомну тему в адмінці.</li>
</ul>

---

📝 Ліцензія
Проект створено в навчальних та комерційних цілях для демонстрації навичок побудови Fullstack / DevOps автоматизацій.
