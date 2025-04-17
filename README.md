Este proyecto es una plantilla de laravel que sirva como base para futuros proyectos

## Módulo de Respaldos Automáticos

Para utilizar el módulo de respaldos automáticos del sistema, siga los siguientes pasos:

1. **Instalar Rclone**:
   - Asegúrese de tener instalada la herramienta [Rclone](https://rclone.org/).
   - Verifique que Rclone esté disponible en el PATH de su sistema.

2. **Configurar la conexión remota**:
   - Cree una conexión con el remoto que vaya a utilizar siguiendo la [documentación oficial de Rclone](https://rclone.org/docs/).

3. **Configurar las variables de entorno**:
   - Añada las siguientes variables en su archivo `.env` con los valores correspondientes:
     ```env
     RCLONE_CONFIG_FILE_PATH=
     RCLONE_CONFIG_NAME=
     RCLONE_BACKUP_PATH=
     ```
     - `RCLONE_CONFIG_FILE_PATH`: Ruta al archivo de configuración de Rclone.
     - `RCLONE_CONFIG_NAME`: Nombre de la configuración remota creada.
     - `RCLONE_BACKUP_PATH`: Ruta en el remoto donde se almacenarán los respaldos.

4. **Verificar la ruta del archivo de configuración de Rclone**:
   - Puede utilizar el siguiente comando para obtener la ruta del archivo de configuración de Rclone:
     ```bash
     rclone config file
     ```
   - Esto mostrará la ubicación del archivo de configuración que debe usar en la variable `RCLONE_CONFIG_FILE_PATH`.

5. **Activar el Task Scheduler de Laravel**:
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

