<?php

declare(strict_types=1);

use App\Database;
use App\Repositories\PostRepository;
use App\Repositories\ServiceRepository;

require dirname(__DIR__) . '/src/bootstrap.php';

$services = [
    [
        'slug' => 'pozos-profundos', 'title' => 'Pozos profundos', 'icon' => 'img/icons/pozos.png', 'hero_image' => 'img/servicios/header-pozos-profundos.jpg',
        'card_summary' => 'Ejecución, implementación y evaluación de pozos profundos.', 'sort_order' => 1, 'is_published' => true,
        'content' => [['type' => 'intro', 'title' => 'Sobre el servicio', 'image' => 'img/servicios/pozos-profundos.jpg', 'paragraphs' => [
            'Contamos con la capacidad de ejecutar pozos profundos, con su respectiva implementación que consiste en suministro de motobombas, tuberías, tablero control y fuerza.',
            'Para pozos existentes, tenemos la capacidad de evaluar el buen funcionamiento de los pozos, mediante filmación y posterior corrección. Consisten en el reemplazo de motobomba, tuberías y en caso de ser necesario, profundizar los pozos existentes.',
        ]]],
    ],
    [
        'slug' => 'lavado-de-estanques', 'title' => 'Lavado de estanques', 'icon' => 'img/icons/estanque.png', 'hero_image' => 'img/servicios/header-lavado-de-estanques.jpg',
        'card_summary' => 'Lavado sanitario de estanques de agua potable y pozos de aguas servidas.', 'sort_order' => 2, 'is_published' => true,
        'content' => [
            ['type' => 'intro', 'title' => 'Servicio de Lavado de estanques de agua potable', 'image' => 'img/servicios/lavado-de-estanques.jpg', 'paragraphs' => ['El Servicio de Lavado de Pozos de Aguas Servidas es fundamental para lograr el buen funcionamiento de los sistemas de evacuación de aguas servidas, con el fin de evitar que las bombas sumergidas de elevación de aguas servidas se obstruyan.']],
            ['type' => 'feature', 'title' => '“Reglamento de los servicios de agua destinados al consumo humano”', 'decor' => true, 'paragraphs' => ['Según el decreto 76 que modifica decreto 735 del “Reglamento de los servicios de agua destinados al consumo humano” Es necesario ejecutar lavados a los estanques de agua potable al menos una vez al año.']],
        ],
    ],
    [
        'slug' => 'plantas-de-tratamientos-de-aguas-servidas-ptas', 'title' => 'Plantas de tratamiento (PTAS)', 'icon' => 'img/icons/ptas.png', 'hero_image' => 'img/servicios/header-ptas.jpg',
        'card_summary' => 'Operación, mantenimiento e ingeniería para plantas de tratamiento.', 'sort_order' => 3, 'is_published' => true,
        'content' => [
            ['type' => 'intro', 'title' => 'Plan de mantenimiento', 'image' => 'img/servicios/ptas.jpg', 'paragraphs' => [
                'Hidrocinco ofrece a sus clientes un plan de mantenimiento, que incluye la revisión operativa de los equipos, el estudio in situ de la calidad del lodo y el efluente, la medición de los principales parámetros operacionales de las plantas y el análisis a las aguas residuales de acuerdo al Decreto supremo 90, que regula su descarga a cursos superficiales de agua.',
                'Hidrocinco, además ofrece un plan de mejoras que permite optimizar los requerimientos operacionales de las plantas existente, por medio de estudios técnicos. Contamos con experiencia en plantas de lodos activados, aireación extendida y lechos empacados, desde su diseño, construcción y mantenimiento.',
                'Con el plan de mantenimiento, la asesoría técnica del equipo de ingeniería y la amplia red de móviles de emergencia, desplegados desde La IV Región hasta la X Región, nuestra empresa logra entregar una solución integral y un servicio orientado a lograr la continuidad operacional de los sistemas de tratamientos de aguas servidas.',
            ]],
            ['type' => 'list', 'title' => 'Servicio de operación de Plantas de Tratamientos', 'intro' => 'El servicio de mantención preventiva logra un Biproceso controlado y funcionando al nivel de desempeño para obtener un efluente con bajos niveles de contaminación, mediante el control de los parámetros relevantes directos e indirectos, entre los cuales se puede mencionar:', 'items' => ['Medición de pH', 'Medición de Oxígeno disuelto', 'Medición de temperatura', 'Medición de manto de lodos', 'Medición de sedimentabilidad de lodos', 'Medición de Cloro Libre residual en el efluente', 'Medición de solidos suspendidos totales']],
            ['type' => 'feature', 'title' => 'Desarrollo de proyectos e instalaciones', 'paragraphs' => ['Hidrocinco cuenta con un departamento de Ingeniería especializado en Plantas de tratamiento y que es capaz de dar soluciones orientadas al saneamiento ambiental, de los recurso hídricos, con el objetivo de dar cumplimiento a las normativas ambientales vigentes y cuidar el medio ambiente.']],
        ],
    ],
    [
        'slug' => 'sala-de-calderas', 'title' => 'Sala de Calderas', 'icon' => 'img/icons/caldera.png', 'hero_image' => 'img/servicios/header-sala-de-calderas.jpg',
        'card_summary' => 'Mantenimiento preventivo de calderas y atención de emergencias.', 'sort_order' => 4, 'is_published' => true,
        'content' => [['type' => 'intro', 'title' => 'Mantención', 'image' => 'img/servicios/sala-de-calderas.jpg', 'highlight_first' => true, 'paragraphs' => [
            'Hidrocinco se encuentra con equipos de técnicos capacitados para otorgar el mantenimiento preventivo de calderas',
            'que se complementa con el servicio de mantenimineot de las salas de bomba, con un plan de mantenimiento que prevee el deterioro del sistema, con un servicio de atención de emergencias 24/7',
        ]]],
    ],
    [
        'slug' => 'taller-y-servicio-tecnico', 'title' => 'Taller y Servicio Técnico', 'icon' => 'img/icons/taller.png', 'hero_image' => 'img/servicios/header-taller.jpg',
        'card_summary' => 'Reparación, fabricación, automatización y soporte técnico especializado.', 'sort_order' => 5, 'is_published' => true,
        'content' => [
            ['type' => 'list', 'title' => 'Taller y Servicio Técnico', 'items' => ['Reparación de bombas', 'Fabricación de membranas de caucho para cilindros hidroneumáticos', 'Sopladores', 'Motorreductores', 'Acondicionamiento de piezas para bombas y accesorios para el equipamiento de plantas de tratamiento y Plantas elevadoras de aguas servidas.', 'Bridas', 'Difusores', 'Cadenas para Biodiscos', 'Canastillos de pozos de aguas servidas']],
            ['type' => 'list', 'title' => 'Taller eléctrico', 'items' => ['Diseño y fabricación de tableros de fuerza y control', 'Programación de sistemas PLC', 'Fabricación de variadores de frecuencia', 'Instalaciones eléctricas de sistema de bombeo']],
            ['type' => 'list', 'title' => 'Taller metalmecánico', 'intro' => 'Nuestra empresa cuenta con servicio mecanizado, diseño y fabricación de:', 'items' => ['Cilindros hidroneumáticos', 'Manifold de sistema de bombeo en cobre, fierro, polipropileno random (PPR o termofusión) y policloruro de vinilo (PVC)', 'Bridas', 'Difusores', 'Cadenas para Biodiscos', 'Canastillos de pozos de aguas servidas', 'Soportes y estructuras de mejoramiento para sistemas de bombeos y plantas de tratamiento.']],
        ],
    ],
    [
        'slug' => 'limpia-fosas', 'title' => 'Limpia Fosas', 'icon' => 'img/icons/limpia-fosas.png', 'hero_image' => 'img/servicios/header-limpia-fosas.jpg',
        'card_summary' => 'Retiro, transporte y disposición certificada de residuos no peligrosos.', 'sort_order' => 6, 'is_published' => true,
        'content' => [['type' => 'intro', 'title' => 'Sobre el servicio', 'image' => 'img/servicios/limpia-fosas.jpg', 'paragraphs' => [
            'Este servicio comprende el transporte, manejo y disposición final de residuos no peligrosos provenientes de cámaras desgrasadoras, además de la mantención y limpieza de redes, retiro de aguas servidas y destape de alcantarillado.',
            'Nuestro proceso se encuentra certificado y contamos con todas las resoluciones sanitarias requeridas para el transporte, gestión y disposición de residuos.',
        ]]],
    ],
    [
        'slug' => 'sala-de-bombas', 'title' => 'Sala de bombas', 'icon' => 'img/icons/bombas.png', 'hero_image' => 'img/servicios/header-sala-de-bombas.jpg',
        'card_summary' => 'Mantenimiento preventivo, correctivo y monitoreo de sistemas de bombeo.', 'sort_order' => 7, 'is_published' => true,
        'content' => [
            ['type' => 'intro', 'title' => 'Mantención', 'image' => 'img/servicios/sala-de-bombas.jpg', 'highlight_first' => true, 'paragraphs' => [
                'Nuestra empresa cuenta con el Servicio de Mantenimiento Preventivo y Correctivo, para ello, contamos con equipos de Técnicos en terreno con conocimientos eléctricos y mecánicos.',
                'Contamos con un software de mantenimiento que nos permite controlar sus activos y con ello asegurar la continuidad operacional de sus sistemas. Nos encontramos capacitados para ofrecer nuestro software que nos permite el monitoreo, alerta, alarmas y notificaciones, orientadas a la generación de protocolos de mantenimiento predictivo.',
            ]],
            ['type' => 'feature', 'title' => 'Desarrollo de proyectos e instalaciones', 'paragraphs' => ['Estamos capacitados para el desarrollo de Proyectos, sistemas de bombeo, Manifold, fabricación de tableros y sistemas de control y automatización.']],
        ],
    ],
];

