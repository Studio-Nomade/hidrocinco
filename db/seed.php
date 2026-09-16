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

$morePosts = [
    [
        'slug' => 'futuro-de-la-innovacion-en-soluciones-hidraulicas',
        'title' => 'Futuro de la Innovación en Soluciones Hidráulicas',
        'status' => 'published', 'published_at' => '2025-03-26',
        'featured_image' => 'img/blog/futuro-innovacion.webp',
        'excerpt' => 'Inteligencia Artificial para mantenimiento predictivo: las fallas en los sistemas hidráulicos serán detectadas antes de que ocurran gracias a IA y machine learning.',
        'body_html' => <<<'HTML'
<h3>1. 📈 Inteligencia Artificial para mantenimiento predictivo</h3>
<p>Las fallas en los sistemas hidráulicos serán detectadas antes de que ocurran gracias a IA y machine learning. Algoritmos analizarán datos en tiempo real para predecir averías en bombas, tuberías y sistemas de tratamiento de agua, reduciendo costos de mantenimiento y tiempos de inactividad.</p>
<h3>2. 📶 Sensores IoT para monitoreo remoto</h3>
<p>Los dispositivos de Internet de las Cosas (IoT) permitirán controlar en tiempo real el rendimiento de los sistemas de extracción, acumulación e impulsión de agua. Esto optimizará el consumo energético y garantizará una respuesta inmediata ante posibles fallas.</p>
<h3>3. 💡 Energías renovables en sistemas hidráulicos</h3>
<p>El uso de paneles solares y turbinas hidráulicas en pozos profundos y estaciones de bombeo será cada vez más común, reduciendo la dependencia de fuentes de energía tradicionales y disminuyendo la huella de carbono de los sistemas hidráulicos.</p>
<h3>4. ⚙️ Nanotecnología para tratamiento de agua</h3>
<p>Filtros con nanotecnología avanzarán en la eliminación de contaminantes, permitiendo un tratamiento de agua más eficiente y sostenible. Esto será clave en sectores donde la calidad del agua es crítica, como comunidades rurales o industrias.</p>
<h3>5. 🚀 Impresión 3D para repuestos y estructuras hidráulicas</h3>
<p>La fabricación aditiva permitirá crear piezas personalizadas para sistemas hidráulicos en tiempo récord, reduciendo los tiempos de reparación y mejorando la eficiencia en la reposición de equipos.</p>
<h3>6. 💧 Reutilización de aguas residuales con tecnología avanzada</h3>
<p>El tratamiento y reuso de aguas residuales con biotecnología e inteligencia artificial será clave en la optimización de recursos hídricos, especialmente en ciudades y empresas que buscan reducir el consumo de agua potable en procesos industriales.</p>
<h3>7. 🔗 Blockchain para gestión de recursos hídricos</h3>
<p>El registro descentralizado de datos mediante blockchain garantizará la trazabilidad y transparencia en el consumo de agua, facilitando auditorías en tiempo real y optimizando la gestión de permisos de extracción.</p>
<p>El futuro de las soluciones hidráulicas será más inteligente, eficiente y sostenible. 🌎💧 ¿Cuál de estas tendencias crees que tendrá más impacto en tu negocio? 🚀</p>
HTML,
    ],
    [
        'slug' => 'checklist-esencial-inspeccion-tecnica',
        'title' => 'Checklist Esencial: ¿Están Tus Sistemas Hidráulicos Preparados para una Inspección Técnica?',
        'status' => 'published', 'published_at' => '2025-01-27',
        'featured_image' => 'img/blog/checklist-inspeccion.webp',
        'excerpt' => 'Si eres dueño de un negocio, administrador de edificios y/o grandes empresas, sabemos que garantizar el funcionamiento eficiente de los sistemas hidráulicos es crucial. Una inspección técnica puede convertirse en un dolor de cabeza si los sistemas no cumplen con los estándares requeridos.',
        'body_html' => <<<'HTML'
<p><strong>Si eres dueño de un negocio, administrador de edificios y/o grandes empresas, sabemos que garantizar el funcionamiento eficiente de los sistemas hidráulicos es crucial. Una inspección técnica puede convertirse en un dolor de cabeza si los sistemas de extracción, acumulación, impulsión y tratamiento de agua no cumplen con los estándares requeridos.</strong></p>
<p>En Hidrocinco, sabemos lo importante que es estar preparados, por eso te compartimos este checklist esencial para asegurar que tus sistemas hidráulicos estén en óptimas condiciones.</p>
<h3>1. Revisión de Salas de Bombas</h3>
<p>Las salas de bombas son el corazón de cualquier sistema hidráulico. Para garantizar su correcto funcionamiento, verifica:</p>
<ul>
<li>Estado de las bombas (ausencia de fugas y ruido excesivo).</li>
<li>Presión y caudal dentro de los niveles especificados.</li>
<li>Condiciones de los tableros de control y sistemas de automatización.</li>
<li>Correcto funcionamiento de los sensores y dispositivos de monitoreo.</li>
</ul>
<p><em>Consejo:</em> Realiza un mantenimiento preventivo con técnicos especializados como los de Hidrocinco, quienes utilizan software avanzado para monitorear el estado de tus activos.</p>
<h3>2. Limpieza y Lavado de Estanques</h3>
<p>Los estanques acumuladores son fundamentales para almacenar agua limpia. Antes de una inspección, asegúrate de:</p>
<ul>
<li>Realizar un lavado profesional para eliminar sedimentos y bacterias.</li>
<li>Inspeccionar la estructura en busca de grietas o corrosión.</li>
<li>Comprobar que los sistemas de entrada y salida de agua estén operando correctamente.</li>
</ul>
<h3>3. Mantenimiento de Plantas de Tratamiento de Aguas</h3>
<p>Las Plantas de Tratamiento de Aguas Servidas (PTAS) deben cumplir con estrictas normativas ambientales. Para garantizar que están listas:</p>
<ul>
<li>Verifica el correcto funcionamiento de los sistemas de aireación y filtración.</li>
<li>Controla que los parámetros de calidad del agua tratada cumplan con las regulaciones.</li>
<li>Limpia regularmente los tanques de sedimentación.</li>
</ul>
<h3>4. Inspección de Pozos Profundos</h3>
<p>En sistemas que dependen de pozos profundos, revisa:</p>
<ul>
<li>La integridad estructural del pozo y la tubería.</li>
<li>El estado de las bombas sumergibles y sus componentes eléctricos.</li>
<li>Que no haya presencia de contaminantes en el agua extraída.</li>
</ul>
<h3>5. Evaluación Eléctrica y de Automatización</h3>
<p>La tecnología juega un papel clave en los sistemas hidráulicos modernos. Antes de la inspección:</p>
<ul>
<li>Asegúrate de que los tableros eléctricos y los sistemas de control estén actualizados y en buenas condiciones.</li>
<li>Verifica que los sensores de nivel, presión y flujo estén calibrados correctamente.</li>
<li>Comprueba que el sistema de monitoreo remoto esté operando de manera eficiente.</li>
</ul>
<h3>Beneficios de Estar Preparado</h3>
<p>Estar listo para una inspección técnica no solo evita multas y sanciones, sino que también:</p>
<ul>
<li><strong>Reduce costos a largo plazo:</strong> El mantenimiento preventivo es más económico que las reparaciones de emergencia.</li>
<li><strong>Garantiza la continuidad operativa:</strong> Minimiza los riesgos de interrupciones inesperadas.</li>
<li><strong>Cumple con normativas ambientales:</strong> Especialmente en plantas de tratamiento y sistemas de agua potable.</li>
</ul>
<h3>¡Prepárate con los expertos de Hidrocinco!</h3>
<p>En Hidrocinco, contamos con más de 40 años de experiencia ayudando a empresas y administradores a mantener sus sistemas hidráulicos en óptimas condiciones. Nuestros servicios incluyen mantenimiento preventivo, reparaciones correctivas y soluciones tecnológicas avanzadas para monitorear tus sistemas en tiempo real.</p>
<p>Contáctanos hoy mismo para asegurar que tus sistemas hidráulicos superen cualquier inspección sin contratiempos. Llámanos al +56225567241 o escríbenos a hidrocinco@hidrocinco.cl.</p>
HTML,
    ],
    [
        'slug' => 'como-optimizar-sistemas-en-edificios-y-grandes-empresas',
        'title' => 'Cómo Optimizar Sistemas en Edificios y Grandes Empresas',
        'status' => 'published', 'published_at' => '2025-01-16',
        'featured_image' => 'img/blog/optimizar-edificios.webp',
        'excerpt' => 'En el mundo empresarial actual, la eficiencia operativa es clave para mantener la competitividad y minimizar costos. Los sistemas hidráulicos representan un componente crítico que impacta directamente en los costos energéticos y en el cumplimiento de normativas.',
        'body_html' => <<<'HTML'
<p><strong>En el mundo empresarial actual, la eficiencia operativa es clave para mantener la competitividad y minimizar costos. Para empresas y administradores de edificios, los sistemas hidráulicos representan un componente crítico que no solo asegura el suministro y tratamiento de agua, sino que también impacta directamente en los costos energéticos y en el cumplimiento de normativas.</strong></p>
<p>En Hidrocinco, con más de 40 años de experiencia en el rubro, combinamos tecnología de punta con soluciones personalizadas para optimizar estos sistemas y garantizar su funcionamiento eficiente.</p>
<h3>¿Por qué apostar por tecnología avanzada en sistemas hidráulicos?</h3>
<p>La implementación de herramientas tecnológicas en sistemas hidráulicos ofrece beneficios tangibles para grandes empresas y edificios:</p>
<ol>
<li><strong>Ahorro Energético:</strong> Los sistemas de control y automatización permiten ajustar el funcionamiento de bombas y motores según la demanda real, reduciendo el consumo de energía.</li>
<li><strong>Prevención de Fallas:</strong> El monitoreo remoto y en tiempo real permite identificar irregularidades antes de que se conviertan en problemas graves, evitando reparaciones costosas y tiempos de inactividad.</li>
<li><strong>Optimización de Recursos Hídricos:</strong> La tecnología permite medir y gestionar el uso del agua de manera eficiente, ayudando a cumplir con normativas de sostenibilidad.</li>
<li><strong>Continuidad Operacional:</strong> Las soluciones tecnológicas aseguran un funcionamiento ininterrumpido, especialmente en operaciones críticas como plantas de tratamiento y salas de bombas.</li>
</ol>
<h3>Casos de éxito: La experiencia de Hidrocinco</h3>
<p>En Hidrocinco, hemos implementado sistemas avanzados que garantizan el rendimiento óptimo de los sistemas hidráulicos de nuestros clientes. Uno de los servicios más destacados es el mantenimiento preventivo y correctivo en salas de bombas, donde utilizamos un software especializado que permite monitorear y controlar los activos hidráulicos en tiempo real.</p>
<p>Además, contamos con experiencia en:</p>
<ul>
<li>Diseño e instalación de tableros de control y automatización.</li>
<li>Fabricación de sistemas de bombeo personalizados para satisfacer las necesidades específicas de cada cliente.</li>
<li>Integración de herramientas de monitoreo remoto para garantizar un rendimiento continuo.</li>
</ul>
<h3>Beneficios de elegir Hidrocinco</h3>
<p>Optar por Hidrocinco como aliado estratégico significa acceder a un equipo especializado que entiende la importancia de la tecnología en el rubro hidráulico. Nuestra visión es liderar el mercado nacional ofreciendo soluciones hidráulicas integrales que combinen innovación y eficiencia.</p>
<p>Algunos beneficios adicionales incluyen:</p>
<ul>
<li>Servicio de emergencia 24/7 para garantizar continuidad operacional.</li>
<li>Soluciones personalizadas para cada sector: rural, industrial y urbano.</li>
<li>Uso de herramientas tecnológicas que aseguran un control total de los sistemas hidráulicos.</li>
</ul>
<h3>¡Transforma tus sistemas hidráulicos hoy!</h3>
<p>La innovación en tecnología hidráulica no solo mejora el rendimiento de tus sistemas, sino que también reduce costos a largo plazo y aumenta la sostenibilidad de tus operaciones. En Hidrocinco, estamos listos para ayudarte a optimizar cada componente de tus sistemas de agua.</p>
<p>Contáctanos hoy mismo y descubre cómo la tecnología puede marcar la diferencia en tus proyectos. Llámanos al +56225567241 o escríbenos a hidrocinco@hidrocinco.cl.</p>
<p>Juntos, podemos asegurar que tus sistemas hidráulicos estén a la vanguardia de la eficiencia y la innovación.</p>
HTML,
    ],
    [
        'slug' => 'impulsando-la-eficiencia-hidrica-inteligencia-artificial-tratamiento-de-aguas',
        'title' => 'Impulsando la Eficiencia Hídrica: Inteligencia Artificial en el Tratamiento de Aguas',
        'status' => 'published', 'published_at' => '2024-05-16',
        'featured_image' => 'img/blog/ia-tratamiento-aguas.webp',
        'excerpt' => 'En un mundo donde la gestión eficiente del agua es cada vez más crucial, la aplicación de la inteligencia artificial (IA) en el monitoreo y control de procesos de tratamiento de aguas está demostrando ser un cambio de juego.',
        'body_html' => <<<'HTML'
<p><strong>En un mundo donde la gestión eficiente del agua es cada vez más crucial, la aplicación de la inteligencia artificial (IA) en el monitoreo y control de procesos de tratamiento de aguas está demostrando ser un cambio de juego.</strong></p>
<p>Algoritmos avanzados de IA, como el aprendizaje automático y la visión por computadora, están revolucionando la forma en que se gestionan los recursos hídricos, permitiendo una mayor eficiencia operativa y una reducción significativa de costos.</p>
<h3>1. Mejora del Monitoreo en Tiempo Real</h3>
<p>La IA está permitiendo el monitoreo continuo en tiempo real de una amplia gama de parámetros relevantes en el tratamiento de aguas, como la calidad del agua, la presión del sistema, el flujo de agua y la actividad bacteriana. Los sensores inteligentes equipados con algoritmos de aprendizaje automático pueden detectar anomalías y tendencias no lineales, alertando a los operadores sobre posibles problemas antes de que se conviertan en emergencias.</p>
<p>Según un estudio publicado en la revista «Water Research», la implementación de sistemas de monitoreo basados en IA en una planta de tratamiento de aguas residuales en España resultó en una reducción del 20% en los costos operativos y una mejora del 15% en la eficiencia del proceso.</p>
<h3>2. Optimización de Procesos mediante el Aprendizaje Automático</h3>
<p>Los algoritmos de aprendizaje automático están siendo utilizados para optimizar los procesos de tratamiento de aguas, ajustando automáticamente variables como la dosificación de productos químicos, la velocidad de los equipos y el tiempo de retención en función de las condiciones operativas y las demandas del sistema. Esto no solo mejora la eficiencia del proceso, sino que también reduce el consumo de energía y productos químicos.</p>
<p>Un informe de la revista «Water Science and Technology» destaca cómo un sistema basado en IA implementado en una planta de tratamiento en Australia logró una reducción del 12% en el consumo de energía y una mejora del 18% en la calidad del agua tratada.</p>
<h3>3. Detección y Diagnóstico de Anomalías</h3>
<p>La visión por computadora y los algoritmos de aprendizaje automático permiten la detección y diagnóstico de anomalías en tiempo real, identificando problemas como fugas, obstrucciones y daños en equipos. Estos sistemas pueden analizar imágenes de cámaras de vigilancia y datos de sensores para identificar patrones anómalos y predecir posibles fallos, permitiendo una intervención temprana y la prevención de costosas averías.</p>
<p>Un estudio reciente realizado por investigadores de la Universidad de Stanford demostró cómo un sistema de visión por computadora basado en IA instalado en una planta de tratamiento de aguas en California redujo los tiempos de inactividad no planificados en un 30% y aumentó la vida útil de los equipos en un 25%.</p>
<h3>Conclusiones</h3>
<p>La aplicación de la inteligencia artificial en el monitoreo y control de procesos de tratamiento de aguas está transformando la industria hídrica, permitiendo una gestión más eficiente, sostenible y económica de los recursos hídricos. Al aprovechar el potencial de la IA, las plantas de tratamiento pueden optimizar sus operaciones, reducir costos operativos y garantizar un suministro de agua seguro y de alta calidad para las comunidades en todo el mundo.</p>
<p>¡Confía en Hidrocinco para impulsar la eficiencia hídrica en tu comunidad!</p>
HTML,
    ],
    [
        'slug' => 'hidrocinco-comprometidos-con-la-sostenibilidad-ambiental',
        'title' => 'Hidrocinco: Comprometidos con la Sostenibilidad Ambiental',
        'status' => 'published', 'published_at' => '2024-03-15',
        'featured_image' => 'img/blog/sostenibilidad-ambiental.webp',
        'excerpt' => 'En el corazón de cada acción de Hidrocinco late el compromiso con la preservación del medio ambiente. Más que una empresa de servicios hidráulicos, somos guardianes de los recursos naturales que sostienen la vida en nuestro planeta.',
        'body_html' => <<<'HTML'
<p><strong>En el corazón de cada acción de Hidrocinco late el compromiso con la preservación del medio ambiente. Más que una empresa de servicios hidráulicos, somos guardianes de los recursos naturales que sostienen la vida en nuestro planeta. A través de nuestra filosofía y prácticas comerciales, nos esforzamos por marcar una diferencia positiva en el mundo que habitamos.</strong></p>
<h3>Filosofía Ambiental de Hidrocinco</h3>
<p>En Hidrocinco, comprendemos que nuestras actividades pueden tener un impacto significativo en el medio ambiente. Por lo tanto, hemos adoptado una filosofía ambiental arraigada en el respeto y la responsabilidad hacia la naturaleza. Nos comprometemos a operar de manera sostenible y a minimizar nuestro impacto ambiental en todas nuestras operaciones.</p>
<h3>Compromiso con la Reducción de Residuos</h3>
<p>Entendemos que la gestión adecuada de los residuos es fundamental para proteger el entorno natural. En Hidrocinco, implementamos rigurosos programas de gestión de residuos que priorizan la reducción, reutilización y reciclaje. Nuestros procesos están diseñados para minimizar la generación de residuos y promover la adopción de prácticas sostenibles en todas las etapas de nuestro trabajo.</p>
<h3>Uso Eficiente de Recursos</h3>
<p>La conservación de los recursos naturales es una parte integral de nuestra operación diaria. Buscamos constantemente formas de optimizar el uso de recursos como el agua y la energía en nuestras actividades. Desde la selección de equipos y materiales hasta la ejecución de proyectos, priorizamos la eficiencia y la conservación de los recursos en todo lo que hacemos.</p>
<h3>Promoción de Prácticas Sostenibles</h3>
<p>Como líderes en la industria, reconocemos nuestra responsabilidad en la promoción de prácticas sostenibles en toda la comunidad. A través de iniciativas educativas y colaboraciones con organizaciones ambientales, trabajamos para aumentar la conciencia sobre la importancia de la sostenibilidad y fomentar acciones positivas hacia la protección del medio ambiente.</p>
<h3>Conclusión</h3>
<p>En Hidrocinco, nuestro compromiso con el medio ambiente va más allá de nuestras operaciones comerciales; es una expresión de nuestra responsabilidad como ciudadanos del mundo. Estamos dedicados a proteger y preservar los recursos naturales para las generaciones futuras, y nos comprometemos a seguir liderando el camino hacia un futuro más sostenible y próspero para todos.</p>
<p>¡Únete a nosotros en nuestro viaje hacia la sostenibilidad ambiental y juntos hagamos del mundo un lugar mejor para vivir!</p>
HTML,
    ],
    [
        'slug' => 'eficiencia-en-tu-mantenimiento-con-fracttal-one',
        'title' => 'Eficiencia en tu Mantenimiento con Fracttal One',
        'status' => 'published', 'published_at' => '2024-02-17',
        'featured_image' => 'img/blog/fracttal-one.webp',
        'excerpt' => 'En el dinámico mundo de los sistemas hidráulicos y la gestión de activos, la eficiencia y la fiabilidad son clave. Hidrocinco presenta una solución innovadora que revoluciona la forma en que gestionamos y mantenemos tus activos hidráulicos: Fracttal One.',
        'body_html' => <<<'HTML'
<p><strong>En el dinámico mundo de los sistemas hidráulicos y la gestión de activos, la eficiencia y la fiabilidad son clave. Hidrocinco se enorgullece en presentar una solución innovadora que revoluciona la forma en que gestionamos y mantenemos tus activos hidráulicos: Fracttal One.</strong></p>
<p>La gestión de activos es esencial para cualquier negocio que dependa de sistemas hidráulicos para su funcionamiento. Con el fin de garantizar la máxima eficiencia y rendimiento, es crucial tener un control detallado de cada componente y su estado.</p>
<h3>¿Qué es Fracttal One?</h3>
<p>Fracttal One es una plataforma integral de gestión de activos que permite a Hidrocinco llevar un seguimiento detallado de todos los componentes, desde bombas y válvulas hasta tableros eléctricos y sistemas de tratamiento de aguas. Esta solución basada en la nube centraliza toda la información relevante en una sola plataforma accesible desde cualquier dispositivo con conexión a Internet.</p>
<h3>Beneficios de Fracttal One</h3>
<p><strong>Gestión Centralizada:</strong> Con Fracttal One, todos los datos relacionados con tus activos hidráulicos se encuentran en un solo lugar. Esto facilita la supervisión y el control, permitiendo una respuesta más rápida y eficiente ante cualquier incidencia.</p>
<p><strong>Planificación de Mantenimiento Predictivo:</strong> La plataforma utiliza algoritmos avanzados para predecir posibles fallos y recomendar acciones preventivas. Esto ayuda a evitar tiempos de inactividad no planificados y reduce los costos de mantenimiento a largo plazo.</p>
<p><strong>Seguimiento en Tiempo Real:</strong> Fracttal One proporciona actualizaciones en tiempo real sobre el estado de tus activos, lo que te permite tomar decisiones informadas de manera rápida y eficaz.</p>
<p><strong>Accesibilidad:</strong> Al ser una solución basada en la nube, Fracttal One está disponible en cualquier momento y desde cualquier lugar. Esto facilita la colaboración entre equipos y mejora la eficiencia operativa.</p>
<p>En Hidrocinco, entendemos la importancia de mantener tus sistemas hidráulicos en óptimas condiciones. Con Fracttal One, llevamos la gestión de activos al siguiente nivel, garantizando la máxima eficiencia y fiabilidad en todo momento.</p>
<p>Si estás buscando una solución integral para la gestión de tus activos hidráulicos, no busques más. ¡Contáctanos hoy mismo y descubre cómo Fracttal One puede transformar tu negocio!</p>
HTML,
    ],
];

