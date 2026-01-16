# SellNow (Assessment Project)

This is a **simplified, imperfect** platform for selling digital products, built for **candidate assessment functionality**.
It contains **intentional flaws, bad practices, and security holes**.

## Project Overview

A platform where:
1. Users register and get a public profile (`/username`).
2. Users can upload products (images + digital files).
3. Buyers can browse, add to cart, and "checkout".

## Setup Instructions

1. **Install Dependencies**:
   ```bash
   composer install
   ```

2. **Database**:
   The project is configured to use SQLite by default.
   Initialize the database:
   ```bash
   sqlite3 database/database.sqlite < database/schema.sql
   ```
   *Note: If you switch to MySQL, update `src/Config/Database.php`.*

3. **Run Server**:
   Use PHP built-in server:
   ```bash
   php -S localhost:8000 -t public
   ```

4. **Access**:
   http://localhost:8000


## Directory Structure

- `public/`: Web root (index.php, uploads).
- `src/`: Application classes (Controllers, Models, Config).
- `templates/`: Twig views.
- `database/`: Schema and SQLite file.

Good luck!


## Docker Setup

### Services
- **nginx** (port 8082) - Web server
- **php-fpm** - PHP processor
- **mysql** (port 3307) - Database
- **phpmyadmin** (port 8081) - DB management

### Commands
```bash
# Start services
docker-compose up -d
 
 
```
## Database Schema
### Using MySQL
#### Add indexing and foreign keys, and fix the casing as well.

###  Router (`src/Config/Router.php`)

- Custom routing system with Controller@method syntax
- Supports GET, POST, ANY methods
- Dynamic route parameters: `/{username}`
- Integrated with Container for dependency injection

###  Container (`src/Container.php`)
- Dependency injection container
- Auto-resolves constructor dependencies using Reflection
- Binds interfaces to concrete implementations
- Singleton pattern for shared instances

### Repositories
**Location:** `src/Repositories/`

**Purpose:** Data access layer - handles all database operations

**Interface:** `src/Contracts/RepositoryInterface.php`
- `findById()`
- `findByParams()`
- `getListWhere()`
- `add()`
- `update()`
- `delete()`

**Implemented Repositories:**
- `AuthRepository`
- `UserRepository`
- `ProductRepository`
- `CartRepository` 
- `OrderRepository`
- `PaymentProviderRepository` 

### Services
**Location:** `src/Services/`

###  Models
**Location:** `src/Models/`

**Base Model** (`Model.php`):
- Static methods for Eloquent-style queries
- `find()`, `findBy()`, `all()`, `where()`, `create()`, `updateById()`, `destroy()`
- `join()` - Common JOIN method for relations
- `query()` - Raw SQL execution

**Implemented Models:**
- `User`
- `Product`
- `Cart`
- `Order`
- `PaymentProvider`
 

### Traits

#### `HandlesResponse` (`src/Traits/HandlesResponse.php`)