# Usine Jus

Application Laravel pour la gestion d'une usine de production de jus, avec API JSON.

## Installation locale

```bash
composer install
copy .env.example .env
php artisan key:generate
php artisan migrate:fresh --seed
php artisan serve
```

Par defaut le projet utilise SQLite via `database/database.sqlite`. Pour XAMPP ou WAMP avec MySQL, cree une base `usine_jus` puis configure `.env` ainsi :

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=usine_jus
DB_USERNAME=root
DB_PASSWORD=
SESSION_DRIVER=file
QUEUE_CONNECTION=sync
CACHE_STORE=array
```

Compte de test :

- Admin : `admin@usine-jus.test` / `password`
- Employe : `employe@usine-jus.test` / `password`

## Tables metier

Le schema metier contient 10 tables : `users`, `employees`, `suppliers`, `raw_materials`, `products`, `productions`, `production_details`, `clients`, `orders`, `order_details`.

Laravel ajoute aussi sa table technique `migrations` apres execution des migrations.

## API

Toutes les routes API sont prefixees par `/api`.

### Connexion

```http
POST /api/login
Content-Type: application/json

{
  "email": "admin@usine-jus.test",
  "password": "password"
}
```

La reponse contient `access_token`. Utilise-le ensuite dans les appels proteges :

```http
Authorization: Bearer <access_token>
Accept: application/json
```

### Ressources CRUD

- `GET|POST /api/users`
- `GET|PUT|PATCH|DELETE /api/users/{id}`
- `GET|POST /api/employees`
- `GET|PUT|PATCH|DELETE /api/employees/{id}`
- `GET|POST /api/suppliers`
- `GET|PUT|PATCH|DELETE /api/suppliers/{id}`
- `GET|POST /api/raw-materials`
- `GET|PUT|PATCH|DELETE /api/raw-materials/{id}`
- `GET|POST /api/products`
- `GET|PUT|PATCH|DELETE /api/products/{id}`
- `GET|POST /api/clients`
- `GET|PUT|PATCH|DELETE /api/clients/{id}`
- `GET|POST|DELETE /api/productions`
- `GET|POST|DELETE /api/orders`


### API internes dashboard (protegees)

Ces routes utilisent aussi l'en-tete `Authorization: Bearer <access_token>`.

- `GET /api/internal/dashboard-summary` : retourne les compteurs metier, le total des ventes, la valeur du stock produits, le cout du stock matieres, les commandes par statut et les 5 dernieres commandes.
- `GET /api/internal/stock-alerts` : retourne les produits et matieres premieres dont le stock est inferieur ou egal au seuil d'alerte.

Exemple :

```http
GET /api/internal/dashboard-summary
Authorization: Bearer <access_token>
Accept: application/json
```

### API externes publiques

Ces routes sont prevues pour un portail externe ou une integration partenaire. Elles ne modifient pas le stock.

- `GET /api/external/catalog` : liste les produits disponibles, leur SKU, leur quantite disponible et leur prix public en XAF.
- `POST /api/external/quotes` : calcule un devis a partir des SKU et quantites demandees, avec un indicateur `can_fulfill`.

Exemple de devis :

```http
POST /api/external/quotes
Content-Type: application/json
Accept: application/json

{
  "items": [
    { "sku": "JUS-MANGUE-50", "quantity": 3 }
  ]
}
```

### Demo HTML API

Une page statique de test est disponible dans `public/api-demo.html`. Elle permet de se connecter, lire les ressources protegees, consulter les nouvelles API internes dashboard et tester les nouvelles API externes catalogue/devis.

### Exemple production

```json
{
  "product_id": 1,
  "production_date": "2026-05-07",
  "quantity_produced": 10,
  "materials": [
    { "raw_material_id": 1, "quantity_used": 25 }
  ]
}
```

Effet automatique : diminue le stock des matieres premieres et augmente le stock du produit.

### Exemple commande

```json
{
  "client_id": 1,
  "order_date": "2026-05-07",
  "items": [
    { "product_id": 1, "quantity": 3 }
  ]
}
```

Effet automatique : calcule `total_amount` et diminue le stock des produits.

## Verification

```bash
php artisan route:list
php artisan test
```
