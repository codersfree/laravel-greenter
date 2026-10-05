# Laravel Greenter (v2)

**Laravel Greenter** es un paquete para emitir **comprobantes electrónicos** desde Laravel utilizando [Greenter](https://github.com/thegreenter/greenter). Permite:

* Firmar comprobantes digitalmente
* Enviarlos a SUNAT (SEE o API REST)
* Gestionar respuestas estructuradas (CDRs)
* Generar XML firmados
* Generar su representación impresa en PDF (HTML y PDF)

[![MIT License](https://img.shields.io/badge/license-MIT-blue.svg)](LICENSE)

## 📚 Tabla de Contenidos

* [📦 Requisitos](#-requisitos)
* [🚀 Instalación](#-instalación)
* [⚙️ Configuración Inicial](#️-configuración-inicial)
  * [🏢 Datos de la Empresa Emisora](#-datos-de-la-empresa-emisora)
  * [🛠️ Cambiar a Producción](#️-cambiar-a-producción)
* [🧰 Uso Básico](#-uso-básico)
  * [🧾 Emisión de Comprobante Electrónico](#-emisión-de-comprobante-electrónico)
  * [📨 Estructura de la Respuesta](#-estructura-de-la-respuesta)
  * [📤 Métodos disponibles (Getters)](#métodos-disponibles-getters)
  * [🔄 Convertir a Array](#convertir-a-array)
  * [🔁 Emisión Dinámica para Múltiples Empresas](#-emisión-dinámica-para-múltiples-empresas)
* [🔏 Gestión de XML](#-gestión-de-xml)
  * [🧾 Generar XML Firmado](#generar-xml-firmado)
  * [📨 Enviar XML existente](#enviar-xml-existente)
* [🎨 Generar Representación Impresa](#-generar-representación-impresa)
  * [🧾 HTML](#-html)
  * [🖨️ PDF](#️-pdf)
  * [🛠️ Generar Reporte sin Enviar a SUNAT](#️-generar-reporte-sin-enviar-a-sunat)
* [📦 Otros Tipos de Comprobantes](#-otros-tipos-de-comprobantes)
  * [🎨 Personalizar Plantillas](#-personalizar-plantillas)
* [🧪 Facades Disponibles](#-facades-disponibles)
* [🔐 Seguridad Recomendada](#-seguridad-recomendada)
* [📄 Licencia](#-licencia)

## 📦 Requisitos

Este paquete requiere:

* PHP >= 8.1 (Laravel 13 requiere PHP >= 8.3)
* Laravel 10.x, 11.x, 12.x o 13.x
* Extensiones PHP: `soap`, `openssl`, `dom`, `xml`
* [wkhtmltopdf](https://wkhtmltopdf.org) (opcional, para generación de PDF)

## 🚀 Instalación

Instala el paquete con Composer:

```bash
composer require codersfree/laravel-greenter
```

Publica los archivos de configuración y recursos:

```bash
php artisan vendor:publish --tag=greenter-laravel
```

Esto generará:

* `config/greenter.php`: configuración principal del paquete
* `public/images/logo.png`: logo usado en PDFs
* `public/certs/certificate.pem`: certificado digital de prueba

## ⚙️ Configuración Inicial

### 🏢 Datos de la Empresa Emisora

En `config/greenter.php`, configura los datos de la empresa emisora:

```php
'company' => [
    'ruc' => '20000000001',
    'razonSocial' => 'GREEN SAC',
    'nombreComercial' => 'GREEN',
    'address' => [
        'ubigeo' => '150101',
        'departamento' => 'LIMA',
        'provincia' => 'LIMA',
        'distrito' => 'LIMA',
        'direccion' => 'Av. Villa Nueva 221',
    ],
]
```

### 🛠️ Cambiar a Producción

Cuando estés listo para pasar a producción, edita el archivo `config/greenter.php`, cambia el valor de `mode` a `'prod'` y reemplaza las credenciales de prueba por las credenciales reales proporcionadas por SUNAT:

```php
'mode' => 'prod',

'company' => [
    'certificate' => public_path('certs/certificate.pem'),
    'clave_sol' => [
        'user' => 'USUARIO_SOL',
        'password' => 'CLAVE_SOL',
    ],
    'credentials' => [
        'client_id' => '...',
        'client_secret' => '...',
    ],
],
```

> ⚠️ **Importante:** Nunca subas tus certificados o credenciales a tu repositorio. Usa variables de entorno.

## 🧰 Uso Básico

### 🧾 Emisión de Comprobante Electrónico

Primero define los datos del comprobante:

```php
$data = [
    "ublVersion" => "2.1",
    "tipoOperacion" => "0101", // Catálogo 51
    "tipoDoc" => "01", // Catálogo 01
    "serie" => "F001",
    "correlativo" => "1",
    "fechaEmision" => now(),
    "formaPago" => [
        'tipo' => 'Contado',
    ],
    "tipoMoneda" => "PEN", // Catálogo 02
    "client" => [
        "tipoDoc" => "6", // Catálogo 06
        "numDoc" => "20000000001",
        "rznSocial" => "EMPRESA X",
    ],
    "mtoOperGravadas" => 100.00,
    "mtoIGV" => 18.00,
    "totalImpuestos" => 18.00,
    "valorVenta" => 100.00,
    "subTotal" => 118.00,
    "mtoImpVenta" => 118.00,
    "details" => [
        [
            "codProducto" => "P001",
            "unidad" => "NIU", // Catálogo 03
            "cantidad" => 2,
            "mtoValorUnitario" => 50.00,
            "descripcion" => "PRODUCTO 1",
            "mtoBaseIgv" => 100,
            "porcentajeIgv" => 18.00,
            "igv" => 18.00,
            "tipAfeIgv" => "10",
            "totalImpuestos" => 18.00,
            "mtoValorVenta" => 100.00,
            "mtoPrecioUnitario" => 59.00,
        ],
    ],
    "legends" => [
        [
            "code" => "1000", // Catálogo 15
            "value" => "SON CIENTO DIECIOCHO CON 00/100 SOLES",
        ],
    ],
];
```

> ⚠️ **Importante:** Para saber qué datos debes enviar según el tipo de comprobante que estés emitiendo, te recomendamos revisar la documentación oficial de [greenter](https://greenter.dev)

Envía el comprobante a SUNAT:

```php
use CodersFree\LaravelGreenter\Facades\Greenter;
use Illuminate\Support\Facades\Storage;

// Enviar a SUNAT
$response = Greenter::send('invoice', $data); //invoice, note, despatch, etc

// Guardar XML
$xml = $response->getXml();
if($xml)
{
    Storage::put("sunat/xml/{$name}.xml", $response->getXml());
}

//Guardamos CDR
if($response->getSunatResponse()->getSuccess())
{
    $cdrZip = $response->getSunatResponse()->getCdrZip();
    Storage::put("sunat/cdr/R-{$name}.zip", $cdrZip);
}
```

### 📨 Estructura de la Respuesta

El método Greenter::send() devuelve una instancia de SendResultDTO. Esta clase encapsula toda la información del proceso de firma y envío.

```php
namespace CodersFree\LaravelGreenter\DTOs;

use Greenter\Model\DocumentInterface;

class SendResultDTO
{
    public function __construct(
        private ?DocumentInterface $document = null,
        private ?string $xml = null,
        private ?string $hash = null,
        private ?SunatResponseDto $sunatResponse = null,
    ) {
    }
}
```

```php
<?php

namespace CodersFree\LaravelGreenter\DTOs;

class SunatResponseDto
{
    public function __construct(
        private ?bool $success = null,
        private ?ErrorDto $error = null,
        private ?string $cdrZip = null,
        private ?CdrResponseDto $cdrResponse = null,
    ) {
    }    
}
```

```php
<?php

namespace CodersFree\LaravelGreenter\DTOs;

class ErrorDto
{
    public function __construct(
        private ?string $code = null,
        private ?string $message = null,
    ) {
    }
}
```

```php
<?php

namespace CodersFree\LaravelGreenter\DTOs;

class CdrResponseDto
{
    /**
     * @param string[]|null $notes
     */
    public function __construct(
        private ?bool $accepted = null,
        private ?string $id = null,
        private ?string $code = null,
        private ?string $description = null,
        private ?array $notes = null,
    ) {}
}
```

### Métodos disponibles (Getters)

Puedes acceder a los objetos y valores utilizando los getters de cada DTO:

```php
// --- SendResultDTO ---
$response->getXml();           // string: XML firmado
$response->getHash();          // string: Hash del XML
$response->getDocument();      // DocumentInterface: Objeto del documento (Invoice, Note, etc)
$sunatResponse = $response->getSunatResponse(); // SunatResponseDto

// --- SunatResponseDto ---
$sunatResponse->getSuccess();          // bool: Indica si hubo respuesta de SUNAT (true incluso si fue rechazada con CDR)
$sunatResponse->getCdrZip();           // string|null: Contenido del ZIP del CDR (binario)
$sunatResponse->getError();            // ErrorDto|null: Objeto de error si falló la conexión/validación
$cdrResponse = $sunatResponse->getCdrResponse(); // CdrResponseDto|null: Objeto con datos del CDR

// --- CdrResponseDto ---
if ($cdrResponse) {
    $cdrResponse->getAccepted();        // bool: true si el estado es aceptado
    $cdrResponse->getCode();           // string: '0' = Aceptado
    $cdrResponse->getDescription();    // string: Mensaje de respuesta de SUNAT
    $cdrResponse->getNotes();          // array: Observaciones
}

// --- ErrorDto ---
$error = $sunat->getError();
if ($error) {
    $error->getCode();         // string: Código de error
    $error->getMessage();      // string: Descripción del error
}
```

### Convertir a Array

Para facilitar la creación de APIs, todos los DTOs implementan un método toArray(). Ejemplo:

```php
return response()->json($response->toArray());
```

### 🔁 Emisión Dinámica para Múltiples Empresas

Para cambiar de empresa, credenciales o personalizar parámetros del reporte en tiempo de ejecución, utiliza el helper config() de Laravel antes de llamar a los métodos de Laravel Greenter.

```php
// Configuración dinámica
config([
    // Cambiar empresa emisora
    'greenter.company.ruc' => '20999999999',
    'greenter.company.razonSocial' => 'OTRA EMPRESA SAC',
    'greenter.company.clave_sol.user' => 'MODDATOS',
    'greenter.company.clave_sol.password' => 'MODDATOS',
    'greenter.company.certificate' => storage_path('certs/otro_cert.pem'),

    // Personalizar reporte
    'greenter.report.system.logo' => public_path('images/otro_logo.png'),
    'greenter.report.user.header' => 'Teléfono: 999-999-999',
]);

// Ahora emite el comprobante con la nueva configuración
$result = Greenter::send('invoice', $data);
```

## 🔏 Gestión de XML

Si necesitas manipular el XML manualmente o enviarlo posteriormente.

### Generar XML Firmado

Genera el XML sin enviarlo a SUNAT:

```php
use CodersFree\LaravelGreenter\Facades\GreenterXml;

$xmlSigned = GreenterXml::generateXml('invoice', $data); // Retorna string
```

### Enviar XML existente

Si ya tienes un XML firmado y deseas enviarlo:

```php
// Retorna el mismo SendResultDTO explicado arriba
$result = Greenter::sendXml('invoice', $xmlSigned);
```

## 🎨 Generar Representación Impresa

### 🧾 HTML

```php
use CodersFree\LaravelGreenter\Facades\GreenterReport;

$result = Greenter::send('invoice', $data);
$document = $result->getDocument();

if($document)
{
    $html = GreenterReport::generateHtml($document);
}
```

### 🖨️ PDF

Es necesario tener [wkhtmltopdf](https://wkhtmltopdf.org) instalado en el sistema para generar archivos PDF. Una vez instalado, configura la ruta del ejecutable en el archivo `config/greenter.php`:

```php
'report' => [
    'bin_path' => '/usr/local/bin/wkhtmltopdf',
],
```

```php
use CodersFree\LaravelGreenter\Facades\GreenterReport;

$result = Greenter::send('invoice', $data);
$document = $result->getDocument();

if($document)
{
    $name = $document->getName();

    // Generar PDF
    $pdf = GreenterReport::generatePdf($document);
    Storage::put("sunat/pdf/{$name}.pdf", $pdf);
}
```

### 🛠️ Generar Reporte sin Enviar a SUNAT

Si necesitas visualizar el PDF antes de enviar el comprobante o simplemente para pruebas, debes construir el documento usando los Builders:

```php
use CodersFree\LaravelGreenter\Builders\InvoiceBuilder;
use CodersFree\LaravelGreenter\Facades\GreenterReport;

$data = [ ... ]; // Tus datos

// 1. Construir el documento manualmente
// Usa InvoiceBuilder, NoteBuilder, DespatchBuilder, etc. según corresponda
$document = (new InvoiceBuilder())->build($data);
$name = $document->getName();

// 2. Generar el reporte
$pdf = GreenterReport::generatePdf($document);
Storage::put("sunat/pdf/{$name}.pdf", $pdf);
```

## 📦 Otros Tipos de Comprobantes

Además de facturas, puedes emitir:

| Tipo de Comprobante | Código       | Descripción                     |
|---------------------|--------------|---------------------------------|
| Factura             | `invoice`    | Factura electrónica (01)        |
| Boleta              | `invoice`    | Boleta de venta (03)            |
| Nota de Crédito     | `note`       | Nota de crédito electrónica (07)|
| Nota de Débito      | `note`       | Nota de débito electrónica (08) |
| Guía de Remisión    | `despatch`   | Guía de remisión electrónica    |
| Resumen Diario      | `summary`    | Resumen diario de boletas (RC)  |
| Comunicación de Baja| `voided`     | Comunicación de baja (RA)       |
| Retención           | `retention`  | Comprobante de retención        |
| Percepción          | `perception` | Comprobante de percepción       |

Consulta la [documentación de Greenter](https://github.com/thegreenter/greenter) para ver los campos específicos de cada uno.

### 🎨 Personalizar Plantillas

Publica las plantillas del reporte:

```bash
php artisan vendor:publish --tag=greenter-templates
```

Ubicación por defecto:
`resources/views/vendor/laravel-greenter`

Puedes personalizar y cambiar la ruta:

```php
'report' => [
    'template' => resource_path('templates/laravel-greenter'),
],
```

## 🧪 Facades Disponibles

| Alias            | Función principal                                       |
| ---------------- | --------------------------------------------------------|
| `Greenter`       | Firma y envía comprobantes electrónicos                 |
| `GreenterXml`    | Genera únicamente el XML firmado a partir de los datos. |
| `GreenterReport` | Genera HTML o PDF de la representación impresa          |

## 🔐 Seguridad Recomendada

* Usa `.env` para tus claves y certificados
* Nunca subas archivos sensibles al repositorio
* Protege rutas usando `storage_path()` o `config_path()`
* Valida los datos antes de emitir comprobantes

## 📄 Licencia

Este paquete está bajo licencia MIT.
Desarrollado por [CodersFree](https://codersfree.com)