# vivutio/skeleton

An empty vivutio installation: the core and nothing else, the same for every
supplier, operator and agent.

This repository is the starter every installation is created from: a bare
Symfony application carrying the vivutio core. `composer create-project` copies
it once and then it is yours. Every capability after that arrives as a module,
installed with composer.

## Contents

- [What vivutio is](#what-vivutio-is)
- [The tree](#the-tree)
- [Requirements](#requirements)
- [Install guide](#install-guide)
  - [1. Create the project](#1-create-the-project)
  - [2. Start the database](#2-start-the-database)
  - [3. Run the migrations](#3-run-the-migrations)
  - [4. Create the first administrator](#4-create-the-first-administrator)
  - [5. Serve it](#5-serve-it)
  - [6. Let it send mail](#6-let-it-send-mail)
- [Modules](#modules)
- [What is behind sign-in](#what-is-behind-sign-in)
- [Versions and branches](#versions-and-branches)
- [How it is proven](#how-it-is-proven)
- [Licence](#licence)

## What vivutio is

An installation of vivutio is one tourism business's own system: a company that
runs camps and lodges, an operator that sells tours, an agent that sells what
others supply, or several of these at once. It holds one organization, its
people, the positions they hold and what each position permits. What the
business actually does arrives as modules: properties and their bookings,
tours, sourcing, ticketing. A supplier installs what a supplier needs, and an
operator what an operator needs.

A fresh installation is empty, and honestly so: no sample people, no seeded
properties, no pre-installed modules. The install guide below is the ordered
path from nothing to the first signed-in screen.

## The tree

| Path | What it is |
|---|---|
| `config/packages/security.yaml` | Everything behind sign-in, and the one address a stranger reaches |
| `config/routes/identity.yaml` | Mounts the core's people screens |
| `config/routes/shell.yaml` | Mounts the dashboard everybody lands on |
| `config/routes/place.yaml` | Mounts the destinations and their fees |
| `config/routes/partner.yaml` | Mounts the partners |
| `config/bundles.php` | The core's bundles, enabled |
| `src/` | Your own code; empty |
| `compose.yaml` | PostgreSQL for development |
| `tests/` | What the installation must do: boot with the whole core, and keep strangers out |

## Requirements

- PHP 8.4 or newer, with `ctype`, `iconv` and `pdo_pgsql`
- Composer 2
- PostgreSQL 17, or Docker to run it from `compose.yaml`

## Install guide

### 1. Create the project

```bash
composer create-project vivutio/skeleton my_project
cd my_project
```

### 2. Start the database

```bash
docker compose up -d --wait
```

Or point `DATABASE_URL` in `.env.local` at a PostgreSQL of your own.

### 3. Run the migrations

```bash
php bin/console doctrine:migrations:migrate
```

The core ships the versions that build its tables; you write SQL for none of
them. After migrating, `doctrine:schema:validate` reports the schema in sync.

### 4. Create the first administrator

```bash
php bin/console identity:user:create
```

It asks for the address, the name, the tier and a passphrase, and never echoes
the passphrase. Make this first account a Super Admin.

### 5. Serve it

```bash
symfony serve
```

Or any PHP server with `public/` as its root. Open `/login` and sign in: you
land on the organization's dashboard, empty until a module puts its cards there.

### 6. Let it send mail

```bash
# .env.local
MAILER_DSN=smtp://user:pass@smtp.example.com:587
MAILER_FROM="Your organization <no-reply@your-domain.example>"
```

A link to set a new password is sent by mail. Until `MAILER_DSN` names a
transport the installation sends nothing, and the page for a forgotten
password says so instead of promising an email.

## Modules

An installation adds the modules that apply to it. Each is one command, and its
recipe, read from the endpoint in `composer.json`, registers the module and
mounts its pages:

| Module | For | Install |
|---|---|---|
| Properties | The camps, lodges and hotels you run | `composer require vivutio/property-module` |

After installing a module, run the migrations again: a module adds tables of
its own.

## What is behind sign-in

Everything. `config/packages/security.yaml` names the one address a stranger has
to reach, the sign-in page, and shuts everything else, including every page a
module adds later. Five failed attempts a minute are allowed, a deactivated
account is refused with its reason, and deactivating an account ends a session
it already has.

What a signed-in person may *do* is not decided in that file. Each page names
the permission it checks, and the core's voters decide it per person and per
record, from the positions the organization writes.

## Versions and branches

Every repository of the platform is branched one way: a branch per version line
(`0.1`, `0.2`, …), the newest being where new work lands, and tags on those
branches as the releases. There is no `main`. `composer.json` requires the core
with a caret, `^0.1`, which resolves to the latest tag of that line.

The core has no release yet, so until its first tag the install guide's first
step cannot resolve it. Until then the installation is built from the core's
`0.1` branch, which is what this repository's CI does.

## How it is proven

`composer check` runs the code style and the tests. CI runs them on PHP 8.4 and
8.5 against the core's current `0.1` line, and walks the install guide as
written: database, migrations, schema check, first administrator, a signed-in
page over HTTP.

A release is proven by the fleet gate, a command of the platform's development
kit, which creates a project from this skeleton in an empty directory and
installs every official module into it, before a tag and again after it.

## Licence

**AGPL-3.0-or-later**: see [LICENSE](LICENSE). Use, change and host it freely; if
you offer a changed vivutio to people over a network, they are entitled to the
source of what they are running.
