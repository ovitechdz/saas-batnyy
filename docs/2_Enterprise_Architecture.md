# Enterprise Architecture
## Ovitech Smart Farm - Enterprise Edition 2026

### 1. Architectural Pattern
**Clean Architecture** with **Domain-Driven Design (DDD)** concepts implemented in Laravel 12.
This approach separates the concerns into distinct layers, making the application independent of UI, database, frameworks, and external agencies.

### 2. Layers

#### 2.1 Domain Layer (Core)
*   **Entities**: Core business objects (e.g., `Sheep`, `Vaccine`, `Employee`).
*   **Value Objects**: Immutable attributes (e.g., `RFIDTag`, `Money`, `Coordinates`).
*   **Domain Events**: Events that signify state changes within the domain (e.g., `SheepBorn`, `VaccineAdministered`).

#### 2.2 Application Layer (Use Cases)
*   **Services**: Business logic that orchestrates domain entities (e.g., `LivestockService`, `HealthService`).
*   **DTOs (Data Transfer Objects)**: Objects used to pass data between layers without exposing internal models.
*   **Interfaces/Contracts**: Definitions for external dependencies like Repositories and Gateways.

#### 2.3 Infrastructure Layer
*   **Repositories (Implementations)**: Eloquent-based data access layers (e.g., `EloquentSheepRepository`).
*   **External Services**: Implementations for 3rd-party APIs, AI endpoints, and file storage.
*   **Database**: PostgreSQL connection handling.

#### 2.4 Presentation Layer (UI/API)
*   **Controllers**: REST API endpoints managing incoming HTTP requests.
*   **Form Requests**: Validation logic for inputs.
*   **Resources/Transformers**: Formatting API responses.
*   **Frontend**: PWA leveraging Tailwind CSS 4 and Alpine.js, interacting via REST APIs.

### 3. Key Components & Technologies
*   **API Gateway/Router**: Laravel Routes handling versioned APIs (`/api/v1/livestock`).
*   **Event Bus**: Laravel Events & Listeners for asynchronous operations.
*   **Message Broker**: Redis for background Jobs and Queues (e.g., `GenerateMonthlyReportJob`, `SyncOfflineDataJob`).
*   **Cache Layer**: Redis caching for read-heavy operations like dashboard statistics and configuration settings.

### 4. PWA Architecture
*   **Service Worker**: Caches essential HTML, CSS, JS, and Images for offline viewing.
*   **IndexedDB**: Local database mirroring necessary data (e.g., `LocalSheepList`, `PendingSyncActions`).
*   **Background Sync API**: Periodically pushes offline actions (like registering a new sheep) to the server when online.
*   **Web Push API**: Sending critical notifications to farm workers (e.g., "Vaccination due").

### 5. Deployment Architecture (Target)
*   **Docker Containers**: Separated containers for App (PHP-FPM), Web Server (Nginx), Database (PostgreSQL), and Cache (Redis).
*   **CI/CD Pipeline**: GitHub Actions for automated testing (Pest) and deployment.
*   **Load Balancer**: Distributing traffic across multiple application instances for scalability.
