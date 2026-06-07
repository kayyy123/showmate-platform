---
name: ShowMate Light
colors:
  surface: '#f9f9f9'
  surface-dim: '#dadada'
  surface-bright: '#f9f9f9'
  surface-container-lowest: '#ffffff'
  surface-container-low: '#f3f3f4'
  surface-container: '#eeeeee'
  surface-container-high: '#e8e8e8'
  surface-container-highest: '#e2e2e2'
  on-surface: '#1a1c1c'
  on-surface-variant: '#43474f'
  inverse-surface: '#2f3131'
  inverse-on-surface: '#f0f1f1'
  outline: '#737780'
  outline-variant: '#c3c6d1'
  surface-tint: '#3a5f94'
  primary: '#001e40'
  on-primary: '#ffffff'
  primary-container: '#003366'
  on-primary-container: '#799dd6'
  inverse-primary: '#a7c8ff'
  secondary: '#705d00'
  on-secondary: '#ffffff'
  secondary-container: '#fcd400'
  on-secondary-container: '#6e5c00'
  tertiary: '#460000'
  on-tertiary: '#ffffff'
  tertiary-container: '#6e0000'
  on-tertiary-container: '#ff6d59'
  error: '#ba1a1a'
  on-error: '#ffffff'
  error-container: '#ffdad6'
  on-error-container: '#93000a'
  primary-fixed: '#d5e3ff'
  primary-fixed-dim: '#a7c8ff'
  on-primary-fixed: '#001b3c'
  on-primary-fixed-variant: '#1f477b'
  secondary-fixed: '#ffe16d'
  secondary-fixed-dim: '#e9c400'
  on-secondary-fixed: '#221b00'
  on-secondary-fixed-variant: '#544600'
  tertiary-fixed: '#ffdad4'
  tertiary-fixed-dim: '#ffb4a8'
  on-tertiary-fixed: '#410000'
  on-tertiary-fixed-variant: '#930100'
  background: '#f9f9f9'
  on-background: '#1a1c1c'
  surface-variant: '#e2e2e2'
typography:
  headline-lg:
    fontFamily: Anton
    fontSize: 48px
    fontWeight: '400'
    lineHeight: '1.1'
    letterSpacing: 0.02em
  headline-lg-mobile:
    fontFamily: Anton
    fontSize: 32px
    fontWeight: '400'
    lineHeight: '1.1'
  headline-md:
    fontFamily: Anton
    fontSize: 32px
    fontWeight: '400'
    lineHeight: '1.2'
  body-lg:
    fontFamily: Work Sans
    fontSize: 18px
    fontWeight: '400'
    lineHeight: '1.6'
  body-md:
    fontFamily: Work Sans
    fontSize: 16px
    fontWeight: '400'
    lineHeight: '1.5'
  label-bold:
    fontFamily: Archivo Narrow
    fontSize: 14px
    fontWeight: '700'
    lineHeight: '1.2'
  label-sm:
    fontFamily: Archivo Narrow
    fontSize: 12px
    fontWeight: '500'
    lineHeight: '1.2'
spacing:
  unit: 8px
  container-max-width: 1280px
  gutter: 24px
  margin-mobile: 16px
  margin-desktop: 40px
---

## Brand & Style
The design system for ShowMate leverages a **High-Contrast Modern** aesthetic tailored for high-stakes environments like news, advertising, and events. It prioritizes clarity and immediate information retrieval through a stark, authoritative visual language. 

The brand personality is confident, urgent, and precise. By transitioning to a light mode theme, the system maintains its accessibility-first approach while providing a clean, "paper-white" editorial feel that reduces eye strain in bright environments. The style avoids unnecessary decoration, focusing instead on structural integrity and bold typographic hierarchy to guide the user's attention.

## Colors
The palette is built on a foundation of extreme contrast to ensure WCAG AAA compliance for critical text elements.

- **Primary Background (#FFFFFF):** A pure white base provides a neutral, high-reflectance surface for maximum legibility.
- **Primary Surface/Text (#003366):** Deep Blue acts as the anchor, used for all primary headings, body copy, and structural elements like navigation bars and sidebars.
- **Interactive Focus (#FFD700):** Canary Yellow is reserved strictly for Call-to-Action (CTA) backgrounds and focus indicators. Its high vibrancy against both white and blue makes interactive states unmistakable.
- **Accent/Error (#E60000):** A bold red is used sparingly for urgent alerts or destructive actions, maintaining the system's authoritative tone.

## Typography
The typography system uses a mix of high-impact display faces and highly legible grotesques.

- **Anton (Headlines):** Used for large displays and section headers. Its condensed, bold nature conveys urgency and power.
- **Work Sans (Body):** A professional, grounded font that ensures readability in dense data or long-form content.
- **Archivo Narrow (Labels):** Utilized for metadata, tags, and small utility text where space is at a premium but legibility remains mandatory.

Uppercase styling is preferred for labels and secondary headers to reinforce the "ShowMate" editorial character.

## Layout & Spacing
The layout follows a **Fixed Grid** philosophy for desktop to maintain a structured, magazine-like feel, while transitioning to a fluid model for mobile devices.

- **Grid:** A 12-column grid is used for desktop (1280px max-width) with 24px gutters. Elements should snap to the grid to maintain visual tension.
- **Rhythm:** An 8px linear scale governs all padding and margins, ensuring a consistent vertical rhythm.
- **Mobile Reflow:** On mobile, margins reduce to 16px and the grid collapses to 4 columns. Complex data tables should switch to card-based list views.

## Elevation & Depth
This design system rejects traditional shadows in favor of **Bold Borders** and **Tonal Layering** to communicate hierarchy.

- **Hierarchy through Contrast:** Depth is created by placing Deep Blue surfaces (#003366) on top of Pure White backgrounds. 
- **Outlines:** Use 2px solid borders in Deep Blue for containers and inputs to define boundaries without relying on soft shadows.
- **No Blurs:** Avoid glassmorphism or soft blurs. All layers must be opaque and crisp to support the high-contrast accessibility requirement.

## Shapes
The design system employs a **Sharp (0px)** roundedness strategy. Every element—from buttons and input fields to cards and modals—uses hard 90-degree corners. This reinforces the "Brutalist" and "Professional" aspects of the brand, suggesting stability, precision, and a no-nonsense approach to the UI.

## Components
Consistent component styling is vital for maintaining the high-contrast identity.

- **Buttons:** 
  - *Primary:* Canary Yellow (#FFD700) background with Deep Blue (#003366) text. No border.
  - *Secondary:* Pure White background with 2px Deep Blue border and text.
- **Inputs:** White background with a 2px Deep Blue border. On focus, the border remains Deep Blue but the element receives a 4px Canary Yellow outer "glow" or offset ring.
- **Chips/Tags:** Deep Blue background with White text for high visibility, or White background with Deep Blue border for secondary status.
- **Cards:** White background with a 1px or 2px Deep Blue border. Headers within cards should have a Deep Blue background with White text to clearly define the content area.
- **Checkboxes:** Square (0px radius). When checked, they should be filled Deep Blue with a White checkmark or Canary Yellow for high-visibility focus.