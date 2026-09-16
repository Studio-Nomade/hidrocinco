# Anexo — Migración de DNS a SiteGround (Opción B: zona completa)

> Complementa el **Hito 10 (deploy)**. Runbook para mover el DNS de `hidrocinco.cl` desde BlueHosting
> (nameservers `ns1/ns2/ns3.pymedns.net`) a **SiteGround**, cancelando BlueHosting, **sin cortar el
> correo corporativo de Microsoft 365**.

## Contexto y decisiones

- **Registrador:** NIC.cl. Hoy los nameservers apuntan a BlueHosting (`*.pymedns.net`).
- **Hosting web:** migra de BlueHosting (cPanel, IP `186.64.114.35`) → **SiteGround GoGeek**.
- **Correo corporativo:** **Microsoft 365** (no cambia; lo define el DNS, no el hosting).
- **Email marketing:** **Brevo** → **se mantiene** (confirmado).
- **App `appscinco.cl`:** vive en dominio independiente, pero **sigue usando `hidrocinco.cl` para dos
  cosas** (verificado en su código):
  - **`ws.hidrocinco.cl`** → WebSocket **en producción** (telemetría en vivo de la APK). **MANTENER**
    + sus validaciones ACM (para que el cert siga renovando).
  - **3 DKIM de SES** → **ELIMINAR**. SES envía con `Source: contacto@appscinco.cl`; `hidrocinco.cl`
    solo aparece como `Reply-To`/destinatario. Los DKIM bajo hidrocinco.cl son remanentes → el SPF de
    hidrocinco.cl **NO** lleva `include:amazonses.com`.
  - **`app.hidrocinco.cl`** → **no existe** como registro en la zona actual. Es el default de los QR de
    órdenes de trabajo cuando `APP_URL` no está seteada en prod. Acción de AppsCinco (no de esta
    migración): setear `APP_URL` en prod, **o** crear `app.hidrocinco.cl` apuntando al recurso AWS
    correspondiente si hay QRs en circulación que dependan de él.
- **Estrategia:** **Opción B** — mover la zona completa a SiteGround. El corte de nameservers en NIC
  lo hace Sebastián **después** de auditar el diseño e instalarlo en SiteGround.

## Regla de oro

El registro **MX → `hidrocinco-cl.mail.protection.outlook.com`** (más su SPF/autodiscover/DMARC) es lo
que mantiene vivo el correo M365. **Nunca** se debe cambiar el nameserver a SiteGround sin haber
recreado ANTES estos registros en la zona de SiteGround. Además, al agregar el dominio, SiteGround
crea **su propio MX y SPF** por defecto: **hay que borrarlos/reemplazarlos** por los de M365.

## Zona objetivo en SiteGround (Site Tools → DNS Zone Editor)

> Para valores largos (DKIM, SES, ACM, ALB) **copiar el valor EXACTO desde el Zone Editor de
> BlueHosting** (Editar → copiar todo el string). No transcribir a mano: un carácter erróneo rompe la
> firma. Si BlueHosting permite exportar el archivo de zona, úsalo como fuente.

### 1) Web (apuntar a SiteGround)
| Nombre | Tipo | Valor | TTL |
|---|---|---|---|
| `hidrocinco.cl` (raíz / `@`) | A | **IP de SiteGround** (Site Tools → Site → Server Details) | 3600 |
| `www` | CNAME | `hidrocinco.cl` | 3600 |

### 2) Correo Microsoft 365 (críticos — no tocar)
| Nombre | Tipo | Valor | Notas |
|---|---|---|---|
| `hidrocinco.cl` | MX | `hidrocinco-cl.mail.protection.outlook.com` | Prioridad **0** |
| `autodiscover` | CNAME | `autodiscover.outlook.com` | Outlook autodiscover |
| `_dmarc` | TXT | `v=DMARC1; p=none; rua=mailto:postmaster@hidrocinco.cl` | **Solo uno** |
| `hidrocinco.cl` | TXT (SPF) | `v=spf1 include:spf.protection.outlook.com include:spf.brevo.com ~all` | **Un solo SPF** por dominio |

**SPF:** usar el include exacto que muestre cada proveedor en su panel (hoy Microsoft:
`spf.protection.outlook.com`; Brevo: `spf.brevo.com`). Dejar `~all` (softfail) durante la transición;
endurecer a `-all` cuando se valide que todo llega bien. **NO** añadir `include:amazonses.com`:
verificado que SES envía con `Source: contacto@appscinco.cl`, no como `@hidrocinco.cl`.

### 3) Brevo (mantener)
| Nombre | Tipo | Valor |
|---|---|---|
| `hidrocinco.cl` | TXT | `brevo-code:81fc8e2abc2640f981f67cf2a0684304` |
| `mail._domainkey` | TXT | `k=rsa; p=…` (copiar exacto de BlueHosting) |

> Verificar en el panel de Brevo (Senders & IP → Domains → Authenticate) qué registros exige hoy; si
> Brevo pide un include SPF o un DMARC específico, alinear con lo de arriba.

