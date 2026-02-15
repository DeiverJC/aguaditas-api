# Documentación de la API de Aguaditas

Esta documentación detalla los endpoints disponibles en el backend de Aguaditas, construidos principalmente con **Laravel Restify**.

---

## 🔐 Autenticación

Base URL: `{{base_url}}/api/`

### 1. Iniciar Sesión (Login)

- **Método**: `POST`
- **Endpoint**: `login`
- **Body (JSON)**:
    ```json
    {
        "email": "admin@aguaditas.com",
        "password": "password"
    }
    ```
- **Campos Requeridos**: `email`, `password`.
- **Respuesta Exitosa (200)**:
    ```json
    {
      "token": "...",
      "user": { ... }
    }
    ```

### 2. Cerrar Sesión (Logout)

- **Método**: `POST`
- **Endpoint**: `logout`
- **Headers**: `Authorization: Bearer {token}`
- **Respuesta Exitosa (204 No Content)**

### 3. Perfil de Usuario Actual

- **Método**: `GET`
- **Endpoint**: `user`
- **Headers**: `Authorization: Bearer {token}`
- **Respuesta Exitosa (200)**: Datos del usuario autenticado.

---

## 🛠 Repositorios Restify

Base URL: `{{base_url}}/api/restify/`
Todas las peticiones a estos endpoints requieren el header `Authorization: Bearer {token}`.

### 📦 Productos (`products`)

- **Listar**: `GET products`
- **Ver**: `GET products/{id}`
- **Crear**: `POST products`
    - **Body**:
        ```json
        {
            "name": "Nombre del Producto",
            "sku": "SKU001",
            "unit_type": "und",
            "sale_price": 1500.0
        }
        ```
    - **Campos Requeridos**: `name`, `sku`, `unit_type`, `sale_price`.
- **Actualizar**: `PATCH products/{id}`
- **Eliminar**: `DELETE products/{id}`

### 👥 Clientes (`clients`)

- **Listar**: `GET clients`
- **Ver**: `GET clients/{id}`
- **Crear**: `POST clients`
    - **Body**:
        ```json
        {
            "name": "Nombre Cliente",
            "phone": "555-5555",
            "address": "Calle Falsa 123"
        }
        ```
    - **Campos Requeridos**: `name`.
- **Actualizar**: `PATCH clients/{id}`
- **Eliminar**: `DELETE clients/{id}`

### 🛒 Pedidos (`orders`)

- **Listar**: `GET orders`
- **Ver**: `GET orders/{id}`
- **Crear**: `POST orders`
    - **Body**:
        ```json
        {
            "client_id": 1,
            "items": [
                { "product_id": 1, "quantity": 2 },
                { "product_id": 2, "quantity": 1 }
            ]
        }
        ```
    - **Campos Requeridos**: `client_id`, `items`.
- **Respuesta (201)**: Detalles del pedido creado y sus ítems.

### 🔄 Ajustes de Inventario (`inventory-adjustments`)

- **Listar**: `GET inventory-adjustments` (soporta `?related=user,items`)
- **Ver**: `GET inventory-adjustments/{id}`
- **Crear**: `POST inventory-adjustments`
    - **Body**:
        ```json
        {
            "user_id": 1,
            "type": "input",
            "description": "Carga inicial"
        }
        ```
    - **Campos Requeridos**: `user_id`, `type` (`input` o `output`).
- **Actualizar**: `PATCH inventory-adjustments/{id}` (Solo si el status es `draft`).
- **Eliminar**: `DELETE inventory-adjustments/{id}` (Solo si el status es `draft`).

#### Acciones de Ajuste

- **Finalizar Ajuste**: `POST inventory-adjustments/{id}/actions/finalize`
    - **Efecto**: Actualiza el stock en la tabla `inventories` y crea registros en `inventory_movements`.

### 📝 Ítems de Ajuste (`inventory-adjustment-items`)

- **Crear**: `POST inventory-adjustment-items`
    - **Body**:
        ```json
        {
            "inventory_adjustment_id": 1,
            "product_id": 1,
            "quantity": 10
        }
        ```
    - **Campos Requeridos**: `inventory_adjustment_id`, `product_id`, `quantity`.
- **Actualizar**: `PATCH inventory-adjustment-items/{id}`
- **Eliminar**: `DELETE inventory-adjustment-items/{id}`

### 👤 Usuarios (`users`)

- **Listar**: `GET users`
- **Ver**: `GET users/{id}`
- **Crear**: `POST users` (Solo Admins)
    - **Body**:
        ```json
        {
            "name": "Nombre",
            "email": "correo@ejemplo.com",
            "role": "admin",
            "password": "password"
        }
        ```
    - **Campos Requeridos**: `name`, `email`, `role` (`admin` o `repartidor`), `password`.

---

## 📌 Parámetros Globales de Restify

- **Relaciones**: Usa `?related=relacion1,relacion2` para incluir datos relacionados.
- **Paginación**: Automática en el listado (Index).
- **Búsqueda**: `?search=valor` (Disponible si el repositorio lo tiene habilitado).
