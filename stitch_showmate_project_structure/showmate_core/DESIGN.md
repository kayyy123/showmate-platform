---
name: ShowMate Core
colors:
  surface: '#121414'
  surface-dim: '#121414'
  surface-bright: '#37393a'
  surface-container-lowest: '#0c0f0f'
  surface-container-low: '#1a1c1c'
  surface-container: '#1e2020'
  surface-container-high: '#282a2b'
  surface-container-highest: '#333535'
  on-surface: '#e2e2e2'
  on-surface-variant: '#d0c6ab'
  inverse-surface: '#e2e2e2'
  inverse-on-surface: '#2f3131'
  outline: '#999077'
  outline-variant: '#4d4732'
  surface-tint: '#e9c400'
  primary: '#fff6df'
  on-primary: '#3a3000'
  primary-container: '#ffd700'
  on-primary-container: '#705e00'
  inverse-primary: '#705d00'
  secondary: '#a7c8ff'
  on-secondary: '#003061'
  secondary-container: '#1f477b'
  on-secondary-container: '#93b6f1'
  tertiary: '#defcff'
  on-tertiary: '#00363a'
  tertiary-container: '#00f1ff'
  on-tertiary-container: '#006a70'
  error: '#ffb4ab'
  on-error: '#690005'
  error-container: '#93000a'
  on-error-container: '#ffdad6'
  primary-fixed: '#ffe16d'
  primary-fixed-dim: '#e9c400'
  on-primary-fixed: '#221b00'
  on-primary-fixed-variant: '#544600'
  secondary-fixed: '#d5e3ff'
  secondary-fixed-dim: '#a7c8ff'
  on-secondary-fixed: '#001b3c'
  on-secondary-fixed-variant: '#1f477b'
  tertiary-fixed: '#79f5ff'
  tertiary-fixed-dim: '#00dbe8'
  on-tertiary-fixed: '#002022'
  on-tertiary-fixed-variant: '#004f54'
  background: '#121414'
  on-background: '#e2e2e2'
  surface-variant: '#333535'
  surface-deep: '#002244'
  success-green: '#2AB57D'
  soft-blue: '#E0F3F7'
  ui-gray: '#404040'
typography:
  display-lg:
    fontFamily: Inter
    fontSize: 32px
    fontWeight: '700'
    lineHeight: '1.2'
    letterSpacing: -0.02em
  headline-lg:
    fontFamily: Inter
    fontSize: 24px
    fontWeight: '700'
    lineHeight: '1.3'
  headline-lg-mobile:
    fontFamily: Inter
    fontSize: 20px
    fontWeight: '700'
    lineHeight: '1.3'
  body-md:
    fontFamily: Inter
    fontSize: 16px
    fontWeight: '400'
    lineHeight: '1.6'
  body-sm:
    fontFamily: Inter
    fontSize: 14px
    fontWeight: '400'
    lineHeight: '1.5'
  label-lg:
    fontFamily: Inter
    fontSize: 14px
    fontWeight: '600'
    lineHeight: '1'
    letterSpacing: 0.05em
  cta:
    fontFamily: Inter
    fontSize: 16px
    fontWeight: '700'
    lineHeight: '1'
rounded:
  sm: 0.125rem
  DEFAULT: 0.25rem
  md: 0.375rem
  lg: 0.5rem
  xl: 0.75rem
  full: 9999px
spacing:
  base: 4px
  container-padding: 1.25rem
  stack-gap: 1rem
  touch-target-min: 44px
  card-gap: 0.75rem
---

## Brand & Style
The design system is engineered for MSMEs (UMKM) who require a high-utility, high-clarity digital presence. The brand personality is professional, industrious, and accessible, prioritizing "information over decoration."

The design style follows a **High-Contrast / Modern** approach. It utilizes a deep, authoritative background to make content pop, ensuring that even in outdoor or high-glare environments, the catalog remains perfectly legible. The aesthetic avoids unnecessary shadows or gradients in favor of crisp edges and bold color blocks, creating a "utility-first" interface that inspires trust in micro-transactions.

## Colors
This design system employs a **Dark Mode by Default** strategy to emphasize focus and professionalism. 

- **Primary (#FFD700):** Used exclusively for high-intent actions, focus indicators, and critical highlights. Its high luminance against the deep blue ensures instant visual hierarchy.
- **Secondary (#003366):** The foundational canvas. It provides a stable, professional backdrop that reduces eye strain compared to pure black.
- **Neutral (#FFFFFF):** Reserved for primary text and high-contrast borders to maintain AAA accessibility standards.
- **Surface-Deep (#002244):** A darker variant of the secondary color used for nested elements or background depth.

## Typography
The system uses **Inter** exclusively to leverage its exceptional legibility on small screens. 

- **Headlines:** Use Bold (700) weights to create clear section breaks. 
- **Body Text:** Set at a 16px base to ensure readability for a wide demographic of users, including those with visual impairments.
- **Inter-letter Spacing:** Negative tracking is applied to large display text for a tighter, more professional "editorial" feel, while labels use positive tracking for clarity at small sizes.

## Layout & Spacing
This is a **Mobile-First Fixed Grid** system. While the catalog scales to desktop, the content container is capped at 480px width on larger screens to mimic the focused experience of a smartphone.

- **Rhythm:** A 4px baseline grid governs all spacing. 
- **Touch Areas:** Every interactive element (links, buttons, toggles) must maintain a minimum height and width of 44px to accommodate diverse thumb sizes and motor abilities.
- **Margins:** Standard horizontal padding of 20px (1.25rem) ensures content doesn't hit the bezel of the device.

## Elevation & Depth
In alignment with the high-contrast aesthetic, the system avoids traditional soft shadows. Depth is achieved through **Tonal Layering** and **High-Contrast Outlines**.

- **Level 0 (Background):** Primary #003366.
- **Level 1 (Cards):** Surface-Deep #002244 with a 1px solid border of #FFFFFF at 10% opacity.
- **Level 2 (Active/Focus):** Elements gain a 2px solid border of Canary Yellow (#FFD700). 
- **Overlays:** Modals and drawers use a 60% opacity black backdrop blur to maintain focus on the task at hand.

## Shapes
The shape language is **Soft (0.25rem)**. This provides a subtle modern feel without the playfulness of pill-shaped buttons. 

The intentional use of slightly rounded corners (4px to 12px) maintains the "professional tool" identity. Large containers like product cards should use `rounded-lg` (8px), while buttons and input fields use the base `rounded` (4px).

## Components

- **Product Cards:** The primary component. Features a 1:1 aspect ratio image thumbnail on the left, followed by a title and price. The entire card is a single touch target.
- **Action Buttons (CTA):** Full-width by default. Background is Canary Yellow (#FFD700) with Black (#000000) text for maximum impact.
- **Focus Rings:** Any focused interactive element must display a 3px offset solid ring in Canary Yellow.
- **Input Fields:** Dark background (#002244) with a 1px White border. Labels are always visible above the field (no floating labels) to ensure accessibility.
- **Chips/Badges:** Used for product categories. Minimalist styling with a White outline and no fill to avoid competing with primary buttons.
- **Simple Headers:** Centered logo or text, limited to 20px size to maximize vertical space for the catalog items below.