### 4) AWS (AppsCinco) — MANTENER (verificado en producción)
| Nombre | Tipo | Valor (copiar exacto) |
|---|---|---|
| `ws` | CNAME | `alb-plataformah5-prod-2077543636.us-east-1.elb.amazonaws.com` |
| `_4716…` | CNAME | `…acm-validations.aws` |
| `_eb80…www` | CNAME | `…acm-validations.aws` |

- `ws.hidrocinco.cl` es el **WebSocket en producción** de la APK → **mantener sí o sí**.
- Las **2 validaciones ACM** se mantienen para que el/los certificado(s) de esos recursos AWS sigan
  **auto-renovando** (ACM re-valida por estos CNAME; si se borran, la próxima renovación falla y el
  cert expira → `ws` pierde HTTPS).
- **`app.hidrocinco.cl`**: no hay registro hoy. Si se confirma que QRs en circulación lo usan, crear el
  CNAME/A hacia el recurso AWS que corresponda; si no, setear `APP_URL` en la app y dejarlo fuera.
  (Acción de AppsCinco, no bloquea esta migración.)

## Registros que NO se recrean (mueren con BlueHosting)

- **A de cPanel:** `mail`, `ftp`, `cpanel`, `whm`, `webdisk`, `webmail`, `autoconfig`, `cpcontacts`,
  `cpcalendars` (todos → `186.64.114.35`).
- **SRV + TXT** de `_caldav._tcp`, `_caldavs._tcp`, `_carddav._tcp`, `_carddavs._tcp` y
  `_autodiscover._tcp` (→ `pyme74.pymedns.net`).
- **DKIM de cPanel:** `default._domainkey`.
- **3 DKIM de Amazon SES** (`*._domainkey` → `…dkim.amazonses.com`): SES no envía como
  `@hidrocinco.cl` (usa `contacto@appscinco.cl`), así que son remanentes → **no recrear**.
- **Validaciones transitorias:** `_cpanel-dcv-test-record` y **todos** los `_acme-challenge.*`.
- **Duplicados/obsoletos:** el segundo `_dmarc` (el que **no** tiene `rua`) y el **SPF viejo**
  (`v=spf1 a mx ip4:186.64.114.35 … -all`), reemplazado por el corregido.

## Secuencia de corte (la ejecuta Sebastián tras auditar el diseño)

1. **Instalar el sitio en SiteGround** (Hito 10) y anotar la **IP** del servidor.
2. **Construir toda la zona objetivo** en Site Tools → DNS Zone Editor (secciones 1–4).
3. **Eliminar/replazar el MX y el SPF que SiteGround crea por defecto** al agregar el dominio, para
   que queden los de M365.
4. **(Día antes)** En BlueHosting, bajar el **TTL** de `14400` a `300` en los registros que cambian,
   para acelerar la propagación.
5. **NIC.cl:** cambiar los nameservers de `*.pymedns.net` a los **NS de SiteGround** (Site Tools →
   Name Servers). *(Este cambio de NS puede tardar 24–48 h.)*
6. **SSL:** al resolver el dominio hacia SiteGround, dejar que emita el certificado Let's Encrypt
   (Site Tools → Security → SSL).
7. **Verificar:**
   - Web: `https://hidrocinco.cl` y `https://www.hidrocinco.cl` cargan desde SiteGround con SSL válido.
   - Correo: enviar y recibir un correo de prueba a una casilla `@hidrocinco.cl` (M365).
   - `dig hidrocinco.cl MX +short` → `hidrocinco-cl.mail.protection.outlook.com`.
   - `dig hidrocinco.cl TXT +short` → un único SPF (con Outlook + Brevo) y sin duplicados.
   - Brevo: revisar que el dominio siga "autenticado" en su panel.
8. **Verificar `ws`:** `wss://ws.hidrocinco.cl` conecta y la APK recibe telemetría en vivo.
9. Endurecer SPF `~all` → `-all` una vez validado que todo el correo legítimo pasa.
10. **Cancelar BlueHosting** solo tras 3–7 días de verificación estable.

## Checklist rápido

- [ ] IP de SiteGround anotada
- [ ] Zona objetivo creada en SiteGround (web + M365 + Brevo + `ws` + 2 ACM)
- [ ] 3 DKIM de SES **NO** recreados; SPF **sin** `include:amazonses.com`
- [ ] MX/SPF por defecto de SiteGround eliminados/reemplazados
- [ ] Un único SPF (Outlook + Brevo) y un único `_dmarc`
- [ ] TTL bajado a 300 en BlueHosting (día previo)
- [ ] NS cambiados en NIC.cl → SiteGround
- [ ] SSL emitido en SiteGround
- [ ] Web OK (raíz + www + HTTPS)
- [ ] Correo M365 OK (enviar/recibir + `dig MX`)
- [ ] Brevo autenticado
- [ ] `ws.hidrocinco.cl` OK (WebSocket + telemetría APK)
- [ ] `app.hidrocinco.cl` resuelto por AppsCinco (APP_URL o crear registro) — si aplica
- [ ] BlueHosting cancelado (tras margen de días)
