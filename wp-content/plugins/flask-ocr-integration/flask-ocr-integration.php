<?php
/*
Plugin Name: Flask Service OCR - Farmacias
Description: Plugin Que Conecta E Integra Todo.
*/

function render_comparar($atts) {
    global $post;
    
    // Obtener el producto actual de WooCommerce
    $product = wc_get_product($post->ID);
    
    // Obtener el título del producto y sanitizarlo
    $title = get_the_title($product->ID);
    $title = sanitize_text_field($title);
    
    // Dividir el título en palabras
    $words = explode('+', $title);
    
    
    // Construir la URL de origen para la tabla
    $src = esc_url("https://microservicio.compymedics.site/compare/?query=" . urlencode($words[0]));

    // Verificar si se proporcionó una URL válida
    if (empty($src)) {
        return '<p>Error: La URL de origen es requerida.</p>';
    }

    // Crear la tabla
    $tabla = <<<EOD
    <div class="m0 p0" id="tabla-render">
        <div class="container text-center">
            <h1>Cargando comparación</h1>
        </div>
    </div>
    <script>
        // Obtén una referencia al elemento div donde deseas renderizar el resultado
        const divResultado = document.getElementById('tabla-render');
    
        // URL del endpoint
        const endpointURL = '$src';
    
        // Realiza una solicitud fetch al endpoint
        fetch(endpointURL)
            .then((response) => {
                if (!response.ok) {
                    throw new Error('No se consiguio informacion del producto.');
                }
                return response.text();
            })
            .then((html) => {
                // Renderiza el resultado HTML en el div
                divResultado.innerHTML = html;
            })
            .catch((error) => {
                console.error('Error:', error);
                divResultado.innerHTML = error;
            });
    </script>
    EOD;
    return $tabla;
}

add_shortcode('compara_precio', 'render_comparar');
?>

