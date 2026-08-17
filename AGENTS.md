# AGENTS.md
# Kampoeng Radja — Codex Instructions

## 1. Project Overview

This repository contains the digital system for Kampoeng Radja Jambi.

The project is developed in multiple phases:

- Phase 1 — Landing Page / Company Profile
- Phase 2 — Employee KPI System
- Phase 3 — Closing Event Marketing System

Current development workstreams:

> **LANDING PAGE / COMPANY PROFILE — active, incomplete**

> **DASHBOARD INTERNAL FOUNDATION — active only for documented modules**

Dashboard modules currently documented as active are Data Absensi and preparation for Kelola Karyawan. KPI and Closing Event remain inactive and must not be implemented unless explicitly activated with requirements.

---

# 2. Mandatory Documentation Read Order

Before changing Phase 1 code, Codex MUST read the relevant documentation.

## Global Documentation

Read in this order:

1. `docs/GLOBAL/PROJECT_CONTEXT.md`
2. `docs/GLOBAL/TECH_STACK.md`
3. `docs/GLOBAL/BRAND_GUIDELINE.md`
4. `docs/GLOBAL/ARCHITECTURE.md`
5. `docs/GLOBAL/ACCESS_CONTROL.md` when access/auth is relevant
6. `docs/GLOBAL/ACCESS_CONTROL_MATRIX.md` when access/auth is relevant
7. `docs/GLOBAL/AGENT_RULES.md`

## Phase 1 Documentation

Read in this order:

8. `docs/LANDING-PAGE/PRD.md`
9. `docs/LANDING-PAGE/FIGMA.md`
10. `docs/LANDING-PAGE/FIGMA_ACCURACY.md`
11. `docs/LANDING-PAGE/UI_SPEC.md`
12. `docs/LANDING-PAGE/USER_FLOW.md`
13. `docs/LANDING-PAGE/COMPONENTS.md`
14. `docs/LANDING-PAGE/RESPONSIVE.md`
15. `docs/LANDING-PAGE/CONTENT.md`
16. `docs/LANDING-PAGE/ASSETS.md`
17. `docs/LANDING-PAGE/REFERENCE.md`
18. `docs/LANDING-PAGE/TODO.md`
19. `docs/LANDING-PAGE/AGENT_HANDOFF.md`

Before release/review also read:

20. `docs/LANDING-PAGE/DELIVERY_CHECKLIST.md`

## Dashboard Documentation

Before changing Dashboard code, read:

1. `docs/DASHBOARD/README.md`
2. the module `PRD.md`, `PERMISSIONS.md`, and `UI_SPEC.md` when available
3. approved files under the module `references/`

If a module document is missing or marked `TBD`, do not invent business rules or permissions.

If required content is missing, do not invent it.

---

# 3. Source of Truth

Use the following source-of-truth model.

## Business / Scope / Function

Primary:

`docs/LANDING-PAGE/PRD.md`

Use it for:

- pages;
- features;
- business logic;
- data requirements;
- access rules;
- acceptance criteria.

---

## Visual Desktop

Primary:

> **Figma nodes marked `[APPROVED FOR DEVELOPMENT]`**

Supporting documents:

- `FIGMA.md`
- `FIGMA_ACCURACY.md`

Figma controls:

- layout;
- section order;
- size;
- spacing;
- typography;
- color;
- border;
- radius;
- shadow;
- media;
- crop;
- icon;
- decoration;
- visible states.

Do not redesign approved Figma.

---

## Behavior

Use:

1. `UI_SPEC.md`
2. `USER_FLOW.md`

for behavior that is not fully visible in Figma.

---

## Responsive

Use:

`RESPONSIVE.md`

Important Phase 1 decision:

> The design team only provides **desktop Figma designs**.

There will be no dedicated tablet/mobile Figma frames for Phase 1.

Therefore:

- desktop may be Figma verified;
- tablet/mobile are `[RESPONSIVE FALLBACK]`;
- never claim tablet/mobile are pixel-accurate to Figma.

---

# 4. Approved Figma Frames

The following nodes are approved for development:

- Beranda — `1:318`
- Tentang Kami — `1:2`
- Wahana — `1:149`
- Galeri Event — `1:679`
- Navbar — `1:285`
- Footer — `1:251`
- Main Logo — `1:675`
- Footer Logo — `1:677`

Detail Media & Berita:

