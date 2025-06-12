# Laravel Greenter

**Laravel Greenter** es un paquete para emitir comprobantes electrónicos desde Laravel utilizando [Greenter](https://github.com/thegreenter/greenter). Permite:

* Firmar comprobantes digitalmente
* Enviarlos a SUNAT (SEE o API REST)
* Generar su representación impresa en PDF

[![MIT License](https://img.shields.io/badge/license-MIT-blue.svg)](LICENSE)

## 📚 Tabla de Contenidos

- [📦 Requisitos](#-requisitos)
- [🚀 Instalación](#-instalación)
- [⚙️ Configuración Inicial](#️-configuración-inicial)
    - [🏢 Datos de la Empresa Emisora](#-datos-de-la-empresa-emisora)
    - [🛠️ Cambiar a Producción](#️-cambiar-a-producción)
- [🧰 Uso Básico](#-uso-básico)
    - [✉️ Enviar una Factura Electrónica](#️-enviar-una-factura-electrónica)
    - [🚚 Enviar una Guía de Remisión (API REST)](#-enviar-una-guía-de-remisión-api-rest)
    - [🔁 Emisión Dinámica para Múltiples Empresas](#-emisión-dinámica-para-múltiples-empresas)
- [🎨 Generar Representación Impresa](#-generar-representación-impresa)
    - [🧾 HTML](#-html)
    - [🖨️ PDF](#️-pdf)
    - [🎨 Personalizar Plantillas](#-personalizar-plantillas)
- [📦 Otros Tipos de Comprobantes](#-otros-tipos-de-comprobantes)
- [🧪 Facades Disponibles](#-facades-disponibles)
- [🧱 Estructura del Paquete](#-estructura-del-paquete)
- [🔐 Seguridad Recomendada](#-seguridad-recomendada)
- [📄 Licencia](#-licencia)

## 📦 Requisitos

Este paquete requiere:

* PHP >= 8.2
* Laravel 11.x o 12.x
* Extensiones: `ext-soap`, `openssl`
* [wkhtmltopdf](https://wkhtmltopdf.org) (opcional, para generar PDFs)

## 🚀 Instalación

Instala el paquete vía Composer:

```bash
composer require codersfree/laravel-greenter
```

Publica los archivos de configuración y recursos:

```bash
php artisan vendor:publish --tag=greenter-laravel
```

Esto generará:

* `config/greenter.php`: configuración del paquete
* `public/images/logo.png`: logo que aparecerá en los PDFs
* `public/certs/certificate.pem`: certificado digital de prueba

## ⚙️ Configuración Inicial

### 🏢 Datos de la Empresa Emisora

Edita `config/greenter.php` con los datos de tu empresa:

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

En `config/greenter.php` modifica el modo y agrega tus credenciales reales proporcionadas por Sunat:

```php
'mode' => 'prod',

'company' => [
    'certificate' => public_path('certs/certificate.pem'),
    'clave_sol' => [
        'user' => 'MODDATOS',
        'password' => 'MODDATOS',
    ],
    'credentials' => [
        'client_id' => '...',
        'client_secret' => '...',
    ],
],
```

> ⚠️ **Importante:** Nunca subas los certificados o claves a tu repositorio.
> No es necesario cambiar nada aqui mientras se está haciendo pruebas.

## 🧰 Uso Básico

### ✉️ Enviar una Factura Electrónica (SOAP)

Primero se debe definir los datos que tendrá el comprobante

```php
//Ejemplo de factura simple

$data = [
    "ublVersion" => "2.1",
    "tipoOperacion" => "0101",
    "tipoDoc" => "01",
    "serie" => "F001",
    "correlativo" => "1",
    "fechaEmision" => "2025-06-12", //Cambiarlo por la fecha actual
    "formaPago" => [
        'tipo' => 'Contado',
    ],
    "tipoMoneda" => "PEN",
    "client" => [
        "tipoDoc" => "6",
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
            "unidad" => "NIU",
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
            "code" => "1000",
            "value" => "SON DOSCIENTOS TREINTA Y SEIS CON 00/100 SOLES",
        ],
    ],
];
```

Envío del documento

```php
use CodersFree\LaravelGreenter\Facades\Greenter;
use Illuminate\Support\Facades\Storage;

try {
    $response = Greenter::send('invoice', $data);

    $name = $response->getDocument()->getName();
    Storage::put("sunat/xml/{$name}.xml", $response->getXml());
    Storage::put("sunat/cdr/{$name}.zip", $response->getCdrZip());

    return response()->json([
        'success' => true,
        'cdrResponse' => $response->readCdr(),
        'xml' => Storage::url("sunat/xml/{$name}.xml"),
        'cdr' => Storage::url("sunat/cdr/{$name}.zip"),
    ]);
} catch (\Throwable $e) {
    return response()->json([
        'success' => false,
        'code' => $e->getCode(),
        'message' => $e->getMessage(),
    ], 500);
}
```

### 🚚 Enviar una Guía de Remisión (API REST)

Ejemplo de guía de remisión

```php
$data = [
    "version" => 2022,
    "tipoDoc" => "09",
    "serie" => "T001",
    "correlativo" => "123",
    "fechaEmision" => "2025-06-12",  //Cambiarlo por la fecha actual
    "destinatario" => [
        "tipoDoc" => "6",
        "numDoc" => 20000000002,
        "rznSocial" => "EMPRESA DEST 1",
        "address" => [
            "direccion" => "Direccion cliente"
        ]
    ],
    "envio" => [
        "codTraslado" => "01",
        "modTraslado" => "01",
        "fecTraslado" => "2025-06-12", //Cambiarlo por la fecha actual
        "pesoTotal" => 12.5,
        "undPesoTotal" => "KGM",
        "llegada" => [
            "ubigueo" => "150101",
            "direccion" => "AV LIMA"
        ],
        "partida" => [
            "ubigueo" => "150203",
            "direccion" => "AV ITALIA"
        ],
        'transportista' => [
            "tipoDoc" => "6",
            "numDoc" => "20000000003",
            "rznSocial" => "TRANSPORTISTA X",
            "nroMtc" => "0001",
        ],
    ],
    "details" => [
        [
            "cantidad" => 2,
            "unidad" => "ZZ",
            "descripcion" => "PRODUCTO 1",
            "codigo" => "PROD1"
        ]
    ]
];
```

Las guías deben ser envíadas por Api Rest

```php
use CodersFree\LaravelGreenter\Facades\GreenterApi;
use Illuminate\Support\Facades\Storage;

try {
    $response = GreenterApi::send('despatch', $data);

    $name = $response->getDocument()->getName();
    Storage::put("sunat/xml/{$name}.xml", $response->getXml());
    Storage::put("sunat/cdr/{$name}.zip", $response->getCdrZip());

    return response()->json([
        'success' => true,
        'cdrResponse' => $response->readCdr(),
        'xml' => Storage::url("sunat/xml/{$name}.xml"),
        'cdr' => Storage::url("sunat/cdr/{$name}.zip"),
    ]);
} catch (\Throwable $e) {
    return response()->json([
        'success' => false,
        'code' => $e->getCode(),
        'message' => $e->getMessage(),
    ], 500);
}
```

### 🔁 Emisión Dinámica para Múltiples Empresas

Puedes emitir comprobantes desde distintas empresas en tiempo real:

```php

$data = [ ... ]; // Datos de la factura

$response = Greenter::setCompany([
    'ruc' => '20999999999',
    'razonSocial' => 'Otra Empresa SAC',
    'certificate' => public_path('certs/otro_cert.pem'),
    ...
])->send('invoice', $data);
```

## 🎨 Generar Representación Impresa

### 🧾 HTML

```php
use CodersFree\LaravelGreenter\Facades\Greenter;
use CodersFree\LaravelGreenter\Facades\GreenterReport;

$data = [ ... ]; // Datos de la factura

$response = Greenter::send('invoice', $data);

$html = GreenterReport::generateHtml($response->getDocument());
```

### 🖨️ PDF

Requiere tener [wkhtmltopdf](https://wkhtmltopdf.org) instalado. Configura la ruta donde se instalo en `config/greenter.php`:

```php
'report' => [
    'bin_path' => '/usr/local/bin/wkhtmltopdf', // o donde esté instalado
]
```

```php
use CodersFree\LaravelGreenter\Facades\Greenter;
use CodersFree\LaravelGreenter\Facades\GreenterReport;

$data = [ ... ]; // Datos de la factura

$response = Greenter::send('invoice', $data);

$pdf = GreenterReport::generatePdf($response->getDocument());
Storage::put("sunat/pdf/{$name}.pdf", $pdf);
```

### 🎨 Personalizar Plantillas

Publica las plantillas:

```bash
php artisan vendor:publish --tag=greenter-templates
```

Ubicación por defecto:
`resources/views/vendor/laravel-greenter`

Puedes moverlas y configurar la nueva ruta:

```php
'report' => [
    'template' => resource_path('templates/laravel-greenter'),
],
```

> También puedes personalizar los estilos, columnas o el logo (`public/images/logo.png`).

## 📦 Otros Tipos de Comprobantes

Este paquete también permite emitir:

* Boletas
* Notas de crédito / débito
* Retenciones
* Percepciones

Consulta la documentación oficial de [Greenter](https://github.com/thegreenter/greenter) para más detalles sobre cada tipo.

## 🧪 Facades Disponibles

| Alias            | Función principal                             |
| ---------------- | --------------------------------------------- |
| `Greenter`       | Firma y envía comprobantes por SOAP (SEE)     |
| `GreenterApi`    | Envía comprobantes vía API REST               |
| `GreenterReport` | Genera PDF o HTML para representación impresa |

## 🧱 Estructura del Paquete

Ejemplos de métodos disponibles:

* `Greenter::send('invoice', $data)`
* `GreenterApi::send('despatch', $data)`
* `GreenterReport::generateHtml($document)`
* `GreenterReport::generatePdf($document)`

## 🔐 Seguridad Recomendada

* No subas tus certificados ni credenciales al repositorio
* Usa `.env` para claves (`clave_sol`, client\_id, client\_secret, etc.)
* Usa `storage_path()` para rutas privadas
* Valida y sanitiza datos antes de emitir comprobantes

## 📄 Licencia

Este paquete está bajo licencia MIT.
Desarrollado por [CodersFree](https://codersfree.com)
