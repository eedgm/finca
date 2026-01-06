# 🐄 Sistema de Gestión de Fincas (Finca)

<p align="center">
  <img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo">
</p>

<p align="center">
  <strong>Sistema completo de gestión ganadera diseñado para el manejo integral de vacas y fincas</strong>
</p>

## 📋 Acerca del Sistema

**Finca** es un sistema web desarrollado con Laravel diseñado específicamente para la gestión integral de ganado bovino. El sistema permite el control completo del ciclo de vida de las vacas, desde su registro hasta el seguimiento de su historial médico, genealogía, ventas y más.

### Características Principales

- **Gestión de Vacas**: Registro completo con características físicas, razas, colores, marcas distintivas y genealogía
- **Árbol Genealógico**: Visualización interactiva de la genealogía de las vacas con navegación entre padres, madres, hijos y hermanos
- **Historial Médico**: Seguimiento detallado de historiales de salud, peso, medicinas administradas y tipos de vaca
- **Gestión de Razas**: Sistema de razas con cálculo automático de porcentajes basado en parentesco
- **Fincas**: Administración de múltiples fincas con control de usuarios y permisos
- **Medicinas**: Inventario y control de medicamentos con seguimiento de uso
- **Ventas**: Registro y seguimiento de ventas de ganado
- **Roles y Permisos**: Sistema completo de autorización con roles y permisos granulares

## 🚀 Tecnologías

- **Backend**: Laravel 11
- **Frontend**: Livewire, Alpine.js, Tailwind CSS
- **Base de Datos**: MySQL/PostgreSQL
- **Autenticación**: Laravel Sanctum
- **Autorización**: Spatie Laravel Permission

## 📱 API REST para Aplicación Móvil

El sistema incluye una **API REST completa** desarrollada con Laravel Sanctum para autenticación, diseñada para la futura integración con una aplicación móvil desarrollada con **Expo/React Native**.

### Endpoints Disponibles

La API proporciona endpoints para:

- **Vacas (Cows)**: CRUD completo, historiales, ventas
- **Fincas (Farms)**: Gestión de fincas, vacas asociadas, usuarios
- **Historiales (Histories)**: Registro y consulta de historiales médicos
- **Medicinas (Medicines)**: Inventario y control de medicamentos
- **Tipos de Vaca (Cow Types)**: Clasificación y tipos de ganado
- **Razas (Breeds)**: Gestión de razas bovinas
- **Mercados (Markets)**: Gestión de puntos de venta
- **Fabricantes (Manufacturers)**: Control de fabricantes de medicinas
- **Ventas (Solds)**: Registro de transacciones
- **Usuarios (Users)**: Gestión de usuarios y asignación a fincas

### Autenticación API

```bash
POST /api/login
```

La API utiliza Laravel Sanctum para autenticación mediante tokens, permitiendo una integración segura con aplicaciones móviles.

## 🏗️ Estructura del Proyecto

El sistema está organizado con una arquitectura modular que incluye:

- **Modelos Eloquent** con relaciones complejas (many-to-many, one-to-many)
- **Livewire Components** para interfaces interactivas
- **Policies** para control de acceso granular
- **API Controllers** para endpoints REST
- **Migrations** para gestión de esquema de base de datos
- **Seeders** para datos iniciales (razas panameñas, permisos, etc.)

## 📦 Instalación

```bash
# Clonar el repositorio
git clone [repository-url]

# Instalar dependencias
composer install
npm install

# Configurar entorno
cp .env.example .env
php artisan key:generate

# Configurar base de datos en .env
# Luego ejecutar migraciones y seeders
php artisan migrate --seed

# Compilar assets
npm run build

# Iniciar servidor
php artisan serve
```

## 🔐 Permisos y Roles

El sistema incluye un sistema completo de roles y permisos:

- **Super Admin**: Acceso completo al sistema
- **Admin**: Gestión de roles, permisos y usuarios
- **User**: Acceso a funcionalidades básicas según permisos asignados

Los permisos se gestionan a nivel granular para cada módulo (vacas, fincas, historiales, etc.).

## 📄 Licencia

Este proyecto es software de código abierto licenciado bajo la [Licencia MIT](https://opensource.org/licenses/MIT).

---

<p align="center">
  Desarrollado con ❤️ usando Laravel
</p>