- node `1:650`
- visually approved
- implementation scope still requires PRD/TODO confirmation.

Do not implement the detail page only because a Figma node exists.

---

# 5. Figma-First Protocol

Before implementing or modifying a UI section:

1. Open the relevant Figma frame from `FIGMA.md`.
2. Identify the exact section/node.
3. Record the node in `FIGMA.md` if it is missing.
4. Inspect the real values.
5. Inspect:
   - frame size;
   - container;
   - alignment;
   - grid/flex structure;
   - width/height;
   - gaps;
   - padding/margins;
   - typography;
   - colors;
   - radius;
   - border;
   - effects/shadows;
   - media crop/mask;
   - icons;
   - decoration;
   - states.
6. Check assets in `ASSETS.md`.
7. Audit the existing implementation.
8. Only then implement.

Do not code from screenshots or textual summaries if the approved Figma can be inspected.

---

# 6. Pixel Accuracy

Do not claim:

- pixel-perfect;
- pixel-accurate;
- 100% identical;
- Figma verified;

unless visual QA was actually performed.

For a desktop frame to be called verified:

1. render at the exact Figma frame viewport;
2. compare side-by-side or overlay;
3. fix Critical deviations;
4. fix Major deviations or document explicit approval;
5. update `DELIVERY_CHECKLIST.md`.

A successful build is not proof of visual accuracy.

---

# 7. Figma Access Failure

If Figma cannot be accessed:

record:

```text
[BLOCKED: FIGMA ACCESS]
```

Codex may continue with:

- non-visual backend work;
- code audit;
- documented fallback;
- existing mapped assets.

Codex MUST NOT:

- make large visual guesses;
- redesign;
- claim visual accuracy.

---

# 8. Phase 1 Public Scope

The public website has four main pages:

1. Beranda
2. Tentang Kami
3. Wahana
4. Galeri Event

These pages are accessible without login.

The main public navigation provides access to these pages.

Login is not a primary public navigation item.

Its location follows PRD/Figma, currently through the footer.

---

# 9. Homepage Scope

Homepage business sections currently include:

1. Hero
2. Informasi / Insight
3. Media & Berita
4. Promo & Event
5. Wahana Unggulan / USP
6. Sponsorship / Mitra
7. Lokasi
8. Footer

Visual order and composition follow approved Figma.

Do not force generic layout patterns from this list.

---

# 10. Tentang Kami Scope

Business content includes:

- Hero
- profile/introduction if present in approved design
- Sejarah / Kisah
- Visi & Misi
- Struktur Organisasi
- Footer

Do not invent company history, vision, mission, personnel, or organization data.

---

# 11. Wahana Requirements

Wahana must support dynamic category/label filtering.

Requirements:

- multiple labels may be selected;
- active labels can be toggled off;
- `Cari` applies the current selection unless PRD changes;
- `Reset` clears all filters;
- filter logic is **AND**, not OR.

Example:

```text
Air + Anak-anak
```

must return only items containing both labels.

Default state:

> all available guest-visible items.

Empty state must not break the page.

---

# 12. Galeri Event Requirements

Galeri Event must support sorting:

- Terbaru
- Terlama

Sorting is based on event date.

Default sort is still an open decision unless stakeholder sets it.

Do not invent pagination, infinite scroll, or detail interactions unless required.

---

# 13. Admin Minimum Scope

Phase 1 includes minimum content management according to PRD.

Potential active modules:

- Wahana
- categories
- labels
- label assignment
- Galeri Event
- Media & Berita
- Promo
- Mitra

Implement only modules confirmed by current PRD/TODO.

Do not build KPI/Closing Event dashboards.

---

# 14. Do Not Invent Information

Do not fabricate Kampoeng Radja data.

Never invent:

- history;
- vision;
- mission;
- employee names;
- organizational structure;
- address;
- phone;
- operating hours;
- social accounts;
- attractions;
- event data;
- promotional data;
- partner data;
- statistics;
- pricing.

Use documented statuses:

```text
[PRODUKSI RESMI]
[FIGMA SEMENTARA]
[PERLU KONTEN RESMI]
[PERLU ASET RESMI]
[PLACEHOLDER TERDOKUMENTASI]
[PERLU KLARIFIKASI]
```

---

# 15. Assets

Before adding media:

1. read `ASSETS.md`;
2. inspect existing project assets;
3. inspect the Figma node;
4. reuse correct assets.

If Figma contains the intended asset and production media is not yet available:

