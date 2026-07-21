# Teal & Sapphire UI Color Architecture
**Design System Specification & Token Mapping**

---

## 1. Core Component Color Mapping

The following table defines the exact architectural roles, hex values, and visual weights for the **Teal & Sapphire** management interface. These specifications ensure structural visual separation between global navigation, workspace canvas, and interactive triggers.

| UI Component | Role / State | Exact Hex Code | Visual Weight |
| :--- | :--- | :--- | :--- |
| **Sidebar Canvas** | Structural background | `#1E3A8A` *(Deep Sapphire)* | High (Grounding) |
| **Active Nav Indicator** | Left border accent on active tab | `#2DD4BF` *(Vibrant Teal)* | High (Focal Point) |
| **Workspace Canvas** | Main page background behind tables | `#F8FAFC` *(Soft Slate)* | Low (Neutral) |
| **Table Row Hover** | Mouse-over state for data rows | `#F0F9FF` *(Soft Sky Tint)* | Subtle (Guidance) |
| **Primary CTA Button** | Main submit or create action | `#0D9488` *(Dark Teal)* | Highest (Action) |
| **Verified Status Badge** | Pill container for positive states | `#CCFBF1` *(Mint/Teal Tint)* | Medium (Semantic) |

---

## 2. Extended Design Token Definition

To maintain visual consistency across all layout views, data tables, and modal forms, integrate these secondary tokens alongside your primary component mapping.

### Structural & Canvas Layers
* **Dark Sapphire Border (`#1E293B`)**: Used for divider lines within dark containers like the sidebar or dark-mode cards.
* **Surface Card (`#FFFFFF`)**: Pure white base for all floating data tables, metric cards, and input forms.
* **Border Divider (`#E2E8F0`)**: Subtle border separation for table headers, pagination bars, and card containers.

### Semantic Status Palette
When using cool teals and structural blues as your core interface palette, status badges must remain distinct to prevent visual fatigue and ambiguity:
* **Success / Verified (`#CCFBF1` bg / `#115E59` text)**: Aligns with the core teal identity while remaining distinct from clickable buttons.
* **Warning / Review (`#FEF3C7` bg / `#92400E` text)**: Warm amber provides high-visibility contrast against cool slate workspaces.
* **Danger / Overdue (`#FFE4E6` bg / `#9F1239` text)**: Crisp crimson/rose ensures critical alerts cut through without clashing with navy elements.

---

## 3. Implementation Guidelines

### The 60-30-10 Distribution Rule
1. **60% Dominant Neutral (`#F8FAFC`)**: Allocate to the workspace canvas and background containers to keep the layout feeling light and breathable.
2. **30% Structural Frame (`#FFFFFF` & `#1E3A8A`)**: Use pure white for content cards and deep sapphire for the global sidebar to establish solid architectural boundaries.
3. **10% Intentional Accent (`#0D9488` & `#2DD4BF`)**: Reserve vibrant teals strictly for primary interactive elements (buttons, active toggles, and navigation indicators).

### Contrast & Accessibility (WCAG 2.1 AA)
* Always pair **Dark Teal (`#0D9488`)** buttons with crisp `#FFFFFF` text to maintain an accessible contrast ratio of at least 4.5:1.
* Do not place mid-tone teal text over mid-tone blue backgrounds (e.g., `#0D9488` on `#1E3A8A`); use white text with vibrant teal indicators instead.

---

## 4. CSS Custom Properties Reference

For immediate integration into standard stylesheets or design system token files, copy the variables below:

```css
:root {
  /* Canvas & Structural Foundations */
  --color-canvas-workspace: #F8FAFC;
  --color-surface-card: #FFFFFF;
  --color-sidebar-bg: #1E3A8A;
  --color-border-subtle: #E2E8F0;

  /* Interactive Accents */
  --color-accent-primary: #0D9488;
  --color-accent-highlight: #2DD4BF;
  --color-hover-row: #F0F9FF;

  /* Semantic Badge Tokens */
  --color-badge-verified-bg: #CCFBF1;
  --color-badge-verified-text: #115E59;
  --color-badge-warning-bg: #FEF3C7;
  --color-badge-warning-text: #92400E;
  --color-badge-danger-bg: #FFE4E6;
  --color-badge-danger-text: #9F1239;
}
```
