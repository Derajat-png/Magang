---
name: Soft Enterprise
colors:
  surface: '#f8f9ff'
  surface-dim: '#cbdbf5'
  surface-bright: '#f8f9ff'
  surface-container-lowest: '#ffffff'
  surface-container-low: '#eff4ff'
  surface-container: '#e5eeff'
  surface-container-high: '#dce9ff'
  surface-container-highest: '#d3e4fe'
  on-surface: '#0b1c30'
  on-surface-variant: '#574048'
  inverse-surface: '#213145'
  inverse-on-surface: '#eaf1ff'
  outline: '#8b7079'
  outline-variant: '#debec8'
  surface-tint: '#b4136d'
  primary: '#b10e6b'
  on-primary: '#ffffff'
  primary-container: '#d23284'
  on-primary-container: '#fffbff'
  inverse-primary: '#ffb0cd'
  secondary: '#765469'
  on-secondary: '#ffffff'
  secondary-container: '#fdd0ea'
  on-secondary-container: '#79576c'
  tertiary: '#ab2457'
  on-tertiary: '#ffffff'
  tertiary-container: '#cc3f70'
  on-tertiary-container: '#fffbff'
  error: '#ba1a1a'
  on-error: '#ffffff'
  error-container: '#ffdad6'
  on-error-container: '#93000a'
  primary-fixed: '#ffd9e4'
  primary-fixed-dim: '#ffb0cd'
  on-primary-fixed: '#3e0022'
  on-primary-fixed-variant: '#8c0053'
  secondary-fixed: '#ffd8ed'
  secondary-fixed-dim: '#e5bad3'
  on-secondary-fixed: '#2c1325'
  on-secondary-fixed-variant: '#5c3d51'
  tertiary-fixed: '#ffd9e0'
  tertiary-fixed-dim: '#ffb1c3'
  on-tertiary-fixed: '#3f0019'
  on-tertiary-fixed-variant: '#8e0542'
  background: '#f8f9ff'
  on-background: '#0b1c30'
  surface-variant: '#d3e4fe'
typography:
  headline-xl:
    fontFamily: Plus Jakarta Sans
    fontSize: 40px
    fontWeight: '700'
    lineHeight: 48px
    letterSpacing: -0.02em
  headline-lg:
    fontFamily: Plus Jakarta Sans
    fontSize: 32px
    fontWeight: '700'
    lineHeight: 40px
    letterSpacing: -0.02em
  headline-lg-mobile:
    fontFamily: Plus Jakarta Sans
    fontSize: 28px
    fontWeight: '700'
    lineHeight: 36px
  headline-md:
    fontFamily: Plus Jakarta Sans
    fontSize: 24px
    fontWeight: '600'
    lineHeight: 32px
  body-lg:
    fontFamily: Inter
    fontSize: 18px
    fontWeight: '400'
    lineHeight: 28px
  body-md:
    fontFamily: Inter
    fontSize: 16px
    fontWeight: '400'
    lineHeight: 24px
  body-sm:
    fontFamily: Inter
    fontSize: 14px
    fontWeight: '400'
    lineHeight: 20px
  label-md:
    fontFamily: Inter
    fontSize: 14px
    fontWeight: '600'
    lineHeight: 20px
    letterSpacing: 0.05em
  label-sm:
    fontFamily: Inter
    fontSize: 12px
    fontWeight: '500'
    lineHeight: 16px
rounded:
  sm: 0.25rem
  DEFAULT: 0.5rem
  md: 0.75rem
  lg: 1rem
  xl: 1.5rem
  full: 9999px
spacing:
  base: 4px
  xs: 4px
  sm: 8px
  md: 16px
  lg: 24px
  xl: 40px
  xxl: 64px
  container-max: 1200px
  gutter: 24px
---

## Brand & Style

This design system balances the approachability of a lifestyle brand with the structural integrity required for business operations. The personality is "Empathetic Professionalism"—it treats business tasks not as chores, but as moments of organized delight. 

The aesthetic is a refined **Minimalism** blended with **Soft UI** principles. It utilizes generous whitespace and a "frosted" clarity to ensure that the vibrant pink palette feels sophisticated rather than overwhelming. The target audience includes small business owners and staff who spend hours interacting with the interface; therefore, the visual language prioritizes eye comfort, clarity of intent, and a welcoming atmosphere that reduces the stress of management.