$postRepository = new PostRepository();
$existingPost = $postRepository->findBySlug($postData['slug']);
$existingPost ? $postRepository->update((int) $existingPost['id'], $postData) : $postRepository->create($postData);

foreach ($morePosts as $p) {
    $ex = $postRepository->findBySlug($p['slug']);
    $ex ? $postRepository->update((int) $ex['id'], $p) : $postRepository->create($p);
}

$db = Database::connection();
$admin = $db->prepare('SELECT id FROM admin_users WHERE email = :email');
$admin->execute(['email' => 'admin@hidrocinco.cl']);
$adminId = $admin->fetchColumn();
if (!$adminId) {
    $seedPassword = (string) getenv('ADMIN_SEED_PASSWORD');
    if (strlen($seedPassword) < 14) {
        throw new RuntimeException('Define ADMIN_SEED_PASSWORD con al menos 14 caracteres para crear el administrador inicial.');
    }
    $adminData = ['email' => 'admin@hidrocinco.cl', 'password_hash' => password_hash($seedPassword, PASSWORD_DEFAULT), 'name' => 'Administrador'];
    $db->prepare('INSERT INTO admin_users (email,password_hash,name) VALUES (:email,:password_hash,:name)')->execute($adminData);
}

echo "Seed completado: 7 servicios, 7 notas y 1 usuario administrador.\n";
