Instrucciones de uso - La Media Docena Admin (v2)

1) Copia la carpeta al directorio de tu servidor local (ej: C:\xampp\htdocs\la_media_docena_v2)
2) Asegúrate de que Apache y MySQL estén corriendo (XAMPP/WAMP/Laragon)
3) Importa 'la_media_docena.sql' manualmente en phpMyAdmin O:
   - Abre http://localhost/la_media_docena_v2/install.php para ejecutar el script y crear el admin por defecto
   - Usuario por defecto: admin
   - Contraseña por defecto: 12345
4) Abre http://localhost/la_media_docena_v2/login.php e inicia sesión con admin/12345
5) El panel permite CRUD de platillos, visualizar pedidos y clientes, y exportar CSV (Excel compatible)
6) Para exportar PDF en servidor instala una librería como dompdf o tcpdf y te apoyo a integrarla.

Seguridad recomendada:
- Cambia la contraseña del admin al primer inicio.
- Configura un usuario MySQL con permisos limitados en producción.
- Usa HTTPS en hosting real.
