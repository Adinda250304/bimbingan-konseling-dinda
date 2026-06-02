---
name: Bimbingan Konseling Dinda
description: Warm-paper editorial sanctuary — committed serif display, one decisive magenta, flat surfaces at rest.

colors:
  primary: "oklch(60% 0.25 350)"
  editorial-magenta: "oklch(60% 0.25 350)"
  editorial-magenta-deep: "oklch(52% 0.25 350)"
  warm-ash-cream: "oklch(96% 0.005 350)"
  crisp-paper-white: "oklch(98% 0 0)"
  deep-graphite: "oklch(10% 0 0)"
  soft-charcoal: "oklch(25% 0 0)"
  mid-ash: "oklch(55% 0 0)"
  paper-mist: "oklch(92% 0 0)"
  magenta-whisper: "oklch(60% 0.25 350 / 0.15)"
  magenta-veil: "oklch(60% 0.25 350 / 0.25)"

typography:
  display:
    fontFamily: "Cormorant Garamond, Georgia, serif"
    fontSize: "4.5rem"
    fontWeight: 300
    lineHeight: 1
  headline:
    fontFamily: "Cormorant Garamond, Georgia, serif"
    fontSize: "2.5rem"
    fontWeight: 400
    lineHeight: 1.2
  title:
    fontFamily: "Cormorant Garamond, Georgia, serif"
    fontSize: "1.75rem"
    fontWeight: 400
    lineHeight: 1.3
  body:
    fontFamily: "Instrument Sans, system-ui, sans-serif"
    fontSize: "1rem"
    fontWeight: 400
    lineHeight: 1.6
  body-lead:
    fontFamily: "Instrument Sans, system-ui, sans-serif"
    fontSize: "1.0625rem"
    fontWeight: 400
    lineHeight: 1.65
  supporting:
    fontFamily: "Instrument Sans, system-ui, sans-serif"
    fontSize: "0.875rem"
    fontWeight: 400
    lineHeight: 1.6
  label:
    fontFamily: "Instrument Sans, system-ui, sans-serif"
    fontSize: "0.9rem"
    fontWeight: 500
    letterSpacing: "0.05em"
  micro-label:
    fontFamily: "Instrument Sans, system-ui, sans-serif"
    fontSize: "0.6875rem"
    fontWeight: 500
    letterSpacing: "0.1em"
  mono:
    fontFamily: "Space Grotesk, monospace"
    fontSize: "0.75rem"
    fontWeight: 400

rounded:
  none: "0px"
  sm: "4px"
  md: "8px"
  lg: "12px"
  xl: "16px"

spacing:
  xs: "8px"
  sm: "16px"
  md: "24px"
  lg: "32px"
  xl: "48px"
  "2xl": "80px"
  "3xl": "120px"

components:
  button-primary:
    backgroundColor: "{colors.deep-graphite}"
    textColor: "{colors.crisp-paper-white}"
    typography: "{typography.label}"
    rounded: "{rounded.none}"
    padding: "16px 48px"
  button-primary-hover:
    backgroundColor: "{colors.editorial-magenta}"
    textColor: "{colors.crisp-paper-white}"
  input-text:
    backgroundColor: "transparent"
    textColor: "{colors.deep-graphite}"
    rounded: "{rounded.sm}"
    padding: "8px 12px"
  card:
    backgroundColor: "{colors.warm-ash-cream}"
    textColor: "{colors.deep-graphite}"
    rounded: "{rounded.md}"
    padding: "24px"
  card-feature:
    backgroundColor: "{colors.crisp-paper-white}"
    textColor: "{colors.deep-graphite}"
    rounded: "{rounded.lg}"
    padding: "48px"
  nav-link:
    textColor: "{colors.deep-graphite}"
    typography: "{typography.body}"
  nav-link-hover:
    textColor: "{colors.editorial-magenta}"
---

# Design System: Bimbingan Konseling Dinda

## 1. Overview: The Editorial Sanctuary

**Creative North Star: "The Editorial Sanctuary"**

This project adopts the Impeccable design system. The interface feels **considered, unhurried, and expert** — the work of someone who has made the calls a thousand times and has zero interest in chasing the current AI-tool aesthetic.

The aesthetic philosophy is **restraint in service of craft**. Every element earns its place. Nothing is decorative without function. The palette is dominated by warm paper tones with one vibrant voice. The typography pairs a stately italic serif with a clean neutral sans. Motion is reserved for moments that actually communicate state.

## 2. Colors: The Warm-Paper Palette

A two-chord palette: warm paper neutrals carrying a near-invisible magenta tint, plus one decisive accent in the same hue family. No secondary or tertiary accents in the core system — the restraint is doctrinal.

### Primary
- **Editorial Magenta** (oklch(60% 0.25 350)): The one vibrant voice. Primary CTAs, active navigation states, live-state indicators, rare editorial emphasis. Never used as a gradient, never as a background wash, never as text fill. Rarity is the design choice.