## Colors

The palette uses a tiered pink system to communicate hierarchy without losing professional grounding.

- **Primary (#EC4899):** Used for main actions (CTAs), active states, and brand identifiers. It provides the "sweet" vibrant energy.
- **Secondary (#FBCFE8):** A soft pastel used for background fills, role indicators, and subtle highlights.
- **Tertiary (#9D174D):** A deep, "rose-wine" pink used for high-contrast text or critical alerts to ensure readability and accessibility.
- **Neutral (#64748B):** A slate grey that anchors the design, used for body text and icon outlines to maintain a professional "SaaS" feel.
- **Surface:** Pure white (#FFFFFF) is the primary canvas, with extremely light grey (#F8FAFC) used for subtle sectional grouping.

## Typography

The typographic strategy pairs **Plus Jakarta Sans** for headlines to provide a friendly, modern spark, with **Inter** for all functional and body text to ensure maximum legibility and a systematic feel.

- **Headlines:** Use tighter letter-spacing and bold weights to create a strong visual anchor.
- **Body:** Inter is kept at a standard scale with generous line-height to prevent "text-heaviness" in data-rich environments.
- **Role Indicators:** Use the `label-md` style to clearly distinguish between user roles (Owner, Staff, Customer) with high visibility.

## Layout & Spacing

The design system utilizes a **Fluid Grid** with fixed maximum containers for desktop to maintain intimacy in the login experience.

- **Login Container:** Centered both vertically and horizontally, restricted to 440px width on desktop to maintain focus.
- **Rhythm:** A 4px/8px baseline grid is strictly followed. Components are separated by `lg` (24px) spacing, while internal element grouping (like labels and inputs) uses `sm` (8px).
- **Responsive:** On mobile, margins reduce from 40px to 16px. Cards expand to full-width but retain their internal padding to preserve the "soft" aesthetic.

## Elevation & Depth

Hierarchy is established through **Ambient Shadows** and **Tonal Layers** rather than heavy lines.

- **Level 0 (Background):** Solid #F8FAFC.
- **Level 1 (Cards/Containers):** Pure white background with a very soft, diffused shadow: `0px 4px 20px rgba(236, 72, 153, 0.08)`. The pink-tinted shadow keeps the "sweet" theme consistent.
- **Level 2 (Interactive/Floating):** Higher elevation for active elements or dropdowns: `0px 10px 32px rgba(236, 72, 153, 0.12)`.
- **Inner Depth:** For input fields, a subtle 1px border (#E2E8F0) is used, which transitions to a 2px Primary Pink border on focus with a soft outer glow.

## Shapes

The shape language is consistently **Rounded**, reflecting a friendly and accessible nature.

- **Standard Elements:** Buttons, inputs, and small cards use a 0.5rem (8px) radius.
- **Large Containers:** Main login cards and role-selection panels use `rounded-xl` (1.5rem/24px) to emphasize the soft, welcoming UI.
- **Micro-elements:** Chips and role badges are fully pill-shaped to distinguish them from interactive buttons.

## Components

### Role Selectors
The login experience starts with role selection. Each role (Owner, Cashier, Customer) is presented as a large, soft-elevated card. 
- **Owner:** Uses Primary Pink accents.
- **Cashier:** Uses Secondary Pink accents.
- **Customer:** Uses a soft neutral-to-pink gradient background.
Active selection is indicated by a 2px primary border and a subtle "bounce" scale animation (1.02x).

### Buttons
- **Primary:** Solid Primary Pink with white text. Soft shadow applied.
- **Secondary:** Secondary Pink background with Tertiary Pink text. No shadow.
- **Ghost:** Transparent background with Primary Pink text; used for "Forgot Password."

### Input Fields
Labels are placed above the field in `label-sm` style using the Neutral color. The inputs have a 16px horizontal padding. On error, the border turns to a warm coral-red, and a small "sweet" icon (like a rounded exclamation) appears.

### Chips & Badges
Role badges are pill-shaped with `Secondary Pink` fills and `Tertiary Pink` text. They appear at the top-right of the login card to confirm the current context.

### Lists
Lists (like recent logins) should feature 48px rounded avatars and `body-md` titles, separated by very light 1px dividers that fade out at the edges.