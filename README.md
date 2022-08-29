# Rutas de usuario
### Lista los usuarios GET
http://localhost/user
### Crea un nuevo usuario POST
http://localhost/user/new

{
    "name": "test2",
    "surname": "test",
    "email": "test@email"
}

### Busca un usuario por el id GET
http://localhost/user/{id}
### Edita un usuario PUT
http://localhost/user/{id}/edit

{
    "name": "test2",
    "surname": "test",
    "email": "test@email"
}

### Elimina un usuario DELETE
http://localhost/user/{id}

# Rutas de pedido
### Busca los pedidos por usuario 
http://localhost/order/list/{email}
### Crea un nuevo pedido
http://localhost/order/new/{email}

{
    "name": "johan",
    "surname": "ibarra"
    "email": "sd@test.com",
    "productName": "Avion",
    "quantity": 3,
    "unitPrice": 50
}

### Busca un pedido en especifico
http://localhost/order/{id}
### Elimina un pedido
http://localhost/order/{new}
### Edita un pedido
http://localhost/order/{id}/edit
{
    "productName": "Avion",
    "quantity": 3,
    "unitPrice": 50
}
