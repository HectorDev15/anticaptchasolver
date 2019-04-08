# Libreria php para resolver Captcha usando el servicio de Anti-Captcha (https://anti-captcha.com)
nota: para el uso de esta libreria necesitas estar registrado y poseer un balance positivo.
# Instalacion
```php
composer require hectordev15/anticaptchasolver
```
# Imagen a texto
```sh
$client = new \AntiCaptchaSolver\Client('Tu API Key');
```
Convertir la imagen a base64
```sh
$image = $client->image_url_to_base64('http://url-to-captcha-image');
````
Crear Nueva tarea de Solvin Captcha
```sh
$result = $client->solveCaptcha($image);
````
Esperar el resultado
```sh
echo $result
````
  
# Solucionando Recaptcha
```sh
$client = new \AntiCaptchaSolver\Client('Tu API Key');
```
Declarar el array con los datos necesarios
```sh
$options = 
[
    'websiteURL' => 'URL del sitio con recaptcha',
    'websiteKey' => 'Key de Recaptcha',
];
````
Crear Nueva tarea
```sh
$result = $client->solveNoCaptcha($options);
````
Esperar el resultado
```sh
echo $result
````

# Enviando Recaptcha
```sh
$client = new \AntiCaptchaSolver\Client('Tu API Key');
```
Declarar el array con los datos necesarios
```sh
$options = 
[
    'websiteURL' => 'URL del sitio con recaptcha',
    'websiteKey' => 'Key de Recaptcha',
];
````
Crear Nueva tarea
```sh
$result = $client->sendNoCaptcha($options);
````
El resultado es el ID de la solicitud al servicio
```sh
echo $result
````

# Obteniendo Recaptcha Result
```sh
$client = new \AntiCaptchaSolver\Client('Tu API Key');
```
enviar el ID con el id de tu solicitud
```sh
echo $client->takeNoCaptcha('Tu ID');
````

# Para Obtener el siteKey
Ubica la siguiente etiqueta HTMl en el sitio 
```sh
<div class="g-recaptcha" data-sitekey="6Lc_aCMTAAAAABx7u2W0WPXnVbI_v6ZdbM6rYf16"></div>
````

Mas Informacion Documentacion de anti-captcha : https://anticaptcha.atlassian.net/wiki/display/API/API+v.2+Documentation

Este repositorio es basado https://github.com/Majesko/anti-captcha
