<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Repositories\ServiceRepository;

/**
 * Redirecciones 301 desde las URLs del WordPress anterior.
 *
 * En WordPress los servicios, las notas del blog y algunas páginas vivían en la raíz
 * (`/fracttal-one/`), y además existían archivos de categoría y autor. El inventario
 * sale del `wp-sitemap.xml` del sitio antiguo (noviembre 2026).
 */
final class LegacyRedirectController
{
    /**
     * Notas y páginas antiguas (en la raíz) → destino en el sitio nuevo.
     * Los servicios no se listan aquí: se resuelven por slug contra la base de datos.
     */
    private const ROOT_SLUGS = [
        'futuro-de-la-innovacion-en-soluciones-hidraulicas' => '/blog/futuro-de-la-innovacion-en-soluciones-hidraulicas',
        'checklist-esencial-estan-tus-sistemas-hidraulicos-preparados-para-una-inspeccion-tecnica' => '/blog/checklist-esencial-inspeccion-tecnica',
        'como-optimizar-sistemas-en-edificios-y-grandes-empresas' => '/blog/como-optimizar-sistemas-en-edificios-y-grandes-empresas',
        'impulsando-la-eficiencia-hidrica-inteligencia-artificial-en-el-tratamiento-de-aguas' => '/blog/impulsando-la-eficiencia-hidrica-inteligencia-artificial-tratamiento-de-aguas',
        'hidrocinco-comprometidos-con-la-sostenibilidad-ambiental' => '/blog/hidrocinco-comprometidos-con-la-sostenibilidad-ambiental',
        'fracttal-one' => '/blog/eficiencia-en-tu-mantenimiento-con-fracttal-one',
        'guia-de-mantenimiento-sistemas-hidraulicos-en-edificios' => '/blog/guia-de-mantenimiento-sistemas-hidraulicos-en-edificios',
        'en-mantencion' => '/',
        'formulario-expo' => '/',
    ];

    /** `/{slug}` de la raíz: nota/página antigua o servicio antiguo. */
    public function rootSlug(string $slug): void
    {
        if (isset(self::ROOT_SLUGS[$slug])) {
            redirect(self::ROOT_SLUGS[$slug], 301);
        }

        $service = (new ServiceRepository())->findBySlug($slug, true);
        if ($service) {
            redirect('/servicios/' . $service['slug'], 301);
        }

        not_found();
    }

    /** `/category/{slug}` y `/author/{slug}`: archivos de WordPress → índice del blog. */
    public function toBlog(string $slug): void
    {
        redirect('/blog', 301);
    }
}
