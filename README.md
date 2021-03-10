# Proyecto Rest API de ePayco

## Instalación

1. Ejecutar `composer install` o .
1. Crear virtualhost. **Opcinalmente se puede ejecutar `symfony server:start` para levantar un servidor**.
    

1. Ejecutar comandos `php bin/console doctrine:database:create && php bin/console doctrine:schema:update --force`

## ENDPOINS

-   todos visibles desde el controlador principal `BaseController`

```                                  
  cliente        GET      ANY      ANY    /api/cliente    
  registrar      POST     ANY      ANY    /api/registrar    
  recarga        POST     ANY      ANY    /api/recarga    
  token          POST     ANY      ANY    /api/token    
  procesar       POST     ANY      ANY    /api/procesar    
  billetera      GET      ANY      ANY    /api/billetera/{id}     
```










