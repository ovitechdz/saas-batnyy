# Folder Structure
## Ovitech Smart Farm - Enterprise Edition 2026

This document outlines the folder structure for the Ovitech Smart Farm Laravel 12 application, adhering strictly to Clean Architecture and Domain-Driven Design (DDD).

```text
c:\xampp\htdocs\newprjct\
├── app/
│   ├── Domain/                 # Core Business Logic (DDD)
│   │   ├── Livestock/
│   │   │   ├── Entities/       # Sheep, Group, Breed
│   │   │   ├── ValueObjects/   # RFIDTag, Weight
│   │   │   ├── Events/         # SheepRegistered, SheepDeceased
│   │   │   └── Repositories/   # Interface Definitions
│   │   ├── Health/             # Vaccine, Treatment, Illness
│   │   ├── Breeding/           # Pregnancy, Offspring
│   │   ├── Feed/               # Inventory, Ration
│   │   └── Finance/            # Transaction, Payroll
│   │
│   ├── Application/            # Application Use Cases & Services
│   │   ├── Livestock/
│   │   │   ├── Services/       # RegisterSheepService, TransferGroupService
│   │   │   └── DTOs/           # SheepRegistrationDTO
│   │   ├── Health/
│   │   └── ... (Other modules)
│   │
│   ├── Infrastructure/         # External Dependencies & Implementations
│   │   ├── Persistence/        # Eloquent Repositories
│   │   │   ├── EloquentLivestockRepository.php
│   │   │   └── ...
│   │   ├── AI/                 # AI Model API integrations
│   │   ├── Hardware/           # RFID & QR scanner integrations
│   │   └── Storage/            # File system implementations
│   │
│   ├── Presentation/           # Delivery Mechanism (HTTP/CLI)
│   │   ├── Http/
│   │   │   ├── Controllers/    # API Controllers
│   │   │   │   ├── Api/V1/
│   │   │   │   │   ├── LivestockController.php
│   │   │   │   │   └── ...
│   │   │   ├── Requests/       # Form Requests (Validation)
│   │   │   └── Middleware/     # Custom Middleware (e.g., RBAC)
│   │   ├── Resources/          # API Resource Transformers
│   │   └── Console/            # Artisan Commands
│   │
│   ├── Models/                 # Eloquent Models (Kept separate from Domain Entities)
│   │   ├── SheepModel.php
│   │   └── ...
│   │
│   └── Providers/              # Service Providers
│       ├── AppServiceProvider.php
│       ├── DomainServiceProvider.php
│       └── RepositoryServiceProvider.php
│
├── bootstrap/                  # Framework bootstrapping
├── config/                     # Application configuration
├── database/                   # Migrations, Seeders, Factories
│   ├── migrations/
│   ├── seeders/
│   └── factories/
│
├── public/                     # Publicly accessible files (index.php, PWA assets)
│   ├── build/                  # Vite compiled assets
│   ├── manifest.json           # PWA Manifest
│   ├── sw.js                   # Service Worker
│   └── icons/                  # PWA Icons
│
├── resources/                  # Uncompiled assets
│   ├── css/                    # Tailwind CSS 4 source files
│   │   └── app.css
│   ├── js/                     # ES2026, Alpine.js, PWA logic
│   │   ├── app.js
│   │   ├── bootstrap.js
│   │   ├── pwa/
│   │   │   ├── indexeddb.js    # Local storage logic
│   │   │   └── sync.js         # Background sync logic
│   │   └── components/         # Reusable Alpine components
│   └── views/                  # Blade Templates
│       ├── layouts/
│       │   └── app.blade.php
│       ├── components/         # Blade Components (UI kit)
│       └── pages/              # Module specific views
│
├── routes/                     # Application routes
│   ├── api.php
│   ├── web.php
│   └── console.php
│
├── storage/                    # Logs, cached templates, uploads
├── tests/                      # Automated Testing (Pest)
│   ├── Unit/                   # Testing Domain & Application layers
│   └── Feature/                # Testing endpoints & Infrastructure
│
├── .env                        # Environment variables
├── composer.json               # PHP dependencies
├── package.json                # NPM dependencies
├── tailwind.config.js          # Tailwind CSS 4 configuration
└── vite.config.js              # Vite bundler configuration
```
