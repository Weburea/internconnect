# Agent Task Directives & Strategy

This document details the developer's instructions on operating procedures, efficiency standards, and token preservation protocols for the InternConnect project.

---

## 1. Operating Guidelines

All AI agents must prioritize speed, target-driven implementation, and clear design execution.

- **Focus on Major Targets**: Prioritize structural modifications, primary features, and database integrity over secondary adjustments.
- **Save Tokens**: Avoid generating verbose, repetitive explanations, plans, or summaries. Keep descriptions and conversations concise.
- **Go Straight to the Point**: Immediately target key source files, write correct code, run necessary checks, and report success without excessive pre-ambles.
- **Prioritize Design & Coding**: Dedicate resources to writing high-quality code and layouts compliant with the design system.

---

## 2. Cloudinary Storage Policy

- **All UI Media Assets**: Move all UI assets (including SVGs, PNGs, JPEGs, and videos) to the Cloudinary product environment under the `internConnect` root folder.
- **Global Helper Integration**: Always use the global `asset()` helper function to request assets. Do not hardcode local asset paths.
