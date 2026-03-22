# Coral Breeze Design System

## 1. Overview & Creative North Star
**Creative North Star: The Vibrant Curator**

Coral Breeze is a high-end editorial design system designed to bridge the gap between professional enterprise tools and warm, approachable consumer experiences. It moves away from the "clinical" look of typical SaaS dashboards by embracing **Organic Brutalism**—a style that combines bold, high-contrast typography and vivid "Coral" pops with soft, multi-tonal background gradients.

The system breaks the rigid grid through intentional asymmetry: search bars are pill-shaped, cards use aggressive 2xl rounding, and floating "AI-enhanced" elements overlap container boundaries to suggest a layered, three-dimensional workspace.

## 2. Colors
The palette is built on a foundation of "Fresh Pastels" (Mint, Peach, Sky) contrasted against a signature "Coral" primary.

- **The "No-Line" Rule:** Standard 1px borders are strictly prohibited for sectioning. Layout boundaries must be defined through background shifts (e.g., a `surface_container` mint transition to a `surface_container_high` blue) or subtle tonal transitions.
- **Surface Hierarchy & Nesting:** Use `surface_container_low` (#FFF9F0) for search inputs to create an "inset" feel, while `surface` (Pure White) is reserved for cards that should "float" above the multi-tonal background.
- **Signature Textures:** Utilize the "Logo Gradient" (Coral to Teal) for high-impact brand moments and the "Instagram Gradient" for social-specific context. Hero backgrounds should utilize a 135-degree linear gradient across the primary pastel spectrum.

## 3. Typography
The system uses **Plus Jakarta Sans** exclusively to maintain a modern, geometric yet friendly rhythm.

**Typographic Scale (Calibrated):**
- **Display/Hero:** 84px (Used for expressive emoji or massive impact statements).
- **Heading 1/Brand:** 1.5rem (24px) - Extrabold, tracking-tight.
- **Heading 2/Section:** 1.875rem (30px) - Extrabold, for empty states.
- **Body Large:** 1.125rem (18px) - Medium, for lead paragraphs.
- **Body Standard:** 0.875rem (14px) - Regular, for chat messages.
- **Label/Action:** 11px / 10px - Bold, uppercase for badges and metadata.

The hierarchy relies on weight (Extrabold vs. Medium) rather than just size to create a "magazine" feel.

## 4. Elevation & Depth
Depth is conveyed through **Tonal Layering** and soft, colored shadows rather than grey offsets.

- **The Layering Principle:** Use a `sidebar-gradient` (Peach to Mint) to visually separate navigation from the white `surface` cards.
- **Ambient Shadows (Coral Shadow):** Instead of neutral black shadows, use `rgba(255, 107, 107, 0.12)` with a 30px blur. This creates a "glow" effect that feels lighter and more modern.
- **Shadow Truths:** 
  - `shadow-coral`: `0 4px 20px rgba(255,107,107,0.08)` for standard cards.
  - `shadow-xl`: `0 10px 25px rgba(255,107,107,0.3)` for primary "Bolt" CTAs.
- **Glassmorphism:** Use backdrop blurs on sticky headers to allow the background gradients to bleed through, maintaining a sense of place.

## 5. Components
- **Buttons:** Primary buttons are pill-shaped (full-round) with linear gradients (`from-primary to-[#ff8585]`). They must use `hover:scale-105` for a tactile, bouncy feel.
- **Chips:** Filter chips use `primary_container` (#FFE8E8) with `primary` (#FF6B6B) text. Active states should swap to solid primary with a heavy shadow.
- **Conversation Cards:** Must feature a `transition: transform 0.2s` on hover. Avatars are wrapped in brand-color rings (WhatsApp green or Instagram gradient) to denote source.
- **Badges:** Small, high-contrast pills. For "WhatsApp," use 10px font with `tracking-wider` and `uppercase` styling to mimic editorial labels.

## 6. Do's and Don'ts
### Do's
- Use multi-color gradients for page backgrounds to avoid a "flat" appearance.
- Apply `rounded-2xl` (1.25rem) to most containers and `rounded-full` to interactive inputs.
- Use iconography with the `material-symbols-outlined` style, maintaining a weight of 300-500.

### Don'ts
- **No hard black (#000000):** Use `text-main` (#2D3748) for all high-contrast text.
- **No standard grey borders:** If a border is required for accessibility, use `peach-border` (#FFE8D6).
- **No sharp corners:** Angularity is the enemy of the Coral Breeze aesthetic. Even small tooltips must have at least 0.5rem rounding.