Este proyecto es una plantilla de laravel que sirva como base para futuros proyectos

## Módulo de Respaldos Automáticos

Para utilizar el módulo de respaldos automáticos del sistema, siga los siguientes pasos:

1. **Configurar las variables de entorno**:
   - Añade la siguiente variable en tu archivo `.env` para definir el disco de almacenamiento de respaldos:
     ```env
     BACKUP_FILESYSTEM_DISK=local
     ```
     - Puedes usar cualquier disco configurado en `config/filesystems.php` (por ejemplo: `local`, `public`, `s3`, etc.).
     - Si usas S3 u otro servicio, asegúrate de configurar también las variables correspondientes (por ejemplo, `AWS_ACCESS_KEY_ID`, `AWS_SECRET_ACCESS_KEY`, etc.).

2. **Configurar el disco de respaldos en Laravel**:
   - En el archivo `config/filesystems.php` ya existe una sección llamada `backup`. Asegúrate de que esté así:
     ```php
     'backup' => [
         'driver' => env('BACKUP_FILESYSTEM_DISK', 'local'),
     ],
     ```
   - Puedes personalizar los parámetros según el driver que elijas.

3. **Activar el Task Scheduler de Laravel**:
   - Para que los respaldos automáticos se ejecuten, es necesario activar el Task Scheduler de Laravel.
   - Puede hacerlo de las siguientes maneras:
     - **En entornos de desarrollo** (no recomendado en producción):
       Ejecute el siguiente comando para mantener activo el proceso:
       ```bash
       php artisan schedule:work
       ```
     - **En entornos de producción** (recomendado):
       Configure un cron job en Linux con la siguiente línea:
       ```bash
       * * * * * cd /path-to-your-project && php artisan schedule:run >> /dev/null 2>&1
       ```