> export/use the Figma source asset as `[FIGMA SEMENTARA]`.

Do not use:

- random stock imagery;
- Unsplash;
- Picsum;
- unrelated generated imagery;
- full-frame Figma screenshots as website assets.

Screenshots are for visual QA only.

Do not depend on temporary Figma URLs at runtime.

---

# 16. Component Architecture

Use Vue components where reuse is real.

Good candidates may include:

- Navbar
- Footer
- filter controls
- repeated cards
- modal/lightbox
- repeated section patterns

But do not create generic abstractions merely because they are common patterns.

Do NOT automatically create:

```text
BaseButton
BaseCard
BaseBadge
BaseModal
BaseTooltip
PageContainer
SectionWrapper
```

If reuse forces visual divergence from Figma:

> visual fidelity wins.

Page-specific components are acceptable.

---

# 17. Tailwind / CSS

Tailwind is an implementation tool, not a design source.

Codex may use:

- standard utilities;
- arbitrary values;
- custom CSS;
- scoped styles;

when necessary to match Figma.

Do not round Figma values to default Tailwind tokens if the visual result changes.

Examples of valid implementation:

```text
max-w-[1180px]
rounded-[22px]
leading-[1.18]
```

when those values match the design.

---

# 18. Responsive Rules

Tablet/mobile are not backed by Figma frames.

Use `RESPONSIVE.md`.

Rules:

- adapt, do not redesign;
- preserve content hierarchy;
- preserve content order unless usability requires an internal rearrangement;
- preserve brand character;
- avoid overflow;
- keep interaction usable;
- do not simply scale desktop down;
- do not force default Tailwind breakpoints.

Mobile-first is allowed as an engineering strategy, not as a visual source of truth.

---

# 19. Existing Code Audit

Before modifying or creating code:

1. inspect repository structure;
2. inspect routes;
3. inspect pages;
4. inspect components;
5. inspect layouts;
6. inspect controllers;
7. inspect models;
8. inspect migrations;
9. inspect auth;
10. inspect styles;
11. inspect dependencies;
12. inspect assets.

Do not rebuild foundations that already exist.

Do not delete code solely because another approach seems easier.

---

# 20. Dependencies

Before adding a dependency:

1. inspect `package.json`;
2. inspect lockfile;
3. inspect existing packages;
4. check if native Vue/CSS can solve the requirement;
5. confirm the dependency is justified.

Do not add speculative libraries.

Specifically:

> `vue-grid-layout` is NOT a requirement.

Do not introduce a generic UI framework unless explicitly justified.

---

# 21. Routing / Inertia

Follow the existing Laravel + Inertia architecture.

Do not create Vue Router for primary page navigation unless architecture explicitly changes.

Do not create a separate REST API merely because the frontend uses Vue.

Use the existing Inertia/Laravel flow unless requirement says otherwise.

---

# 22. Database

Do not create Phase 2/3 tables during Phase 1.

Before changing database schema:

- confirm the entity is required by PRD;
- inspect existing migration/model;
- avoid duplicate tables;
- avoid speculative schema.

If unclear, record the decision in `TODO.md`.

---

# 23. Authentication / Roles

Basic role concepts:

- Super Admin
- Admin
- User

Guest:

- public pages only.

Admin/Super Admin:

- may access content management according to Phase 1 scope.

User:

- does not automatically receive content management access.

Do not invent a KPI/Closing destination after login.

If post-login destination is unresolved, use the documented open decision.

---

# 24. UI Behavior

Use `UI_SPEC.md` for behavior.

Mandatory active interactions include:

- Wahana multi-select
- Wahana toggle
- Wahana AND logic
- Wahana Cari
- Wahana Reset
- Galeri Event Terbaru/Terlama

The following are NOT automatically required:

- sponsor auto-scroll
- pause-on-hover
- drag
- grayscale hover
- sticky filter
- tooltip social
- infinite scroll
- hover scale/elevation
- generic skeletons
- generic lightbox

Only implement them if current Figma/PRD/UI_SPEC requires them.

---

# 25. Accessibility

Interactive UI must remain usable.

At minimum:

- semantic HTML;
- correct button/link semantics;
- keyboard access;
- usable focus states;
- alt text for informative images;
- modal focus handling if modal exists;
- essential information cannot depend only on hover.

Accessibility improvements should avoid visible redesign where possible.

---

# 26. Open Decisions

