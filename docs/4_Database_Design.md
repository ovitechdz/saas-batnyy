# Database Design (PostgreSQL)
## Ovitech Smart Farm - Enterprise Edition 2026

This document outlines the core database schema for the Ovitech Smart Farm platform.

### Core Tables

#### `users` (System Users & Employees)
*   `id` (UUID, Primary Key)
*   `farm_id` (UUID, Foreign Key)
*   `name` (String)
*   `email` (String, Unique)
*   `password` (String)
*   `role_id` (UUID, Foreign Key - RBAC)
*   `status` (Enum: active, inactive)
*   `remember_token` (String)
*   `created_at`, `updated_at`, `deleted_at`

#### `farms` (Multi-tenant Support)
*   `id` (UUID, Primary Key)
*   `name` (String)
*   `subscription_tier` (String)
*   `location` (Point/Geometry)
*   `created_at`, `updated_at`

#### `sheep` (Livestock Core)
*   `id` (UUID, Primary Key)
*   `farm_id` (UUID, Foreign Key)
*   `rfid_tag` (String, Unique, Nullable)
*   `qr_code` (String, Unique, Nullable)
*   `name` (String, Nullable)
*   `gender` (Enum: ram, ewe, wether)
*   `breed_id` (UUID, Foreign Key)
*   `birth_date` (Date)
*   `status` (Enum: healthy, sick, pregnant, sold, deceased)
*   `mother_id` (UUID, Foreign Key, Nullable - Pedigree)
*   `father_id` (UUID, Foreign Key, Nullable - Pedigree)
*   `created_at`, `updated_at`, `deleted_at`

#### `health_records`
*   `id` (UUID, Primary Key)
*   `sheep_id` (UUID, Foreign Key)
*   `vet_id` (UUID, Foreign Key - References `users`)
*   `diagnosis` (Text)
*   `treatment` (Text)
*   `date_recorded` (Timestamp)
*   `created_at`, `updated_at`

#### `vaccinations`
*   `id` (UUID, Primary Key)
*   `sheep_id` (UUID, Foreign Key)
*   `vaccine_type` (String)
*   `batch_number` (String)
*   `administered_date` (Date)
*   `next_due_date` (Date)
*   `administered_by` (UUID, Foreign Key - References `users`)
*   `created_at`, `updated_at`

#### `feed_inventory`
*   `id` (UUID, Primary Key)
*   `farm_id` (UUID, Foreign Key)
*   `feed_type` (String)
*   `quantity_kg` (Decimal)
*   `reorder_level_kg` (Decimal)
*   `created_at`, `updated_at`

#### `breeding_cycles`
*   `id` (UUID, Primary Key)
*   `ewe_id` (UUID, Foreign Key)
*   `ram_id` (UUID, Foreign Key)
*   `mating_date` (Date)
*   `expected_lambing_date` (Date)
*   `actual_lambing_date` (Date, Nullable)
*   `status` (Enum: mated, pregnant, open, aborted, lambed)
*   `created_at`, `updated_at`

### Caching & Queues (Redis)
*   Active Sessions
*   Rate Limiting counters
*   Background Jobs (Report Generation, PWA Data Sync, AI Data Processing)

### Indexing Strategy
*   B-Tree indexes on `rfid_tag`, `qr_code` for O(1) lookups during scanning.
*   Composite indexes on `(farm_id, status)` for fast dashboard aggregations.
*   Foreign keys properly indexed to prevent cascading delete slowdowns.
