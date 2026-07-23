import os
import math
from PIL import Image, ImageDraw, ImageFilter

statusbar_dir = r"c:\xampp\htdocs\Payslip\public\images\status-bar"
os.makedirs(statusbar_dir, exist_ok=True)

def save_scaled(img_large, filename, scale=10):
    w, h = img_large.size
    img_small = img_large.resize((w // scale, h // scale), Image.Resampling.LANCZOS)
    dest = os.path.join(statusbar_dir, filename)
    img_small.save(dest, "PNG")
    print(f"  {filename}: {img_small.width}x{img_small.height}px")

# ═══════════════════════════════════════════════════════════════
# REFERENCE MEASUREMENTS (from iPhone-style status bar):
#   Signal  → placed at x=433, y=26 on 590px canvas
#   WiFi    → placed at x=478, y=27
#   Battery → placed at x=513, y=26
#   Target final sizes: signal≈28×18, wifi≈18×14, battery≈36×18
# ═══════════════════════════════════════════════════════════════

print("Generating status bar icons...")

# ───────────────────────────────────────────────────────────────
# 1. SIGNAL BARS
#    4 bars, tight compact spacing, all increasing height L→R
#    Final target: 28×18px → use 280×180 canvas at 10x
# ───────────────────────────────────────────────────────────────
def make_signal(active_bars, filename):
    W, H = 280, 180
    img = Image.new("RGBA", (W, H), (0, 0, 0, 0))
    d = ImageDraw.Draw(img)

    # 4 bars: bar_w=42, gap=9 — compact tight iOS style
    bar_w = 42
    gap   = 9
    bot   = H - 5
    # Heights: 55, 95, 135, 175 — steep steps
    heights = [55, 95, 135, 175]

    for i, bh in enumerate(heights):
        x0 = 5 + i * (bar_w + gap)
        x1 = x0 + bar_w
        y0 = bot - bh
        y1 = bot
        alpha = 255 if i < active_bars else 55
        d.rounded_rectangle([x0, y0, x1, y1], radius=9, fill=(0, 0, 0, alpha))

    save_scaled(img, filename)

for n in range(1, 5):
    make_signal(n, f"signal-{n}-bars.png")
make_signal(4, "signal_original.png")

# ───────────────────────────────────────────────────────────────
# 2. WI-FI ICON
#    3 arcs + dot, compact — final: 18×14px → 180×140 canvas
# ───────────────────────────────────────────────────────────────
def make_wifi():
    W, H = 180, 140
    img = Image.new("RGBA", (W, H), (0, 0, 0, 0))
    d = ImageDraw.Draw(img)

    cx, cy = W // 2, H - 12   # anchor point at bottom centre

    # Dot
    dr = 11
    d.ellipse([cx-dr, cy-dr, cx+dr, cy+dr], fill=(0, 0, 0, 255))

    # 3 arcs — thin stroke, 225°→315°
    radii  = [35, 65, 95]
    stroke = 15
    a0, a1 = 225, 315

    for r in radii:
        bbox = [cx-r, cy-r, cx+r, cy+r]
        d.arc(bbox, start=a0, end=a1, fill=(0, 0, 0, 255), width=stroke)
        # Round caps
        cr = stroke // 2 - 1
        for ang in [a0, a1]:
            rad = math.radians(ang)
            ex, ey = cx + r*math.cos(rad), cy + r*math.sin(rad)
            d.ellipse([ex-cr, ey-cr, ex+cr, ey+cr], fill=(0, 0, 0, 255))

    save_scaled(img, "wifi_original.png")

make_wifi()

# ───────────────────────────────────────────────────────────────
# 3. BATTERY ICON  — iOS pill style
#    Reference: compact rounded rectangle, thin outline, solid fill
#    Final target: ~36×18px → 360×180 canvas at 10x
# ───────────────────────────────────────────────────────────────
def make_battery(level, filename):
    W, H = 380, 180
    img = Image.new("RGBA", (W, H), (0, 0, 0, 0))
    d = ImageDraw.Draw(img)

    # Outer body — thin outline, highly rounded (pill-like)
    bL, bT, bR, bB = 5, 25, 320, 155
    outline_w = 12
    radius    = 28
    d.rounded_rectangle([bL, bT, bR, bB], radius=radius, outline=(0, 0, 0, 255), width=outline_w)

    # Small nub (terminal) on the right
    nL, nT, nR, nB = 325, 70, 355, 110
    d.rounded_rectangle([nL, nT, nR, nB], radius=9, fill=(0, 0, 0, 255))

    # Inner fill — close gaps for "full" = solid look
    pad_x = outline_w + 2   # tight inner padding
    pad_y = outline_w + 2
    iL = bL + pad_x
    iT = bT + pad_y
    iR = bR - pad_x
    iB = bB - pad_y

    if level == "full":
        fR = iR
        fc = (0, 0, 0, 255)
    elif level == "medium":
        fR = iL + int((iR - iL) * 0.55)
        fc = (0, 0, 0, 255)
    elif level == "low":
        fR = iL + int((iR - iL) * 0.20)
        fc = (239, 68, 68, 255)
    else:
        fR = iL
        fc = (0, 0, 0, 255)

    if fR > iL:
        d.rounded_rectangle([iL, iT, fR, iB], radius=radius - outline_w - 2, fill=fc)

    save_scaled(img, filename)

for lv in ["full", "medium", "low"]:
    make_battery(lv, f"battery-{lv}.png")

print("Done!")