Read:

`docs/LANDING-PAGE/TODO.md`

Current examples include:

- Detail Media & Berita page scope
- Galeri Event default sort
- Struktur Organisasi static vs dynamic
- post-login destination for basic User role
- remaining section node mappings

Do not silently make permanent business decisions.

If an open decision blocks correct implementation:

1. record it;
2. continue non-blocked work;
3. use only documented temporary behavior.

---

# 27. Conflict Handling

If sources conflict:

## Business / Scope

`PRD.md` wins.

## Desktop Visual

Approved Figma wins.

## Behavior

Use `UI_SPEC.md` / `USER_FLOW.md`, unless they conflict with PRD.

## Responsive

`RESPONSIVE.md` wins for non-desktop fallback.

## Content / Assets

Use `CONTENT.md` / `ASSETS.md`.

If conflict remains unresolved:

> record it in `TODO.md`.

Never silently alter business requirements to simplify implementation.

---

# 28. Development Workflow

For each task:

## Step 1 — Read

Read the relevant docs.

## Step 2 — Inspect

Inspect:

- Figma;
- existing code;
- assets;
- dependencies.

## Step 3 — Map

Identify:

- page;
- Figma frame;
- section node;
- files affected;
- data requirements;
- behavior.

## Step 4 — Implement

Make the smallest coherent implementation that satisfies the active requirement.

## Step 5 — Verify Function

Check:

- behavior;
- routing;
- data;
- auth;
- regression;
- build/tests.

## Step 6 — Verify Visual

For desktop:

- render at exact Figma size;
- compare;
- fix deviations.

For tablet/mobile:

- perform responsive fallback QA.

## Step 7 — Update Docs

Update when relevant:

- `FIGMA.md`
- `ASSETS.md`
- `TODO.md`
- `DELIVERY_CHECKLIST.md`

## Step 8 — Report

Report:

- implemented changes;
- files changed;
- Figma frame/node;
- assets used;
- tested viewports;
- functional verification;
- visual QA status;
- remaining deviations/blockers.

---

# 29. Task Completion Rules

Do not mark a frontend task complete merely because:

- code compiles;
- route loads;
- tests pass;
- it looks approximately correct.

For Figma-backed desktop UI, completion requires visual verification.

If visual QA cannot be performed:

report:

```text
Implementation complete technically.
Visual status: NOT VERIFIED AGAINST FIGMA.
```

---

# 30. Phase Boundary

Current active scope:

> **LANDING PAGE FASE 1 + DOCUMENTED DASHBOARD FOUNDATION**

Dashboard work is allowed only for modules explicitly documented under `docs/DASHBOARD/` or directly activated by the stakeholder.

Do NOT implement:

- Employee KPI System
- KPI dashboard
- KPI calculation
- employee evaluation workflow
- daily report KPI
- Closing Event Marketing
- Closing Event dashboard
- Phase 2/3 business logic
- speculative Phase 2/3 database tables

unless explicitly instructed with active requirements. Data Absensi and Kelola Karyawan documentation do not activate KPI or Closing Event.

---

# 31. Prohibited Behaviors

Codex MUST NOT:

- redesign approved Figma;
- invent company data;
- use stock assets where Figma assets exist;
- claim pixel-perfect without QA;
- claim mobile/tablet Figma verification;
- add speculative libraries;
- force generic component abstractions;
- build future phases;
- make large unrelated refactors;
- silently resolve business ambiguity;
- treat old implementation status as current truth without verification.

---

# 32. Preferred Behaviors

Always prefer:

- current documentation;
- approved Figma;
- existing project conventions;
- measured values;
- minimal coherent changes;
- official or mapped Figma assets;
- reusable components only when genuinely reusable;
- maintainable Laravel/Vue/Inertia structure;
- explicit blocker reporting;
- evidence-based QA.

---

# 33. Final Principle

The goal is not merely to make the application work.

The goal is to implement the documented Kampoeng Radja requirements **accurately**, while preserving maintainability for future phases.

For Phase 1:

> **PRD defines what to build.**

> **Approved Figma defines how desktop looks.**

> **UI_SPEC and USER_FLOW define how it behaves.**

> **RESPONSIVE defines how desktop intent adapts to tablet/mobile.**

> **FIGMA_ACCURACY defines how visual correctness is proven.**

Required workflow:

> **read → inspect → map → audit → implement → compare → fix → verify**