$serviceRepository = new ServiceRepository();
foreach ($services as $service) {
    $existing = $serviceRepository->findBySlug($service['slug']);
    if ($existing) {
        $serviceRepository->update((int) $existing['id'], $service);
    } else {
        $serviceRepository->create($service);
    }
}

$postData = [
    'slug' => 'guia-de-mantenimiento-sistemas-hidraulicos-en-edificios',
    'title' => 'Guía de mantenimiento | Sistemas Hidráulicos en edificios',
    'status' => 'published', 'published_at' => '2024-01-15',
    'excerpt' => 'Los sistemas hidráulicos en edificios desempeñan un papel crucial en el funcionamiento diario de las instalaciones. Desde el suministro de agua potable hasta el drenaje de aguas residuales, estos sistemas deben mantenerse en condiciones óptimas para garantizar la comodidad y la seguridad de los ocupantes.',
    'featured_image' => 'img/blog/guia-mantenimiento.jpg',
    'body_html' => <<<'HTML'
<p><strong>En esta guía, exploraremos las mejores prácticas para el mantenimiento de sistemas hidráulicos en edificios, proporcionando consejos prácticos para asegurar su rendimiento y durabilidad a largo plazo.</strong></p>
<ol>
<li><h3>Inspecciones regulares:</h3><p>Una parte fundamental del mantenimiento de sistemas hidráulicos en edificios es realizar inspecciones regulares. Estas inspecciones pueden identificar problemas potenciales antes de que se conviertan en emergencias costosas. Se deben revisar tuberías, válvulas, grifos y otros componentes para detectar signos de desgaste, fugas o corrosión.</p></li>
<li><h3>Limpieza y desagüe:</h3><p>La limpieza regular de tuberías y desagües es esencial para prevenir obstrucciones y mantener un flujo de agua eficiente. Se pueden utilizar métodos de limpieza mecánica o química para eliminar acumulaciones de sedimentos, residuos y otros materiales que puedan obstruir las tuberías.</p></li>
<li><h3>Mantenimiento de bombas y calderas:</h3><p>Las bombas y calderas son componentes clave de muchos sistemas hidráulicos en edificios. Es importante realizar un mantenimiento regular de estos equipos para garantizar su funcionamiento seguro y eficiente. Esto puede incluir la lubricación de partes móviles, la limpieza de filtros y la realización de pruebas de funcionamiento.</p></li>
<li><h3>Reparaciones oportunas:</h3><p>Cualquier problema identificado durante las inspecciones regulares debe abordarse de inmediato. Las reparaciones o reemplazos necesarios deben realizarse de manera oportuna para evitar daños mayores o interrupciones en el suministro de agua.</p></li>
<li><h3>Actualización de sistemas obsoletos:</h3><p>Los sistemas hidráulicos en edificios pueden volverse obsoletos con el tiempo, lo que puede provocar problemas de rendimiento y eficiencia. En algunos casos, puede ser necesario actualizar o modernizar estos sistemas para garantizar su funcionalidad y cumplir con los estándares actuales de seguridad y eficiencia.</p></li>
</ol>
<p>En resumen, el mantenimiento adecuado de los sistemas hidráulicos en edificios es esencial para garantizar su rendimiento óptimo y prolongar su vida útil. Al seguir estas mejores prácticas de mantenimiento, los propietarios y administradores de edificios pueden evitar problemas costosos y mantener sus instalaciones en condiciones óptimas para el uso diario.</p>
HTML,
];

$postRepository = new PostRepository();
$existingPost = $postRepository->findBySlug($postData['slug']);
$existingPost ? $postRepository->update((int) $existingPost['id'], $postData) : $postRepository->create($postData);

$db = Database::connection();
$admin = $db->prepare('SELECT id FROM admin_users WHERE email = :email');
$admin->execute(['email' => 'admin@hidrocinco.cl']);
$adminId = $admin->fetchColumn();
if (!$adminId) {
    $adminData = ['email' => 'admin@hidrocinco.cl', 'password_hash' => password_hash('Hidrocinco2026!', PASSWORD_DEFAULT), 'name' => 'Administrador'];
    $db->prepare('INSERT INTO admin_users (email,password_hash,name) VALUES (:email,:password_hash,:name)')->execute($adminData);
}

echo "Seed completado: 7 servicios, 1 nota y 1 usuario administrador.\n";
