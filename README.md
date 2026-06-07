<div align="center">

<br>

```
███╗   ███╗███████╗██████╗ ███████╗██╗   ██╗██████╗ ██████╗ ██╗  ██╗   ██╗
████╗ ████║██╔════╝██╔══██╗██╔════╝██║   ██║██╔══██╗██╔══██╗██║  ╚██╗ ██╔╝
██╔████╔██║█████╗  ██║  ██║███████╗██║   ██║██████╔╝██████╔╝██║   ╚████╔╝ 
██║╚██╔╝██║██╔══╝  ██║  ██║╚════██║██║   ██║██╔═══╝ ██╔═══╝ ██║    ╚██╔╝  
██║ ╚═╝ ██║███████╗██████╔╝███████║╚██████╔╝██║     ██║     ███████╗██║   
╚═╝     ╚═╝╚══════╝╚═════╝ ╚══════╝ ╚═════╝ ╚═╝     ╚═╝     ╚══════╝╚═╝   
```

**Plateforme intelligente de gestion des achats médicaux**

<br>

![PHP](https://img.shields.io/badge/PHP-8.2-777BB4?style=for-the-badge&logo=php&logoColor=white)
![Laravel](https://img.shields.io/badge/Laravel-12-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-MariaDB_10.4-4479A1?style=for-the-badge&logo=mysql&logoColor=white)
![Bootstrap](https://img.shields.io/badge/Bootstrap-5.3-7952B3?style=for-the-badge&logo=bootstrap&logoColor=white)

![Chart.js](https://img.shields.io/badge/Chart.js-FF6384?style=for-the-badge&logo=chartdotjs&logoColor=white)
![OpenRouter](https://img.shields.io/badge/OpenRouter_AI-LLaMA_3.3_70B-00C7B7?style=for-the-badge&logo=openai&logoColor=white)
![DomPDF](https://img.shields.io/badge/DomPDF-PDF_Generation-red?style=for-the-badge)
![17Track](https://img.shields.io/badge/17Track-Parcel_Tracking-1DA1F2?style=for-the-badge)

<br>

</div>

---

## Vue d'ensemble

MedSupply est une application web full-stack conçue pour digitaliser et centraliser la chaîne d'approvisionnement médicale. Elle connecte les hôpitaux et les fournisseurs au sein d'un écosystème unifié, couvrant la gestion des commandes, du stock, des livraisons, de la facturation et de l'analyse des données — le tout enrichi par un assistant IA intégré.

L'application repose sur une architecture **multi-rôle** avec trois espaces distincts et sécurisés : Administrateur, Chef d'hôpital et Fournisseur.

---

## Fonctionnalités

<br>

### Administrateur — Contrôle total de la plateforme

| Module | Description |
|--------|-------------|
| Dashboard | Statistiques globales en temps réel — commandes, revenus, alertes |
| Gestion utilisateurs | CRUD complet avec export, filtres et contrôle des accès |
| Gestion hôpitaux | Suivi des établissements et de leurs responsables |
| Gestion fournisseurs | Vérification, notation et supervision des catalogues |
| Commandes | Validation, suivi du workflow complet et génération de factures PDF |
| Analytics | Graphiques interactifs Chart.js, comparaisons par période et suggestions IA |
| Audit Logs | Journal horodaté de toutes les actions effectuées sur la plateforme |
| Assistant IA | Chat intelligent propulsé par LLaMA 3.3 70B via OpenRouter |

<br>

### Chef d'hôpital — Gestion des achats et du stock

| Module | Description |
|--------|-------------|
| Dashboard | Vue d'ensemble du stock, alertes critiques et commandes récentes |
| Catalogue | Navigation dans les produits fournisseurs avec panier multi-fournisseur |
| Stock | Gestion des entrées manuelles, alertes de rupture et stock critique |
| Commandes | Création, suivi et tracking en temps réel via 17Track API |
| Fournisseurs | Consultation des profils et catalogues disponibles |
| Assistant IA | Aide à la décision d'achat et gestion de stock |

<br>

### Fournisseur — Gestion du catalogue et des livraisons

| Module | Description |
|--------|-------------|
| Dashboard | Résumé des commandes reçues et indicateurs de performance |
| Produits | Ajout, modification, suppression et import CSV en masse |
| Images | Upload et gestion des visuels produits |
| Commandes | Confirmation, expédition avec numéro de suivi carrier |
| Livraisons | Suivi de l'état des expéditions |
| Factures | Consultation et téléchargement des factures générées |

---

## Architecture technique

```
┌─────────────────────────────────────────────────────────────────┐
│                        CLIENT (Browser)                         │
│              Blade Templates · Bootstrap 5 · Chart.js           │
└───────────────────────────┬─────────────────────────────────────┘
                            │ HTTP
┌───────────────────────────▼─────────────────────────────────────┐
│                     Laravel 12 (PHP 8.2)                        │
│  ┌─────────────┐  ┌──────────────┐  ┌────────────────────────┐  │
│  │   Routing   │  │ Controllers  │  │      Middleware         │  │
│  │  web.php    │  │ Admin/       │  │   Auth · Role Guard     │  │
│  │             │  │ Hospital/    │  │                        │  │
│  │             │  │ Supplier/    │  │                        │  │
│  └─────────────┘  └──────┬───────┘  └────────────────────────┘  │
│                          │                                       │
│  ┌───────────────────────▼────────────────────────────────────┐  │
│  │                  Eloquent ORM / Models                     │  │
│  │  User · Hospital · Supplier · Product · Order · StockItem  │  │
│  │  CartItem · Delivery · Invoice · AuditLog · Notification   │  │
│  └───────────────────────┬────────────────────────────────────┘  │
│                          │                                       │
│  ┌───────────────────────▼────────────────────────────────────┐  │
│  │                    Services Layer                          │  │
│  │        SeventeenTrackService · AIAssistantService          │  │
│  └───────────────────────┬────────────────────────────────────┘  │
└──────────────────────────┼──────────────────────────────────────┘
                           │
        ┌──────────────────┼──────────────────┐
        │                  │                  │
┌───────▼──────┐  ┌────────▼──────┐  ┌───────▼────────┐
│   MySQL /    │  │  OpenRouter   │  │   17Track API  │
│  MariaDB     │  │  LLaMA 3.3    │  │  Parcel Track  │
└──────────────┘  └───────────────┘  └────────────────┘
```

---

## Stack technique

### Backend
- **PHP 8.2** — langage principal
- **Laravel 12** — framework MVC, routing, middleware, Eloquent ORM
- **MariaDB 10.4 / MySQL** — base de données relationnelle
- **Laravel Migrations & Seeders** — gestion du schéma et des données
- **Laravel Queue** (sync) — traitement asynchrone des emails
- **Laravel Password Facade** — système de réinitialisation de mot de passe
- **DomPDF (barryvdh/laravel-dompdf)** — génération de factures PDF

### Frontend
- **Blade** — moteur de templates Laravel
- **Bootstrap 5.3** — framework CSS
- **Chart.js** — graphiques et visualisations interactives
- **Font Awesome 6.5** — iconographie
- **Google Fonts** (DM Sans, Fraunces) — typographie
- **CSS Variables & Animations** — design system glassmorphism personnalisé

### APIs & Services externes
- **OpenRouter API** — assistant IA (LLaMA 3.3 70B Versatile)
- **17Track API** — tracking de colis en temps réel
- **Gmail SMTP** — envoi d'emails transactionnels et de confirmation

### Environnement
- **XAMPP** (Apache + MySQL)
- **Composer** — gestionnaire de dépendances PHP
- **phpMyAdmin** — administration base de données

---

## Installation

```bash
# 1. Cloner le dépôt
git clone https://github.com/tijani-douaa/MedSupply.git
cd medsupply

# 2. Installer les dépendances
composer install

# 3. Configurer l'environnement
cp .env.example .env
php artisan key:generate
```

Configurer `.env` :

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=medsupply
DB_USERNAME=root
DB_PASSWORD=

MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=noreply.medsupply@gmail.com
MAIL_PASSWORD=your_app_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply.medsupply@gmail.com
MAIL_FROM_NAME="MedSupply"

QUEUE_CONNECTION=sync

OPENROUTER_API_KEY=your_openrouter_key
SEVENTEEN_TRACK_API_KEY=your_17track_key
```

```bash
# 4. Migrer la base de données
php artisan migrate

# 5. Insérer les données initiales
php artisan db:seed

# 6. Lier le stockage public
php artisan storage:link

# 7. Lancer le serveur
php artisan serve
```

L'application sera accessible sur `http://localhost:8000`

---

## Structure du projet

```
medsupply/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Admin/          # Dashboard, Users, Hospitals, Suppliers, Orders, Analytics, AI, AuditLogs
│   │   │   ├── Hospital/       # Dashboard, Orders, Stock, Suppliers, Cart, Settings
│   │   │   ├── Supplier/       # Dashboard, Orders, Products, Deliveries, Invoices, Settings
│   │   │   └── Auth/           # ForgotPassword, ResetPassword
│   │   └── Middleware/
│   ├── Models/                 # User, Hospital, Supplier, Product, Order, OrderItem,
│   │                           # StockItem, StockMovement, CartItem, Delivery,
│   │                           # Invoice, AuditLog, Notification, AiConversation
│   └── Services/
│       ├── SeventeenTrackService.php
│       └── AIAssistantService.php
├── database/
│   ├── migrations/
│   └── seeders/
├── resources/
│   └── views/
│       ├── admin/
│       ├── hospital/
│       ├── supplier/
│       ├── auth/
│       ├── emails/
│       ├── pdf/
│       └── layouts/
├── routes/
│   └── web.php
└── public/
```

---

---

<div align="center">

Conçu et développé par **Tijani Douaâ** — 2026

</div>