### Neutral
- **Warm Ash Cream** (oklch(96% 0.005 350)): Primary page background. Near-white with a near-imperceptible magenta tint that creates subconscious cohesion with Editorial Magenta. Used on `body` and standard surfaces.
- **Crisp Paper White** (oklch(98% 0 0)): Pure background. Used for inverted text moments (white-on-dark CTAs) and surfaces needing maximum contrast. Almost never the page background — too cold alone.
- **Deep Graphite** (oklch(10% 0 0)): Primary text for body copy and headlines. Softer than pure black, reads as confident-but-not-aggressive on warm paper. Background of the primary CTA.

## 3. Typography: The Italic-and-Ink Voice

**Display Font:** Cormorant Garamond (with Georgia fallback)
**Body Font:** Instrument Sans (with system-ui fallback)
**Label/Mono Font:** Space Grotesk (used as a geometric mono, not for code blocks)

### Named Rules

**The Italic-Is-Voice Rule.** Italic is used as a voice choice for display type, not as emphasis within body copy. Body emphasis is carried by weight or by swapping to the mono family. Treating italic as emphasis inside paragraphs dilutes the display voice.

**The 1.6 Leading Rule.** Body line-height is 1.6 everywhere. Not 1.5, not 1.7, not "relaxed". This is the load-bearing readability decision — when the site reads as calm and editorial, it's 1.6 doing the work.

**The Fluid-Headlines-Only Rule.** Headings use `clamp()` fluid sizing. Body copy uses fixed `rem` values. Fluid body sizes look clever and feel wrong — they make line-lengths wander off spec.

## 4. Elevation

Flat by default. Depth is conveyed through **state response**, not structural shadow. Surfaces rest on a single tonal layer (Warm Ash Cream); shadows appear only when an element is hovered, deliberately lifted, or requires ambient separation from a busy area.

### Named Rules

**The Flat-By-Default Rule.** Surfaces are flat at rest. If you find yourself adding a shadow to a non-interactive, non-elevated element, stop — you're reaching for Material Design muscle memory. Use a hairline Paper Mist border instead, or no articulation at all.

**The Low-Alpha Rule.** Every shadow in the system uses ≤0.15 alpha on its strongest blur. Higher alphas read as 2014 Material Design drop shadows — an immediate tell that the design wasn't considered.

## 5. Components

### Buttons
- **Shape:** Flat and squared by default (`border-radius: 0`). Sharp corners are an explicit editorial choice.
- **Primary (hero-cta-combined):** Deep Graphite background, Crisp Paper White text. Padding 16px / 48px (`--spacing-sm` / `--spacing-xl`). Uppercase, `letter-spacing: 0.05em`, weight 500. No border, no shadow at rest.
- **Hover:** `transform: translateY(-2px)` and background shifts to Editorial Magenta. Transition 200ms linear ease. A small confident step up, never a bounce.

### Cards & Containers
- **Corner Style:** Controlled vocabulary — 4px (chips / inline callouts), 8px (standard cards and card-CTAs), 12px (feature cards, install blocks), 16px (large content frames). No single "rounded-lg" default. Radius is picked per component weight.
- **Background:** Warm Ash Cream or Crisp Paper White depending on layering. Deeper nested surfaces may lift to Paper Mist as a near-imperceptible tone shift.
- **Shadow:** Flat at rest — see Elevation for the shadow vocabulary that applies on hover/lift.

## 6. Do's and Don'ts

### Do:
- **Do** treat Warm Ash Cream (not Crisp Paper White) as the default page background. Warmth is load-bearing.
- **Do** use Editorial Magenta on ≤10% of any given screen. Scarcity is what makes it read as decisive rather than noisy.
- **Do** set all new colors in OKLCH.
- **Do** use italic display type as a voice, not as emphasis inside paragraphs. Body emphasis is carried by weight.
- **Do** keep the primary CTA sharp and squared. `border-radius: 0`, uppercase, letter-tracked. This is the editorial signature.

### Don't:
- **Don't** use pure black (#000) or pure white (#fff). Always the tinted neutrals (Deep Graphite / Warm Ash Cream / Crisp Paper White).
- **Don't** use `border-left` or `border-right` greater than 1px as a colored stripe on cards, list items, callouts, or alerts. Ever.
- **Don't** use `background-clip: text` with a gradient. Gradient text is banned across the site. If you want emphasis, use weight or size, never gradient fill.
- **Don't** default to dark mode. The site is light mode because editorial reading is a light-mode activity.
- **Don't** use glassmorphism (blurred translucent cards, glass borders, glow backgrounds as decoration).
- **Don't** add a second accent color. If a layout "needs" a second emphasis point, use scale or weight, not hue.
