<!DOCTYPE html>
<html lang="es">


<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ejemplo de Página HTML</title>
    
</head>

<body>
    <!-- Encabezado principal de la página -->
    <header>
        <h1>Bienvenido a mi página web</h1>
        <p>Esta es una página de ejemplo con etiquetas HTML comunes.</p>
    </header>

    <!-- Sección de contenido principal -->
    <section>
        <h2>Sobre Nosotros</h2>
        <p>Somos una empresa dedicada a ofrecer los mejores servicios.</p>
        <p><strong>Misión:</strong> Proveer la mejor experiencia a nuestros clientes.</p>
        <p><em>Visión:</em> Convertirnos en líderes del mercado.</p>
    </section>

    <!-- Lista de servicios -->
    <section>
        <h2>Servicios</h2>
        <ul>
            <li>Consultoría</li>
            <li>Desarrollo web</li>
            <li>Marketing digital</li>
        </ul>
    </section>

    <!-- Imagen y enlaces -->
    <section>
        <h2>Galería</h2>
        <p>Conoce nuestros proyectos:</p>
        <a href="https://example.com/proyecto1">Proyecto 1</a><br>
        <a href="https://example.com/proyecto2">Proyecto 2</a><br>
        <img src="https://via.placeholder.com/150" alt="Imagen de ejemplo">
    </section>

    <!-- Tabla de precios -->
    <section>
        <h2>Precios</h2>
        <table border="1">
            <!-- Encabezado de la tabla que describe el contenido de las columnas -->
            <tr>
                <th>Servicio</th>
                <th>Precio</th>
                
            </tr>
            <!-- Fila que describe la tarifa para el servicio de consultoría -->
            <tr>
                <td>Consultoría</td>
                <td>$50</td>
                
            </tr>
            <!-- Fila que describe la tarifa para el servicio de desarrollo web -->
            <tr>
                <td>Desarrollo web</td>
                <td>$200</td>
            </tr>

           <!--  Fila que describe el total -->
            
            <tr>
                <td>Total</td>
                <td>250</td>
            </tr>

        </table>


    </section>

    <!-- Formulario de contacto -->
    <section>
        <h2>Contacto</h2>
        <form action="/enviar" method="post">
            <label for="nombre">Nombre:</label>
            <input type="text" id="nombre" name="nombre"><br><br>
            <label for="email">Correo:</label>
            <input type="email" id="email" name="email"><br><br>
            <label for="mensaje">Mensaje:</label><br>
            <textarea id="mensaje" name="mensaje"></textarea><br><br>
            <button type="submit">Enviar</button>
        </form>
    </section>

    <!-- Pie de página -->
    <footer>
        <hr>
        <p>&copy; 2024 Mi Página Web. Todos los derechos reservados.</p>
    </footer>
</body>
</html>
