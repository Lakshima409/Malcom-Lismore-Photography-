# Malcolm Lismore Photography — User Guide

This guide explains how to use the website and the admin panel.

---

## 1. Browsing the public website

### Home page
- The **hero** introduces the site with a Scottish landscape.
- **Explore Gallery** jumps to the gallery; **Make an Enquiry** opens the
  booking form.
- Scroll for featured work, testimonials (which auto-advance) and the journal.

### Gallery
- Open **Gallery** from the navigation.
- Use the category filters (Landscapes, Wildlife, Coastal Birds, Weddings,
  Portraits & Events) to narrow the selection.
- Click any photograph to open its **detail page**, where you can read a fuller
  description and move between photographs with the Previous/Next buttons.

### Prices
- The **Prices** page lists every package. Each package shows a starting price,
  what's included, and an **Enquire Now** button.
- Choosing a package pre-selects that service on the enquiry form.

### Making an enquiry
1. Go to **Make an Enquiry**.
2. Fill in your details. Required fields are marked with `*`.
3. Tick the privacy consent box and press **Send Enquiry**.
4. On success you'll see a **Thank You** confirmation. Malcolm will reply,
   usually within two working days.

---

## 2. Using the admin panel

Access the panel at `/admin/login.php`.

**Demo login:** username `admin`, password `Admin@1234`.

### Dashboard
- Shows the number of **new enquiries**, total enquiries, photographs and
  categories, plus the five most recent enquiries.

### Managing enquiries
- **Enquiries** lists every submission. Use the **search box** and **status
  filter** to find records.
- Click **View** to read the full enquiry and update its status
  (`new` → `contacted` → `booked` → `closed`).

### Managing photographs
- **Photos** lists all gallery images.
- **Add Photo**: enter a title, category, alt text and either upload an image
  or type an existing image path. Uploads are limited to JPG/PNG/GIF/WebP and
  5 MB, and are validated before being stored.
- **Edit**: change any detail or replace the image.
- **Delete**: permanently removes the record (and the uploaded file if it lives
  in `assets/uploads/`). You'll be asked to confirm first.

### Logging out
- Use the **Logout** button in the admin navigation. For security, logout uses a
  secure POST request.

---

## 3. Tips for Malcolm
- Add new categories by inserting rows into the `categories` table (or extend the
  admin panel) — the gallery filters read directly from the database.
- Keep image `alt_text` descriptive: it improves accessibility and SEO.
- Final pricing is always confirmed per enquiry, so the "starting from" prices
  are a guide rather than a fixed quote.
