# Sistema de Gestión de Precios y Proveedores

Sistema desarrollado en Laravel para automatizar la actualización de precios de un almacén a partir de las listas que envían los proveedores en formato PDF.

## 🎯 Problema que resuelve

Actualizar precios manualmente a partir de listas de proveedores es lento y propenso a errores. Este sistema automatiza el proceso: extrae la información de los PDFs, categoriza los productos con IA y actualiza la base de datos, reduciendo el trabajo manual a una revisión final.

## 🛠️ Stack técnico

- **Backend:** Laravel, MySQL
- **Automatización:** n8n (extracción de PDFs, orquestación del flujo)
- **IA:** API de Claude (Anthropic) — categorización automática de productos
- **Control de versiones:** Git

## ⚙️ Cómo funciona

1. Se recibe el PDF de precios de un proveedor.
2. n8n extrae el texto del documento (sin necesidad de OCR, ya que los PDFs son generados digitalmente).
3. El texto extraído se envía a la API de Claude con un prompt estructurado, que devuelve los productos identificados en formato JSON (nombre, precio, categoría).
4. El sistema compara los datos contra los productos ya existentes en la base y actualiza únicamente lo que cambió.
5. Los cambios quedan reflejados en el panel de Laravel para su revisión.

## 📦 Funcionalidades principales

- Gestión de productos, categorías y proveedores.
- Registro de ofertas y actualización de precios por proveedor.
- Dashboard con historial de actualizaciones.

## 🚧 Estado del proyecto

En desarrollo activo. Próximos pasos: mejorar el manejo de errores en el flujo de n8n (notificaciones automáticas si falla la extracción de un PDF) y ampliar la categorización a más proveedores.

## 🔒 Nota sobre datos sensibles

El archivo `.env` con credenciales no está incluido en el repositorio, según las buenas prácticas de seguridad.