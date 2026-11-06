<?php

declare(strict_types=1);

namespace App;

/**
 * Destinatarios de los correos que generan los formularios públicos.
 *
 * Los valores por defecto viven aquí para no depender del config.php de producción.
 * Se pueden sobrescribir por grupo en config.php:
 *
 *     'mail' => ['recipients' => ['emergency' => ['to' => [...], 'cc' => [...], 'bcc' => [...]]]]
 */
final class MailRecipients
{
    /** Contacto y newsletter. */
    public const FORMS = 'forms';
    /** Formulario de Emergencias 24/7. */
    public const EMERGENCY = 'emergency';

    private const DEFAULTS = [
        self::FORMS => [
            'to' => ['hidrocinco@hidrocinco.cl'],
            'cc' => ['promo@hidrocinco.cl'],
            'bcc' => ['webdesign@studionomade.cl'],
        ],
        self::EMERGENCY => [
            'to' => ['hidrocinco@hidrocinco.cl'],
            'cc' => ['promo@hidrocinco.cl', 'rgodoy@hidrocinco.cl'],
            'bcc' => ['webdesign@studionomade.cl'],
        ],
    ];

    /** @return array{to: list<string>, cc: list<string>, bcc: list<string>} */
    public static function for(string $group): array
    {
        if (!isset(self::DEFAULTS[$group])) {
            throw new \InvalidArgumentException("Grupo de destinatarios desconocido: {$group}");
        }
        $override = config('mail.recipients.' . $group);
        $source = is_array($override) ? $override + self::DEFAULTS[$group] : self::DEFAULTS[$group];

        $result = [];
        foreach (['to', 'cc', 'bcc'] as $kind) {
            $result[$kind] = self::clean($source[$kind] ?? []);
        }
        if ($result['to'] === []) {
            $result['to'] = self::DEFAULTS[$group]['to'];
        }
        return $result;
    }

    /** @return list<string> */
    private static function clean(mixed $addresses): array
    {
        $list = is_array($addresses) ? $addresses : [$addresses];
        $valid = array_filter(
            array_map(static fn ($address): string => trim((string) $address), $list),
            static fn (string $address): bool => filter_var($address, FILTER_VALIDATE_EMAIL) !== false,
        );
        return array_values(array_unique($valid));
    }
}
